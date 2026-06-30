<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PastaOption;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Table;
use App\Models\TableTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Mesa do Cliente — cardápio interativo, comanda e pagamento.
 * A comanda fica salva em sessão até ser confirmada (enviada à cozinha).
 */
class ClienteController extends Controller
{
    /**
     * Lista de mesas para o cliente selecionar (identificação por número).
     */
    public function mesas()
    {
        $tables = Table::orderBy('number')->get();

        return view('cliente.mesas', compact('tables'));
    }

    /**
     * Exibe o cardápio e a comanda aberta da mesa.
     */
    public function menu(Table $table)
    {
        // Categorias com produtos disponíveis
        $categories = Category::with(['availableProducts'])
            ->where('active', true)
            ->orderBy('sort_order')
            ->get();

        // Opções para o "Monte sua Massa" (apenas ativas)
        $massas = PastaOption::active()->ofType(PastaOption::MASSA)->orderBy('sort_order')->get();
        $molhos = PastaOption::active()->ofType(PastaOption::MOLHO)->orderBy('sort_order')->get();
        $ingredientes = PastaOption::active()->ofType(PastaOption::INGREDIENTE)->orderBy('sort_order')->get();

        // Carrinho atual (em sessão, por mesa)
        $cart = $this->getCart($table->id);

        // Pedidos já enviados à cozinha (comanda aberta), com itens não cancelados primeiro
        $sentOrders = $table->activeOrders()->with('items')->latest()->get();

        // Outras mesas disponíveis como destino de transferência
        $otherTables = Table::where('id', '!=', $table->id)->orderBy('number')->get();

        return view('cliente.menu', compact(
            'table', 'categories', 'cart', 'sentOrders',
            'massas', 'molhos', 'ingredientes', 'otherTables'
        ));
    }

    /**
     * Adiciona/incrementa um item fixo (não customizado) no carrinho.
     */
    public function addItem(Request $request, Table $table)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'notes'      => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        // Produtos "Monte sua Massa" precisam passar pelo fluxo de customização
        if ($product->is_customizable) {
            return back()->withErrors(['cart' => 'Este prato precisa ser personalizado. Use o botão "Montar".']);
        }

        $cart = $this->getCart($table->id);

        $key = (string) $product->id . '|' . ($data['notes'] ?? '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => (float) $product->price,
                'quantity'   => 1,
                'notes'      => $data['notes'] ?? '',
                'customization' => null,
            ];
        }

        $this->saveCart($table->id, $cart);

        return back()->with('success', $product->name . ' adicionado à comanda.');
    }

    /**
     * Adiciona ao carrinho um item personalizado de "Monte sua Massa":
     * escolha de massa, molho e até 8 ingredientes.
     *
     * IMPORTANTE: o limite de ingredientes e a validade das opções escolhidas
     * são validados aqui no backend, mesmo que o frontend já bloqueie o limite —
     * o frontend pode ser manipulado, então a regra real vive aqui.
     */
    public function addCustomItem(Request $request, Table $table)
    {
        $data = $request->validate([
            'product_id'      => ['required', 'exists:products,id'],
            'massa_id'        => ['required', 'integer', 'exists:pasta_options,id'],
            'molho_id'        => ['required', 'integer', 'exists:pasta_options,id'],
            'ingredientes'    => ['array'],
            'ingredientes.*'  => ['integer', 'exists:pasta_options,id'],
            'notes'           => ['nullable', 'string', 'max:255'],
        ], [
            'massa_id.required' => 'Escolha o tipo de massa.',
            'molho_id.required' => 'Escolha o molho.',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if (! $product->is_customizable) {
            return back()->withErrors(['cart' => 'Este prato não aceita personalização.']);
        }

        // ---- Revalidação no backend: limite de ingredientes ----
        $ingredienteIds = array_values(array_unique($data['ingredientes'] ?? []));

        if (count($ingredienteIds) > PastaOption::MAX_INGREDIENTES) {
            return back()->withErrors([
                'cart' => 'Escolha no máximo ' . PastaOption::MAX_INGREDIENTES . ' ingredientes.',
            ]);
        }

        // ---- Revalidação no backend: as opções escolhidas existem, estão ativas e são do tipo certo ----
        $massa = PastaOption::active()->ofType(PastaOption::MASSA)->find($data['massa_id']);
        $molho = PastaOption::active()->ofType(PastaOption::MOLHO)->find($data['molho_id']);

        if (! $massa || ! $molho) {
            return back()->withErrors(['cart' => 'A massa ou o molho escolhido não está mais disponível.']);
        }

        $ingredientes = PastaOption::active()->ofType(PastaOption::INGREDIENTE)
            ->whereIn('id', $ingredienteIds)
            ->get();

        // Se algum ingrediente enviado não bateu na busca (id inválido/inativo), rejeita o pedido inteiro
        if ($ingredientes->count() !== count($ingredienteIds)) {
            return back()->withErrors(['cart' => 'Um ou mais ingredientes escolhidos não estão mais disponíveis.']);
        }

        // ---- Preço final: base do produto + extras de cada ingrediente ----
        $extras = (float) $ingredientes->sum('extra_price');
        $unitPrice = (float) $product->price + $extras;

        $customization = [
            'massa'        => $massa->name,
            'molho'        => $molho->name,
            'ingredientes' => $ingredientes->pluck('name')->values()->all(),
        ];

        $cart = $this->getCart($table->id);

        // Cada combinação customizada é um item próprio no carrinho (chave única por conteúdo)
        $key = 'custom-' . Str::random(10);

        $cart[$key] = [
            'product_id'    => $product->id,
            'name'          => $product->name,
            'price'         => $unitPrice,
            'quantity'      => 1,
            'notes'         => $data['notes'] ?? '',
            'customization' => $customization,
        ];

        $this->saveCart($table->id, $cart);

        return back()->with('success', 'Sua massa personalizada foi adicionada à comanda.');
    }

    /**
     * Aumenta ou diminui a quantidade de um item.
     * Quando a quantidade chega a zero, o item é removido.
     */
    public function updateQty(Request $request, Table $table)
    {
        $data = $request->validate([
            'key'   => ['required', 'string'],
            'delta' => ['required', 'integer'],
        ]);

        $cart = $this->getCart($table->id);

        if (isset($cart[$data['key']])) {
            $cart[$data['key']]['quantity'] += $data['delta'];

            // Remove o item quando a quantidade chega a zero
            if ($cart[$data['key']]['quantity'] <= 0) {
                unset($cart[$data['key']]);
            }
        }

        $this->saveCart($table->id, $cart);

        return back();
    }

    /**
     * Remove um item da comanda.
     */
    public function removeItem(Request $request, Table $table)
    {
        $key = $request->input('key');
        $cart = $this->getCart($table->id);
        unset($cart[$key]);
        $this->saveCart($table->id, $cart);

        return back()->with('success', 'Item removido da comanda.');
    }

    /**
     * Confirma o pedido: cria a Order + OrderItems e envia à cozinha.
     * Marca a mesa como "ocupada".
     */
    public function confirm(Request $request, Table $table)
    {
        $cart = $this->getCart($table->id);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Adicione itens antes de confirmar o pedido.']);
        }

        // Abre a comanda da mesa (se ainda não estiver aberta)
        if ($table->status === 'livre') {
            $table->update(['status' => 'ocupada', 'opened_at' => now()]);
        } elseif (! $table->opened_at) {
            $table->update(['opened_at' => now()]);
        }

        // Cria o pedido
        $order = Order::create([
            'table_id' => $table->id,
            'user_id'  => $request->user()?->id,
            'status'   => 'recebido',
            'total'    => 0,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id'    => $item['product_id'],
                'product_name'  => $item['name'],
                'quantity'      => $item['quantity'],
                'unit_price'    => $item['price'],
                'notes'         => $item['notes'] ?: null,
                'status'        => 'enviado',
                'customization' => $item['customization'] ?? null,
            ]);
        }

        $order->recalculateTotal();

        // Limpa o carrinho
        $this->saveCart($table->id, []);

        return redirect()
            ->route('cliente.menu', $table)
            ->with('success', 'Pedido enviado para a cozinha! 🍽️');
    }

    /**
     * Cancela um item já enviado à cozinha, com motivo obrigatório registrado.
     * Só é permitido cancelar itens que ainda não foram marcados como "pronto".
     */
    public function cancelItem(Request $request, Table $table, OrderItem $orderItem)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'min:3', 'max:255'],
        ], [
            'reason.required' => 'Informe o motivo do cancelamento.',
            'reason.min'      => 'Descreva o motivo com um pouco mais de detalhe.',
        ]);

        // Garante que o item pertence a um pedido desta mesa
        if ($orderItem->order->table_id !== $table->id) {
            abort(404);
        }

        if ($orderItem->isCancelled()) {
            return back()->withErrors(['cancel' => 'Este item já está cancelado.']);
        }

        if ($orderItem->status === 'pronto') {
            return back()->withErrors(['cancel' => 'Não é possível cancelar um item que já está pronto.']);
        }

        $orderItem->update([
            'cancelled_at'  => now(),
            'cancel_reason' => $data['reason'],
            'cancelled_by'  => $request->user()?->id,
        ]);

        $order = $orderItem->order;
        $order->recalculateTotal();
        $order->recalculateStatus();

        return back()->with('success', 'Item cancelado: ' . $orderItem->product_name . '.');
    }

    /**
     * Transfere a comanda (todos os pedidos ativos) da mesa atual para outra mesa livre,
     * registrando a auditoria de quem transferiu.
     */
    public function transfer(Request $request, Table $table)
    {
        $data = $request->validate([
            'to_table_id' => ['required', 'integer', 'exists:tables,id'],
        ], [
            'to_table_id.required' => 'Selecione a mesa de destino.',
        ]);

        $toTable = Table::findOrFail($data['to_table_id']);

        if ($toTable->id === $table->id) {
            return back()->withErrors(['transfer' => 'A mesa de destino deve ser diferente da atual.']);
        }

        if ($toTable->status !== 'livre') {
            return back()->withErrors(['transfer' => 'A mesa de destino precisa estar livre.']);
        }

        $orders = $table->activeOrders()->get();

        if ($orders->isEmpty()) {
            return back()->withErrors(['transfer' => 'Não há comanda aberta nesta mesa para transferir.']);
        }

        $openedAt = $table->opened_at;

        // Move todos os pedidos ativos para a mesa de destino
        Order::whereIn('id', $orders->pluck('id'))->update(['table_id' => $toTable->id]);

        // Abre a comanda na mesa de destino e libera a mesa de origem
        $toTable->update(['status' => 'ocupada', 'opened_at' => $openedAt ?? now()]);
        $table->update(['status' => 'livre', 'opened_at' => null]);

        // Move também o carrinho em sessão (itens ainda não enviados)
        $cart = $this->getCart($table->id);
        if (! empty($cart)) {
            $existingCart = $this->getCart($toTable->id);
            $this->saveCart($toTable->id, $existingCart + $cart);
            $this->saveCart($table->id, []);
        }

        // Registra a auditoria da transferência
        TableTransfer::create([
            'from_table_id' => $table->id,
            'to_table_id'   => $toTable->id,
            'user_id'       => $request->user()?->id,
            'orders_moved'  => $orders->count(),
        ]);

        return redirect()
            ->route('cliente.menu', $toTable)
            ->with('success', "Comanda transferida da Mesa {$table->number} para a Mesa {$toTable->number}.");
    }

    /**
     * Registra o pagamento e encerra a comanda da mesa.
     * Fluxo: mesa fica "fechada" e em seguida "livre".
     */
    public function pay(Request $request, Table $table)
    {
        $data = $request->validate([
            'method' => ['required', 'in:pix,dinheiro,cartao'],
        ], [
            'method.required' => 'Selecione a forma de pagamento.',
            'method.in'       => 'Forma de pagamento inválida.',
        ]);

        $total = $table->partial_total;

        if ($total <= 0) {
            return back()->withErrors(['pay' => 'Não há valor a pagar nesta mesa.']);
        }

        // Registra o pagamento
        Payment::create([
            'table_id' => $table->id,
            'amount'   => $total,
            'method'   => $data['method'],
            'status'   => 'pago',
        ]);

        // Marca os pedidos como entregues
        $table->activeOrders()->update(['status' => 'entregue']);

        // Mesa fechada e depois liberada
        $table->update(['status' => 'fechada']);
        $table->update(['status' => 'livre', 'opened_at' => null]);

        return redirect()
            ->route('cliente.mesas')
            ->with('success', 'Pagamento de ' . 'R$ ' . number_format($total, 2, ',', '.') . ' via ' . ucfirst($data['method']) . ' concluído. Mesa liberada!');
    }

    // ---------- Helpers de carrinho em sessão ----------

    private function cartKey(int $tableId): string
    {
        return "cart_table_{$tableId}";
    }

    private function getCart(int $tableId): array
    {
        return session($this->cartKey($tableId), []);
    }

    private function saveCart(int $tableId, array $cart): void
    {
        session([$this->cartKey($tableId) => $cart]);
    }
}
