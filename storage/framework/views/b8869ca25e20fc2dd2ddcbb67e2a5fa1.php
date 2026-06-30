<?php $__env->startSection('title', 'Opções "Monte sua Massa"'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:900px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Opções "Monte sua Massa"</h1>
            <p class="page-sub">Cadastre as massas, molhos e ingredientes disponíveis para personalização.</p>
        </div>
        <a href="<?php echo e(route('gerente.pasta-options.create')); ?>" class="btn btn-primary"><i class="ti ti-plus"></i> Nova opção</a>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $labels = ['massa' => 'Massas', 'molho' => 'Molhos', 'ingrediente' => 'Ingredientes'];
    ?>

    <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h2 class="page-title" style="font-size:16px;margin:22px 0 10px;"><?php echo e($label); ?></h2>
        <table class="table" style="margin-bottom:8px;">
            <thead>
                <tr><th>Ordem</th><th>Nome</th><th>Preço adicional</th><th>Ativo</th><th style="text-align:right;">Ações</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pastaOptions->where('type', $type); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($opt->sort_order); ?></td>
                        <td><?php echo e($opt->name); ?></td>
                        <td><?php echo e($opt->extra_price > 0 ? 'R$ ' . number_format($opt->extra_price, 2, ',', '.') : '—'); ?></td>
                        <td>
                            <?php if($opt->active): ?>
                                <span class="badge badge-livre">Sim</span>
                            <?php else: ?>
                                <span class="badge badge-fechada">Não</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="<?php echo e(route('gerente.pasta-options.edit', $opt)); ?>" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i></a>
                            <form method="POST" action="<?php echo e(route('gerente.pasta-options.destroy', $opt)); ?>" style="display:inline;"
                                  onsubmit="return confirm('Excluir a opção \'<?php echo e($opt->name); ?>\'?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);">Nenhuma opção cadastrada nesta categoria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/brian/Documentos/restaurante/resources/views/gerente/pasta_options/index.blade.php ENDPATH**/ ?>