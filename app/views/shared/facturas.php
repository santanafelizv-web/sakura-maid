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

    <div style="text-align:center;margin-top:1rem;display:flex;gap:1rem;justify-content:center">
      <button onclick="event.stopPropagation();imprimirFactura(<?=(int)$f['id']?>)" class="btn btn-outline btn-sm">🖨️ Imprimir</button>
      <button onclick="event.stopPropagation();descargarPDF(<?=(int)$f['id']?>, '<?=e($f['numero'])?>')" class="btn btn-primary btn-sm">📄 Descargar PDF</button>
    </div>

  </div>
</div>
<?php endforeach; ?>
</div>

<style>
@media print {
  .sidebar, nav, .page-head, .actions-bar, button, .btn { display: none !important; }
  .card { box-shadow: none !important; border: 1px solid #ddd; }
  #print-area { display: block !important; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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

function descargarPDF(id, numero) {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  const det = document.getElementById('detalle-'+id);
  det.style.display='block';

  // Encabezado
  doc.setFillColor(201, 123, 132);
  doc.rect(0, 0, 210, 35, 'F');
  doc.setTextColor(255, 255, 255);
  doc.setFontSize(22);
  doc.setFont('helvetica', 'bold');
  doc.text('🌸 Sakura Maid Services', 15, 18);
  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  doc.text('Factura: ' + numero, 15, 28);

  // Datos de la factura
  doc.setTextColor(50, 50, 50);
  doc.setFontSize(11);
  let y = 50;

  const cards = det.querySelectorAll('[style*="background:var(--g100)"]');
  cards.forEach(card => {
    const label = card.querySelector('[style*="font-size:.75rem"]');
    const value = card.querySelector('[style*="font-weight:600"]');
    if(label && value) {
      doc.setFont('helvetica', 'bold');
      doc.text(label.innerText + ':', 15, y);
      doc.setFont('helvetica', 'normal');
      doc.text(value.innerText, 80, y);
      y += 10;
    }
  });

  // Línea separadora
  y += 5;
  doc.setDrawColor(201, 123, 132);
  doc.setLineWidth(0.5);
  doc.line(15, y, 195, y);
  y += 10;

  // Resumen financiero
  doc.setFontSize(12);
  const resumen = det.querySelectorAll('[style*="display:flex;justify-content:space-between"]');
  resumen.forEach(row => {
    const cols = row.querySelectorAll('span');
    if(cols.length >= 2) {
      doc.setFont('helvetica', 'normal');
      doc.text(cols[0].innerText, 80, y);
      doc.text(cols[1].innerText, 150, y, {align: 'right'});
      y += 10;
    }
  });

  // Pie de página
  doc.setFontSize(9);
  doc.setTextColor(150, 150, 150);
  doc.text('Sakura Maid Services © ' + new Date().getFullYear() + ' — Santo Domingo, RD', 105, 285, {align: 'center'});

  doc.save(numero + '.pdf');
}
</script>
<?php endif; ?>
<?php require __DIR__.'/../shared/layout_bottom.php'; ?>