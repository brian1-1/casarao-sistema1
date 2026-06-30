<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .rank-list { display: flex; flex-direction: column; gap: 8px; }
    .rank-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #faf7f3; border-radius: 9px; font-size: 13px; }
    .rank-name { font-weight: 700; }
    .rank-qty { color: var(--brand); font-weight: 800; }
    .cancel-row { padding: 10px 12px; border-radius: 9px; background: #fbe6e1; margin-bottom: 8px; font-size: 12px; }
    .cancel-row b { color: #962a13; }
    .cancel-meta { color: var(--muted); margin-top: 2px; }
    @media (max-width: 880px) { .dash-grid { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

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
        <div class="stat-card">
            <div class="stat-val"><?php echo e($cancelledCount); ?></div>
            <div class="stat-lab"><i class="ti ti-circle-x"></i> Cancelamentos do dia</div>
        </div>
    </div>

    <div style="display:flex;gap:10px;margin-bottom:22px;flex-wrap:wrap;">
        <a href="<?php echo e(route('gerente.products.index')); ?>" class="btn btn-primary"><i class="ti ti-tag"></i> Gerenciar Produtos</a>
        <a href="<?php echo e(route('gerente.categories.index')); ?>" class="btn btn-ghost"><i class="ti ti-category"></i> Gerenciar Categorias</a>
        <a href="<?php echo e(route('gerente.pasta-options.index')); ?>" class="btn btn-ghost"><i class="ti ti-adjustments"></i> Opções "Monte sua Massa"</a>
    </div>

    <div class="dash-grid">
        
        <div>
            <h2 class="page-title" style="font-size:16px;margin-bottom:12px;">Pratos mais pedidos hoje</h2>
            <div class="rank-list">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="rank-row">
                        <span class="rank-name"><?php echo e($p->product_name); ?></span>
                        <span class="rank-qty"><?php echo e($p->total_qty); ?>x</span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rank-row" style="color:var(--muted);justify-content:center;">Nenhum pedido registrado hoje.</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div>
            <h2 class="page-title" style="font-size:16px;margin-bottom:12px;">Últimos cancelamentos</h2>
            <?php $__empty_1 = true; $__currentLoopData = $cancelledItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="cancel-row">
                    <b><?php echo e($item->quantity); ?>x <?php echo e($item->product_name); ?></b> · Mesa <?php echo e($item->order->table->number ?? '—'); ?>

                    <div class="cancel-meta">
                        Motivo: <?php echo e($item->cancel_reason); ?>

                        <?php if($item->cancelledBy): ?> · por <?php echo e($item->cancelledBy->name); ?> <?php endif; ?>
                        · <?php echo e($item->cancelled_at->format('H:i')); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="rank-row" style="color:var(--muted);justify-content:center;">Nenhum cancelamento hoje.</div>
            <?php endif; ?>
        </div>
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
                    <td><?php echo e($order->items->whereNull('cancelled_at')->sum('quantity')); ?></td>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/brian/Documentos/restaurante/resources/views/gerente/dashboard.blade.php ENDPATH**/ ?>