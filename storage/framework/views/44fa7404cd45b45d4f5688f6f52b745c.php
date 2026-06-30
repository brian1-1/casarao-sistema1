<?php $__env->startSection('title', $product->exists ? 'Editar produto' : 'Novo produto'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:640px;">
    <h1 class="page-title"><?php echo e($product->exists ? 'Editar produto' : 'Novo produto'); ?></h1>
    <p class="page-sub">Preencha os dados do produto.</p>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form method="POST"
          action="<?php echo e($product->exists ? route('gerente.products.update', $product) : route('gerente.products.store')); ?>"
          enctype="multipart/form-data" class="card card-pad">
        <?php echo csrf_field(); ?>
        <?php if($product->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <div class="form-group">
            <label class="form-label">Nome *</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $product->name)); ?>" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Categoria *</label>
            <select name="category_id" class="form-control" required>
                <option value="">Selecione...</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php if(old('category_id', $product->category_id) == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $product->description)); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Preço (R$) *</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="<?php echo e(old('price', $product->price)); ?>" required>
            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Imagem (opcional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">Se não enviar, será usado um placeholder padrão.</div>
            <?php if($product->image): ?>
                <img src="<?php echo e($product->image_url); ?>" alt="" style="margin-top:10px;width:120px;border-radius:8px;">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="available" value="1" <?php if(old('available', $product->exists ? $product->available : true)): echo 'checked'; endif; ?>>
                <span>Disponível para venda</span>
            </label>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Salvar</button>
            <a href="<?php echo e(route('gerente.products.index')); ?>" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/gerente/products/form.blade.php ENDPATH**/ ?>