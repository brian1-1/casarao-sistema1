@extends('layouts.app')
@section('title', 'Opções "Monte sua Massa"')

@section('content')
<div class="container" style="max-width:900px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Opções "Monte sua Massa"</h1>
            <p class="page-sub">Cadastre as massas, molhos e ingredientes disponíveis para personalização.</p>
        </div>
        <a href="{{ route('gerente.pasta-options.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Nova opção</a>
    </div>

    @include('partials.flash')

    @php
        $labels = ['massa' => 'Massas', 'molho' => 'Molhos', 'ingrediente' => 'Ingredientes'];
    @endphp

    @foreach($labels as $type => $label)
        <h2 class="page-title" style="font-size:16px;margin:22px 0 10px;">{{ $label }}</h2>
        <table class="table" style="margin-bottom:8px;">
            <thead>
                <tr><th>Ordem</th><th>Nome</th><th>Preço adicional</th><th>Ativo</th><th style="text-align:right;">Ações</th></tr>
            </thead>
            <tbody>
                @forelse($pastaOptions->where('type', $type) as $opt)
                    <tr>
                        <td>{{ $opt->sort_order }}</td>
                        <td>{{ $opt->name }}</td>
                        <td>{{ $opt->extra_price > 0 ? 'R$ ' . number_format($opt->extra_price, 2, ',', '.') : '—' }}</td>
                        <td>
                            @if($opt->active)
                                <span class="badge badge-livre">Sim</span>
                            @else
                                <span class="badge badge-fechada">Não</span>
                            @endif
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="{{ route('gerente.pasta-options.edit', $opt) }}" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i></a>
                            <form method="POST" action="{{ route('gerente.pasta-options.destroy', $opt) }}" style="display:inline;"
                                  onsubmit="return confirm('Excluir a opção \'{{ $opt->name }}\'?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);">Nenhuma opção cadastrada nesta categoria.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
</div>
@endsection
