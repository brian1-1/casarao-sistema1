<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;

/**
 * Painel do Garçom — visão geral das mesas e dos pedidos prontos.
 */
class GarcomController extends Controller
{
    /**
     * Lista todas as mesas com status, valor parcial, nº de pedidos e horário de abertura,
     * além dos pedidos prontos na cozinha aguardando entrega.
     */
    public function index()
    {
        $tables = Table::orderBy('number')->get()->map(function (Table $table) {
            $orders = $table->activeOrders()->get();

            return [
                'model'        => $table,
                'partial'      => (float) $orders->sum('total'),
                'orders_count' => $orders->count(),
                'opened_at'    => $table->opened_at,
            ];
        });

        // Pedidos prontos aguardando o garçom entregar (com seus itens, incluindo cancelados para exibição)
        $readyOrders = Order::with(['table', 'items'])
            ->where('status', 'pronto')
            ->latest()
            ->get();

        return view('garcom.index', compact('tables', 'readyOrders'));
    }

    /**
     * Marca um pedido pronto como entregue.
     */
    public function deliver(Order $order)
    {
        $order->update(['status' => 'entregue']);

        return back()->with('success', 'Pedido da Mesa ' . $order->table->number . ' marcado como entregue.');
    }
}
