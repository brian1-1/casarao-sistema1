@extends('layouts.app')
@section('title', 'Categorias')

@section('content')
<div class="container" style="max-width:820px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Categorias</h1>
            <p class="page-sub">Organize os produtos do cardápio.</p>
        </div>
        <a href="{{ route('gerente.categories.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Nova categoria</a>
    </div>

    @include('partials.flash')

    <table class="table">
        <thead>
            <tr><th>Ordem</th><th>Categoria</th><th>Produtos</th><th>Ativa</th><th style="text-align:right;">Ações</th></tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->sort_order }}</td>
                    <td><i class="ti {{ $cat->icon ?? 'ti-category' }}"></i> {{ $cat->name }}</td>
                    <td>{{ $cat->products_count }}</td>
                    <td>
                        @if($cat->active)
                            <span class="badge badge-livre">Sim</span>
                        @else
                            <span class="badge badge-fechada">Não</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('gerente.categories.edit', $cat) }}" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i></a>
                        <form method="POST" action="{{ route('gerente.categories.destroy', $cat) }}" style="display:inline;"
                              onsubmit="return confirm('Excluir a categoria \'{{ $cat->name }}\'?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);">Nenhuma categoria cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
