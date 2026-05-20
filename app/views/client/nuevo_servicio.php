<?php $pageTitle='Contratar Maid'; $ap='maids'; require __DIR__.'/../shared/layout_top.php'; ?>
<div class="page-head"><h1>Contratar a <?=e($maid['nombre'].' '.$maid['apellido'])?></h1><p>Completa los detalles del servicio</p></div>
<?php if($err??null): ?><div class="alert alert-error">⚠️ <?=e($err)?></div><?php endif; ?>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.2rem;align-items:start">
<div class="card">
<form method="POST" action="/servicios/nuevo">
  <input type="hidden" name="maid_id" value="<?=(int)$maid['id']?>">
  <div class="form-group"><label>Descripción del servicio</label><textarea name="descripcion" class="form-control" rows="3" placeholder="Ej: Limpieza general del apartamento, 2 cuartos y 1 baño..."></textarea></div>
  <div class="form-group"><label>Fecha</label><input type="date" name="fecha" class="form-control" min="<?=date('Y-m-d')?>" required></div>
  <div class="form-row">
    <div class="form-group"><label>Hora inicio</label><input type="time" name="hora_inicio" id="hi" class="form-control" required></div>
    <div class="form-group"><label>Hora fin</label><input type="time" name="hora_fin" id="hf" class="form-control" required></div>
  </div>
  <div class="form-group"><label>Dirección</label><input type="text" name="direccion" class="form-control" placeholder="Av. 27 de Febrero #45, Santo Domingo" required></div>
  <div id="est" style="display:none;background:var(--rose-pale);border-radius:var(--r);padding:1rem;margin-bottom:1rem">
    <div style="font-size:.8rem;color:var(--g600)">Estimado del servicio</div>
    <div id="estVal" style="font-size:1.6rem;font-family:'Playfair Display',serif;color:var(--rose)">RD$0</div>
    <div style="font-size:.75rem;color:var(--g400)">+18% ITBIS se añade en la factura</div>
  </div>
  <div style="display:flex;gap:.8rem"><button type="submit" class="btn btn-primary">Confirmar 🌸</button><a href="/maids" class="btn btn-secondary btn-auto">Cancelar</a></div>
</form>
</div>
<div class="maid-card">
  <div class="m-avatar"><?=strtoupper(mb_substr($maid['nombre'],0,1))?></div>
  <div class="m-name"><?=e($maid['nombre'].' '.$maid['apellido'])?></div>
  <div class="m-stars"><?php $c=round($maid['calificacion_promedio']??0); for($i=1;$i<=5;$i++) echo $i<=$c?'★':'☆'; ?></div>
  <div class="m-rate">RD$<?=number_format((float)$maid['tarifa_hora'],0,'.','.')?>/hr</div>
  <div class="m-desc"><?=e($maid['descripcion']?:'Maid profesional.')?></div>
  <span class="badge b-disponible">disponible</span>
</div>
</div>
<script>
const tarifa=<?=(float)$maid['tarifa_hora']?>;
function calc(){const hi=document.getElementById('hi').value,hf=document.getElementById('hf').value;if(!hi||!hf)return;const m=(parseInt(hf)*60+parseInt(hf.split(':')[1]))-(parseInt(hi)*60+parseInt(hi.split(':')[1]));if(m<=0)return;const p=tarifa*(m/60);document.getElementById('estVal').textContent='RD$'+p.toLocaleString('es-DO',{maximumFractionDigits:0});document.getElementById('est').style.display='block';}
document.getElementById('hi').addEventListener('change',calc);
document.getElementById('hf').addEventListener('change',calc);
</script>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>
