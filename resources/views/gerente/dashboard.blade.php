@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .rank-list { display: flex; flex-direction: column; gap: 8px; }
    .rank-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #faf7f3; border-radius: 9px; font-size: 13px; }
    .rank-name { font-weight: 700; }
    .rank-qty { color: var(--brand); font-weight: 800; }
    .cancel-row { padding: 10px 12px; border-radius: 9px; background: #fbe6e1; margin-bottom: 8px; font-size: 12px; }
    .cancel-row b { color: #962a13; }
    .cancel-meta { color: var(--muted); margin-top: 2px; }
    @media (max-width: 880px) { .dash-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">Visão geral do dia · {{ now()->format('d/m/Y') }}</p>

    @include('partials.flash')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-val">R$ {{ number_format($revenue, 2, ',', '.') }}</div>
            <div class="stat-lab"><i class="ti ti-cash"></i> Faturamento do dia</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ $ordersCount }}</div>
            <div class="stat-lab"><i class="ti ti-clipboard-list"></i> Pedidos do dia</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ $occupied }}</div>
            <div class="stat-lab"><i class="ti ti-users"></i> Mesas ocupadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ $free }}</div>
            <div class="stat-lab"><i class="ti ti-circle-check"></i> Mesas livres</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">{{ $cancelledCount }}</div>
            <div class="stat-lab"><i class="ti ti-circle-x"></i> Cancelamentos do dia</div>
        </div>
    </div>

    <div style="display:flex;gap:10px;margin-bottom:22px;flex-wrap:wrap;">
        <a href="{{ route('gerente.products.index') }}" class="btn btn-primary"><i class="ti ti-tag"></i> Gerenciar Produtos</a>
        <a href="{{ route('gerente.categories.index') }}" class="btn btn-ghost"><i class="ti ti-category"></i> Gerenciar Categorias</a>
        <a href="{{ route('gerente.pasta-options.index') }}" class="btn btn-ghost"><i class="ti ti-adjustments"></i> Opções "Monte sua Massa"</a>
    </div>

    <div class="dash-grid">
        {{-- Pratos mais pedidos --}}
        <div>
            <h2 class="page-title" style="font-size:16px;margin-bottom:12px;">Pratos mais pedidos hoje</h2>
            <div class="rank-list">
                @forelse($topProducts as $p)
                    <div class="rank-row">
                        <span class="rank-name">{{ $p->product_name }}</span>
                        <span class="rank-qty">{{ $p->total_qty }}x</span>
                    </div>
                @empty
                    <div class="rank-row" style="color:var(--muted);justify-content:center;">Nenhum pedido registrado hoje.</div>
                @endforelse
            </div>
        </div>

        {{-- Cancelamentos --}}
        <div>
            <h2 class="page-title" style="font-size:16px;margin-bottom:12px;">Últimos cancelamentos</h2>
            @forelse($cancelledItems as $item)
                <div class="cancel-row">
                    <b>{{ $item->quantity }}x {{ $item->product_name }}</b> · Mesa {{ $item->order->table->number ?? '—' }}
                    <div class="cancel-meta">
                        Motivo: {{ $item->cancel_reason }}
                        @if($item->cancelledBy) · por {{ $item->cancelledBy->name }} @endif
                        · {{ $item->cancelled_at->format('H:i') }}
                    </div>
                </div>
            @empty
                <div class="rank-row" style="color:var(--muted);justify-content:center;">Nenhum cancelamento hoje.</div>
            @endforelse
        </div>
    </div>

    <h2 class="page-title" style="font-size:17px;">Últimos pedidos</h2>
    <table class="table" style="margin-top:12px;">
        <thead>
            <tr><th>Pedido</th><th>Mesa</th><th>Itens</th><th>Status</th><th>Total</th><th>Horário</th></tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td>#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>Mesa {{ $order->table->number }}</td>
                    <td>{{ $order->items->whereNull('cancelled_at')->sum('quantity') }}</td>
                    <td>{{ $order->status_label }}</td>
                    <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d/m H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">Nenhum pedido registrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
