<?php $pageTitle='Dashboard'; $ap='dash'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>¡Hola, <?=e($user['nombre'])?>! 🌸</h1><p>Resumen de tus servicios</p></div>

<?php if(!empty($notificaciones)): ?>
<div class="notif-list" style="margin-bottom:1.2rem">
  <?php foreach($notificaciones as $n): ?>
  <div class="notif-item"><div class="notif-dot"></div><div><div class="notif-text"><?=e($n['titulo'])?>: <?=e($n['mensaje'])?></div><div class="notif-time"><?=e($n['created_at'])?></div></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="stats-row">
  <div class="card"><div class="card-title">Servicios totales</div><div class="card-value"><?=(int)$stats['t']?></div></div>
  <div class="card"><div class="card-title">Completados</div><div class="card-value" style="color:var(--rose)"><?=(int)$stats['comp']?></div></div>
  <div class="card"><div class="card-title">Pendientes</div><div class="card-value"><?=(int)$stats['pend']?></div></div>
  <div class="card"><div class="card-title">Total gastado</div><div class="card-value">RD$<?=number_format((float)$gasto,0,'.','.')?></div></div>
</div>

<div class="charts-row">
  <div class="chart-card"><div class="chart-title">📈 Servicios por mes (<?=date('Y')?>)</div><div class="chart-wrap"><canvas id="cBar"></canvas></div></div>
  <div class="chart-card"><div class="chart-title">🍩 Estado de servicios</div><div class="chart-wrap"><canvas id="cDonut"></canvas></div></div>
</div>

<div class="actions-bar">
  <a href="/maids" class="btn btn-primary btn-auto">🔍 Buscar Maid</a>
  <a href="/servicios" class="btn btn-outline btn-auto">📋 Ver todos</a>
</div>

<div class="card"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem"><h2 style="font-size:1rem">Servicios recientes</h2><a href="/servicios" style="font-size:.82rem">Ver todos →</a></div>
<?php if(empty($recientes)): ?><p style="color:var(--g400);text-align:center;padding:2rem">Aún no tienes servicios. <a href="/maids">Busca una Maid</a></p>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>Maid</th><th>Fecha</th><th>Estado</th><th>Total</th></tr></thead><tbody>
<?php foreach($recientes as $s): ?><tr>
<td><?=e($s['mn'].' '.$s['ma'])?></td><td><?=e($s['fecha'])?></td>
<td><span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span></td>
<td>RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>

<script>
fetch('/api/dashboard-data').then(r=>r.json()).then(d=>{
  const rose='rgba(201,123,132,', olive='rgba(132,108,91,';
  new Chart(document.getElementById('cBar'),{type:'bar',data:{labels:d.labels,datasets:[{label:'Servicios',data:d.valores,backgroundColor:rose+'0.7)',borderColor:rose+'1)',borderWidth:1,borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  if(d.donut_labels.length){
    new Chart(document.getElementById('cDonut'),{type:'doughnut',data:{labels:d.donut_labels,datasets:[{data:d.donut_vals,backgroundColor:['#ffc107','#17a2b8','#007bff','#28a745','#dc3545'],borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{size:11}}}}}});
  }
});
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
