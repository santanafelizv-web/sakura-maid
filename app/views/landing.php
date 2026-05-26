<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sakura Maid Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/landing.css">
</head>
<body>
  <video class="video-bg" autoplay loop muted playsinline>
    <source src="/video/background.mp4" type="video/mp4">
  </video>

  <div class="curtain-overlay" id="curtainOverlay">
    <img src="/images/SakuraMaidLogo.webp" alt="SakuraMaid" class="curtain-logo">
    <div class="curtain-title playfair">Sakura Maid Services</div>
  </div>

  <nav class="navbar navbar-expand-lg navbar-gradient" id="mainNav" data-gradient="true" style="position: fixed; top: 0; left: 0; right: 0; z-index: 1030; backdrop-filter: blur(8px);">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center playfair text-white" href="/" style="font-size: 2.2rem;">
        <img src="/images/SakuraMaidLogo.webp" alt="SakuraMaid" width="120" height="120">
        <span class="ms-2">Sakura Maid Services</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
        </ul>
      </div>
    </div>
  </nav>

  <section class="hero-section d-flex align-items-center" style="background: linear-gradient(135deg, rgba(201, 123, 132, 0.55) 0%, rgba(255, 225, 198, 0.55) 100%);">
    <div class="container text-center py-5">
      <h1 class="display-3 fw-bold text-white mb-3 playfair animate-fade-in-up" style="font-size: clamp(2.2rem, 5vw, 4rem);">
        Conectamos familias con las mejores maids
      </h1>
      <p class="lead text-white mb-5 mx-auto animate-fade-in-up-delay" style="max-width: 600px; font-weight: 300;">
        Encuentra, contrata y gestiona el servicio de limpieza ideal para tu hogar de forma segura y sencilla.
      </p>
      <div class="d-flex gap-3 justify-content-center flex-wrap animate-fade-in-up-delay-2">
        <a href="/login" class="btn-hero" id="heroBtn">
          <span class="sparkle">🌸</span>
          ¿Quieres contratar una Maid?
          <span class="sparkle">✨</span>
        </a>
      </div>
    </div>
  </section>

  <section class="py-5" style="background-color: #FFFBD9;">
    <div class="container py-4">
      <h2 class="text-center playfair fw-bold mb-5" style="color: #1E2019; font-size: clamp(1.5rem, 3vw, 2.2rem);">¿Por qué elegir SakuraMaid?</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card feature-card h-100 text-center p-4">
            <div class="mb-3">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle feature-icon">🔍</span>
            </div>
            <h5 class="fw-bold mb-2 playfair" style="color: #846C5B;">Encuentra la maid ideal</h5>
            <p class="mb-0 small" style="color: #5a4a3d;">Explora perfiles verificados con reseñas reales, habilidades y disponibilidad en tu zona.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card h-100 text-center p-4">
            <div class="mb-3">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle feature-icon">📅</span>
            </div>
            <h5 class="fw-bold mb-2 playfair" style="color: #846C5B;">Contrata en minutos</h5>
            <p class="mb-0 small" style="color: #5a4a3d;">Selecciona fechas, horarios y recurrencia. Pago seguro con cálculo automático de precio.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card h-100 text-center p-4">
            <div class="mb-3">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle feature-icon">💬</span>
            </div>
            <h5 class="fw-bold mb-2 playfair" style="color: #846C5B;">Comunicación directa</h5>
            <p class="mb-0 small" style="color: #5a4a3d;">Chat integrado, reseñas post-servicio y notificaciones para mantenerte al tanto.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="text-center py-4" style="color: #846C5B; background-color: #FFFBD9;">
    <small>&copy; <?=date('Y')?> Sakura Maid Services. Desarrollado por NovaTech.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    var nav = document.getElementById('mainNav');
    if (!nav) return;

    function updateNav() {
      if (nav.dataset.gradient === 'true') {
        if (window.scrollY > 50) {
          nav.classList.remove('navbar-gradient');
          nav.classList.add('navbar-hidden');
        } else {
          nav.classList.remove('navbar-hidden');
          nav.classList.add('navbar-gradient');
        }
      }
    }

    updateNav();
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          updateNav();
          ticking = false;
        });
        ticking = true;
      }
    });

    var curtain = document.getElementById('curtainOverlay');
    var curtainLinks = document.querySelectorAll('.curtain-link');

    function navigateWithCurtain(url) {
      curtain.classList.add('active');
      setTimeout(function () {
        window.location.href = url;
      }, 800);
    }

    document.getElementById('heroBtn').addEventListener('click', function (e) {
      e.preventDefault();
      navigateWithCurtain(this.getAttribute('href'));
    });

    curtainLinks.forEach(function (link) {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        navigateWithCurtain(this.getAttribute('href'));
      });
    });
  });
  </script>
</body>
</html>
