<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Registro — Sakura Maid Services</title>
<link rel="stylesheet" href="/css/sakura.css"></head>
<body>
<div class="auth-wrap">
  <div class="auth-panel">
    <div class="auth-logo">🌸 Sakura</div>
    <h2>Únete a Sakura</h2>
    <p>Sé cliente o conviértete en Maid. Tú decides cómo usar la plataforma.</p>
  </div>
  <div class="auth-form-wrap">
    <div class="auth-box">
      <h1 class="auth-title">Crear cuenta</h1>
      <p class="auth-sub">Completa tus datos para empezar</p>
      <?php if($error): ?><div class="alert alert-error">⚠️ <?=e($error)?></div><?php endif ?>
      <form method="POST" action="/registro">
        <div class="form-group"><label>¿Cómo usarás Sakura?</label>
          <div class="rol-grid">
            <label class="rol-opt"><input type="radio" name="rol" value="cliente" <?=(($old['rol']??'cliente')==='cliente'?'checked':'')?>><div class="rol-card"><span class="ri">🏠</span><span class="rl">Soy Cliente</span></div></label>
            <label class="rol-opt"><input type="radio" name="rol" value="maid" <?=(($old['rol']??'')==='maid'?'checked':'')?>><div class="rol-card"><span class="ri">🧹</span><span class="rl">Soy Maid</span></div></label>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?=e($old['nombre']??'')?>" placeholder="María" required></div>
          <div class="form-group"><label>Apellido</label><input type="text" name="apellido" class="form-control" value="<?=e($old['apellido']??'')?>" placeholder="López" required></div>
        </div>
        <div class="form-group"><label>Correo</label><input type="email" name="email" class="form-control" value="<?=e($old['email']??'')?>" placeholder="tu@correo.com" required></div>
        <div class="form-group"><label>Teléfono (opcional)</label><input type="tel" name="telefono" class="form-control" value="<?=e($old['telefono']??'')?>" placeholder="809-000-0000"></div>
        <div class="form-row">
          <div class="form-group"><label>Contraseña</label><input type="password" name="password" class="form-control" placeholder="Mín. 8 caracteres" required></div>
          <div class="form-group"><label>Confirmar</label><input type="password" name="confirm" class="form-control" placeholder="Repite la contraseña" required></div>
        </div>
        <button type="submit" class="btn btn-primary">Crear mi cuenta 🌸</button>
      </form>
      <div class="auth-foot">¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></div>
    </div>
  </div>
</div>
</body></html>
