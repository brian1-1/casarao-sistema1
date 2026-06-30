<?php $__env->startSection('title', 'Mesa ' . $table->number); ?>

<?php
    // Totais do carrinho (itens ainda não enviados)
    $cartTotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
    // Total já enviado à cozinha (comanda aberta)
    $sentTotal = $sentOrders->sum('total');
    $grandTotal = $cartTotal + $sentTotal;
?>

<?php $__env->startPush('styles'); ?>
<style>
    .tc-layout { display: grid; grid-template-columns: 1fr 360px; gap: 20px; align-items: start; }
    .cat-nav { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
    .cat-btn { background: #fff; border: 1px solid var(--border); border-radius: 22px; padding: 8px 16px; font-size: 13px; font-weight: 700; color: var(--muted); display: inline-flex; gap: 6px; align-items: center; }
    .cat-btn.active { background: var(--brand); color: #fff; border-color: var(--brand); }
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
    .prod-card { display: flex; flex-direction: column; }
    .prod-img { width: 100%; height: 140px; object-fit: cover; background: #e8e0d8; }
    .prod-body { padding: 14px; display: flex; flex-direction: column; flex: 1; }
    .prod-name { font-weight: 700; font-size: 14px; }
    .prod-desc { font-size: 12px; color: var(--muted); margin: 4px 0 10px; flex: 1; line-height: 1.5; }
    .prod-foot { display: flex; align-items: center; justify-content: space-between; }
    .prod-price { font-weight: 800; color: var(--brand); font-size: 15px; }
    .btn-add { width: 38px; height: 38px; border-radius: 10px; background: var(--brand); color: #fff; border: none; font-size: 20px; display: flex; align-items: center; justify-content: center; }
    .cat-section { display: none; }
    .cat-section.active { display: block; }
    .cat-title { font-size: 18px; font-weight: 800; margin-bottom: 14px; }

    .comanda { position: sticky; top: 84px; background: var(--panel-bg); color: var(--panel-text); border-radius: var(--radius); padding: 18px; }
    .comanda-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .comanda-title { font-size: 15px; font-weight: 800; }
    .comanda-mesa { font-size: 12px; color: var(--panel-muted); }
    .comanda-list { max-height: 260px; overflow-y: auto; margin-bottom: 12px; }
    .citem { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .citem-info { flex: 1; }
    .citem-name { font-size: 13px; font-weight: 600; }
    .citem-obs { font-size: 11px; color: var(--panel-muted); }
    .citem-sub { font-size: 13px; font-weight: 700; white-space: nowrap; }
    .qty-ctrl { display: flex; align-items: center; gap: 4px; }
    .qty-btn { width: 24px; height: 24px; border-radius: 6px; border: none; background: rgba(255,255,255,0.14); color: #fff; font-size: 15px; display: flex; align-items: center; justify-content: center; }
    .qty-val { min-width: 22px; text-align: center; font-weight: 700; font-size: 13px; }
    .comanda-empty { text-align: center; color: var(--panel-muted); font-size: 13px; padding: 20px 0; }
    .comanda-total { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-top: 1px solid rgba(255,255,255,0.15); font-size: 16px; font-weight: 800; }
    .pay-methods { display: flex; gap: 8px; margin: 8px 0 12px; }
    .pay-opt { flex: 1; }
    .pay-opt input { display: none; }
    .pay-opt label { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 6px; border-radius: 9px; background: rgba(255,255,255,0.08); font-size: 11px; font-weight: 700; cursor: pointer; border: 2px solid transparent; }
    .pay-opt input:checked + label { border-color: var(--gold); background: rgba(212,160,23,0.18); }
    .sent-tag { font-size: 10px; background: var(--green); color: #fff; padding: 1px 6px; border-radius: 10px; font-weight: 700; }
    @media (max-width: 880px) { .tc-layout { grid-template-columns: 1fr; } .comanda { position: static; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 class="page-title">Mesa <?php echo e($table->number); ?> · Cardápio</h1>
            <p class="page-sub">Adicione itens, ajuste a quantidade e confirme o pedido.</p>
        </div>
        <a href="<?php echo e(route('cliente.mesas')); ?>" class="btn btn-ghost btn-sm"><i class="ti ti-arrow-left"></i> Voltar às mesas</a>
    </div>

    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="tc-layout">
        
        <div>
            <div class="cat-nav">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="cat-btn <?php echo e($i === 0 ? 'active' : ''); ?>" data-cat="<?php echo e($cat->slug); ?>" onclick="showCat('<?php echo e($cat->slug); ?>', this)">
                        <i class="ti <?php echo e($cat->icon ?? 'ti-tools-kitchen-2'); ?>"></i> <?php echo e($cat->name); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="cat-section <?php echo e($i === 0 ? 'active' : ''); ?>" id="cat-<?php echo e($cat->slug); ?>">
                    <div class="cat-title"><?php echo e($cat->name); ?></div>
                    <div class="menu-grid">
                        <?php $__empty_1 = true; $__currentLoopData = $cat->availableProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="card prod-card">
                                <img class="prod-img" src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>"
                                     onerror="this.onerror=null;this.src='<?php echo e(asset('images/placeholder.svg')); ?>';">
                                <div class="prod-body">
                                    <div class="prod-name"><?php echo e($product->name); ?></div>
                                    <div class="prod-desc"><?php echo e($product->description); ?></div>
                                    <div class="prod-foot">
                                        <div class="prod-price">R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?></div>
                                        <form method="POST" action="<?php echo e(route('cliente.item.add', $table)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                            <button class="btn-add" type="submit" aria-label="Adicionar <?php echo e($product->name); ?>">+</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="page-sub">Nenhum produto disponível nesta categoria.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <aside class="comanda">
            <div class="comanda-head">
                <div>
                    <div class="comanda-title">Comanda</div>
                    <div class="comanda-mesa">Mesa <?php echo e($table->number); ?></div>
                </div>
                <span class="badge badge-<?php echo e($table->status); ?>"><?php echo e($table->status_label); ?></span>
            </div>

            
            <?php if($sentOrders->isNotEmpty()): ?>
                <div class="comanda-list">
                    <?php $__currentLoopData = $sentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="citem">
                                <div class="qty-val"><?php echo e($item->quantity); ?>x</div>
                                <div class="citem-info">
                                    <div class="citem-name"><?php echo e($item->product_name); ?> <span class="sent-tag"><?php echo e($order->status_label); ?></span></div>
                                    <?php if($item->notes): ?><div class="citem-obs"><?php echo e($item->notes); ?></div><?php endif; ?>
                                </div>
                                <div class="citem-sub">R$ <?php echo e(number_format($item->subtotal, 2, ',', '.')); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <div class="comanda-list">
                <?php $__empty_1 = true; $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="citem">
                        <div class="qty-ctrl">
                            <form method="POST" action="<?php echo e(route('cliente.item.qty', $table)); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="key" value="<?php echo e($key); ?>">
                                <input type="hidden" name="delta" value="-1">
                                <button class="qty-btn" type="submit" aria-label="Diminuir">−</button>
                            </form>
                            <span class="qty-val"><?php echo e($item['quantity']); ?></span>
                            <form method="POST" action="<?php echo e(route('cliente.item.qty', $table)); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="key" value="<?php echo e($key); ?>">
                                <input type="hidden" name="delta" value="1">
                                <button class="qty-btn" type="submit" aria-label="Aumentar">+</button>
                            </form>
                        </div>
                        <div class="citem-info">
                            <div class="citem-name"><?php echo e($item['name']); ?></div>
                            <?php if($item['notes']): ?><div class="citem-obs"><?php echo e($item['notes']); ?></div><?php endif; ?>
                            <div class="citem-obs">R$ <?php echo e(number_format($item['price'], 2, ',', '.')); ?> un.</div>
                        </div>
                        <div class="citem-sub">R$ <?php echo e(number_format($item['price'] * $item['quantity'], 2, ',', '.')); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php if($sentOrders->isEmpty()): ?>
                        <div class="comanda-empty">Seu pedido<br>aparecerá aqui.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="comanda-total">
                <span>Total</span>
                <span>R$ <?php echo e(number_format($grandTotal, 2, ',', '.')); ?></span>
            </div>

            
            <?php if(!empty($cart)): ?>
                <form method="POST" action="<?php echo e(route('cliente.confirm', $table)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-gold" style="width:100%;margin-bottom:10px;">
                        <i class="ti ti-send"></i> Enviar pedido para a cozinha
                    </button>
                </form>
            <?php endif; ?>

            
            <?php if($sentTotal > 0): ?>
                <form method="POST" action="<?php echo e(route('cliente.pay', $table)); ?>" onsubmit="return confirm('Confirmar pagamento e encerrar a conta da mesa?');">
                    <?php echo csrf_field(); ?>
                    <div class="pay-methods">
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-pix" value="pix" checked>
                            <label for="m-pix"><i class="ti ti-qrcode"></i> Pix</label>
                        </div>
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-din" value="dinheiro">
                            <label for="m-din"><i class="ti ti-cash"></i> Dinheiro</label>
                        </div>
                        <div class="pay-opt">
                            <input type="radio" name="method" id="m-car" value="cartao">
                            <label for="m-car"><i class="ti ti-credit-card"></i> Cartão</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-green" style="width:100%;">
                        <i class="ti ti-check"></i> Encerrar conta · R$ <?php echo e(number_format($sentTotal, 2, ',', '.')); ?>

                    </button>
                </form>
            <?php endif; ?>
        </aside>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Alterna entre as categorias do cardápio
    function showCat(slug, btn) {
        document.querySelectorAll('.cat-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('cat-' + slug).classList.add('active');
        btn.classList.add('active');
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/restaurante_laravel/resources/views/cliente/menu.blade.php ENDPATH**/ ?>