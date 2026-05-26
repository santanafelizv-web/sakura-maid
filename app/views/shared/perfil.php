<?php $pageTitle='Mi Perfil'; $ap='perfil'; $user=authUser();
$avatarSeed = urlencode($user['avatar_seed']??$user['nombre'].$user['apellido']);
$avatarUrl = "https://api.dicebear.com/7.x/lorelei/svg?seed={$avatarSeed}&backgroundColor=C97B84&radius=50";
require __DIR__.'/layout_top.php'; ?>
<div class="page-head"><h1>Mi Perfil 👤</h1><p>Actualiza tu información personal</p></div>
<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<?php if($err??null): ?><div class="alert alert-error">⚠️ <?=e($err)?></div><?php endif; ?>
<div style="max-width:500px"><div class="card">
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1.2rem;border-bottom:1px solid var(--g200)">
  <div class="avatar-wrap" id="avatarWrap" style="position:relative;cursor:pointer;flex-shrink:0" onclick="openAvatarModal()">
    <img id="profileAvatar" src="<?=$avatarUrl?>" alt="<?=e($user['nombre'])?>" style="width:64px;height:64px;border-radius:50%;background:#f0ece8;display:block">
    <div style="position:absolute;bottom:-4px;right:-4px;background:var(--rose);color:#fff;border-radius:50%;width:24px;height:24px;font-size:13px;display:flex;align-items:center;justify-content:center;border:2px solid var(--white);box-shadow:var(--sh)">✎</div>
  </div>
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

<!-- AVATAR MODAL -->
<div class="modal-overlay" id="avatarModal" onclick="closeAvatarModal(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3>Elige tu avatar</h3>
      <span class="modal-close" onclick="closeAvatarModal()">&times;</span>
    </div>
    <div class="avatar-grid">
      <?php foreach($seeds as $s): $src="https://api.dicebear.com/7.x/lorelei/svg?seed=".urlencode($s)."&backgroundColor=C97B84&radius=50"; ?>
      <div class="avatar-opt" data-seed="<?=e($s)?>">
        <img src="<?=$src?>" alt="<?=e($s)?>" loading="lazy">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
function openAvatarModal() {
  document.getElementById('avatarModal').classList.add('open');
}
function closeAvatarModal(e) {
  if (e && e.target !== e.currentTarget) return;
  document.getElementById('avatarModal').classList.remove('open');
}

document.getElementById('avatarModal').addEventListener('click', function (e) {
  var opt = e.target.closest('.avatar-opt');
  if (!opt) return;
  var seed = opt.getAttribute('data-seed');
  var form = new FormData();
  form.append('seed', seed);
  fetch('/perfil/avatar', { method: 'POST', body: form })
    .then(function (r) { return r.json(); })
    .then(function (d) {
      if (d.ok) {
        document.getElementById('profileAvatar').src =
          'https://api.dicebear.com/7.x/lorelei/svg?seed=' + encodeURIComponent(seed) + '&backgroundColor=C97B84&radius=50';
        document.querySelectorAll('.avatar-opt').forEach(function (el) { el.classList.remove('selected'); });
        opt.classList.add('selected');
        closeAvatarModal();
      }
    });
});
</script>
<?php require __DIR__.'/layout_bottom.php'; ?>