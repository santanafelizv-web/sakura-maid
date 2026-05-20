<?php $pageTitle='Todos los Servicios'; $ap='servicios'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Todos los Servicios</h1><p>Vista de administrador</p></div>
<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<div class="card"><div class="table-wrap"><table><thead><tr><th>#</th><th>Cliente</th><th>Maid</th><th>Fecha</th><th>Estado</th><th>Total</th><th>Acción</th></tr></thead><tbody>
<?php foreach($servicios as $s): ?><tr>
<td><?=(int)$s['id']?></td><td><?=e($s['cn'].' '.$s['ca'])?></td><td><?=e($s['mn'].' '.$s['ma'])?></td>
<td><?=e($s['fecha'])?></td><td><span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span></td>
<td>RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></td>
<td><?php if($s['estado']==='en_progreso'||$s['estado']==='confirmado'): ?>
<form method="POST" action="/servicios/estado" style="display:inline"><input type="hidden" name="id" value="<?=(int)$s['id']?>">
<button name="estado" value="completado" class="btn btn-success btn-sm">Completar</button></form><?php else: ?>—<?php endif; ?></td>
</tr><?php endforeach; ?></tbody></table></div></div>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
