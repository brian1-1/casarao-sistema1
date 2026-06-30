@extends('layouts.app')
@section('title', $pastaOption->exists ? 'Editar opção' : 'Nova opção')

@section('content')
<div class="container" style="max-width:560px;">
    <h1 class="page-title">{{ $pastaOption->exists ? 'Editar opção' : 'Nova opção' }}</h1>
    <p class="page-sub">Cadastre uma massa, molho ou ingrediente do "Monte sua Massa".</p>

    @include('partials.flash')

    <form method="POST"
          action="{{ $pastaOption->exists ? route('gerente.pasta-options.update', $pastaOption) : route('gerente.pasta-options.store') }}"
          class="card card-pad">
        @csrf
        @if($pastaOption->exists) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">Tipo *</label>
            <select name="type" class="form-control" required>
                <option value="">Selecione...</option>
                <option value="massa" @selected(old('type', $pastaOption->type) === 'massa')>Massa</option>
                <option value="molho" @selected(old('type', $pastaOption->type) === 'molho')>Molho</option>
                <option value="ingrediente" @selected(old('type', $pastaOption->type) === 'ingrediente')>Ingrediente</option>
            </select>
            @error('type')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nome *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $pastaOption->name) }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Preço adicional (R$)</label>
            <input type="number" step="0.01" min="0" name="extra_price" class="form-control" value="{{ old('extra_price', $pastaOption->extra_price ?? 0) }}">
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">Deixe 0 para opções sem custo extra. Só faz sentido para ingredientes.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Ordem de exibição</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $pastaOption->sort_order ?? 0) }}" min="0">
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="active" value="1" @checked(old('active', $pastaOption->exists ? $pastaOption->active : true))>
                <span>Opção ativa (aparece para o cliente)</span>
            </label>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Salvar</button>
            <a href="{{ route('gerente.pasta-options.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
