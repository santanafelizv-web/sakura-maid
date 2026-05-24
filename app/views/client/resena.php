<?php $pageTitle='Reseñas'; $ap='resenas'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Reseñas ⭐</h1><p>Califica tus servicios completados</p></div>

<?php if(empty($resenas) && empty($pendientes)): ?>
<div class="card" style="text-align:center;padding:3rem">
  <div style="font-size:3rem;margin-bottom:1rem">⭐</div>
  <p style="color:var(--g400)">No tienes servicios completados aún.</p>
</div>
<?php else: ?>

<?php if(!empty($pendientes)): ?>
<div style="margin-bottom:1.5rem">
  <h2 style="font-size:1rem;margin-bottom:1rem;color:var(--rose)">Pendientes por calificar</h2>
  <div style="display:flex;flex-direction:column;gap:.8rem">
  <?php foreach($pendientes as $s):
    $ini = strtoupper(substr($s['mn'],0,1).substr($s['ma'],0,1));
  ?>
  <div class="card" style="display:flex;align-items:center;gap:1rem">
    <div style="width:46px;height:46px;border-radius:50%;background:#C97B84;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0"><?=$ini?></div>
    <div style="flex:1">
      <div style="font-weight:700"><?=e($s['mn'].' '.$s['ma'])?></div>
      <div style="font-size:.82rem;color:var(--g400)">Servicio del <?=e($s['fecha'])?> · RD$<?=number_format($s['precio_total'],0,'.','.')?></div>
    </div>
    <a href="/resenas/crear?servicio_id=<?=(int)$s['id']?>" class="btn btn-primary btn-sm">⭐ Calificar</a>
  </div>
  <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php if(!empty($resenas)): ?>
<div>
  <h2 style="font-size:1rem;margin-bottom:1rem">Reseñas dejadas</h2>
  <div style="display:flex;flex-direction:column;gap:.8rem">
  <?php foreach($resenas as $r):
    $ini = strtoupper(substr($r['mn'],0,1).substr($r['ma'],0,1));
    $est = (int)$r['calificacion'];
  ?>
  <div class="card">
    <div style="display:flex;align-items:center;gap:1rem">
      <div style="width:46px;height:46px;border-radius:50%;background:#C97B84;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0"><?=$ini?></div>
      <div style="flex:1">
        <div style="font-weight:700"><?=e($r['mn'].' '.$r['ma'])?></div>
        <div style="font-size:.82rem;color:var(--g400)">Servicio del <?=e($r['fecha'])?></div>
        <div style="margin:.3rem 0">
          <?php for($i=1;$i<=5;$i++): ?>
            <span style="color:<?=$i<=$est?'#f4c542':'#ddd'?>;font-size:1.1rem">★</span>
          <?php endfor; ?>
        </div>
        <?php if($r['comentario']): ?>
        <div style="font-size:.85rem;color:var(--g500);font-style:italic">"<?=e($r['comentario'])?>"</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php endif; ?>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>