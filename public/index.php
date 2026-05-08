<?php
/**
 * CRISAMEX — Router Principal
 * Seguridad Enterprise v10
 */

// ── CONSTANTES ──────────────────────────────────────────────
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH',  ROOT_PATH.'/src');

// ── AUTOLOAD SEGURO ─────────────────────────────────────────
require_once SRC_PATH.'/config/database.php';
require_once SRC_PATH.'/helpers/security.php';
require_once SRC_PATH.'/helpers/mailer.php';

// ── SESIÓN SEGURA ───────────────────────────────────────────
Security::secureSession();

// ── HEADERS DE SEGURIDAD ────────────────────────────────────
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header_remove('X-Powered-By');
header_remove('Server');

// ── RATE LIMITING GLOBAL ────────────────────────────────────
$ip  = Security::getClientIP();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Máximo 200 requests por minuto por IP
if(!Security::rateLimit('global_'.$ip, 200, 60)){
  http_response_code(429);
  die('<h1>429 — Demasiadas solicitudes</h1><p>Por favor espere un momento.</p>');
}

// Rate limit especial en logins — máx 5 intentos/5min
if(in_array($uri,['/admin/login','/portal/login','/portal/registro']) && $_SERVER['REQUEST_METHOD']==='POST'){
  if(!Security::rateLimit('login_'.$ip, 5, 300)){
    http_response_code(429);
    Security::logAttack('Brute Force Detected', $ip);
    die('<h1>429 — Demasiados intentos</h1><p>Acceso bloqueado por 5 minutos. <a href="/">Volver al inicio</a></p>');
  }
}

// ── SANITIZAR INPUT GLOBAL ──────────────────────────────────
$_GET  = Security::cleanInput($_GET);
$_POST = Security::cleanInput($_POST);

// ── ROUTER ──────────────────────────────────────────────────
$routes = [
  '/'                      => 'home',
  '/quienes-somos'         => 'quienes-somos',
  '/servicios'             => 'servicios',
  '/certificaciones'       => 'certificaciones',
  '/planes'                => 'planes',
  '/contacto'              => 'contacto',
  '/contacto/enviar'       => 'contacto-enviar',
  '/404'                   => '404',

  // Portal clientes
  '/portal'                => 'portal/dashboard',
  '/portal/login'          => 'portal/login',
  '/portal/registro'       => 'portal/registro',
  '/portal/logout'         => 'portal/logout',
  '/portal/mensajes'       => 'portal/mensajes',
  '/portal/documentos'     => 'portal/documentos',
  '/portal/licencia'       => 'portal/licencia',
  '/portal/perfil'         => 'portal/perfil',

  // Admin
  '/admin'                 => 'admin/dashboard',
  '/admin/login'           => 'admin/login',
  '/admin/logout'          => 'admin/logout',
  '/admin/mensajes'        => 'admin/mensajes',
  '/admin/comunicaciones'  => 'admin/comunicaciones',
  '/admin/clientes'        => 'admin/clientes',
  '/admin/planes'          => 'admin/planes',
  '/admin/servicios'       => 'admin/servicios',
  '/admin/equipo'          => 'admin/equipo',
  '/admin/configuracion'   => 'admin/configuracion',
];

$path = rtrim($uri, '/') ?: '/';

// ── PROTEGER RUTAS ADMIN ────────────────────────────────────
if(str_starts_with($path, '/admin') && $path !== '/admin/login'){
  if(empty($_SESSION['admin_id'])){
    header('Location: /admin/login'); exit;
  }
}

// ── PROTEGER RUTAS PORTAL ───────────────────────────────────
$publicPortal = ['/portal/login','/portal/registro'];
if(str_starts_with($path, '/portal') && !in_array($path, $publicPortal)){
  if(empty($_SESSION['cliente_id'])){
    header('Location: /portal/login'); exit;
  }
}

// ── DESPACHAR CONTROLADOR ───────────────────────────────────
$ctrl = $routes[$path] ?? null;

if($ctrl){
  $file = SRC_PATH.'/controllers/'.$ctrl.'.php';
  if(file_exists($file)){
    require_once $file;
  } else {
    http_response_code(500);
    require_once SRC_PATH.'/controllers/404.php';
  }
} else {
  http_response_code(404);
  require_once SRC_PATH.'/controllers/404.php';
}
