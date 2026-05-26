<?php $pageTitle='Panel Maid'; $ap='dash'; require __DIR__.'/../shared/layout_top.php'; ?>

<div class="page-head">
  <h1>Panel de Maid 🧹</h1>
  <p>Gestiona tus trabajos y disponibilidad — <?=date('d/m/Y')?></p>
</div>

<?php if($perfil&&empty($perfil['descripcion'])): ?>
<div class="alert alert-info">📝 <a href="/maids/perfil"><strong>Completa tu perfil</strong></a> para aparecer en el buscador.</div>
<?php endif; ?>

<?php if(!empty($notificaciones)): ?>
<div class="notif-list" style="margin-bottom:1.2rem">
  <?php foreach($notificaciones as $n): ?>
  <div class="notif-item"><button class="notif-close" onclick="this.parentElement.classList.add('dismissed')">&times;</button><div class="notif-dot"></div><div><div class="notif-text"><?=e($n['titulo'])?>: <?=e($n['mensaje'])?></div><div class="notif-time"><?=e($n['created_at'])?></div></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if(!empty($proximo_trabajo)): ?>
<div class="card" style="background:linear-gradient(135deg,#f0ece8,#fff);border-left:4px solid #846C5B;margin-bottom:1.2rem">
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
    <div style="display:flex;align-items:center;gap:1rem">
      <div style="width:48px;height:48px;border-radius:12px;background:#846C5B;display:flex;align-items:center;justify-content:center;font-size:1.4rem">📅</div>
      <div>
        <div style="font-size:.78rem;color:#846C5B;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Próximo trabajo</div>
        <div style="font-weight:700;font-size:1rem"><?=e($proximo_trabajo['cn'].' '.$proximo_trabajo['ca'])?></div>
        <div style="font-size:.82rem;color:var(--g400)"><?=e($proximo_trabajo['fecha'])?> · <?=e(substr($proximo_trabajo['hora_inicio'],0,5))?> – <?=e(substr($proximo_trabajo['hora_fin'],0,5))?></div>
        <div style="font-size:.78rem;color:var(--g400)"><?=e($proximo_trabajo['direccion']??'')?></div>
      </div>
    </div>
    <span class="badge b-<?=e($proximo_trabajo['estado'])?>"><?=e($proximo_trabajo['estado'])?></span>
  </div>
</div>
<?php endif; ?>

<?php if($perfil): 
  $estrellas = round($perfil['calificacion_promedio']??0);
  $seed = urlencode($user['nombre'].$user['apellido']);
  $avatar = "https://api.dicebear.com/7.x/lorelei/svg?seed={$seed}&backgroundColor=846C5B&radius=50";
?>
<div class="card" style="display:flex;align-items:center;gap:1.2rem;margin-bottom:1.2rem">
  <img src="<?=$avatar?>" alt="<?=e($user['nombre'])?>" style="width:60px;height:60px;border-radius:50%;flex-shrink:0;background:#f0ece8">
  <div style="flex:1">
    <div style="font-weight:700;font-size:1rem"><?=e($user['nombre'].' '.$user['apellido'])?></div>
    <div style="font-size:.88rem;color:var(--g400);margin:.2rem 0">
      <?php for($i=1;$i<=5;$i++): ?>
        <span style="color:<?=$i<=$estrellas?'#f4c542':'#ddd'?>;font-size:1.1rem">★</span>
      <?php endfor; ?>
      <span style="font-size:.82rem;color:var(--g400);margin-left:.3rem"><?=number_format($perfil['calificacion_promedio']??0,1)?> · <?=(int)$perfil['total_servicios']?> servicios</span>
    </div>
    <div style="font-size:.78rem">
      <span class="badge b-<?=e($perfil['disponibilidad'])?>"><?=e($perfil['disponibilidad'])?></span>
      <span style="color:var(--g400);margin-left:.5rem">RD$<?=number_format($perfil['tarifa_hora'],0,'.','.')?>/hr</span>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="stats-row">
  <div class="card">
    <div class="card-title">Trabajos totales</div>
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
    <div class="card-title">Ingresos este mes</div>
    <div class="card-value">RD$<?=number_format((float)($ingresos_este_mes??0),0,'.','.')?></div>
    <div style="font-size:.78rem;color:var(--g400);margin-top:.3rem">Total: RD$<?=number_format((float)$ingresos,0,'.','.')?></div>
  </div>
</div>

<div class="charts-row">
  <div class="chart-card"><div class="chart-title">Trabajos por mes</div><div class="chart-wrap"><canvas id="cBar"></canvas></div></div>
  <div class="chart-card"><div class="chart-title">Estado de trabajos</div><div class="chart-wrap"><canvas id="cDonut"></canvas></div></div>
</div>

<?php if(!empty($resenas)): ?>
<div class="card" style="margin-top:1.2rem">
  <div style="margin-bottom:1rem"><h2 style="font-size:1rem">Reseñas recientes</h2></div>
  <div style="display:flex;flex-direction:column;gap:.8rem">
    <?php foreach($resenas as $r): 
      $est = (int)$r['calificacion'];
      $seed_c = urlencode($r['nombre'].$r['apellido']);
      $avatar_c = "https://api.dicebear.com/7.x/lorelei/svg?seed={$seed_c}&backgroundColor=C97B84&radius=50";
    ?>
    <div style="display:flex;align-items:flex-start;gap:1rem;padding:.8rem;background:var(--g100);border-radius:var(--r)">
      <img src="<?=$avatar_c?>" alt="<?=e($r['nombre'])?>" style="width:36px;height:36px;border-radius:50%;flex-shrink:0;background:#f0ece8">
      <div style="flex:1">
        <div style="font-weight:600;font-size:.88rem"><?=e($r['nombre'].' '.$r['apellido'])?></div>
        <div style="margin:.2rem 0">
          <?php for($i=1;$i<=5;$i++): ?>
            <span style="color:<?=$i<=$est?'#f4c542':'#ddd'?>;font-size:1rem">★</span>
          <?php endfor; ?>
        </div>
        <?php if($r['comentario']): ?>
        <div style="font-size:.82rem;color:var(--g500);font-style:italic">"<?=e($r['comentario'])?>"</div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="card" style="margin-top:1.2rem">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
    <h2 style="font-size:1rem">Trabajos recientes</h2>
    <a href="/servicios" style="font-size:.82rem">Ver todos →</a>
  </div>
  <?php if(empty($recientes)): ?>
  <p style="color:var(--g400);text-align:center;padding:2rem">Sin trabajos aún. Completa tu perfil para aparecer en el buscador.</p>
  <?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.7rem">
    <?php foreach($recientes as $s): 
      $seed_c = urlencode($s['cn'].$s['ca']);
      $avatar_c = "https://api.dicebear.com/7.x/lorelei/svg?seed={$seed_c}&backgroundColor=846C5B&radius=50";
    ?>
    <div style="display:flex;align-items:center;gap:1rem;padding:.8rem;background:var(--g100);border-radius:var(--r)">
      <img src="<?=$avatar_c?>" alt="<?=e($s['cn'])?>" style="width:38px;height:38px;border-radius:50%;flex-shrink:0;background:#f0ece8">
      <div style="flex:1">
        <div style="font-weight:600;font-size:.88rem"><?=e($s['cn'].' '.$s['ca'])?></div>
        <div style="font-size:.78rem;color:var(--g400)"><?=e($s['fecha'])?> · <?=e(substr($s['hora_inicio'],0,5))?> – <?=e(substr($s['hora_fin'],0,5))?> · RD$<?=number_format((float)$s['precio_total'],0,'.','.')?></div>
      </div>
      <span class="badge b-<?=e($s['estado'])?>"><?=e($s['estado'])?></span>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<script>
fetch('/api/dashboard-data').then(r=>r.json()).then(d=>{
  new Chart(document.getElementById('cBar'),{type:'bar',data:{labels:d.labels,datasets:[{label:'Trabajos',data:d.valores,backgroundColor:'rgba(132,108,91,0.7)',borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  if(d.donut_labels.length) new Chart(document.getElementById('cDonut'),{type:'doughnut',data:{labels:d.donut_labels,datasets:[{data:d.donut_vals,backgroundColor:['#ffc107','#17a2b8','#007bff','#28a745','#dc3545']}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}});
});
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>