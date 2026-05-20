<?php $pageTitle='Mi Perfil'; $ap='perfil'; $user=authUser(); require __DIR__.'/layout_top.php'; ?>
<div class="page-head"><h1>Mi Perfil 👤</h1><p>Actualiza tu información personal</p></div>
<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<?php if($err??null): ?><div class="alert alert-error">⚠️ <?=e($err)?></div><?php endif; ?>
<div style="max-width:500px"><div class="card">
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1.2rem;border-bottom:1px solid var(--g200)">
  <div class="s-avatar" style="width:56px;height:56px;font-size:1.4rem"><?=strtoupper(mb_substr($user['nombre'],0,1))?></div>
  <div><div style="font-weight:600;font-size:1.05rem"><?=e($user['nombre'].' '.$user['apellido'])?></div>
  <div style="color:var(--rose);font-size:.83rem;text-transform:capitalize"><?=e($user['rol'])?></div>
  <div style="color:var(--g400);font-size:.8rem"><?=e($user['email'])?></div></div>
</div>
<form method="POST" action="/perfil">
  <div class="form-row">
    <div class="form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?=e($user['nombre'])?>" required></div>
    <div class="form-group"><label>Apellido</label><input type="text" name="apellido" class="form-control" value="<?=e($user['apellido'])?>" required></div>
  </div>
  <div class="form-group"><label>Correo</label><input type="email" class="form-control" value="<?=e($user['email'])?>" disabled></div>
  <div class="form-group"><label>Teléfono</label><input type="tel" name="telefono" class="form-control" value="<?=e($user['telefono']??'')?>" placeholder="809-000-0000"></div>
  <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>
</div></div>
<?php require __DIR__.'/layout_bottom.php'; ?>
