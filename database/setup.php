#!/usr/bin/env php
<?php
/**
 * CRISAMEX - Script de inicialización de base de datos
 * Ejecutar una vez después del primer deploy en Render:
 * php database/setup.php
 */

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'crisamex_db';
$user = getenv('DB_USER') ?: 'crisamex_user';
$pass = getenv('DB_PASS') ?: '';

echo "🔧 Conectando a la base de datos...\n";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Conexión exitosa.\n";
} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage() . "\n");
}

$sql = file_get_contents(__DIR__ . '/init.sql');

// Ejecutar por bloques
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    fn($s) => !empty($s) && $s !== '--'
);

$ok = 0; $err = 0;
foreach ($statements as $stmt) {
    if (empty(trim($stmt))) continue;
    try {
        $pdo->exec($stmt);
        $ok++;
    } catch (PDOException $e) {
        // Ignorar errores de tabla ya existente
        if (strpos($e->getMessage(), 'already exists') === false) {
            echo "⚠️  " . $e->getMessage() . "\n";
            $err++;
        }
    }
}

echo "✅ Base de datos inicializada. ($ok OK, $err errores)\n";
echo "🎉 CRISAMEX listo. Admin: admin@crisamex.com / password\n";
