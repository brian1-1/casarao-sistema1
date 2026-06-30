@extends('layouts.app')
@section('title', 'Painel do Garçom')

@push('styles')
<style>
    .g-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start; }
    .mesa-tile { padding: 16px; }
    .mesa-tile-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .mesa-tile-num { font-size: 20px; font-weight: 800; }
    .mesa-info { font-size: 12px; color: var(--muted); display: flex; flex-direction: column; gap: 3px; }
    .mesa-info b { color: var(--text); }
    .ready-card { padding: 14px; margin-bottom: 12px; }
    .ready-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .ready-mesa { font-weight: 800; }
    .ready-item { font-size: 13px; padding: 3px 0; }
    .ready-obs { font-size: 11px; color: var(--muted); }
    .section-h { font-size: 15px; font-weight: 800; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    @media (max-width: 880px) { .g-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container">
    <h1 class="page-title">Painel do Garçom</h1>
    <p class="page-sub">Acompanhe o status das mesas e os pedidos prontos para entrega.</p>

    @include('partials.flash')

    <div class="g-grid">
        {{-- Mesas --}}
        <div>
            <div class="section-h"><i class="ti ti-layout-grid"></i> Mesas</div>
            <div class="cards-grid">
                @foreach($tables as $t)
                    <div class="card mesa-tile">
                        <div class="mesa-tile-head">
                            <span class="mesa-tile-num">Mesa {{ $t['model']->number }}</span>
                            <span class="badge badge-{{ $t['model']->status }}">{{ $t['model']->status_label }}</span>
                        </div>
                        <div class="mesa-info">
                            <span>Valor parcial: <b>R$ {{ number_format($t['partial'], 2, ',', '.') }}</b></span>
                            <span>Pedidos: <b>{{ $t['orders_count'] }}</b></span>
                            <span>Abertura: <b>{{ $t['opened_at'] ? $t['opened_at']->format('H:i') : '—' }}</b></span>
                        </div>
                        <a href="{{ route('cliente.menu', $t['model']) }}" class="btn btn-ghost btn-sm" style="margin-top:10px;width:100%;">
                            <i class="ti ti-eye"></i> Abrir comanda
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pedidos prontos na cozinha --}}
        <div>
            <div class="section-h"><i class="ti ti-bell-ringing"></i> Prontos para entregar</div>
            @forelse($readyOrders as $order)
                <div class="card ready-card">
                    <div class="ready-head">
                        <span class="ready-mesa">Mesa {{ $order->table->number }}</span>
                        <span style="font-size:11px;color:var(--muted);"><i class="ti ti-clock"></i> {{ $order->created_at->format('H:i') }}</span>
                    </div>
                    @foreach($order->items as $item)
                        @if(!$item->isCancelled())
                            <div class="ready-item">{{ $item->quantity }}x {{ $item->product_name }}
                                @if($item->isCustom())<div class="ready-obs">{{ $item->customization_label }}</div>@endif
                                @if($item->notes)<div class="ready-obs">obs: {{ $item->notes }}</div>@endif
                            </div>
                        @endif
                    @endforeach
                    <form method="POST" action="{{ route('garcom.deliver', $order) }}" style="margin-top:10px;">
                        @csrf
                        <button class="btn btn-green btn-sm" style="width:100%;"><i class="ti ti-check"></i> Marcar como entregue</button>
                    </form>
                </div>
            @empty
                <div class="card card-pad" style="text-align:center;color:var(--muted);font-size:13px;">
                    <i class="ti ti-coffee" style="font-size:26px;"></i><br>Nenhum pedido pronto.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Atualização automática a cada 15 segundos
    setTimeout(() => location.reload(), 15000);
</script>
@endpush
