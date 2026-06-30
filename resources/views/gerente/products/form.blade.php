@extends('layouts.app')
@section('title', $product->exists ? 'Editar produto' : 'Novo produto')

@section('content')
<div class="container" style="max-width:640px;">
    <h1 class="page-title">{{ $product->exists ? 'Editar produto' : 'Novo produto' }}</h1>
    <p class="page-sub">Preencha os dados do produto.</p>

    @include('partials.flash')

    <form method="POST"
          action="{{ $product->exists ? route('gerente.products.update', $product) : route('gerente.products.store') }}"
          enctype="multipart/form-data" class="card card-pad">
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">Nome *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Categoria *</label>
            <select name="category_id" class="form-control" required>
                <option value="">Selecione...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Serve quantas pessoas?</label>
            <input type="text" name="serves" class="form-control" placeholder="Ex: Serve 1 pessoa / Serve 2 pessoas / Serve 3 unidades" value="{{ old('serves', $product->serves) }}">
            @error('serves')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Preço (R$) *</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            @error('price')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Imagem (opcional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">Se não enviar, será usado um placeholder padrão.</div>
            @if($product->image)
                <img src="{{ $product->image_url }}" alt="" style="margin-top:10px;width:120px;border-radius:8px;">
            @endif
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="available" value="1" @checked(old('available', $product->exists ? $product->available : true))>
                <span>Disponível para venda</span>
            </label>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_customizable" value="1" @checked(old('is_customizable', $product->is_customizable))>
                <span>É um prato "Monte sua Massa" (cliente escolhe massa, molho e ingredientes)</span>
            </label>
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">
                Marque apenas para pratos que devem usar o seletor de personalização. Configure as opções de massa, molho e ingredientes em
                <a href="{{ route('gerente.pasta-options.index') }}" style="text-decoration:underline;">"Opções Monte sua Massa"</a>.
            </div>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="requires_meat_point" value="1" @checked(old('requires_meat_point', $product->requires_meat_point))>
                <span>É um prato de carne (cliente escolhe o ponto: mal passado, ao ponto ou bem passado)</span>
            </label>
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">
                Marque para pratos de carne. O cliente verá um seletor de ponto antes de adicionar o item à comanda.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Salvar</button>
            <a href="{{ route('gerente.products.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
