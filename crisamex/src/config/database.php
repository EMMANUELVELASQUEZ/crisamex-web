<?php
/**
 * CRISAMEX — src/config/database.php
 * Versión con UTF-8 correcto — resuelve acentos y ñ
 */
class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }
        return self::$instance;
    }

    private static function connect(): PDO {
        $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
        $db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'crisamex_db';
        $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'crisamex_user';
        $pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
        $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false,
        ];

        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log('[CRISAMEX DB] ' . $e->getMessage());
            http_response_code(503);
            die('Error de base de datos. Intente más tarde.');
        }
    }

    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): array|false {
        return self::query($sql, $params)->fetch();
    }

    public static function lastInsertId(): string {
        return self::getInstance()->lastInsertId();
    }

    public static function getConfig(string $key): ?string {
        $row = self::fetchOne(
            "SELECT valor FROM site_config WHERE clave = ? LIMIT 1",
            [$key]
        );
        return $row ? $row['valor'] : null;
    }
}
