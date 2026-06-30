<?php $__env->startSection('title', 'Mesas'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .mesa-card { text-align: center; padding: 22px; cursor: pointer; transition: transform .12s, box-shadow .12s; display: block; }
    .mesa-card:hover { transform: translateY(-3px); box-shadow: 0 8px 22px rgba(0,0,0,0.12); }
    .mesa-num { font-size: 34px; font-weight: 800; color: var(--text); }
    .mesa-lab { font-size: 12px; color: var(--muted); margin-bottom: 10px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="page-title">Mesas do Restaurante</h1>
    <p class="page-sub">Selecione uma mesa para abrir o cardápio e a comanda.</p>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="cards-grid">
        <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('cliente.menu', $table)); ?>" class="card mesa-card">
                <div class="mesa-num"><?php echo e($table->number); ?></div>
                <div class="mesa-lab">Mesa</div>
                <span class="badge badge-<?php echo e($table->status); ?>"><?php echo e($table->status_label); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/cliente/mesas.blade.php ENDPATH**/ ?>