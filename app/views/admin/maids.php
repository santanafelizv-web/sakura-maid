<?php $pageTitle='Gestionar Maids'; $ap='maids'; require __DIR__.'/../shared/layout_top.php'; ?>

<div class="page-head">
  <h1>Gestionar Maids 🧹</h1>
  <p>Edita la tarifa por hora de cada maid</p>
</div>

<?php if($ok??null): ?><div class="alert alert-success">✓ <?=e($ok)?></div><?php endif; ?>
<?php if($err??null): ?><div class="alert alert-err">✗ <?=e($err)?></div><?php endif; ?>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Maid</th><th>Email</th><th>Tarifa actual</th><th>Nueva tarifa</th></tr>
      </thead>
      <tbody>
      <?php foreach($maids as $m): ?>
      <tr>
        <td><strong><?=e($m['nombre'].' '.$m['apellido'])?></strong></td>
        <td><?=e($m['email'])?></td>
        <td>RD$<?=number_format((float)$m['tarifa_hora'],0,'.','.')?>/hr</td>
        <td>
          <form method="POST" action="/admin/maids/tarifa" style="display:flex;align-items:center;gap:.5rem">
            <input type="hidden" name="maid_id" value="<?=(int)$m['id']?>">
            <input type="number" name="tarifa_hora" value="<?=(float)$m['tarifa_hora']?>" min="0" step="50"
              style="width:100px;padding:.4rem .6rem;border:1px solid var(--g200);border-radius:var(--r);font-size:.9rem">
            <button type="submit" class="btn btn-primary btn-auto" style="padding:.4rem 1rem">Guardar</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:1.5rem">
  <a href="/dashboard" class="btn btn-outline btn-auto">← Volver al dashboard</a>
</div>

<?php require __DIR__.'/../shared/layout_bottom.php'; ?>