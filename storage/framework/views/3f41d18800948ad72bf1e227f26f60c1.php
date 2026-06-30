
<div class="kcard">
    <div class="kcard-head">
        <span class="kcard-mesa">Mesa <?php echo e($item->order->table->number); ?></span>
        <span class="kcard-time"><i class="ti ti-clock"></i> <?php echo e($item->created_at->format('H:i')); ?></span>
    </div>
    <div class="kcard-meta">
        Pedido #<?php echo e(str_pad($item->order_id, 3, '0', STR_PAD_LEFT)); ?>

        <?php if($item->isCustom()): ?>
            <span class="kcard-tag-custom"><i class="ti ti-adjustments"></i> Monte sua Massa</span>
        <?php endif; ?>
    </div>

    <div class="kcard-item">
        <span class="kcard-qty"><?php echo e($item->quantity); ?></span>
        <div style="display:inline-block;vertical-align:top;margin-left:6px;">
            <div class="kcard-iname"><?php echo e($item->product_name); ?></div>
            <?php if($item->isCustom()): ?>
                <div class="kcard-icustom"><?php echo e($item->customization_label); ?></div>
            <?php endif; ?>
            <?php if($item->notes): ?><div class="kcard-iobs">obs: <?php echo e($item->notes); ?></div><?php endif; ?>
        </div>
    </div>

    <?php if($item->order->notes): ?>
        <div class="kcard-iobs" style="margin-top:6px;">Obs. geral: <?php echo e($item->order->notes); ?></div>
    <?php endif; ?>

    <?php if($next && $label): ?>
        <div class="kcard-foot">
            <form method="POST" action="<?php echo e(route('cozinha.item.status', $item)); ?>" style="flex:1;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="status" value="<?php echo e($next); ?>">
                <button type="submit" class="btn <?php echo e($btnClass); ?> btn-sm" style="width:100%;"><?php echo e($label); ?></button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH /home/brian/Documentos/restaurante/resources/views/cozinha/partials/card.blade.php ENDPATH**/ ?>