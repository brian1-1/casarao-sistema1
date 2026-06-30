<?php $__env->startSection('title', 'Produtos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Produtos</h1>
            <p class="page-sub">Gerencie o cardápio do restaurante.</p>
        </div>
        <a href="<?php echo e(route('gerente.products.create')); ?>" class="btn btn-primary"><i class="ti ti-plus"></i> Novo produto</a>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <table class="table">
        <thead>
            <tr><th>Produto</th><th>Categoria</th><th>Serve</th><th>Preço</th><th>Disponível</th><th style="text-align:right;">Ações</th></tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="display:flex;align-items:center;gap:10px;">
                        <img src="<?php echo e($product->image_url); ?>" alt="" style="width:42px;height:42px;border-radius:8px;object-fit:cover;"
                             onerror="this.onerror=null;this.src='<?php echo e(asset('images/placeholder.svg')); ?>';">
                        <div>
                            <div style="font-weight:700;display:flex;align-items:center;gap:6px;">
                                <?php echo e($product->name); ?>

                                <?php if($product->is_customizable): ?>
                                    <span class="badge badge-ocupada" title="Monte sua Massa"><i class="ti ti-adjustments"></i></span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:11px;color:var(--muted);"><?php echo e(Str::limit($product->description, 50)); ?></div>
                        </div>
                    </td>
                    <td><?php echo e($product->category->name ?? '—'); ?></td>
                    <td style="font-size:12px;color:var(--muted);"><?php echo e($product->serves ?: '—'); ?></td>
                    <td>R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?></td>
                    <td>
                        <?php if($product->available): ?>
                            <span class="badge badge-livre">Sim</span>
                        <?php else: ?>
                            <span class="badge badge-fechada">Não</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?php echo e(route('gerente.products.edit', $product)); ?>" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i></a>
                        <form method="POST" action="<?php echo e(route('gerente.products.destroy', $product)); ?>" style="display:inline;"
                              onsubmit="return confirm('Tem certeza que deseja excluir o produto \'<?php echo e($product->name); ?>\'?');">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">Nenhum produto cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top:16px;"><?php echo e($products->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/brian/Documentos/restaurante/resources/views/gerente/products/index.blade.php ENDPATH**/ ?>