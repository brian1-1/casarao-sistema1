
<?php $totalItens = $order->items->sum('quantity'); ?>
<div class="kcard">
    <div class="kcard-head">
        <span class="kcard-mesa">Mesa <?php echo e($order->table->number); ?></span>
        <span class="kcard-time"><i class="ti ti-clock"></i> <?php echo e($order->created_at->format('H:i')); ?></span>
    </div>
    <div class="kcard-meta">Pedido #<?php echo e(str_pad($order->id, 3, '0', STR_PAD_LEFT)); ?> · <?php echo e($totalItens); ?> <?php echo e($totalItens > 1 ? 'itens' : 'item'); ?></div>

    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="kcard-item">
            <span class="kcard-qty"><?php echo e($item->quantity); ?></span>
            <div>
                <div class="kcard-iname"><?php echo e($item->product_name); ?></div>
                <?php if($item->notes): ?><div class="kcard-iobs">obs: <?php echo e($item->notes); ?></div><?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($order->notes): ?>
        <div class="kcard-iobs" style="margin-top:6px;">Obs. geral: <?php echo e($order->notes); ?></div>
    <?php endif; ?>

    <div class="kcard-foot">
        <form method="POST" action="<?php echo e(route('cozinha.status', $order)); ?>" style="flex:1;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="status" value="<?php echo e($next); ?>">
            <button type="submit" class="btn <?php echo e($btnClass); ?> btn-sm" style="width:100%;"><?php echo e($label); ?></button>
        </form>
    </div>
</div>
<?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/cozinha/partials/card.blade.php ENDPATH**/ ?>