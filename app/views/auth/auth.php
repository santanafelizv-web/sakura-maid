<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=$mode==='login'?'Iniciar Sesión':'Registro'?> — Sakura Maid Services</title>
<link rel="stylesheet" href="/css/sakura.css">
<style>
html,body{overflow:hidden;height:100%}
.auth-wrap{min-height:100vh;display:grid;grid-template-columns:1fr 1fr}
.auth-form-wrap{display:flex;align-items:center;justify-content:center;padding:2.5rem 2rem;background:var(--white)}
.auth-box{width:100%;max-width:430px}
.auth-stack{position:relative;width:100%;min-height:580px}
.auth-form-panel{position:absolute;top:0;left:0;width:100%;transition:transform .5s cubic-bezier(.22,1,.36,1),opacity .5s cubic-bezier(.22,1,.36,1)}
.auth-form-panel.slide-out{transform:translateY(40px);opacity:0;pointer-events:none}
.auth-form-panel.slide-in{transform:translateY(-30px);opacity:0;pointer-events:none}
.auth-form-panel.slide-in.active{transform:translateY(0);opacity:1;pointer-events:all}
.auth-form-panel:not(.slide-out):not(.slide-in){transform:translateY(0);opacity:1}

.auth-panel .typing-wrap{min-height:5rem;position:relative;z-index:1}
.auth-panel .typing-title{color:var(--apricot);font-size:1.8rem;text-align:center;font-family:'Playfair Display',serif}
.auth-panel .typing-text{color:var(--g400);text-align:center;margin-top:.6rem;max-width:260px;font-size:.95rem}

@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}
.btn-back{position:fixed;bottom:2rem;right:2rem;width:56px;height:56px;border-radius:50%;background:var(--rose);color:#fff;border:none;font-size:1.6rem;cursor:pointer;box-shadow:0 3px 12px rgba(201,123,132,.4);transition:all .2s;display:flex;align-items:center;justify-content:center;z-index:100;text-decoration:none}
.btn-back:hover{transform:scale(1.1);box-shadow:0 5px 20px rgba(201,123,132,.5)}
</style>
</head>
<body>
<div class="auth-wrap">
  <div class="auth-panel">
    <div class="auth-logo">🌸 Sakura</div>
    <div class="typing-wrap">
      <div class="typing-title" id="typingTitle"></div>
      <div class="typing-text" id="typingText"></div>
    </div>
  </div>
  <div class="auth-form-wrap">
    <div class="auth-box">
      <div class="auth-stack" id="authStack">
        <div class="auth-form-panel <?=$mode==='login'?'':'slide-in'?>" id="loginPanel">
          <h1 class="auth-title">Iniciar sesión</h1>
          <p class="auth-sub">Ingresa tus credenciales para continuar</p>
          <?php if($mode==='login'): ?>
          <?php if(!empty($error)): ?><div class="alert alert-error">⚠️ <?=e($error)?></div><?php endif ?>
          <?php if(!empty($success)): ?><div class="alert alert-success">✓ <?=e($success)?></div><?php endif ?>
          <?php endif; ?>
          <form method="POST" action="/login">
            <div class="form-group"><label>Correo electrónico</label>
              <input type="email" name="email" class="form-control" placeholder="tu@correo.com" required autocomplete="email">
            </div>
            <div class="form-group"><label>Contraseña</label>
              <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary">Entrar ✨</button>
          </form>
          <div class="auth-foot">¿No tienes cuenta? <a href="#" data-switch="register">Regístrate gratis</a></div>
        </div>

        <div class="auth-form-panel <?=$mode==='register'?'':'slide-in'?>" id="registerPanel">
          <h1 class="auth-title">Crear cuenta</h1>
          <p class="auth-sub">Completa tus datos para empezar</p>
          <?php if($mode==='register'): ?>
          <?php if(!empty($error)): ?><div class="alert alert-error">⚠️ <?=e($error)?></div><?php endif ?>
          <?php endif; ?>
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
          <div class="auth-foot">¿Ya tienes cuenta? <a href="#" data-switch="login">Inicia sesión</a></div>
        </div>
      </div>
    </div>
  </div>
</div>

<a href="/" class="btn-back">←</a>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var messages = {
    login: { title: 'Bienvenida de vuelta', text: 'Tu servicio de limpieza de confianza, siempre a un clic de distancia.' },
    register: { title: 'Únete a Sakura', text: 'Sé cliente o conviértete en Maid. Tú decides cómo usar la plataforma.' }
  };

  var currentMode = '<?=$mode?>';
  var typingTitle = document.getElementById('typingTitle');
  var typingText = document.getElementById('typingText');
  var loginPanel = document.getElementById('loginPanel');
  var registerPanel = document.getElementById('registerPanel');

  function typeText(el, text, speed) {
    el.textContent = '';
    var i = 0;
    function type() {
      if (i < text.length) {
        el.textContent += text.charAt(i);
        i++;
        setTimeout(type, speed);
      }
    }
    type();
  }

  function switchMode(mode) {
    if (mode === currentMode) return;
    var msg = messages[mode];
    var outPanel = mode === 'login' ? registerPanel : loginPanel;
    var inPanel = mode === 'login' ? loginPanel : registerPanel;

    typingTitle.style.opacity = '0';
    typingText.style.opacity = '0';

    outPanel.classList.remove('slide-in');
    outPanel.classList.remove('active');
    outPanel.classList.add('slide-out');

    setTimeout(function () {
      outPanel.classList.remove('slide-out');
      outPanel.classList.add('slide-in');
      inPanel.classList.remove('slide-in');
      inPanel.classList.remove('slide-out');
      inPanel.classList.add('active');
    }, 300);

    setTimeout(function () {
      typingTitle.style.opacity = '1';
      typingTitle.textContent = '';
      typeText(typingTitle, msg.title, 50);
    }, 200);

    setTimeout(function () {
      typingText.style.opacity = '1';
      typingText.textContent = '';
      typeText(typingText, msg.text, 20);
    }, 600);

    currentMode = mode;
    history.replaceState(null, '', mode === 'login' ? '/login' : '/registro');
  }

  document.querySelectorAll('[data-switch]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      switchMode(this.getAttribute('data-switch'));
    });
  });

  typeText(typingTitle, messages[currentMode].title, 50);
  setTimeout(function () {
    typeText(typingText, messages[currentMode].text, 20);
  }, 400);
});
</script>
</body></html>
