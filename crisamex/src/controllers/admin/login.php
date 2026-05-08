<?php
if(isset($_SESSION['admin_id'])){ header('Location: /admin'); exit; }

$error = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  if(empty($email)||empty($pass)){
    $error = 'Completa todos los campos.';
  } else {
    $user = Database::fetchOne("SELECT * FROM admin_usuarios WHERE email=? AND activo=1", [$email]);
    if($user && password_verify($pass, $user['password_hash'])){
      $_SESSION['admin_id']     = $user['id'];
      $_SESSION['admin_nombre'] = $user['nombre'];
      $_SESSION['admin_rol']    = $user['rol'];
      Database::query("UPDATE admin_usuarios SET ultimo_acceso=NOW() WHERE id=?", [$user['id']]);
      header('Location: /admin'); exit;
    } else {
      $error = 'Email o contraseña incorrectos.';
    }
  }
}

// Mensajes pendientes
$pendientes = 0;
try {
  $r1 = Database::fetchOne("SELECT COUNT(*) as c FROM contacto_mensajes WHERE leido=0");
  $r2 = Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE leido_admin=0 AND de='cliente'");
  $pendientes = ($r1['c']??0) + ($r2['c']??0);
} catch(Exception $e){}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Panel Admin — CRISAMEX</title>
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
<meta name="theme-color" content="#C8151B">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{height:100%;-webkit-font-smoothing:antialiased}
body{font-family:'DM Sans',sans-serif;min-height:100vh;background:#f0f2f5;display:flex;align-items:center;justify-content:center;padding:20px}

.card{width:100%;max-width:420px;background:#fff;box-shadow:0 8px 48px rgba(0,0,0,.12);overflow:hidden}

/* CABECERA OSCURA */
.card-head{background:#1a1a1a;padding:32px;text-align:center;border-bottom:3px solid #C8151B;position:relative;overflow:hidden}
.card-head::before{content:'';position:absolute;top:-60px;right:-60px;width:180px;height:180px;background:rgba(200,21,27,.12);border-radius:50%}
.card-head::after{content:'';position:absolute;bottom:-40px;left:-40px;width:140px;height:140px;background:rgba(255,255,255,.04);border-radius:50%}
.logo-box{background:#fff;display:inline-block;padding:10px 18px;border-radius:6px;margin-bottom:16px;position:relative;z-index:1}
.logo-box img{height:46px;width:auto;display:block}
.card-head h1{font-family:'Bebas Neue',sans-serif;font-size:1.4rem;letter-spacing:3px;color:#fff;margin-bottom:4px;position:relative;z-index:1}
.card-head p{font-size:.72rem;color:rgba(255,255,255,.4);letter-spacing:1.5px;text-transform:uppercase;position:relative;z-index:1}

/* ALERTA PENDIENTES */
.pending-bar{background:#fef2f2;border-bottom:1px solid #fecaca;padding:11px 24px;display:flex;align-items:center;gap:10px;font-size:.82rem;color:#991b1b}
.pending-bar .badge{background:#C8151B;color:#fff;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:10px;margin-left:auto;font-family:'Bebas Neue',sans-serif;letter-spacing:1px}

/* CUERPO FORMULARIO */
.card-body{padding:32px}

.alert{padding:12px 16px;background:#fef2f2;border:1.5px solid #fecaca;color:#991b1b;border-radius:5px;font-size:.84rem;display:flex;align-items:center;gap:9px;margin-bottom:20px;border-left:3px solid #C8151B}

.fg{margin-bottom:18px}
.fg label{display:block;font-size:.7rem;font-weight:700;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;display:flex;align-items:center;gap:5px}
.fg label i{color:#C8151B;font-size:.75rem}
.fi{position:relative}
.fi .ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#ccc;font-size:.82rem;pointer-events:none}
.fi input{width:100%;padding:12px 42px 12px 38px;background:#f8f8f8;border:1.5px solid #e8e8e8;border-radius:5px;font-family:'DM Sans',sans-serif;font-size:.92rem;color:#1a1a1a;outline:none;transition:all .25s}
.fi input:focus{border-color:#C8151B;background:#fff;box-shadow:0 0 0 3px rgba(200,21,27,.08)}
.fi input::placeholder{color:#c0c0c0}
.eye-btn{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#bbb;font-size:.85rem;padding:4px;transition:color .2s}
.eye-btn:hover{color:#C8151B}

.btn-login{width:100%;padding:14px;background:#C8151B;color:#fff;border:none;border-radius:5px;font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:2.5px;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:10px;margin-top:8px}
.btn-login:hover{background:#9a1015;transform:translateY(-2px);box-shadow:0 6px 20px rgba(200,21,27,.3)}
.btn-login:active{transform:translateY(0)}

/* INFO BOXES */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:24px}
.info-box{background:#f8f8f8;border:1px solid #ebebeb;padding:12px;border-radius:5px;text-align:center;transition:all .2s;cursor:default}
.info-box:hover{border-color:#fecaca;background:#fef2f2}
.info-box i{font-size:1.15rem;color:#C8151B;display:block;margin-bottom:5px}
.info-box strong{display:block;font-family:'Bebas Neue',sans-serif;font-size:.95rem;letter-spacing:1px;color:#1a1a1a}
.info-box span{font-size:.68rem;color:#999;text-transform:uppercase;letter-spacing:.5px}

/* ACCESO RÁPIDO MÓVIL */
.mobile-call{display:none;margin-top:20px;padding:14px 18px;background:#1a1a1a;border-radius:5px;align-items:center;justify-content:center;gap:10px;font-family:'Bebas Neue',sans-serif;font-size:1rem;letter-spacing:2px;color:#fff;text-decoration:none;transition:all .25s}
.mobile-call:hover{background:#C8151B}
.mobile-call i{font-size:1rem}

/* FOOTER CARD */
.card-foot{border-top:1px solid #f0f0f0;padding:14px 24px;display:flex;justify-content:space-between;align-items:center;background:#fafafa}
.card-foot a{font-size:.78rem;color:#888;text-decoration:none;display:flex;align-items:center;gap:5px;transition:color .2s}
.card-foot a:hover{color:#C8151B}

/* RESPONSIVE MÓVIL */
@media(max-width:480px){
  body{padding:0;align-items:stretch;background:#fff}
  .card{max-width:100%;box-shadow:none;min-height:100vh;display:flex;flex-direction:column}
  .card-body{flex:1;padding:24px}
  .card-head{padding:24px}
  .mobile-call{display:flex}
  .info-grid{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<div class="card">

  <!-- CABECERA -->
  <div class="card-head">
    <div class="logo-box">
      <img src="/images/logo-crisamex.jpg" alt="CRISAMEX">
    </div>
    <h1>PANEL ADMINISTRATIVO</h1>
    <p>Acceso restringido · Solo personal autorizado</p>
  </div>

  <!-- ALERTA MENSAJES -->
  <?php if($pendientes > 0): ?>
  <div class="pending-bar">
    <i class="fas fa-bell"></i>
    <span>Tienes mensajes sin leer de clientes</span>
    <span class="badge"><?= $pendientes ?> NUEVOS</span>
  </div>
  <?php endif; ?>

  <!-- FORMULARIO -->
  <div class="card-body">

    <?php if($error): ?>
    <div class="alert">
      <i class="fas fa-exclamation-circle"></i>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

      <div class="fg">
        <label><i class="fas fa-envelope"></i> Correo electrónico</label>
        <div class="fi">
          <i class="fas fa-envelope ico"></i>
          <input type="email" name="email" placeholder="admin@crisamex.com"
                 required value="<?= htmlspecialchars($_POST['email']??'') ?>"
                 autocomplete="username">
        </div>
      </div>

      <div class="fg">
        <label><i class="fas fa-lock"></i> Contraseña</label>
        <div class="fi">
          <i class="fas fa-lock ico"></i>
          <input type="password" name="password" id="pwd"
                 placeholder="••••••••••••" required autocomplete="current-password">
          <button type="button" class="eye-btn" onclick="togglePwd()" id="eye-btn" title="Mostrar/ocultar">
            <i class="fas fa-eye" id="eye-ico"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-login">
        <i class="fas fa-shield-alt"></i> ENTRAR AL PANEL
      </button>

    </form>

    <!-- Acceso rápido en móvil -->
    <a href="tel:+525556508420" class="mobile-call">
      <i class="fas fa-phone-alt"></i> LLAMAR A SOPORTE
    </a>

    <!-- Info del panel -->
    <div class="info-grid">
      <div class="info-box"><i class="fas fa-comments"></i><strong>Mensajes</strong><span>Clientes</span></div>
      <div class="info-box"><i class="fas fa-users-cog"></i><strong>Clientes</strong><span>Cuentas</span></div>
      <div class="info-box"><i class="fas fa-cogs"></i><strong>Servicios</strong><span>Contenido</span></div>
      <div class="info-box"><i class="fas fa-star"></i><strong>Planes</strong><span>Licencias</span></div>
    </div>

  </div>

  <!-- PIE -->
  <div class="card-foot">
    <a href="/"><i class="fas fa-arrow-left"></i> Volver al sitio</a>
    <a href="/portal/login"><i class="fas fa-user-circle"></i> Portal Clientes</a>
  </div>

</div>

<script>
function togglePwd(){
  var inp = document.getElementById('pwd');
  var ico = document.getElementById('eye-ico');
  if(inp.type==='password'){
    inp.type='text';
    ico.className='fas fa-eye-slash';
  } else {
    inp.type='password';
    ico.className='fas fa-eye';
  }
}
// Enter key en email → saltar a password
document.querySelector('[name=email]').addEventListener('keydown', function(e){
  if(e.key==='Enter'){ e.preventDefault(); document.getElementById('pwd').focus(); }
});
</script>
</body>
</html>
