<?php $pageTitle='Mis Servicios'; $ap='servicios'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Mis Servicios</h1><p>Historial de contrataciones</p></div>
<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<?php if($err??null): ?><div class="alert alert-error">⚠️ <?=e($err)?></div><?php endif; ?>
<div class="actions-bar"><a href="/maids" class="btn btn-primary btn-auto">+ Contratar nueva Maid</a></div>
<div class="card"><?php if(empty($servicios)): ?><p style="color:var(--g400);text-align:center;padding:2rem">Sin servicios aún. <a href="/maids">Busca una Maid</a></p>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>#</th><th>Maid</th><th>Fecha</th><th>Horario</th><th>Dirección</th><th>Estado</th><th>Total</th><th>Acción</th></tr></thead><tbody>
<?php foreach($servicios as $s): ?><tr>
<td><?=(int)$s['id']?></td><td><?=e($s['mn'].' '.$s['ma'])?></td><td><?=e($s['fecha'])?></td>
<td><?=e(substr($s['hora_inicio'],0,5))?> – <?=e(substr($s['hora_fin'],0,5))?></td>
<td><?=e(mb_strimwidth($s['direccion'],0,25,'…'))?></td>
<td><span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span></td>
<td>RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></td>
<td style="display:flex;gap:.4rem;flex-wrap:wrap">
<?php if($s['estado']==='pendiente'): ?>
<form method="POST" action="/servicios/estado" style="display:inline"><input type="hidden" name="id" value="<?=(int)$s['id']?>"><button name="estado" value="cancelado" class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar este servicio?')">Cancelar</button></form>
<?php endif; ?>
<?php if($s['estado']==='cancelado'): ?>
<form method="POST" action="/servicios/eliminar" style="display:inline"><input type="hidden" name="id" value="<?=(int)$s['id']?>"><button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este servicio?')">🗑️ Eliminar</button></form>
<?php elseif($s['estado']==='completado'): ?><a href="/facturas" class="btn btn-outline btn-sm">🧾 Factura</a>
<?php elseif($s['estado']==='pendiente'): ?><?php else: ?><span style="color:var(--g400);font-size:.8rem">—</span><?php endif; ?>
</td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>