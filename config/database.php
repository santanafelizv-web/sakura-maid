<?php
define('DB_HOST',     getenv('MYSQLHOST')     ?: 'hopper.proxy.rlwy.net');
define('DB_PORT',     getenv('MYSQLPORT')     ?: '40174');
define('DB_NAME',     getenv('MYSQLDATABASE') ?: 'sakura_maid_db');
define('DB_USER',     getenv('MYSQLUSER')     ?: 'root');
define('DB_PASSWORD', getenv('MYSQLPASSWORD') ?: 'qTDpgfRgHbTJthTYWiYeVeqyRhwHYJzy');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4";
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:2rem;color:#c0392b"><h2>❌ Error de conexión</h2><p>'.$e->getMessage().'</p></div>');
        }
    }
    return $pdo;
}
