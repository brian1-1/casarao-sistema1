<?php $__env->startSection('title', 'Painel do Garçom'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .g-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start; }
    .mesa-tile { padding: 16px; }
    .mesa-tile-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .mesa-tile-num { font-size: 20px; font-weight: 800; }
    .mesa-info { font-size: 12px; color: var(--muted); display: flex; flex-direction: column; gap: 3px; }
    .mesa-info b { color: var(--text); }
    .ready-card { padding: 14px; margin-bottom: 12px; }
    .ready-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .ready-mesa { font-weight: 800; }
    .ready-item { font-size: 13px; padding: 3px 0; }
    .ready-obs { font-size: 11px; color: var(--muted); }
    .section-h { font-size: 15px; font-weight: 800; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    @media (max-width: 880px) { .g-grid { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="page-title">Painel do Garçom</h1>
    <p class="page-sub">Acompanhe o status das mesas e os pedidos prontos para entrega.</p>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="g-grid">
        
        <div>
            <div class="section-h"><i class="ti ti-layout-grid"></i> Mesas</div>
            <div class="cards-grid">
                <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mesa-tile">
                        <div class="mesa-tile-head">
                            <span class="mesa-tile-num">Mesa <?php echo e($t['model']->number); ?></span>
                            <span class="badge badge-<?php echo e($t['model']->status); ?>"><?php echo e($t['model']->status_label); ?></span>
                        </div>
                        <div class="mesa-info">
                            <span>Valor parcial: <b>R$ <?php echo e(number_format($t['partial'], 2, ',', '.')); ?></b></span>
                            <span>Pedidos: <b><?php echo e($t['orders_count']); ?></b></span>
                            <span>Abertura: <b><?php echo e($t['opened_at'] ? $t['opened_at']->format('H:i') : '—'); ?></b></span>
                        </div>
                        <a href="<?php echo e(route('cliente.menu', $t['model'])); ?>" class="btn btn-ghost btn-sm" style="margin-top:10px;width:100%;">
                            <i class="ti ti-eye"></i> Abrir comanda
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <div class="section-h"><i class="ti ti-bell-ringing"></i> Prontos para entregar</div>
            <?php $__empty_1 = true; $__currentLoopData = $readyOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card ready-card">
                    <div class="ready-head">
                        <span class="ready-mesa">Mesa <?php echo e($order->table->number); ?></span>
                        <span style="font-size:11px;color:var(--muted);"><i class="ti ti-clock"></i> <?php echo e($order->created_at->format('H:i')); ?></span>
                    </div>
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ready-item"><?php echo e($item->quantity); ?>x <?php echo e($item->product_name); ?>

                            <?php if($item->notes): ?><div class="ready-obs">obs: <?php echo e($item->notes); ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <form method="POST" action="<?php echo e(route('garcom.deliver', $order)); ?>" style="margin-top:10px;">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-green btn-sm" style="width:100%;"><i class="ti ti-check"></i> Marcar como entregue</button>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="card card-pad" style="text-align:center;color:var(--muted);font-size:13px;">
                    <i class="ti ti-coffee" style="font-size:26px;"></i><br>Nenhum pedido pronto.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Atualização automática a cada 15 segundos
    setTimeout(() => location.reload(), 15000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/garcom/index.blade.php ENDPATH**/ ?>