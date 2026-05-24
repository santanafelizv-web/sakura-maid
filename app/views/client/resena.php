<?php $pageTitle='Mis Reseñas'; $ap='resenas'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Mis Reseñas ⭐</h1><p>Reseñas que has dejado</p></div>

<?php if(empty($resenas)): ?>
<div class="card" style="text-align:center;padding:3rem">
  <div style="font-size:3rem;margin-bottom:1rem">⭐</div>
  <p style="color:var(--g400)">Aún no has dejado reseñas.</p>
  <a href="/servicios" class="btn btn-primary" style="margin-top:1rem">Ver mis servicios</a>
</div>
<?php else: ?>
<div style="display:flex;flex-direction:column;gap:1rem">
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
<?php endif; ?>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>