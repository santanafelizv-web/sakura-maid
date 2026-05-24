<?php $pageTitle='Dashboard'; $ap='dash'; require __DIR__.'/../shared/layout_top.php'; ?>

<div class="page-head">
  <h1>¡Hola, <?=e($user['nombre'])?>! 🌸</h1>
  <p>Resumen de tus servicios — <?=date('d/m/Y')?></p>
</div>

<?php if(!empty($notificaciones)): ?>
<div class="notif-list" style="margin-bottom:1.2rem">
  <?php foreach($notificaciones as $n): ?>
  <div class="notif-item"><div class="notif-dot"></div><div><div class="notif-text"><?=e($n['titulo'])?>: <?=e($n['mensaje'])?></div><div class="notif-time"><?=e($n['created_at'])?></div></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- PRÓXIMO SERVICIO -->
<?php if(!empty($proximo_servicio)): ?>
<div class="card" style="background:linear-gradient(135deg,#fce8ea,#fff);border-left:4px solid var(--rose);margin-bottom:1.2rem">
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
    <div style="display:flex;align-items:center;gap:1rem">
      <div style="width:48px;height:48px;border-radius:12px;background:var(--rose);display:flex;align-items:center;justify-content:center;font-size:1.4rem">📅</div>
      <div>
        <div style="font-size:.78rem;color:var(--rose);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Próximo servicio</div>
        <div style="font-weight:700;font-size:1rem"><?=e($proximo_servicio['mn'].' '.$proximo_servicio['ma'])?></div>
        <div style="font-size:.82rem;color:var(--g400)"><?=e($proximo_servicio['fecha'])?> · <?=e(substr($proximo_servicio['hora_inicio'],0,5))?> – <?=e(substr($proximo_servicio['hora_fin'],0,5))?></div>
      </div>
    </div>
    <span class="badge b-<?=e($proximo_servicio['estado'])?>"><?=e($proximo_servicio['estado'])?></span>
  </div>
</div>
<?php endif; ?>

<!-- STATS -->
<div class="stats-row">
  <div class="card">
    <div class="card-title">Servicios totales</div>
    <div class="card-value"><?=(int)$stats['t']?></div>
  </div>
  <div class="card">
    <div class="card-title">Completados</div>
    <div class="card-value" style="color:var(--rose)"><?=(int)$stats['comp']?></div>
  </div>
  <div class="card">
    <div class="card-title">Pendientes</div>
    <div class="card-value"><?=(int)$stats['pend']?></div>
  </div>
  <div class="card">
    <div class="card-title">Gastado este mes</div>
    <div class="card-value">RD$<?=number_format((float)($gasto_este_mes??0),0,'.','.')?></div>
    <div style="font-size:.78rem;color:var(--g400);margin-top:.3rem">Total: RD$<?=number_format((float)$gasto,0,'.','.')?></div>
  </div>
</div>

<!-- MAID FAVORITA Y ACCIONES -->
<div class="charts-row" style="margin-bottom:1.2rem">

  <?php if(!empty($maid_favorita)): 
    $ini = strtoupper(substr($maid_favorita['mn'],0,1).substr($maid_favorita['ma'],0,1));
    $estrellas = round($maid_favorita['calificacion_promedio']??0);
  ?>
  <div class="card" style="display:flex;align-items:center;gap:1.2rem">
    <div style="width:56px;height:56px;border-radius:50%;background:var(--rose);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem;flex-shrink:0"><?=$ini?></div>
    <div style="flex:1">
      <div style="font-size:.75rem;color:var(--g400);margin-bottom:.2rem">Tu maid favorita</div>
      <div style="font-weight:700;font-size:1rem"><?=e($maid_favorita['mn'].' '.$maid_favorita['ma'])?></div>
      <div style="font-size:.82rem;color:var(--g400)"><?=str_repeat('⭐',$estrellas)?> · <?=(int)$maid_favorita['veces']?> servicios contratados</div>
    </div>
    <a href="/maids" class="btn btn-primary btn-sm">Contratar de nuevo</a>
  </div>
  <?php else: ?>
  <div class="card" style="text-align:center;padding:2rem">
    <div style="font-size:2rem;margin-bottom:.5rem">🧹</div>
    <p style="color:var(--g400);margin-bottom:1rem">Aún no has contratado ninguna maid</p>
    <a href="/maids" class="btn btn-primary btn-sm">Buscar Maid</a>
  </div>
  <?php endif; ?>

  <div class="card" style="display:flex;flex-direction:column;gap:.8rem">
    <div style="font-weight:600;font-size:.9rem;margin-bottom:.3rem">Acciones rápidas</div>
    <a href="/maids" class="btn btn-primary btn-auto">Buscar Maid</a>
    <a href="/servicios" class="btn btn-outline btn-auto">Ver mis servicios</a>
    <a href="/facturas" class="btn btn-outline btn-auto">Ver facturas</a>
  </div>

</div>

<!-- GRÁFICOS -->
<div class="charts-row">
  <div class="chart-card"><div class="chart-title">Servicios por mes (<?=date('Y')?>)</div><div class="chart-wrap"><canvas id="cBar"></canvas></div></div>
  <div class="chart-card"><div class="chart-title">Estado de servicios</div><div class="chart-wrap"><canvas id="cDonut"></canvas></div></div>
</div>

<!-- SERVICIOS RECIENTES -->
<div class="card" style="margin-top:1.2rem">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
    <h2 style="font-size:1rem">Servicios recientes</h2>
    <a href="/servicios" style="font-size:.82rem">Ver todos →</a>
  </div>
  <?php if(empty($recientes)): ?>
  <p style="color:var(--g400);text-align:center;padding:2rem">Aún no tienes servicios. <a href="/maids">Busca una Maid</a></p>
  <?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.7rem">
    <?php foreach($recientes as $s): 
      $ini_m = strtoupper(substr($s['mn'],0,1).substr($s['ma'],0,1));
    ?>
    <div style="display:flex;align-items:center;gap:1rem;padding:.8rem;background:var(--g100);border-radius:var(--r)">
      <div style="width:38px;height:38px;border-radius:50%;background:#C97B84;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0"><?=$ini_m?></div>
      <div style="flex:1">
        <div style="font-weight:600;font-size:.88rem"><?=e($s['mn'].' '.$s['ma'])?></div>
        <div style="font-size:.78rem;color:var(--g400)"><?=e($s['fecha'])?> · RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></div>
      </div>
      <span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

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