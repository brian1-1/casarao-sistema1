<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

/**
 * Painel do Gerente — dashboard com indicadores do dia.
 */
class GerenteController extends Controller
{
    public function dashboard()
    {
        $today = today();

        // Faturamento do dia (pagamentos confirmados hoje)
        $revenue = Payment::whereDate('created_at', $today)
            ->where('status', 'pago')
            ->sum('amount');

        // Pedidos do dia
        $ordersCount = Order::whereDate('created_at', $today)->count();

        // Mesas ocupadas / livres
        $occupied = Table::where('status', 'ocupada')->count();
        $free     = Table::where('status', 'livre')->count();
        $closed   = Table::where('status', 'fechada')->count();

        // Últimos pedidos para visão rápida
        $recentOrders = Order::with(['table', 'items'])->latest()->take(8)->get();

        // Pratos mais pedidos hoje (itens não cancelados, agrupados por nome do produto)
        $topProducts = OrderItem::whereHas('order', fn ($q) => $q->whereDate('created_at', $today))
            ->whereNull('cancelled_at')
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Cancelamentos do dia (quantidade e motivos mais recentes)
        $cancelledItems = OrderItem::with(['order.table', 'cancelledBy'])
            ->whereNotNull('cancelled_at')
            ->whereDate('cancelled_at', $today)
            ->latest('cancelled_at')
            ->take(8)
            ->get();

        $cancelledCount = OrderItem::whereNotNull('cancelled_at')
            ->whereDate('cancelled_at', $today)
            ->count();

        return view('gerente.dashboard', compact(
            'revenue', 'ordersCount', 'occupied', 'free', 'closed', 'recentOrders',
            'topProducts', 'cancelledItems', 'cancelledCount'
        ));
    }
}
