<?php
/**
 * CRISAMEX — Prueba de Email
 * Ejecutar: php database/test_email.php
 */
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH',  ROOT_PATH . '/src');
define('APP_URL',   'http://localhost:8090');

// Cargar config (lee el .env automáticamente)
require_once SRC_PATH . '/config/database.php';

$driver = getenv('MAIL_DRIVER') ?: 'no configurado';
$user   = getenv('MAIL_USER')   ?: 'no configurado';

echo "=================================\n";
echo "  CRISAMEX — Prueba de Email\n";
echo "=================================\n";
echo "Driver: $driver\n";
echo "Usuario: $user\n";
echo "---------------------------------\n";

if ($driver === 'no configurado' || $user === 'no configurado') {
    echo "❌ No hay configuración de email.\n";
    echo "   Crea el archivo .env con tus datos.\n";
    exit(1);
}

$destino = $argv[1] ?? $user;
echo "Enviando prueba a: $destino\n";

$ok = Mailer::send(
    $destino,
    '✅ Prueba de email — CRISAMEX',
    '<h2 style="color:#C8151B">¡El email funciona!</h2>
     <p>Este es un correo de prueba del sistema CRISAMEX.</p>
     <p>Si ves este mensaje, la configuración de email está correcta.</p>
     <p><strong>Servicio:</strong> ' . $driver . '</p>',
    'Prueba CRISAMEX'
);

echo $ok
    ? "✅ EMAIL ENVIADO correctamente a $destino\n   Revisa tu bandeja de entrada.\n"
    : "❌ ERROR al enviar. Verifica tus credenciales en .env\n";
echo "=================================\n";
