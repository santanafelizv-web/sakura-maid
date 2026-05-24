<?php $pageTitle='Dejar Reseña'; $ap='servicios'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Dejar Reseña ⭐</h1><p>Califica tu experiencia</p></div>

<div class="card" style="max-width:500px;margin:0 auto">
  <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--g200)">
    <?php $ini = strtoupper(substr($servicio['mn'],0,1).substr($servicio['ma'],0,1)); ?>
    <div style="width:50px;height:50px;border-radius:50%;background:#C97B84;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem"><?=$ini?></div>
    <div>
      <div style="font-weight:700"><?=e($servicio['mn'].' '.$servicio['ma'])?></div>
      <div style="font-size:.82rem;color:var(--g400)">Servicio del <?=e($servicio['fecha'])?></div>
    </div>
  </div>

  <?php if($err): ?><div class="alert alert-error"><?=e($err)?></div><?php endif; ?>

  <form method="POST" action="/resenas/crear">
    <input type="hidden" name="servicio_id" value="<?=(int)$servicio['id']?>">
    
    <div class="form-group">
      <label style="font-weight:600;margin-bottom:.5rem;display:block">Calificación</label>
      <div style="display:flex;gap:.5rem;font-size:2rem" id="stars">
        <?php for($i=1;$i<=5;$i++): ?>
        <span style="cursor:pointer;color:#ddd" data-val="<?=$i?>" onclick="setRating(<?=$i?>)">★</span>
        <?php endfor; ?>
      </div>
      <input type="hidden" name="calificacion" id="calificacion" value="0">
    </div>

    <div class="form-group" style="margin-top:1rem">
      <label style="font-weight:600;margin-bottom:.5rem;display:block">Comentario (opcional)</label>
      <textarea name="comentario" class="form-control" rows="4" placeholder="¿Cómo fue tu experiencia con esta maid?"></textarea>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem">Enviar reseña ⭐</button>
    <a href="/servicios" class="btn btn-outline" style="width:100%;margin-top:.5rem;text-align:center">Cancelar</a>
  </form>
</div>

<script>
function setRating(val) {
  document.getElementById('calificacion').value = val;
  document.querySelectorAll('#stars span').forEach((s,i) => {
    s.style.color = i < val ? '#f4c542' : '#ddd';
  });
}
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>