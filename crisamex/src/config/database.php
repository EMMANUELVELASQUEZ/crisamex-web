<?php
$envFile = dirname(__DIR__, 2) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\"'");
            if (!getenv($key)) putenv("$key=$val");
        }
    }
}

define('DB_HOST',    getenv('DB_HOST')    ?: 'crisamex_db');
define('DB_NAME',    getenv('DB_NAME')    ?: 'crisamex_db');
define('DB_USER',    getenv('DB_USER')    ?: 'crisamex_user');
define('DB_PASS',    getenv('DB_PASS')    ?: 'Crisam3x2024!');
define('DB_CHARSET', 'utf8mb4');
define('APP_URL',    getenv('APP_URL')    ?: 'http://localhost:8090');
define('APP_ENV',    getenv('APP_ENV')    ?: 'development');
define('APP_NAME',   'CRISAMEX');

class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $options = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            );
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                die("Error de conexion: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public static function query($sql, $params = array()) {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll($sql, $params = array()) {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchOne($sql, $params = array()) {
        $result = self::query($sql, $params)->fetch();
        return $result ? $result : null;
    }

    public static function getConfig($key, $default = '') {
        $result = self::fetchOne("SELECT valor FROM site_config WHERE clave = ?", array($key));
        return $result ? $result['valor'] : $default;
    }
}

$mailerFile = dirname(__FILE__) . '/../helpers/mailer.php';
if (file_exists($mailerFile)) {
    require_once $mailerFile;
}
