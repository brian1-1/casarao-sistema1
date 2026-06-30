@extends('layouts.app')
@section('title', 'Produtos')

@section('content')
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Produtos</h1>
            <p class="page-sub">Gerencie o cardápio do restaurante.</p>
        </div>
        <a href="{{ route('gerente.products.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Novo produto</a>
    </div>

    @include('partials.flash')

    <table class="table">
        <thead>
            <tr><th>Produto</th><th>Categoria</th><th>Serve</th><th>Preço</th><th>Disponível</th><th style="text-align:right;">Ações</th></tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td style="display:flex;align-items:center;gap:10px;">
                        <img src="{{ $product->image_url }}" alt="" style="width:42px;height:42px;border-radius:8px;object-fit:cover;"
                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
                        <div>
                            <div style="font-weight:700;display:flex;align-items:center;gap:6px;">
                                {{ $product->name }}
                                @if($product->is_customizable)
                                    <span class="badge badge-ocupada" title="Monte sua Massa"><i class="ti ti-adjustments"></i></span>
                                @endif
                                @if($product->requires_meat_point)
                                    <span class="badge badge-ocupada" title="Pede ponto da carne"><i class="ti ti-flame"></i></span>
                                @endif
                            </div>
                            <div style="font-size:11px;color:var(--muted);">{{ Str::limit($product->description, 50) }}</div>
                        </div>
                    </td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--muted);">{{ $product->serves ?: '—' }}</td>
                    <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                    <td>
                        @if($product->available)
                            <span class="badge badge-livre">Sim</span>
                        @else
                            <span class="badge badge-fechada">Não</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('gerente.products.edit', $product) }}" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i></a>
                        <form method="POST" action="{{ route('gerente.products.destroy', $product) }}" style="display:inline;"
                              onsubmit="return confirm('Tem certeza que deseja excluir o produto \'{{ $product->name }}\'?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">Nenhum produto cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">{{ $products->links() }}</div>
</div>
@endsection
