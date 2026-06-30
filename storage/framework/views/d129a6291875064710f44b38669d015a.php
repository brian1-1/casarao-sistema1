
<?php if(session('success')): ?>
    <div class="alert alert-success"><i class="ti ti-circle-check"></i> <?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="alert alert-error"><i class="ti ti-alert-triangle"></i> <?php echo e($errors->first()); ?></div>
<?php endif; ?>
<?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/partials/flash.blade.php ENDPATH**/ ?>