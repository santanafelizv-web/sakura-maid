<?php
require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../config/database.php';

spl_autoload_register(function(string $class) {
    foreach ([__DIR__.'/../app/controllers/', __DIR__.'/../app/models/'] as $dir) {
        if (file_exists($dir.$class.'.php')) { require_once $dir.$class.'.php'; return; }
    }
});

$uri = strtok($_SERVER['REQUEST_URI'], '?');
$uri = preg_replace('#/+#', '/', $uri);
$uri = rtrim($uri, '/');
if ($uri === '') { $uri = '/'; }
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    ['GET',  '/',                      'HomeController',      'index'],
    ['GET',  '/login',                 'AuthController',      'loginForm'],
    ['POST', '/login',                 'AuthController',      'login'],
    ['GET',  '/registro',              'AuthController',      'registerForm'],
    ['POST', '/registro',              'AuthController',      'register'],
    ['GET',  '/logout',                'AuthController',      'logout'],
    ['GET',  '/dashboard',             'DashboardController', 'index'],
    ['GET',  '/maids',                 'MaidController',      'index'],
    ['GET',  '/maids/perfil',          'MaidController',      'perfil'],
    ['POST', '/maids/perfil',          'MaidController',      'guardarPerfil'],
    ['GET',  '/servicios',             'ServicioController',  'index'],
    ['GET',  '/servicios/nuevo',       'ServicioController',  'nuevo'],
    ['POST', '/servicios/nuevo',       'ServicioController',  'crear'],
    ['POST', '/servicios/estado',      'ServicioController',  'cambiarEstado'],
    ['GET',  '/facturas',              'FacturaController',   'index'],
    ['GET',  '/reportes',              'ReporteController',   'index'],
    ['GET',  '/perfil',                'PerfilController',    'index'],
    ['POST', '/perfil',                'PerfilController',    'actualizar'],
    ['POST', '/perfil/avatar',         'PerfilController',    'actualizarAvatar'],
    ['GET',  '/resenas',               'ResenaController',    'index'],
    ['GET',  '/resenas/crear',         'ResenaController',    'crear'],
    ['POST', '/resenas/crear',         'ResenaController',    'guardar'],
    ['GET',  '/api/dashboard-data',    'ApiController',       'dashboardData'],
    ['GET',  '/api/reporte-data',      'ApiController',       'reporteData'],
];

foreach ($routes as [$rm, $ru, $ctrl, $action]) {
    if ($method === $rm && $uri === $ru) {
        (new $ctrl())->$action(); exit;
    }
}

http_response_code(404);
require_once __DIR__.'/../app/views/shared/404.php';