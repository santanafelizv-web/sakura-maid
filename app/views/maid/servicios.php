<?php $pageTitle='Mis Trabajos'; $ap='servicios'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Mis Trabajos</h1><p>Gestiona el estado de tus servicios</p></div>
<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<div class="card"><?php if(empty($servicios)): ?><p style="color:var(--g400);text-align:center;padding:2rem">Sin trabajos asignados aún.</p>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Horario</th><th>Dirección</th><th>Estado</th><th>Total</th><th>Actualizar</th></tr></thead><tbody>
<?php foreach($servicios as $s): ?><tr>
<td><?=(int)$s['id']?></td><td><?=e($s['cn'].' '.$s['ca'])?></td><td><?=e($s['fecha'])?></td>
<td><?=e(substr($s['hora_inicio'],0,5))?> – <?=e(substr($s['hora_fin'],0,5))?></td>
<td><?=e(mb_strimwidth($s['direccion'],0,22,'…'))?></td>
<td><span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span></td>
<td>RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></td>
<td><?php if(in_array($s['estado'],['pendiente','confirmado','en_progreso'])): ?>
<form method="POST" action="/servicios/estado" style="display:flex;gap:.3rem;flex-wrap:wrap">
<input type="hidden" name="id" value="<?=(int)$s['id']?>">
<?php if($s['estado']==='pendiente'): ?>
  <button name="estado" value="confirmado" class="btn btn-success btn-sm">✓ Confirmar</button>
  <button name="estado" value="cancelado"  class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar?')">✗</button>
<?php elseif($s['estado']==='confirmado'): ?>
  <button name="estado" value="en_progreso" class="btn btn-outline btn-sm">▶ Iniciar</button>
<?php elseif($s['estado']==='en_progreso'): ?>
  <button name="estado" value="completado" class="btn btn-primary btn-sm">✓ Completar</button>
<?php endif; ?></form>
<?php else: ?><span style="color:var(--g400);font-size:.8rem"><?=e($s['estado'])?></span><?php endif; ?></td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
