<?php $pageTitle='Calificar Servicio'; $ap='resenas'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Calificar Servicio ⭐</h1><p>Deja tu reseña sobre este servicio</p></div>

<?php if($err): ?>
<div class="alert alert-error"><?=e($err)?></div>
<?php endif; ?>

<div class="card" style="max-width:600px;margin:0 auto">
  <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
    <?php $ini = strtoupper(substr($servicio['mn'],0,1).substr($servicio['ma'],0,1)); ?>
    <div style="width:56px;height:56px;border-radius:50%;background:#C97B84;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem;flex-shrink:0"><?=$ini?></div>
    <div>
      <div style="font-weight:700;font-size:1.1rem"><?=e($servicio['mn'].' '.$servicio['ma'])?></div>
      <div style="font-size:.85rem;color:var(--g400)">Servicio del <?=e($servicio['fecha'])?> · RD$<?=number_format($servicio['precio_total'],0,'.','.')?></div>
    </div>
  </div>

  <form method="POST" action="/resenas/crear">
    <input type="hidden" name="servicio_id" value="<?=(int)$servicio['id']?>">

    <div style="margin-bottom:1.5rem">
      <label style="display:block;font-weight:600;margin-bottom:.5rem">Calificación</label>
      <div style="display:flex;gap:.5rem" id="estrellas">
        <?php for($i=1;$i<=5;$i++): ?>
        <span data-val="<?=$i?>" style="font-size:2rem;cursor:pointer;color:#ddd">★</span>
        <?php endfor; ?>
      </div>
      <input type="hidden" name="calificacion" id="calificacion" value="0">
    </div>

    <div style="margin-bottom:1.5rem">
      <label style="display:block;font-weight:600;margin-bottom:.5rem">Comentario <span style="color:var(--g400);font-weight:400">(opcional)</span></label>
      <textarea name="comentario" rows="4" style="width:100%;padding:.75rem;border:1px solid var(--g200);border-radius:8px;font-family:inherit;font-size:.95rem;resize:vertical" placeholder="¿Cómo fue tu experiencia?"></textarea>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%">Enviar Reseña</button>
  </form>
</div>

<script>
const estrellas = document.querySelectorAll('#estrellas span');
const input = document.getElementById('calificacion');
estrellas.forEach(s => {
  s.addEventListener('click', () => {
    const val = +s.dataset.val;
    input.value = val;
    estrellas.forEach(e => e.style.color = +e.dataset.val <= val ? '#f4c542' : '#ddd');
  });
  s.addEventListener('mouseover', () => {
    const val = +s.dataset.val;
    estrellas.forEach(e => e.style.color = +e.dataset.val <= val ? '#f4c542' : '#ddd');
  });
  s.addEventListener('mouseout', () => {
    const val = +input.value;
    estrellas.forEach(e => e.style.color = +e.dataset.val <= val ? '#f4c542' : '#ddd');
  });
});
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>