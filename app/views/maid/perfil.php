<?php $pageTitle='Mi Perfil Maid'; $ap='mperfil'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Mi Perfil de Maid 🧹</h1><p>Así te ven los clientes</p></div>
<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<?php if($err??null): ?><div class="alert alert-error">⚠️ <?=e($err)?></div><?php endif; ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem">
<div class="card">
<h2 style="font-size:1rem;margin-bottom:1.2rem">Editar información</h2>
<form method="POST" action="/maids/perfil">
  <div class="form-group"><label>Descripción profesional</label><textarea name="descripcion" class="form-control" rows="4" placeholder="Cuéntale a los clientes sobre tu experiencia..."><?=e($perfil['descripcion']??'')?></textarea></div>
  <div class="form-group"><label>Tarifa por hora (RD$)</label><input type="number" name="tarifa_hora" class="form-control" min="0" step="50" value="<?=e($perfil['tarifa_hora']??350)?>"></div>
  <div class="form-group"><label>Disponibilidad</label>
  <select name="disponibilidad" class="form-control">
    <?php foreach(['disponible'=>'Disponible ✓','ocupado'=>'Ocupado','inactivo'=>'Inactivo'] as $v=>$l): ?>
    <option value="<?=$v?>" <?=($perfil['disponibilidad']??'disponible')===$v?'selected':''?>><?=$l?></option>
    <?php endforeach; ?>
  </select></div>
  <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>
</div>
<div>
<p style="font-size:.82rem;color:var(--g600);margin-bottom:.8rem">Vista previa de tu tarjeta:</p>
<div class="maid-card">
  <div class="m-avatar"><?=strtoupper(mb_substr($user['nombre'],0,1))?></div>
  <div class="m-name"><?=e($user['nombre'].' '.$user['apellido'])?></div>
  <div class="m-stars"><?php $c=round($perfil['calificacion_promedio']??0);for($i=1;$i<=5;$i++) echo $i<=$c?'★':'☆';?> (<?=number_format((float)($perfil['calificacion_promedio']??0),1)?>)</div>
  <div class="m-rate">RD$<?=number_format((float)($perfil['tarifa_hora']??0),0,'.','.')?>/hr</div>
  <div class="m-desc"><?=e(mb_strimwidth($perfil['descripcion']??'Tu descripción aparecerá aquí.',0,80,'…'))?></div>
  <span class="badge b-<?=e($perfil['disponibilidad']??'disponible')?>"><?=e($perfil['disponibilidad']??'disponible')?></span>
  <div style="font-size:.8rem;color:var(--g400);margin-top:.8rem">⭐ <?=(int)($perfil['total_servicios']??0)?> servicios realizados</div>
</div>
</div>
</div>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
