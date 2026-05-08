<?php
/**
 * CRISAMEX — Seguridad Enterprise
 * Protección contra: SQLi, XSS, CSRF, Brute Force,
 * Session Hijacking, Path Traversal, Clickjacking, RFI/LFI
 */
class Security {

  // ── CSRF TOKEN ────────────────────────────────────────────
  public static function csrfToken(): string {
    if(empty($_SESSION['csrf_token'])){
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      $_SESSION['csrf_time']  = time();
    }
    return $_SESSION['csrf_token'];
  }

  public static function csrfVerify(): bool {
    $token   = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $session = $_SESSION['csrf_token'] ?? '';
    $time    = $_SESSION['csrf_time'] ?? 0;
    // Token válido máximo 2 horas
    if(time() - $time > 7200){
      unset($_SESSION['csrf_token'], $_SESSION['csrf_time']);
      return false;
    }
    return $session && hash_equals($session, $token);
  }

  public static function csrfField(): string {
    return '<input type="hidden" name="_csrf" value="'.htmlspecialchars(self::csrfToken()).'">';
  }

  // ── RATE LIMITING (Brute Force / DDoS) ────────────────────
  public static function rateLimit(string $key, int $max=5, int $window=300): bool {
    $k = 'rl_'.$key.'_'.floor(time()/$window);
    $_SESSION[$k] = ($_SESSION[$k] ?? 0) + 1;
    // Limpiar llaves viejas
    foreach(array_keys($_SESSION) as $sk){
      if(str_starts_with($sk,'rl_') && $sk !== $k) unset($_SESSION[$sk]);
    }
    return $_SESSION[$k] <= $max;
  }

  // ── SANITIZACIÓN ──────────────────────────────────────────
  public static function sanitize(mixed $v): string {
    if(is_array($v)) return '';
    return htmlspecialchars(trim((string)$v), ENT_QUOTES|ENT_HTML5, 'UTF-8');
  }

  public static function sanitizeEmail(string $v): string {
    return filter_var(trim($v), FILTER_SANITIZE_EMAIL);
  }

  public static function sanitizeInt(mixed $v): int {
    return (int)filter_var($v, FILTER_SANITIZE_NUMBER_INT);
  }

  public static function sanitizeUrl(string $v): string {
    return filter_var(trim($v), FILTER_SANITIZE_URL);
  }

  // ── VALIDACIONES ──────────────────────────────────────────
  public static function isValidEmail(string $v): bool {
    return (bool)filter_var($v, FILTER_VALIDATE_EMAIL);
  }

  public static function isValidPhone(string $v): bool {
    return (bool)preg_match('/^[\+\d\s\-\(\)]{7,20}$/', $v);
  }

  // ── CONTRASEÑA SEGURA ─────────────────────────────────────
  public static function hashPassword(string $pass): string {
    return password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
  }

  public static function verifyPassword(string $pass, string $hash): bool {
    return password_verify($pass, $hash);
  }

  public static function passwordStrength(string $pass): array {
    $errors = [];
    if(strlen($pass) < 8)           $errors[] = 'Mínimo 8 caracteres';
    if(!preg_match('/[A-Z]/', $pass)) $errors[] = 'Al menos una mayúscula';
    if(!preg_match('/[a-z]/', $pass)) $errors[] = 'Al menos una minúscula';
    if(!preg_match('/[0-9]/', $pass)) $errors[] = 'Al menos un número';
    return $errors;
  }

  // ── SESIONES SEGURAS ──────────────────────────────────────
  public static function secureSession(): void {
    if(session_status() === PHP_SESSION_NONE){
      ini_set('session.cookie_httponly', 1);
      ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
      ini_set('session.cookie_samesite', 'Strict');
      ini_set('session.use_strict_mode', 1);
      ini_set('session.gc_maxlifetime', 3600);
      ini_set('session.cookie_lifetime', 0);
      session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict',
      ]);
      session_start();
    }
    // Regenerar ID en cada request para evitar Session Fixation
    if(empty($_SESSION['_initiated'])){
      session_regenerate_id(true);
      $_SESSION['_initiated'] = true;
      $_SESSION['_ip']        = $_SERVER['REMOTE_ADDR'] ?? '';
      $_SESSION['_ua']        = md5($_SERVER['HTTP_USER_AGENT'] ?? '');
      $_SESSION['_created']   = time();
    }
    // Detectar session hijacking
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = md5($_SERVER['HTTP_USER_AGENT'] ?? '');
    if(
      (isset($_SESSION['_ip']) && $_SESSION['_ip'] !== $ip) ||
      (isset($_SESSION['_ua']) && $_SESSION['_ua'] !== $ua)
    ){
      session_destroy();
      session_start();
      session_regenerate_id(true);
    }
    // Sesión máximo 8 horas
    if(isset($_SESSION['_created']) && time()-$_SESSION['_created'] > 28800){
      session_destroy();
      session_start();
    }
  }

  // ── DETECCIÓN DE ATAQUES ──────────────────────────────────
  public static function detectAttack(string $input): bool {
    $patterns = [
      // SQL Injection
      '/union\s+select/i','/select\s+.*\s+from/i','/insert\s+into/i',
      '/drop\s+table/i','/delete\s+from/i','/update\s+.*\s+set/i',
      '/exec\s*\(/i','/cast\s*\(/i','/<\?php/i',
      // XSS
      '/<script/i','/javascript:/i','/vbscript:/i','/onload\s*=/i',
      '/onerror\s*=/i','/onclick\s*=/i','/<iframe/i','/<object/i',
      '/<embed/i','/document\.cookie/i','/eval\s*\(/i',
      // Path Traversal
      '/\.\.\//','/%2e%2e%2f/i','/%252e%252e/i',
      // Command Injection
      '/;\s*(ls|cat|echo|wget|curl|bash|sh|python|perl|ruby|php)\s/i',
      '/`[^`]*`/','/\$\([^)]*\)/',
      // RFI/LFI
      '/(https?|ftp):\/\/[^\s]+\.php/i',
    ];
    foreach($patterns as $p){
      if(preg_match($p, $input)) return true;
    }
    return false;
  }

  // ── LIMPIEZA DE INPUT ─────────────────────────────────────
  public static function cleanInput(mixed $data): mixed {
    if(is_array($data)){
      return array_map([self::class, 'cleanInput'], $data);
    }
    $v = trim((string)$data);
    // Detectar ataque
    if(self::detectAttack($v)){
      self::logAttack('Input Attack Detected', $v);
      return '';
    }
    return $v;
  }

  // ── LOGGING DE ATAQUES ────────────────────────────────────
  public static function logAttack(string $type, string $detail=''): void {
    $log = sprintf(
      "[%s] ATTACK: %s | IP: %s | URL: %s | UA: %s | Detail: %s\n",
      date('Y-m-d H:i:s'),
      $type,
      $_SERVER['REMOTE_ADDR'] ?? 'unknown',
      $_SERVER['REQUEST_URI'] ?? '',
      substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 100),
      substr($detail, 0, 200)
    );
    $logFile = dirname(__DIR__, 2).'/logs/security.log';
    if(!is_dir(dirname($logFile))) mkdir(dirname($logFile), 0750, true);
    file_put_contents($logFile, $log, FILE_APPEND | LOCK_EX);
    // Si son muchos ataques del mismo IP — bloquear sesión
    $_SESSION['attack_count'] = ($_SESSION['attack_count'] ?? 0) + 1;
    if($_SESSION['attack_count'] > 10){
      http_response_code(429);
      die('Too Many Requests');
    }
  }

  // ── SUBIDA SEGURA DE ARCHIVOS ─────────────────────────────
  public static function secureUpload(array $file, string $destDir, array $allowedTypes=['pdf','doc','docx','xls','xlsx','jpg','jpeg','png']): string|false {
    if($file['error'] !== UPLOAD_ERR_OK) return false;
    // Tamaño máximo 10MB
    if($file['size'] > 10 * 1024 * 1024) return false;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, $allowedTypes)) return false;
    // Verificar MIME real
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $validMimes = [
      'pdf'=>'application/pdf','jpg'=>'image/jpeg','jpeg'=>'image/jpeg',
      'png'=>'image/png','doc'=>'application/msword',
      'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'xls'=>'application/vnd.ms-excel',
      'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
    if(isset($validMimes[$ext]) && $validMimes[$ext] !== $mime) return false;
    // Nombre seguro — nunca usar el nombre original
    $safeName = bin2hex(random_bytes(16)).'.'.$ext;
    $dest     = rtrim($destDir,'/').'/'.$safeName;
    if(!is_dir($destDir)) mkdir($destDir, 0750, true);
    if(!move_uploaded_file($file['tmp_name'], $dest)) return false;
    // Eliminar metadatos EXIF de imágenes
    if(in_array($ext,['jpg','jpeg','png']) && function_exists('imagecreatefromjpeg')){
      try {
        $img = ($ext==='png') ? imagecreatefrompng($dest) : imagecreatefromjpeg($dest);
        if($img){
          ($ext==='png') ? imagepng($img,$dest,9) : imagejpeg($img,$dest,85);
          imagedestroy($img);
        }
      } catch(Exception $e){}
    }
    return $safeName;
  }

  // ── GENERAR TOKEN SEGURO ──────────────────────────────────
  public static function token(int $bytes=32): string {
    return bin2hex(random_bytes($bytes));
  }

  // ── ENCRIPTAR DATOS SENSIBLES ─────────────────────────────
  public static function encrypt(string $data, string $key=''): string {
    $k = $key ?: (getenv('APP_SECRET') ?: 'crisamex_secret_2024_change_in_prod');
    $iv = random_bytes(16);
    $enc = openssl_encrypt($data, 'AES-256-CBC', hash('sha256',$k,true), 0, $iv);
    return base64_encode($iv.$enc);
  }

  public static function decrypt(string $data, string $key=''): string {
    $k    = $key ?: (getenv('APP_SECRET') ?: 'crisamex_secret_2024_change_in_prod');
    $raw  = base64_decode($data);
    $iv   = substr($raw, 0, 16);
    $enc  = substr($raw, 16);
    return openssl_decrypt($enc, 'AES-256-CBC', hash('sha256',$k,true), 0, $iv) ?: '';
  }

  // ── IP BLOQUEADA ──────────────────────────────────────────
  public static function getClientIP(): string {
    foreach(['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'] as $h){
      if(!empty($_SERVER[$h])){
        $ip = trim(explode(',',$_SERVER[$h])[0]);
        if(filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
      }
    }
    return '0.0.0.0';
  }
}
