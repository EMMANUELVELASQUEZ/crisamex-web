<?php
// ============================================
// CRISAMEX — Configuración Central
// ============================================

// ── Leer archivo .env si existe ──────────────
$envFile = dirname(__DIR__, 2) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (strpos($line, '=') !== false) {
            [$key, $val] = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\"'");
            if (!getenv($key)) putenv("$key=$val");
        }
    }
}

// ── Base de datos ────────────────────────────
define('DB_HOST',    getenv('DB_HOST')    ?: 'localhost');
define('DB_NAME',    getenv('DB_NAME')    ?: 'crisamex_db');
define('DB_USER',    getenv('DB_USER')    ?: 'crisamex_user');
define('DB_PASS',    getenv('DB_PASS')    ?: 'Crisam3x2024!');
define('DB_CHARSET', 'utf8mb4');

// ── App ──────────────────────────────────────
define('APP_URL',  getenv('APP_URL')  ?: 'http://localhost:8090');
define('APP_ENV',  getenv('APP_ENV')  ?: 'development');
define('APP_NAME', 'CRISAMEX');

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (APP_ENV === 'development') {
                    die("❌ Error de conexión: " . $e->getMessage());
                } else {
                    die("Error de conexión. Por favor contacte al administrador.");
                }
            }
        }
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?array {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    public static function getConfig(string $key, string $default = ''): string {
        $result = self::fetchOne("SELECT valor FROM site_config WHERE clave = ?", [$key]);
        return $result ? $result['valor'] : $default;
    }
}

// ── Helpers ──────────────────────────────────
require_once dirname(__FILE__) . '/../helpers/mailer.php';

    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (APP_ENV === 'development') {
                    die("Error de conexión: " . $e->getMessage());
                } else {
                    die("Error de conexión a la base de datos. Por favor contacte al administrador.");
                }
            }
        }
        return self::$instance;
    }

    // Query con prepared statements
    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fetch all rows
    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    // Fetch single row
    public static function fetchOne(string $sql, array $params = []): ?array {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    // Get config value
    public static function getConfig(string $key, string $default = ''): string {
        $result = self::fetchOne("SELECT valor FROM site_config WHERE clave = ?", [$key]);
        return $result ? $result['valor'] : $default;
    }
}

// Cargar helpers
require_once dirname(__FILE__) . '/../helpers/mailer.php';
