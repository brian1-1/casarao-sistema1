<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'O Casarão'); ?> — O Casarão</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
    <header class="topbar">
        <div class="topbar-brand">
            <div class="brand-box">C</div>
            <div>
                <div class="brand-title">O Casarão</div>
                <div class="brand-sub">Cozinha Brasileira &amp; Espetaria</div>
            </div>
        </div>

        <nav class="topbar-nav">
            <?php $slug = auth()->user()->role?->slug; ?>
            
            <?php if(in_array($slug, ['cliente','garcom','gerente'])): ?>
                <a href="<?php echo e(route('cliente.mesas')); ?>" class="<?php echo e(request()->routeIs('cliente.*') ? 'active' : ''); ?>"><i class="ti ti-device-tablet"></i> Mesas</a>
            <?php endif; ?>
            <?php if(in_array($slug, ['garcom','gerente'])): ?>
                <a href="<?php echo e(route('garcom.index')); ?>" class="<?php echo e(request()->routeIs('garcom.*') ? 'active' : ''); ?>"><i class="ti ti-bell"></i> Garçom</a>
            <?php endif; ?>
            <?php if(in_array($slug, ['cozinha','gerente'])): ?>
                <a href="<?php echo e(route('cozinha.index')); ?>" class="<?php echo e(request()->routeIs('cozinha.*') ? 'active' : ''); ?>"><i class="ti ti-tools-kitchen-2"></i> Cozinha</a>
            <?php endif; ?>
            <?php if($slug === 'gerente'): ?>
                <a href="<?php echo e(route('gerente.dashboard')); ?>" class="<?php echo e(request()->routeIs('gerente.dashboard') ? 'active' : ''); ?>"><i class="ti ti-chart-bar"></i> Dashboard</a>
                <a href="<?php echo e(route('gerente.products.index')); ?>" class="<?php echo e(request()->routeIs('gerente.products.*') ? 'active' : ''); ?>"><i class="ti ti-tag"></i> Produtos</a>
                <a href="<?php echo e(route('gerente.categories.index')); ?>" class="<?php echo e(request()->routeIs('gerente.categories.*') ? 'active' : ''); ?>"><i class="ti ti-category"></i> Categorias</a>
            <?php endif; ?>
        </nav>

        <div class="topbar-user">
            <span><i class="ti ti-user"></i> <?php echo e(auth()->user()->name); ?> · <?php echo e(auth()->user()->role?->name); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-logout"><i class="ti ti-logout"></i> Sair</button>
            </form>
        </div>
    </header>
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/layouts/app.blade.php ENDPATH**/ ?>