<?php
if(isset($_SESSION['cliente_id'])){ header('Location: /portal'); exit; }
$error = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  if(empty($email)||empty($pass)){
    $error = 'Ingresa tu correo y contraseña.';
  } else {
    $c = Database::fetchOne("SELECT c.*,p.nombre as plan_nombre FROM clientes c LEFT JOIN planes p ON c.plan_id=p.id WHERE c.email=? AND c.activo=1",[$email]);
    if($c && password_verify($pass,$c['password_hash'])){
      $_SESSION['cliente_id']     = $c['id'];
      $_SESSION['cliente_nombre'] = $c['nombre'];
      $_SESSION['cliente_empresa']= $c['empresa'];
      $_SESSION['cliente_plan']   = $c['plan_nombre'] ?? 'Trial';
      $_SESSION['cliente_status'] = $c['licencia_status'];
      Database::query("UPDATE clientes SET ultimo_acceso=NOW() WHERE id=?",[$c['id']]);
      header('Location: /portal'); exit;
    } else { $error = 'Credenciales incorrectas. Verifica tu correo y contraseña.'; }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Portal Clientes — CRISAMEX</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
<meta name="theme-color" content="#C8151B">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#fff;color:#1a1a1a;min-height:100vh;display:grid;grid-template-columns:1fr 1fr;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}

/* Panel izquierdo — rojo */
.left{background:#C8151B;position:relative;overflow:hidden;display:flex;flex-direction:column;padding:48px}
.left::before{content:'';position:absolute;top:-100px;right:-100px;width:350px;height:350px;background:rgba(0,0,0,.08);border-radius:50%}
.left::after{content:'';position:absolute;bottom:-80px;left:-80px;width:280px;height:280px;background:rgba(0,0,0,.06);border-radius:50%}
.left-logo{position:relative;z-index:1}
.left-logo-wrap{background:#fff;display:inline-block;padding:8px 14px;border-radius:4px;margin-bottom:0}
.left-logo-wrap img{height:48px;width:auto;display:block}
.left-body{flex:1;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1}
.left-tag{font-size:.7rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,.7);margin-bottom:16px}
.left-title{font-family:'Bebas Neue',sans-serif;font-size:clamp(3.5rem,6vw,5.5rem);color:#fff;line-height:.9;letter-spacing:3px;margin-bottom:32px}
.left-title .stroke{-webkit-text-stroke:1.5px rgba(255,255,255,.4);color:transparent}
.left-features{list-style:none;display:flex;flex-direction:column;gap:14px}
.left-features li{display:flex;align-items:center;gap:12px;font-size:.9rem;color:rgba(255,255,255,.85);font-weight:300}
.left-features li .ico{width:34px;height:34px;background:rgba(255,255,255,.15);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#fff;flex-shrink:0}
.left-footer{position:relative;z-index:1;font-size:.72rem;color:rgba(255,255,255,.4);margin-top:32px}

/* Panel derecho — blanco */
.right{background:#fff;display:flex;align-items:center;justify-content:center;padding:48px;overflow-y:auto}
.form-wrap{width:100%;max-width:400px}
.back-link{display:inline-flex;align-items:center;gap:7px;color:#888;font-size:.8rem;margin-bottom:32px;transition:color .2s}
.back-link:hover{color:#C8151B}
.form-title{font-family:'Bebas Neue',sans-serif;font-size:2.2rem;letter-spacing:2px;color:#1a1a1a;margin-bottom:4px}
.form-sub{font-size:.86rem;color:#888;margin-bottom:28px;font-weight:300}
.alert{padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:6px;font-size:.85rem;display:flex;align-items:center;gap:8px;margin-bottom:18px}
.fg{margin-bottom:18px}
.fg label{display:block;font-size:.72rem;font-weight:700;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:1px}
.fi{position:relative}
.fi i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#bbb;font-size:.85rem}
.fc{width:100%;padding:12px 14px 12px 40px;background:#f8f8f8;border:1.5px solid #e8e8e8;border-radius:6px;font-family:'DM Sans',sans-serif;font-size:.92rem;color:#1a1a1a;outline:none;transition:all .25s}
.fc:focus{border-color:#C8151B;background:#fff;box-shadow:0 0 0 3px rgba(200,21,27,.08)}
.fc::placeholder{color:#bbb}
.forgot{display:flex;justify-content:flex-end;margin:-10px 0 20px}
.forgot a{font-size:.78rem;color:#C8151B;font-weight:500}
.forgot a:hover{text-decoration:underline}
.btn-submit{width:100%;padding:14px;background:#C8151B;color:#fff;border:none;border-radius:6px;font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:2px;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:10px}
.btn-submit:hover{background:#9a1015;transform:translateY(-2px);box-shadow:0 6px 20px rgba(200,21,27,.3)}
.divider{display:flex;align-items:center;gap:12px;margin:22px 0;font-size:.72rem;color:#bbb;text-transform:uppercase;letter-spacing:1px}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:#e8e8e8}
.register-link{text-align:center;font-size:.86rem;color:#888}
.register-link a{color:#C8151B;font-weight:500}
.register-link a:hover{text-decoration:underline}
.demo-box{margin-top:24px;padding:14px 16px;background:#f8f8f8;border-radius:6px;border-left:3px solid #C8151B}
.demo-box p{font-size:.74rem;color:#888;line-height:1.7}
.demo-box strong{color:#1a1a1a}

@media(max-width:768px){body{grid-template-columns:1fr}.left{display:none}.right{padding:32px 24px;align-items:flex-start;padding-top:48px}}
</style>
</head>
<body>

<!-- PANEL IZQUIERDO ROJO -->
<div class="left">
  <div class="left-logo"><div class="left-logo-wrap"><img src="/images/logo-crisamex.jpg" alt="CRISAMEX"></div></div>
  <div class="left-body">
    <p class="left-tag">Portal exclusivo de clientes</p>
    <div class="left-title">
      PORTAL<br>
      <span class="stroke">CLI</span>ENTES
    </div>
    <ul class="left-features">
      <li><div class="ico"><i class="fas fa-file-alt"></i></div>Accede a tus reportes y documentos</li>
      <li><div class="ico"><i class="fas fa-comments"></i></div>Comunicación directa con CRISAMEX</li>
      <li><div class="ico"><i class="fas fa-bell"></i></div>Notificaciones de cumplimiento en tiempo real</li>
      <li><div class="ico"><i class="fas fa-shield-alt"></i></div>Gestión de tu licencia y plan activo</li>
    </ul>
  </div>
  <div class="left-footer">&copy; <?= date('Y') ?> CRISAMEX — Control de Radiaciones e Ingeniería S.A. de C.V.</div>
</div>

<!-- PANEL DERECHO BLANCO -->
<div class="right">
  <div class="form-wrap">
    <a href="/" class="back-link"><i class="fas fa-arrow-left"></i> Volver al sitio</a>

    <div class="form-title">INICIAR SESIÓN</div>
    <p class="form-sub">Accede a tu portal de cliente CRISAMEX</p>

    <?php if($error): ?>
    <div class="alert"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="fg">
        <label>Correo electrónico</label>
        <div class="fi">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" class="fc" placeholder="correo@empresa.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
        </div>
      </div>
      <div class="fg">
        <label>Contraseña</label>
        <div class="fi">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" class="fc" placeholder="••••••••••" required>
        </div>
      </div>
      <div class="forgot"><a href="/contacto">¿Olvidaste tu contraseña?</a></div>
      <button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i>ENTRAR AL PORTAL</button>
    </form>

    <div class="divider">o</div>
    <p class="register-link">¿No tienes cuenta? <a href="/portal/registro">Regístrate gratis</a></p>
    <p style="text-align:center;margin-top:12px"><a href="/planes" style="font-size:.8rem;color:#888">Ver planes y precios →</a></p>

    <div class="demo-box">
      <p><strong>Demo:</strong> demo@empresa.com / password</p>
    </div>
  </div>
</div>
</body>
</html>
