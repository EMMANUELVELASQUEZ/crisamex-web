<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Sanitizar y validar
$nombre   = trim(strip_tags($_POST['nombre'] ?? ''));
$empresa  = trim(strip_tags($_POST['empresa'] ?? ''));
$email    = trim($_POST['email'] ?? '');
$telefono = trim(strip_tags($_POST['telefono'] ?? ''));
$servicio = trim(strip_tags($_POST['servicio_interes'] ?? ''));
$mensaje  = trim(strip_tags($_POST['mensaje'] ?? ''));
$ip       = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

// Validaciones
$errors = [];
if (empty($nombre) || strlen($nombre) < 2) {
    $errors[] = 'El nombre es requerido (mínimo 2 caracteres).';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Por favor ingrese un correo electrónico válido.';
}
if (empty($mensaje) || strlen($mensaje) < 10) {
    $errors[] = 'El mensaje es requerido (mínimo 10 caracteres).';
}
if (strlen($nombre) > 100 || strlen($email) > 150 || strlen($mensaje) > 3000) {
    $errors[] = 'Algún campo excede el límite de caracteres permitido.';
}

// Rate limiting simple por IP (máx 5 mensajes por hora)
$count = Database::fetchOne(
    "SELECT COUNT(*) as total FROM contacto_mensajes WHERE ip = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)",
    [$ip]
);
if ($count && $count['total'] >= 5) {
    echo json_encode(['success' => false, 'message' => 'Has enviado demasiados mensajes. Espera un momento antes de intentar de nuevo.']);
    exit;
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    Database::query(
        "INSERT INTO contacto_mensajes (nombre, empresa, email, telefono, servicio_interes, mensaje, ip) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$nombre, $empresa, $email, $telefono, $servicio, $mensaje, $ip]
    );

    // Aquí puedes agregar envío de email con PHPMailer o mail()
    // mail('contacto@crisamex.com', 'Nuevo mensaje web - ' . $nombre, $mensaje, "Reply-To: $email");

    echo json_encode([
        'success' => true,
        'message' => '¡Mensaje enviado con éxito! Nos pondremos en contacto contigo a la brevedad. Gracias por contactar a CRISAMEX.'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al guardar tu mensaje. Por favor intenta más tarde o llámanos directamente.'
    ]);
}
