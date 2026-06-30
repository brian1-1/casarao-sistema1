@extends('layouts.app')
@section('title', 'Mesas')

@push('styles')
<style>
    .mesa-card { text-align: center; padding: 22px; cursor: pointer; transition: transform .12s, box-shadow .12s; display: block; }
    .mesa-card:hover { transform: translateY(-3px); box-shadow: 0 8px 22px rgba(0,0,0,0.12); }
    .mesa-num { font-size: 34px; font-weight: 800; color: var(--text); }
    .mesa-lab { font-size: 12px; color: var(--muted); margin-bottom: 10px; }
</style>
@endpush

@section('content')
<div class="container">
    <h1 class="page-title">Mesas do Restaurante</h1>
    <p class="page-sub">Selecione uma mesa para abrir o cardápio e a comanda.</p>

    @include('partials.flash')

    <div class="cards-grid">
        @foreach($tables as $table)
            <a href="{{ route('cliente.menu', $table) }}" class="card mesa-card">
                <div class="mesa-num">{{ $table->number }}</div>
                <div class="mesa-lab">Mesa</div>
                <span class="badge badge-{{ $table->status }}">{{ $table->status_label }}</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
