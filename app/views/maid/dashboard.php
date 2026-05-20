<?php $pageTitle='Panel Maid'; $ap='dash'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Panel de Maid 🧹</h1><p>Gestiona tus trabajos y disponibilidad</p></div>
<?php if($perfil&&empty($perfil['descripcion'])): ?><div class="alert alert-info">📝 <a href="/maids/perfil"><strong>Completa tu perfil</strong></a> para aparecer en el buscador.</div><?php endif; ?>
<?php if(!empty($notificaciones)): ?>
<div class="notif-list" style="margin-bottom:1.2rem">
  <?php foreach($notificaciones as $n): ?><div class="notif-item"><div class="notif-dot"></div><div><div class="notif-text"><?=e($n['titulo'])?>: <?=e($n['mensaje'])?></div><div class="notif-time"><?=e($n['created_at'])?></div></div></div><?php endforeach; ?>
</div>
<?php endif; ?>
<div class="stats-row">
  <div class="card"><div class="card-title">Trabajos totales</div><div class="card-value"><?=(int)$stats['t']?></div></div>
  <div class="card"><div class="card-title">Completados</div><div class="card-value" style="color:var(--rose)"><?=(int)$stats['comp']?></div></div>
  <div class="card"><div class="card-title">Pendientes</div><div class="card-value"><?=(int)$stats['pend']?></div></div>
  <div class="card"><div class="card-title">Ingresos cobrados</div><div class="card-value">RD$<?=number_format((float)$ingresos,0,'.','.')?></div></div>
</div>
<div class="charts-row">
  <div class="chart-card"><div class="chart-title">📈 Trabajos por mes</div><div class="chart-wrap"><canvas id="cBar"></canvas></div></div>
  <div class="chart-card"><div class="chart-title">🍩 Estado</div><div class="chart-wrap"><canvas id="cDonut"></canvas></div></div>
</div>
<div class="actions-bar"><a href="/maids/perfil" class="btn btn-primary btn-auto">✏️ Editar perfil</a><a href="/servicios" class="btn btn-outline btn-auto">📋 Ver trabajos</a></div>
<div class="card"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem"><h2 style="font-size:1rem">Trabajos recientes</h2><a href="/servicios" style="font-size:.82rem">Ver todos →</a></div>
<?php if(empty($recientes)): ?><p style="color:var(--g400);text-align:center;padding:2rem">Sin trabajos aún. Completa tu perfil para aparecer en el buscador.</p>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>Cliente</th><th>Fecha</th><th>Horario</th><th>Estado</th><th>Total</th></tr></thead><tbody>
<?php foreach($recientes as $s): ?><tr>
<td><?=e($s['cn'].' '.$s['ca'])?></td><td><?=e($s['fecha'])?></td>
<td><?=e(substr($s['hora_inicio'],0,5))?> – <?=e(substr($s['hora_fin'],0,5))?></td>
<td><span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span></td>
<td>RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></td>
</tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
<script>
fetch('/api/dashboard-data').then(r=>r.json()).then(d=>{
  new Chart(document.getElementById('cBar'),{type:'bar',data:{labels:d.labels,datasets:[{label:'Trabajos',data:d.valores,backgroundColor:'rgba(132,108,91,0.7)',borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  if(d.donut_labels.length) new Chart(document.getElementById('cDonut'),{type:'doughnut',data:{labels:d.donut_labels,datasets:[{data:d.donut_vals,backgroundColor:['#ffc107','#17a2b8','#007bff','#28a745','#dc3545']}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}});
});
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
