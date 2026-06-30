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
    .kcard-meta { font-size: 11px; color: var(--muted); margin-bottom: 8px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .kcard-tag-custom { background: var(--gold); color: #1c1c1a; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 10px; }
    .kcard-item { padding: 6px 0; border-top: 1px dashed var(--border); }
    .kcard-qty { font-weight: 800; color: var(--brand); }
    .kcard-iname { font-size: 13px; font-weight: 600; }
    .kcard-iobs { font-size: 11px; color: var(--amber); }
    .kcard-icustom { font-size: 11px; color: #8a6b15; margin-top: 2px; }
    .kcard-foot { margin-top: 10px; display: flex; gap: 6px; }
    .kcol-empty { text-align: center; color: var(--muted); font-size: 13px; padding: 26px 0; }
    .massas-panel { margin-top: 22px; }
    .massa-ready-card { padding: 14px; margin-bottom: 10px; border-left: 4px solid var(--gold); }
    .massa-ready-head { display: flex; justify-content: space-between; align-items: center; font-weight: 800; font-size: 14px; }
    .massa-ready-detail { font-size: 12px; color: var(--muted); margin-top: 4px; }
    @media (max-width: 880px) { .k-cols { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Cozinha</h1>
            <p class="page-sub">Itens em tempo real · atualização automática a cada 10s.</p>
        </div>
        <div id="clock" style="font-size:20px;font-weight:800;"></div>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="k-cols">
        
        <div class="kcol">
            <div class="kcol-head">
                <div class="kcol-title"><span class="kdot" style="background:var(--amber)"></span> Enviado</div>
                <span class="kcount"><?php echo e($enviados->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $enviados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('cozinha.partials.card', ['item' => $item, 'next' => 'em_preparo', 'label' => 'Iniciar preparo', 'btnClass' => 'btn-primary'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="kcol-empty"><i class="ti ti-check"></i><br>Nenhum item novo</div>
            <?php endif; ?>
        </div>

        
        <div class="kcol">
            <div class="kcol-head">
                <div class="kcol-title"><span class="kdot" style="background:var(--blue)"></span> Em preparo</div>
                <span class="kcount"><?php echo e($emPreparo->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $emPreparo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('cozinha.partials.card', ['item' => $item, 'next' => 'pronto', 'label' => 'Marcar pronto', 'btnClass' => 'btn-green'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="kcol-empty"><i class="ti ti-tools-kitchen-2"></i><br>Nada em preparo</div>
            <?php endif; ?>
        </div>

        
        <div class="kcol">
            <div class="kcol-head">
                <div class="kcol-title"><span class="kdot" style="background:var(--green)"></span> Pronto para servir</div>
                <span class="kcount"><?php echo e($prontos->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $prontos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('cozinha.partials.card', ['item' => $item, 'next' => null, 'label' => null, 'btnClass' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="kcol-empty"><i class="ti ti-bell"></i><br>Nenhum pronto ainda</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="massas-panel">
        <div class="section-h" style="font-size:15px;font-weight:800;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
            <i class="ti ti-tools-kitchen-2"></i> Massas personalizadas prontas
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $massasProntas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card massa-ready-card">
                <div class="massa-ready-head">
                    <span>Mesa <?php echo e($item->order->table->number); ?> · <?php echo e($item->product_name); ?></span>
                    <span class="badge badge-livre"><i class="ti ti-check"></i> Pronta</span>
                </div>
                <div class="massa-ready-detail"><?php echo e($item->customization_label); ?></div>
                <?php if($item->notes): ?><div class="massa-ready-detail">obs: <?php echo e($item->notes); ?></div><?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card card-pad" style="text-align:center;color:var(--muted);font-size:13px;">
                Nenhuma massa personalizada pronta no momento.
            </div>
        <?php endif; ?>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/brian/Documentos/restaurante/resources/views/cozinha/index.blade.php ENDPATH**/ ?>