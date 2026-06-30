<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">Visão geral do dia · <?php echo e(now()->format('d/m/Y')); ?></p>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-val">R$ <?php echo e(number_format($revenue, 2, ',', '.')); ?></div>
            <div class="stat-lab"><i class="ti ti-cash"></i> Faturamento do dia</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?php echo e($ordersCount); ?></div>
            <div class="stat-lab"><i class="ti ti-clipboard-list"></i> Pedidos do dia</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?php echo e($occupied); ?></div>
            <div class="stat-lab"><i class="ti ti-users"></i> Mesas ocupadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?php echo e($free); ?></div>
            <div class="stat-lab"><i class="ti ti-circle-check"></i> Mesas livres</div>
        </div>
    </div>

    <div style="display:flex;gap:10px;margin-bottom:22px;flex-wrap:wrap;">
        <a href="<?php echo e(route('gerente.products.index')); ?>" class="btn btn-primary"><i class="ti ti-tag"></i> Gerenciar Produtos</a>
        <a href="<?php echo e(route('gerente.categories.index')); ?>" class="btn btn-ghost"><i class="ti ti-category"></i> Gerenciar Categorias</a>
    </div>

    <h2 class="page-title" style="font-size:17px;">Últimos pedidos</h2>
    <table class="table" style="margin-top:12px;">
        <thead>
            <tr><th>Pedido</th><th>Mesa</th><th>Itens</th><th>Status</th><th>Total</th><th>Horário</th></tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>#<?php echo e(str_pad($order->id, 3, '0', STR_PAD_LEFT)); ?></td>
                    <td>Mesa <?php echo e($order->table->number); ?></td>
                    <td><?php echo e($order->items->sum('quantity')); ?></td>
                    <td><?php echo e($order->status_label); ?></td>
                    <td>R$ <?php echo e(number_format($order->total, 2, ',', '.')); ?></td>
                    <td><?php echo e($order->created_at->format('d/m H:i')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">Nenhum pedido registrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/gerente/dashboard.blade.php ENDPATH**/ ?>