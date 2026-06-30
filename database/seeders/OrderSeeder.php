<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Database\Seeder;

/**
 * Cria pedidos de exemplo para demonstrar os painéis de Cozinha e Garçom.
 * O status de cada item segue o status "geral" do pedido de demonstração,
 * já que o status real agora vive no item (não mais só no pedido).
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Mapa de pedidos de demonstração: mesa => [status, [ [produto, qtd, obs, customização|null], ... ]]
        $demo = [
            ['mesa' => 5, 'status' => 'enviado',    'itens' => [
                ['Feijoada Completa', 2, '', null],
                ['Frango com Quiabo', 1, 'sem quiabo', null],
            ]],
            ['mesa' => 8, 'status' => 'em_preparo', 'itens' => [
                ['Espeto de Carne', 4, '', null],
                ['Caipirinha', 2, '', null],
            ]],
            ['mesa' => 3, 'status' => 'pronto',     'itens' => [
                ['Monte sua Massa', 1, '', [
                    'massa'        => 'Penne',
                    'molho'        => 'À bolonhesa',
                    'ingredientes' => ['Bacon', 'Parmesão ralado', 'Champignon'],
                ]],
                ['Suco Natural', 1, 'laranja', null],
            ]],
        ];

        foreach ($demo as $d) {
            $table = Table::where('number', $d['mesa'])->first();
            if (! $table) {
                continue;
            }

            // Marca a mesa como ocupada e registra abertura da comanda
            $table->update(['status' => 'ocupada', 'opened_at' => now()->subMinutes(rand(5, 40))]);

            $order = Order::create([
                'table_id' => $table->id,
                'status'   => $d['status'] === 'enviado' ? 'recebido' : $d['status'],
                'total'    => 0,
            ]);

            foreach ($d['itens'] as [$name, $qty, $obs, $customization]) {
                $product = Product::where('name', $name)->first();
                if (! $product) {
                    continue;
                }

                $extra = 0;
                if ($customization) {
                    $extra = \App\Models\PastaOption::ofType(\App\Models\PastaOption::INGREDIENTE)
                        ->whereIn('name', $customization['ingredientes'])
                        ->sum('extra_price');
                }

                $order->items()->create([
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'quantity'      => $qty,
                    'unit_price'    => (float) $product->price + (float) $extra,
                    'notes'         => $obs ?: null,
                    'status'        => $d['status'],
                    'customization' => $customization,
                ]);
            }

            $order->recalculateTotal();
        }
    }
}
