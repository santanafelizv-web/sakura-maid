<?php $pageTitle='Facturas'; $ap='facturas'; require __DIR__.'/../shared/layout_top.php'; $u=authUser(); ?>
<div class="page-head"><h1>Facturas 🧾</h1><p>Registro de pagos</p></div>

<?php if(empty($facturas)): ?>
<div class="card" style="text-align:center;padding:3rem">
  <div style="font-size:3rem;margin-bottom:1rem">🧾</div>
  <p style="color:var(--g400)">Sin facturas aún.</p>
</div>
<?php else: ?>

<div style="display:flex;flex-direction:column;gap:1rem">
<?php foreach($facturas as $f): ?>
<div class="card" style="cursor:pointer;transition:box-shadow .2s" onclick="toggleFactura(<?=(int)$f['id']?>)" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
  
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
    <div style="display:flex;align-items:center;gap:1rem">
      <div style="width:48px;height:48px;border-radius:12px;background:#fce8ea;display:flex;align-items:center;justify-content:center;font-size:1.4rem">🧾</div>
      <div>
        <div style="font-weight:700;font-size:1rem;color:var(--rose)"><?=e($f['numero'])?></div>
        <div style="font-size:.82rem;color:var(--g400)"><?=e($f['fecha'])?></div>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:1rem">
      <div style="text-align:right">
        <div style="font-weight:700;font-size:1.1rem">RD$<?=number_format((float)$f['total'],2,'.','.')?></div>
        <div style="font-size:.78rem;color:var(--g400)">Total con ITBIS</div>
      </div>
      <span class="badge b-<?=e($f['estado_pago'])?>"><?=e($f['estado_pago'])?></span>
      <span style="color:var(--g400);font-size:1.2rem" id="arrow-<?=(int)$f['id']?>">▼</span>
    </div>
  </div>

  <div id="detalle-<?=(int)$f['id']?>" style="display:none;margin-top:1.5rem;border-top:1px solid var(--g200);padding-top:1.5rem">
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem">
      <div style="background:var(--g100);padding:1rem;border-radius:var(--r)">
        <div style="font-size:.75rem;color:var(--g400);margin-bottom:.3rem">👤 <?=$u['rol']==='cliente'?'Maid':'Cliente'?></div>
        <div style="font-weight:600"><?=$u['rol']==='cliente'?e($f['mn'].' '.$f['ma']):e($f['cn'].' '.$f['ca'])?></div>
      </div>
      <div style="background:var(--g100);padding:1rem;border-radius:var(--r)">
        <div style="font-size:.75rem;color:var(--g400);margin-bottom:.3rem">📅 Fecha del servicio</div>
        <div style="font-weight:600"><?=e($f['fecha'])?></div>
      </div>
      <div style="background:var(--g100);padding:1rem;border-radius:var(--r)">
        <div style="font-size:.75rem;color:var(--g400);margin-bottom:.3rem">📋 N° Factura</div>
        <div style="font-weight:600"><?=e($f['numero'])?></div>
      </div>
      <div style="background:var(--g100);padding:1rem;border-radius:var(--r)">
        <div style="font-size:.75rem;color:var(--g400);margin-bottom:.3rem">💳 Estado de pago</div>
        <div style="font-weight:600"><?=e($f['estado_pago'])?></div>
      </div>
    </div>

    <div style="background:var(--g100);border-radius:var(--r);padding:1.2rem;max-width:400px;margin:0 auto">
      <div style="display:flex;justify-content:space-between;margin-bottom:.6rem">
        <span style="color:var(--g500)">Subtotal</span>
        <span>RD$<?=number_format((float)$f['subtotal'],2,'.','.')?></span>
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:.6rem">
        <span style="color:var(--g500)">ITBIS (18%)</span>
        <span>RD$<?=number_format((float)$f['impuesto'],2,'.','.')?></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1.1rem;border-top:2px solid var(--rose);padding-top:.6rem;margin-top:.6rem">
        <span>Total</span>
        <span style="color:var(--rose)">RD$<?=number_format((float)$f['total'],2,'.','.')?></span>
      </div>
    </div>

    <div style="text-align:center;margin-top:1rem">
      <button onclick="event.stopPropagation();imprimirFactura(<?=(int)$f['id']?>)" class="btn btn-outline btn-sm">🖨️ Imprimir factura</button>
    </div>

  </div>
</div>
<?php endforeach; ?>
</div>

<script>
function toggleFactura(id) {
  const det = document.getElementById('detalle-'+id);
  const arr = document.getElementById('arrow-'+id);
  if(det.style.display==='none'){det.style.display='block';arr.textContent='▲';}
  else{det.style.display='none';arr.textContent='▼';}
}
function imprimirFactura(id) {
  const det = document.getElementById('detalle-'+id);
  det.style.display='block';
  window.print();
}
</script>
<?php endif; ?>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>