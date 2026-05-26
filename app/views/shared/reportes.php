<?php $pageTitle='Reportes'; $ap='reportes'; require __DIR__.'/layout_top.php'; ?>
<div class="page-head" style="display:flex;justify-content:space-between;align-items:center">
  <div>
    <h1>Reportes Empresariales 📊</h1>
    <p>Análisis de datos del negocio</p>
  </div>
  <button onclick="window.print()" class="btn btn-primary btn-auto" style="gap:.5rem">🖨️ Imprimir Reporte</button>
</div>

<style>
@media print {
  nav, .sidebar, .actions-bar, button, .btn { display:none !important; }
  body { background:#fff !important; }
  .card, .chart-card { box-shadow:none !important; border:1px solid #ddd !important; }
}
</style>

<!-- Tarjetas de resumen (se llenan con JS) -->
<div class="stats-row" id="resumen-cards">
  <div class="card"><div class="card-title">Clientes registrados</div><div class="card-value" id="r-clientes">—</div></div>
  <div class="card"><div class="card-title">Maids activas</div><div class="card-value" id="r-maids">—</div></div>
  <div class="card"><div class="card-title">Servicios completados</div><div class="card-value" id="r-completados">—</div></div>
  <div class="card"><div class="card-title">Ingresos totales</div><div class="card-value" id="r-ingresos">—</div></div>
</div>

<div class="charts-row">
  <div class="chart-card">
    <div class="chart-title">💰 Ingresos por mes (<?=date('Y')?>)</div>
    <div class="chart-wrap"><canvas id="cIngresos"></canvas></div>
  </div>
  <div class="chart-card">
    <div class="chart-title">🏆 Top 5 Maids más solicitadas</div>
    <div id="top-maids" style="overflow-y:auto;max-height:220px"></div>
  </div>
</div>

<div class="card" style="margin-top:1rem">
  <div class="chart-title" style="margin-bottom:1rem">📋 Detalle de ingresos por mes</div>
  <div class="table-wrap">
    <table id="tabla-ingresos">
      <thead><tr><th>Mes</th><th>Facturas</th><th>Subtotal</th><th>ITBIS</th><th>Total</th></tr></thead>
      <tbody id="tbody-ingresos"></tbody>
    </table>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
fetch('/api/reporte-data').then(r=>r.json()).then(d=>{
  const res=d.resumen;
  document.getElementById('r-clientes').textContent    = res.clientes;
  document.getElementById('r-maids').textContent       = res.maids;
  document.getElementById('r-completados').textContent = res.completados;
  document.getElementById('r-ingresos').textContent    = 'RD$'+parseFloat(res.ingresos_total).toLocaleString('es-DO',{maximumFractionDigits:0});

  const meses = d.ingresos_mes.map(r=>r.nombre_mes);
  const totales = d.ingresos_mes.map(r=>parseFloat(r.ingresos_total||0));
  new Chart(document.getElementById('cIngresos'),{
    type:'bar',
    data:{labels:meses,datasets:[{label:'Ingresos RD$',data:totales,backgroundColor:'rgba(201,123,132,0.75)',borderRadius:6,borderSkipped:false},{label:'ITBIS',data:d.ingresos_mes.map(r=>parseFloat(r.itbis_total||0)),backgroundColor:'rgba(132,108,91,0.6)',borderRadius:6}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},scales:{x:{stacked:false},y:{beginAtZero:true,ticks:{callback:v=>'RD$'+v.toLocaleString()}}}}
  });

  const tbody=document.getElementById('tbody-ingresos');
  if(d.ingresos_mes.length===0){tbody.innerHTML='<tr><td colspan="5" style="text-align:center;color:var(--g400)">Sin datos de ingresos pagados aún.</td></tr>';}
  d.ingresos_mes.forEach(r=>{
    const tr=document.createElement('tr');
    tr.innerHTML=`<td>${r.nombre_mes}</td><td>${r.total_facturas}</td><td>RD$${parseFloat(r.ingresos_total).toLocaleString('es-DO',{minimumFractionDigits:2})}</td><td>RD$${parseFloat(r.itbis_total).toLocaleString('es-DO',{minimumFractionDigits:2})}</td><td><strong>RD$${(parseFloat(r.ingresos_total)+parseFloat(r.itbis_total)).toLocaleString('es-DO',{minimumFractionDigits:2})}</strong></td>`;
    tbody.appendChild(tr);
  });

  const topDiv=document.getElementById('top-maids');
  if(d.top.length===0){topDiv.innerHTML='<p style="color:var(--g400);text-align:center;padding:1rem">Sin datos aún.</p>';return;}
  d.top.forEach((m,i)=>{
    topDiv.innerHTML+=`<div style="display:flex;align-items:center;gap:.8rem;padding:.7rem 0;border-bottom:1px solid var(--g200)">
      <div style="width:28px;height:28px;border-radius:50%;background:var(--rose);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0">${i+1}</div>
      <div style="flex:1"><div style="font-weight:500;font-size:.9rem">${m.nombre} ${m.apellido}</div>
      <div style="font-size:.78rem;color:var(--g600)">${m.servicios} servicios · ⭐${parseFloat(m.calificacion_promedio).toFixed(1)}</div></div>
      <div style="font-size:.88rem;font-weight:500;color:var(--olive)">RD$${parseFloat(m.ingresos).toLocaleString('es-DO',{maximumFractionDigits:0})}</div>
    </div>`;
  });
});
</script>
<?php require __DIR__.'/layout_bottom.php'; ?>