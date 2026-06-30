<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

/**
 * Painel da Cozinha — gerencia o fluxo de pedidos.
 * O status agora é controlado por ITEM (não pelo pedido inteiro):
 * enviado → em_preparo → pronto.
 * Quando um item "Monte sua Massa" fica pronto, ele aparece destacado
 * no painel de "Massas prontas" para apoiar a montagem do prato.
 */
class CozinhaController extends Controller
{
    /**
     * Exibe os itens agrupados por status em colunas (kanban),
     * além do painel de itens "Monte sua Massa" prontos.
     */
    public function index()
    {
        $items = OrderItem::with(['order.table'])
            ->whereNull('cancelled_at')
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['recebido', 'em_preparo', 'pronto']))
            ->whereIn('status', ['enviado', 'em_preparo', 'pronto'])
            ->orderBy('created_at')
            ->get();

        $enviados   = $items->where('status', 'enviado');
        $emPreparo  = $items->where('status', 'em_preparo');
        $prontos    = $items->where('status', 'pronto');

        // Itens "Monte sua Massa" prontos, para o painel de destaque (item 13 do PDF)
        $massasProntas = $prontos->filter(fn (OrderItem $item) => $item->isCustom())->values();

        return view('cozinha.index', compact('enviados', 'emPreparo', 'prontos', 'massasProntas'));
    }

    /**
     * Avança o status de um item específico do pedido.
     * Ao atualizar o item, o status agregado do pedido (Order) é recalculado.
     */
    public function updateItemStatus(Request $request, OrderItem $orderItem)
    {
        $data = $request->validate([
            'status' => ['required', 'in:enviado,em_preparo,pronto'],
        ]);

        if ($orderItem->isCancelled()) {
            return back()->withErrors(['status' => 'Este item foi cancelado e não pode mudar de status.']);
        }

        $orderItem->update(['status' => $data['status']]);

        $orderItem->order->recalculateStatus();

        return back()->with('success', 'Status do item atualizado.');
    }
}
