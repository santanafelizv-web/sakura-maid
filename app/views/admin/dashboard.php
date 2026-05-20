<?php $pageTitle='Admin Dashboard'; $ap='dash'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Panel de Administración 🌸</h1><p>Resumen general del sistema</p></div>
<div class="stats-row">
  <div class="card"><div class="card-title">Usuarios registrados</div><div class="card-value"><?=(int)$stats['u']?></div></div>
  <div class="card"><div class="card-title">Servicios totales</div><div class="card-value"><?=(int)$stats['s']?></div></div>
  <div class="card"><div class="card-title">Maids activas</div><div class="card-value" style="color:var(--rose)"><?=(int)$stats['m']?></div></div>
  <div class="card"><div class="card-title">Ingresos cobrados</div><div class="card-value">RD$<?=number_format((float)$stats['i'],0,'.','.')?></div></div>
</div>
<div class="charts-row">
  <div class="chart-card"><div class="chart-title">📈 Servicios por mes</div><div class="chart-wrap"><canvas id="cBar"></canvas></div></div>
  <div class="chart-card"><div class="chart-title">💰 Ingresos por mes</div><div class="chart-wrap"><canvas id="cLine"></canvas></div></div>
</div>
<div class="actions-bar">
  <a href="/maids" class="btn btn-primary btn-auto">👩 Ver Maids</a>
  <a href="/servicios" class="btn btn-outline btn-auto">📋 Servicios</a>
  <a href="/reportes" class="btn btn-secondary btn-auto">📊 Reportes</a>
</div>
<script>
fetch('/api/dashboard-data').then(r=>r.json()).then(d=>{
  new Chart(document.getElementById('cBar'),{type:'bar',data:{labels:d.labels,datasets:[{label:'Servicios',data:d.valores,backgroundColor:'rgba(201,123,132,0.7)',borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
  new Chart(document.getElementById('cLine'),{type:'line',data:{labels:d.ingresos_labels,datasets:[{label:'RD$',data:d.ingresos_vals,borderColor:'rgba(132,108,91,1)',backgroundColor:'rgba(132,108,91,0.1)',fill:true,tension:.4}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
});
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
