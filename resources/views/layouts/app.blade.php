<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'O Casarão') — O Casarão</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @auth
    <header class="topbar">
        <div class="topbar-brand">
            <div class="brand-box">C</div>
            <div>
                <div class="brand-title">O Casarão</div>
                <div class="brand-sub">Cozinha Brasileira &amp; Espetaria</div>
            </div>
        </div>

        <nav class="topbar-nav">
            @php $slug = auth()->user()->role?->slug; @endphp
            {{-- Links exibidos conforme o perfil --}}
            @if(in_array($slug, ['cliente','garcom','gerente']))
                <a href="{{ route('cliente.mesas') }}" class="{{ request()->routeIs('cliente.*') ? 'active' : '' }}"><i class="ti ti-device-tablet"></i> Mesas</a>
            @endif
            @if(in_array($slug, ['garcom','gerente']))
                <a href="{{ route('garcom.index') }}" class="{{ request()->routeIs('garcom.*') ? 'active' : '' }}"><i class="ti ti-bell"></i> Garçom</a>
            @endif
            @if(in_array($slug, ['cozinha','gerente']))
                <a href="{{ route('cozinha.index') }}" class="{{ request()->routeIs('cozinha.*') ? 'active' : '' }}"><i class="ti ti-tools-kitchen-2"></i> Cozinha</a>
            @endif
            @if($slug === 'gerente')
                <a href="{{ route('gerente.dashboard') }}" class="{{ request()->routeIs('gerente.dashboard') ? 'active' : '' }}"><i class="ti ti-chart-bar"></i> Dashboard</a>
                <a href="{{ route('gerente.products.index') }}" class="{{ request()->routeIs('gerente.products.*') ? 'active' : '' }}"><i class="ti ti-tag"></i> Produtos</a>
                <a href="{{ route('gerente.categories.index') }}" class="{{ request()->routeIs('gerente.categories.*') ? 'active' : '' }}"><i class="ti ti-category"></i> Categorias</a>
                <a href="{{ route('gerente.pasta-options.index') }}" class="{{ request()->routeIs('gerente.pasta-options.*') ? 'active' : '' }}"><i class="ti ti-adjustments"></i> Monte sua Massa</a>
            @endif
        </nav>

        <div class="topbar-user">
            <span><i class="ti ti-user"></i> {{ auth()->user()->name }} · {{ auth()->user()->role?->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout"><i class="ti ti-logout"></i> Sair</button>
            </form>
        </div>
    </header>
    @endauth

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
