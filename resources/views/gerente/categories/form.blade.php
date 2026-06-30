@extends('layouts.app')
@section('title', $category->exists ? 'Editar categoria' : 'Nova categoria')

@section('content')
<div class="container" style="max-width:560px;">
    <h1 class="page-title">{{ $category->exists ? 'Editar categoria' : 'Nova categoria' }}</h1>
    <p class="page-sub">Preencha os dados da categoria.</p>

    @include('partials.flash')

    <form method="POST"
          action="{{ $category->exists ? route('gerente.categories.update', $category) : route('gerente.categories.store') }}"
          class="card card-pad">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">Nome *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Ícone (classe Tabler Icons)</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="ex: ti-meat">
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">Veja os ícones em tabler-icons.io. Ex.: ti-beer, ti-salad.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Ordem de exibição</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="active" value="1" @checked(old('active', $category->exists ? $category->active : true))>
                <span>Categoria ativa</span>
            </label>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Salvar</button>
            <a href="{{ route('gerente.categories.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
