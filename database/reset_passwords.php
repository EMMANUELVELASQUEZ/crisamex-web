<?php
/**
 * CRISAMEX — Reset de contraseñas
 * Ejecutar: php database/reset_passwords.php
 */

$host = getenv('DB_HOST') ?: 'crisamex_db';
$db   = getenv('DB_NAME') ?: 'crisamex_db';
$user = getenv('DB_USER') ?: 'crisamex_user';
$pass = getenv('DB_PASS') ?: 'Crisam3x2024!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "✅ Conectado a la BD\n\n";
} catch(PDOException $e){
    die("❌ Error BD: ".$e->getMessage()."\n");
}

$nueva = 'Crisamex2024!';
$hash  = password_hash($nueva, PASSWORD_BCRYPT, ['cost'=>10]);

// Admin
$pdo->prepare("UPDATE admin_usuarios SET password_hash=?, activo=1 WHERE email='admin@crisamex.com'")
    ->execute([$hash]);

// Si no existe, crearlo
$existe = $pdo->query("SELECT COUNT(*) FROM admin_usuarios WHERE email='admin@crisamex.com'")->fetchColumn();
if(!$existe){
    $pdo->prepare("INSERT INTO admin_usuarios (nombre,email,password_hash,rol,activo) VALUES (?,?,?,?,1)")
        ->execute(['Administrador','admin@crisamex.com',$hash,'superadmin']);
    echo "✅ Admin creado\n";
} else {
    echo "✅ Admin actualizado\n";
}

// Cliente demo
$exCli = $pdo->query("SELECT COUNT(*) FROM clientes WHERE email='demo@empresa.com'")->fetchColumn();
if($exCli){
    $pdo->prepare("UPDATE clientes SET password_hash=?,activo=1,licencia_status='activa',licencia_fin='2027-12-31' WHERE email='demo@empresa.com'")
        ->execute([$hash]);
    echo "✅ Cliente demo actualizado\n";
}

// Verificar
echo "\n=== VERIFICACIÓN ===\n";
echo "password_verify('{$nueva}', hash) = ";
echo password_verify($nueva, $hash) ? "✅ CORRECTO\n" : "❌ ERROR\n";

$adm = $pdo->query("SELECT email,activo,rol FROM admin_usuarios WHERE email='admin@crisamex.com'")->fetch(PDO::FETCH_ASSOC);
if($adm) echo "Admin: {$adm['email']} | activo={$adm['activo']} | rol={$adm['rol']}\n";

echo "\n======================\n";
echo "🔑 Email: admin@crisamex.com\n";
echo "🔑 Contraseña: {$nueva}\n";
echo "🌐 URL: http://localhost:8090/admin/login\n";
echo "======================\n";
