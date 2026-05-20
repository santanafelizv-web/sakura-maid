<?php $pageTitle='Facturas'; $ap='facturas'; require __DIR__.'/../shared/layout_top.php'; $u=authUser(); ?>
<div class="page-head"><h1>Facturas 🧾</h1><p>Registro de pagos</p></div>
<div class="card"><?php if(empty($facturas)): ?><p style="color:var(--g400);text-align:center;padding:2rem">Sin facturas aún.</p>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>N° Factura</th>
<?php if($u['rol']==='cliente'): ?><th>Maid</th><?php else: ?><th>Cliente</th><?php endif; ?>
<th>Fecha servicio</th><th>Subtotal</th><th>ITBIS 18%</th><th>Total</th><th>Estado</th></tr></thead><tbody>
<?php foreach($facturas as $f): ?><tr>
<td><strong><?=e($f['numero'])?></strong></td>
<td><?=$u['rol']==='cliente'?e($f['mn'].' '.$f['ma']):e($f['cn'].' '.$f['ca'])?></td>
<td><?=e($f['fecha'])?></td>
<td>RD$<?=number_format((float)$f['subtotal'],2,'.','.')?></td>
<td>RD$<?=number_format((float)$f['impuesto'],2,'.','.')?></td>
<td style="font-weight:600">RD$<?=number_format((float)$f['total'],2,'.','.')?></td>
<td><span class="badge b-<?=e($f['estado_pago'])?>"><?=e($f['estado_pago'])?></span></td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
