<?php $pageTitle='Buscar Maids'; $ap='maids'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Encuentra tu Maid 🌸</h1><p>Profesionales disponibles para ti</p></div>
<form method="GET" action="/maids" style="display:flex;gap:.7rem;max-width:460px;margin-bottom:1.5rem">
  <input type="text" name="q" class="form-control" placeholder="Buscar por nombre o descripción..." value="<?=e($q??'')?>">
  <button type="submit" class="btn btn-primary btn-auto">Buscar</button>
</form>
<?php if(empty($maids)): ?><div style="text-align:center;padding:3rem;color:var(--g400)"><div style="font-size:3rem;margin-bottom:.8rem">🔍</div><p>No hay Maids disponibles en este momento.</p></div>
<?php else: ?><div class="maids-grid">
<?php foreach($maids as $m): ?><div class="maid-card">
<div class="m-avatar"><?=strtoupper(mb_substr($m['nombre'],0,1))?></div>
<div class="m-name"><?=e($m['nombre'].' '.$m['apellido'])?></div>
<div class="m-stars"><?php $c=round($m['calificacion_promedio']??0); for($i=1;$i<=5;$i++) echo $i<=$c?'★':'☆'; ?> (<?=number_format((float)$m['calificacion_promedio'],1)?>)</div>
<div class="m-rate">RD$<?=number_format((float)$m['tarifa_hora'],0,'.','.')?>/hr</div>
<div class="m-desc"><?=e(mb_strimwidth($m['descripcion']?:'Maid profesional disponible.',0,80,'…'))?></div>
<span class="badge b-<?=e($m['disponibilidad'])?>" style="margin-bottom:.8rem"><?=e($m['disponibilidad'])?></span><br>
<a href="/servicios/nuevo?maid_id=<?=(int)$m['id']?>" class="btn btn-primary btn-sm" style="margin-top:.5rem;width:100%">Contratar 🧹</a>
</div><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
