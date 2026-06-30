<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .login-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;
        background: linear-gradient(135deg, #2A2420 0%, #1C1C1A 100%); }
    .login-card { background: #fff; border-radius: 18px; width: 100%; max-width: 400px; padding: 34px; box-shadow: 0 18px 50px rgba(0,0,0,0.35); }
    .login-logo { display: flex; align-items: center; gap: 12px; justify-content: center; margin-bottom: 8px; }
    .login-logo .brand-box { width: 46px; height: 46px; font-size: 24px; }
    .login-title { text-align: center; font-size: 19px; font-weight: 800; }
    .login-sub { text-align: center; font-size: 12px; color: var(--muted); margin-bottom: 24px; }
    .login-hint { margin-top: 18px; font-size: 11px; color: var(--muted); background: #faf7f3; border: 1px dashed var(--border); border-radius: 10px; padding: 12px; line-height: 1.7; }
    .login-hint b { color: var(--text); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-logo">
            <div class="brand-box">C</div>
        </div>
        <div class="login-title">O Casarão</div>
        <div class="login-sub">Sistema de Gestão — Acesse sua conta</div>

        <?php if($errors->any()): ?>
            <div class="alert alert-error"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.attempt')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" autofocus required>
            </div>
            <div class="form-group">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="remember" value="1"> <span style="font-size:13px;">Manter conectado</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Entrar</button>
        </form>

        <div class="login-hint">
            <b>Contas de demonstração</b> (senha: <b>senha123</b>)<br>
            Gerente: gerente@casarao.com<br>
            Garçom: garcom@casarao.com<br>
            Cozinha: cozinha@casarao.com<br>
            Cliente: cliente@casarao.com
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/brian/Documentos/restaurante/resources/views/auth/login.blade.php ENDPATH**/ ?>