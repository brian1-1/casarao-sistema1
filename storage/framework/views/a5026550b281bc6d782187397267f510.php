<?php $__env->startSection('title', 'Categorias'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:820px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Categorias</h1>
            <p class="page-sub">Organize os produtos do cardápio.</p>
        </div>
        <a href="<?php echo e(route('gerente.categories.create')); ?>" class="btn btn-primary"><i class="ti ti-plus"></i> Nova categoria</a>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <table class="table">
        <thead>
            <tr><th>Ordem</th><th>Categoria</th><th>Produtos</th><th>Ativa</th><th style="text-align:right;">Ações</th></tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($cat->sort_order); ?></td>
                    <td><i class="ti <?php echo e($cat->icon ?? 'ti-category'); ?>"></i> <?php echo e($cat->name); ?></td>
                    <td><?php echo e($cat->products_count); ?></td>
                    <td>
                        <?php if($cat->active): ?>
                            <span class="badge badge-livre">Sim</span>
                        <?php else: ?>
                            <span class="badge badge-fechada">Não</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?php echo e(route('gerente.categories.edit', $cat)); ?>" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i></a>
                        <form method="POST" action="<?php echo e(route('gerente.categories.destroy', $cat)); ?>" style="display:inline;"
                              onsubmit="return confirm('Excluir a categoria \'<?php echo e($cat->name); ?>\'?');">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--muted);">Nenhuma categoria cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/gerente/categories/index.blade.php ENDPATH**/ ?>