<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Iniciar Sesión — Sakura Maid Services</title>
<link rel="stylesheet" href="/css/sakura.css"></head>
<body>
<div class="auth-wrap">
  <div class="auth-panel">
    <div class="auth-logo">🌸 Sakura</div>
    <h2>Bienvenida de vuelta</h2>
    <p>Tu servicio de limpieza de confianza, siempre a un clic de distancia.</p>
  </div>
  <div class="auth-form-wrap">
    <div class="auth-box">
      <h1 class="auth-title">Iniciar sesión</h1>
      <p class="auth-sub">Ingresa tus credenciales para continuar</p>
      <?php if($error): ?><div class="alert alert-error">⚠️ <?=e($error)?></div><?php endif ?>
      <?php if($success): ?><div class="alert alert-success">✓ <?=e($success)?></div><?php endif ?>
      <form method="POST" action="/login">
        <div class="form-group"><label>Correo electrónico</label>
          <input type="email" name="email" class="form-control" placeholder="tu@correo.com" required autocomplete="email">
        </div>
        <div class="form-group"><label>Contraseña</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary">Entrar ✨</button>
      </form>
      <div class="auth-foot">¿No tienes cuenta? <a href="/registro">Regístrate gratis</a></div>
      <div style="margin-top:1rem;padding:1rem;background:var(--g100);border-radius:var(--r);font-size:.8rem;color:var(--g600)">
        <strong>Cuentas de prueba (password: <code>password</code>)</strong><br>
        admin@sakura.com · maria@sakura.com · ana@sakura.com
      </div>
    </div>
  </div>
</div>
</body></html>
