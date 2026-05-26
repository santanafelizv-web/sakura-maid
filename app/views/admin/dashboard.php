<?php $pageTitle='Admin Dashboard'; $ap='dash'; require __DIR__.'/../shared/layout_top.php'; ?>

<div class="page-head">
  <h1>Panel de Administración 🌸</h1>
  <p>Resumen general del sistema — <?=date('d/m/Y')?></p>
</div>

<div class="stats-row">
  <div class="card">
    <div class="card-title">👥 Clientes registrados</div>
    <div class="card-value"><?=(int)$stats['u']?></div>
    <div style="font-size:.8rem;color:var(--g400);margin-top:.3rem">+<?=(int)$stats['nuevos_semana']?> esta semana</div>
  </div>
  <div class="card">
    <div class="card-title">📋 Servicios totales</div>
    <div class="card-value"><?=(int)$stats['s']?></div>
    <div style="font-size:.8rem;color:var(--g400);margin-top:.3rem"><?=(int)$stats['pendientes']?> pendientes</div>
  </div>
  <div class="card">
    <div class="card-title">🧹 Maids activas</div>
    <div class="card-value" style="color:var(--rose)"><?=(int)$stats['m']?></div>
    <div style="font-size:.8rem;color:var(--g400);margin-top:.3rem"><?=(int)$stats['completados']?> completados</div>
  </div>
  <div class="card">
    <div class="card-title">💰 Ingresos cobrados</div>
    <div class="card-value">RD$<?=number_format((float)$stats['i'],0,'.','.')?></div>
    <div style="font-size:.8rem;color:var(--g400);margin-top:.3rem">ITBIS incluido</div>
  </div>
</div>

<div class="charts-row">
  <div class="chart-card"><div class="chart-title">📈 Servicios por mes (<?=date('Y')?>)</div><div class="chart-wrap"><canvas id="cBar"></canvas></div></div>
  <div class="chart-card"><div class="chart-title">💰 Ingresos por mes (<?=date('Y')?>)</div><div class="chart-wrap"><canvas id="cLine"></canvas></div></div>
</div>

<div class="charts-row" style="margin-top:1.5rem">
  <div class="chart-card">
    <div class="chart-title">🏆 Top Maids más solicitadas</div>
    <?php if(empty($top_maids)): ?>
      <p style="color:var(--g400);text-align:center;padding:2rem">Sin datos aún</p>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:.8rem;padding:.5rem 0">
      <?php foreach($top_maids as $i=>$m):
        $iniciales = strtoupper(substr($m['nombre'],0,1).substr($m['apellido'],0,1));
        $colores = ['#C97B84','#846C5B','#E8A87C','#85C1E9','#82E0AA'];
        $color = $colores[$i % count($colores)];
        $estrellas = round($m['calificacion_promedio']);
      ?>
      <div style="display:flex;align-items:center;gap:1rem;padding:.8rem;background:var(--g100);border-radius:var(--r)">
        <div style="width:42px;height:42px;border-radius:50%;background:<?=$color?>;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.9rem;flex-shrink:0"><?=$iniciales?></div>
        <div style="flex:1">
          <div style="font-weight:600;font-size:.9rem"><?=e($m['nombre'].' '.$m['apellido'])?></div>
          <div style="font-size:.78rem;color:var(--g400)"><?=(int)$m['servicios']?> servicios · <?=str_repeat('⭐',$estrellas)?> <?=number_format($m['calificacion_promedio'],1)?></div>
        </div>
        <div style="font-weight:700;color:var(--rose);font-size:.9rem">RD$<?=number_format($m['ingresos'],0,'.','.')?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <div class="chart-card">
    <div class="chart-title">🕐 Servicios recientes</div>
    <?php if(empty($servicios_recientes)): ?>
      <p style="color:var(--g400);text-align:center;padding:2rem">Sin servicios aún</p>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:.6rem;padding:.5rem 0">
      <?php foreach($servicios_recientes as $s):
        $iniciales_c = strtoupper(substr($s['cn'],0,1).substr($s['ca'],0,1));
      ?>
      <div style="display:flex;align-items:center;gap:.8rem;padding:.7rem;background:var(--g100);border-radius:var(--r)">
        <div style="width:36px;height:36px;border-radius:50%;background:#C97B84;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0"><?=$iniciales_c?></div>
        <div style="flex:1;min-width:0">
          <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?=e($s['cn'].' '.$s['ca'])?> → <?=e($s['mn'].' '.$s['ma'])?></div>
          <div style="font-size:.75rem;color:var(--g400)"><?=e($s['fecha'])?> · RD$<?=number_format($s['precio_total'],0,'.','.')?></div>
        </div>
        <span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <a href="/servicios" style="display:block;text-align:center;margin-top:.8rem;font-size:.82rem;color:var(--rose)">Ver todos →</a>
  </div>
</div>

<script>
fetch('/api/dashboard-data').then(r=>r.json()).then(d=>{
  new Chart(document.getElementById('cBar'),{type:'bar',data:{labels:d.labels,datasets:[{label:'Servicios',data:d.valores,backgroundColor:'rgba(201,123,132,0.7)',borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  if(d.ingresos_labels.length){new Chart(document.getElementById('cLine'),{type:'line',data:{labels:d.ingresos_labels,datasets:[{label:'RD$',data:d.ingresos_vals,borderColor:'rgba(132,108,91,1)',backgroundColor:'rgba(132,108,91,0.1)',fill:true,tension:.4,pointBackgroundColor:'rgba(132,108,91,1)',pointRadius:5}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});}
});
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>