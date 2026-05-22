<?php
define('APP_NAME', 'Sakura Maid Services');

$defaultScheme = 'http';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $defaultScheme = 'https';
}
$defaultHost = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
define('APP_URL', getenv('APP_URL') ?: $defaultScheme.'://'.$defaultHost);

// N8N webhook URL (configura en Railway)
define('N8N_WEBHOOK', getenv('N8N_WEBHOOK_URL') ?: '');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime'=>86400,'path'=>'/','httponly'=>true,'samesite'=>'Lax']);
    session_start();
}

function redirect(string $path): void { header('Location: '.APP_URL.$path); exit; }
function authUser(): ?array           { return $_SESSION['user'] ?? null; }
function e(string $s): string         { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function requireLogin(): void {
    if (!authUser()) redirect('/login');
}
function requireRole(string $rol): void {
    requireLogin();
    $u = authUser();
    if ($u['rol'] !== $rol && $u['rol'] !== 'admin') redirect('/dashboard');
}

// Dispara webhook a n8n
function triggerN8n(string $evento, array $data): void {
    $url = N8N_WEBHOOK;
    if (empty($url)) return;
    $payload = json_encode(['evento' => $evento, 'data' => $data]);
    $ctx = stream_context_create(['http'=>[
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\nContent-Length: ".strlen($payload)."\r\n",
        'content' => $payload,
        'timeout' => 5,
    ]]);
    @file_get_contents($url, false, $ctx);
}
