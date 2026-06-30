<?php $__env->startSection('title', 'Cozinha'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .k-cols { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; align-items: start; }
    .kcol { background: #efe9e2; border-radius: var(--radius); padding: 14px; min-height: 200px; }
    .kcol-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .kcol-title { font-size: 14px; font-weight: 800; display: flex; align-items: center; gap: 7px; }
    .kdot { width: 10px; height: 10px; border-radius: 50%; }
    .kcount { background: #fff; border-radius: 20px; padding: 2px 10px; font-size: 12px; font-weight: 800; }
    .kcard { background: #fff; border-radius: 12px; padding: 14px; margin-bottom: 12px; box-shadow: var(--shadow); }
    .kcard-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
    .kcard-mesa { font-size: 16px; font-weight: 800; }
    .kcard-time { font-size: 11px; color: var(--muted); }
    .kcard-meta { font-size: 11px; color: var(--muted); margin-bottom: 8px; }
    .kcard-item { display: flex; gap: 8px; padding: 4px 0; border-top: 1px dashed var(--border); }
    .kcard-qty { font-weight: 800; color: var(--brand); }
    .kcard-iname { font-size: 13px; font-weight: 600; }
    .kcard-iobs { font-size: 11px; color: var(--amber); }
    .kcard-foot { margin-top: 10px; display: flex; gap: 6px; }
    .kcol-empty { text-align: center; color: var(--muted); font-size: 13px; padding: 26px 0; }
    @media (max-width: 880px) { .k-cols { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Cozinha</h1>
            <p class="page-sub">Pedidos em tempo real · atualização automática a cada 10s.</p>
        </div>
        <div id="clock" style="font-size:20px;font-weight:800;"></div>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="k-cols">
        
        <div class="kcol">
            <div class="kcol-head">
                <div class="kcol-title"><span class="kdot" style="background:var(--amber)"></span> Pedido recebido</div>
                <span class="kcount"><?php echo e($recebidos->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $recebidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('cozinha.partials.card', ['order' => $order, 'next' => 'em_preparo', 'label' => 'Iniciar preparo', 'btnClass' => 'btn-primary'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="kcol-empty"><i class="ti ti-check"></i><br>Nenhum pedido novo</div>
            <?php endif; ?>
        </div>

        
        <div class="kcol">
            <div class="kcol-head">
                <div class="kcol-title"><span class="kdot" style="background:var(--blue)"></span> Em preparo</div>
                <span class="kcount"><?php echo e($emPreparo->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $emPreparo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('cozinha.partials.card', ['order' => $order, 'next' => 'pronto', 'label' => 'Marcar pronto', 'btnClass' => 'btn-green'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="kcol-empty"><i class="ti ti-tools-kitchen-2"></i><br>Nada em preparo</div>
            <?php endif; ?>
        </div>

        
        <div class="kcol">
            <div class="kcol-head">
                <div class="kcol-title"><span class="kdot" style="background:var(--green)"></span> Pronto para servir</div>
                <span class="kcount"><?php echo e($prontos->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $prontos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('cozinha.partials.card', ['order' => $order, 'next' => 'entregue', 'label' => 'Marcar entregue', 'btnClass' => 'btn-gold'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="kcol-empty"><i class="ti ti-bell"></i><br>Nenhum pronto ainda</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Relógio
    function tick() { document.getElementById('clock').textContent = new Date().toLocaleTimeString('pt-BR'); }
    setInterval(tick, 1000); tick();
    // Auto-refresh (polling básico) a cada 10 segundos
    setTimeout(() => location.reload(), 10000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/cozinha/index.blade.php ENDPATH**/ ?>