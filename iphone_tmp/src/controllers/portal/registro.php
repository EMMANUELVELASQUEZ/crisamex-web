<?php
if(isset($_SESSION['cliente_id'])){ header('Location: /portal'); exit; }

$error = '';
$planes = Database::fetchAll("SELECT * FROM planes WHERE activo=1 ORDER BY orden");
$plan_pre = $_GET['plan'] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $nombre   = trim($_POST['nombre'] ?? '');
  $apellidos= trim($_POST['apellidos'] ?? '');
  $empresa  = trim($_POST['empresa'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $telefono = trim($_POST['telefono'] ?? '');
  $cargo    = trim($_POST['cargo'] ?? '');
  $plan_id  = (int)($_POST['plan_id'] ?? 0);
  $pass     = $_POST['password'] ?? '';
  $pass2    = $_POST['password2'] ?? '';

  if(empty($nombre)||empty($empresa)||empty($email)||empty($pass)){
    $error = 'Por favor completa todos los campos requeridos.';
  } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $error = 'Ingresa un correo electrónico válido.';
  } elseif(strlen($pass) < 8){
    $error = 'La contraseña debe tener al menos 8 caracteres.';
  } elseif($pass !== $pass2){
    $error = 'Las contraseñas no coinciden.';
  } else {
    $existe = Database::fetchOne("SELECT id FROM clientes WHERE email=?", [$email]);
    if($existe){
      $error = 'Ya existe una cuenta con ese correo electrónico.';
    } else {
      $hash = password_hash($pass, PASSWORD_BCRYPT);
      Database::query(
        "INSERT INTO clientes (nombre,apellidos,empresa,email,telefono,cargo,password_hash,plan_id,licencia_status,email_verificado) VALUES (?,?,?,?,?,?,?,?,?,?)",
        [$nombre,$apellidos,$empresa,$email,$telefono,$cargo,$hash,$plan_id?:null,'trial',1]
      );
      $nuevo_id = Database::getInstance()->lastInsertId();
      Database::query("INSERT INTO portal_notificaciones (cliente_id,titulo,mensaje,tipo) VALUES (?,?,?,?)",
        [$nuevo_id,'¡Bienvenido a CRISAMEX!','Tu cuenta ha sido creada. Un asesor se pondrá en contacto contigo en breve.','success']);
      $cliente = Database::fetchOne("SELECT c.*, p.nombre as plan_nombre FROM clientes c LEFT JOIN planes p ON c.plan_id=p.id WHERE c.id=?", [$nuevo_id]);
      $_SESSION['cliente_id']      = $cliente['id'];
      $_SESSION['cliente_nombre']  = $cliente['nombre'];
      $_SESSION['cliente_empresa'] = $cliente['empresa'];
      $_SESSION['cliente_plan']    = $cliente['plan_nombre'] ?? 'Trial';
      $_SESSION['cliente_status']  = 'trial';
      // Enviar email de bienvenida al cliente
$plan_nombre_email = $cliente['plan_nombre'] ?? 'Trial';
Mailer::bienvenida($email, $nombre, $empresa, $plan_nombre_email);

// Notificar al admin
$admin = Database::fetchOne("SELECT email FROM admin_usuarios WHERE activo=1 LIMIT 1");
if($admin) Mailer::avisoAdminNuevoRegistro($admin['email'], $nombre, $empresa, $email, $plan_nombre_email);

header('Location: /portal?bienvenido=1'); exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Cuenta — CRISAMEX</title>
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
<meta name="theme-color" content="#C8151B">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{height:100%}
body{font-family:'DM Sans',sans-serif;background:#f5f5f5;color:#1a1a1a;min-height:100vh;-webkit-font-smoothing:antialiased}

/* ── LAYOUT SPLIT ── */
.page{display:grid;grid-template-columns:380px 1fr;min-height:100vh}

/* ── PANEL IZQUIERDO ── */
.sidebar{background:#1a1a1a;position:sticky;top:0;height:100vh;overflow-y:auto;display:flex;flex-direction:column;padding:0}

.sb-header{background:#C8151B;padding:28px 28px 24px}
.sb-logo-wrap{background:#fff;display:inline-block;padding:8px 14px;border-radius:6px;margin-bottom:18px}
.sb-logo-wrap img{height:44px;width:auto;display:block}
.sb-tag{font-size:.68rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,.75);margin-bottom:8px}
.sb-title{font-family:'Bebas Neue',sans-serif;font-size:2.2rem;letter-spacing:2px;color:#fff;line-height:1}

/* Planes selector */
.sb-plans{padding:24px 20px;flex:1}
.sb-plans-label{font-size:.68rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:#888;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.sb-plans-label::after{content:'';flex:1;height:1px;background:#333}

.plan-item{background:#252525;border:2px solid #333;border-radius:8px;padding:14px 16px;margin-bottom:10px;cursor:pointer;transition:all .25s;display:flex;align-items:center;gap:12px;position:relative}
.plan-item:hover{border-color:#C8151B;background:#2a1a1b}
.plan-item.selected{border-color:#C8151B;background:#1f0e0f}
.plan-item.selected::after{content:'✓';position:absolute;right:14px;top:50%;transform:translateY(-50%);width:24px;height:24px;background:#C8151B;border-radius:50%;color:#fff;font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;line-height:24px;text-align:center}
.plan-ico{width:38px;height:38px;background:#C8151B;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.95rem;color:#fff;flex-shrink:0}
.plan-item.selected .plan-ico{background:#fff;color:#C8151B}
.plan-name{font-family:'Bebas Neue',sans-serif;font-size:1.05rem;letter-spacing:1px;color:#fff;display:block;line-height:1.1}
.plan-price{font-size:.76rem;color:#888;margin-top:2px}
.plan-item.selected .plan-name{color:#fff}
.plan-item.selected .plan-price{color:rgba(255,255,255,.5)}

/* Plan popular badge */
.plan-popular{position:absolute;top:-8px;right:12px;background:#C8151B;color:#fff;font-size:.62rem;font-weight:700;padding:2px 8px;border-radius:10px;letter-spacing:1px;text-transform:uppercase}

.sb-footer{padding:20px;border-top:1px solid #2a2a2a;font-size:.72rem;color:#555}
.sb-footer a{color:#888;text-decoration:none}
.sb-footer a:hover{color:#C8151B}

/* ── PANEL DERECHO — FORMULARIO ── */
.main{background:#fff;overflow-y:auto}
.main-inner{padding:40px 48px;max-width:680px}

.back-link{display:inline-flex;align-items:center;gap:7px;color:#888;font-size:.8rem;margin-bottom:28px;text-decoration:none;transition:color .2s;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif}
.back-link:hover{color:#C8151B}

.form-title{font-family:'Bebas Neue',sans-serif;font-size:2.5rem;letter-spacing:2px;color:#1a1a1a;margin-bottom:4px}
.form-sub{font-size:.88rem;color:#888;margin-bottom:32px;font-weight:300}

/* Dividers de sección */
.section-sep{display:flex;align-items:center;gap:12px;margin:28px 0 20px}
.section-sep span{font-size:.7rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#C8151B;white-space:nowrap}
.section-sep::before,.section-sep::after{content:'';flex:1;height:1px;background:#e8e8e8}
.section-sep::before{display:none}

/* Error */
.alert{padding:12px 16px;background:#fef2f2;border:1.5px solid #fecaca;color:#991b1b;border-radius:6px;font-size:.85rem;display:flex;align-items:center;gap:10px;margin-bottom:22px;border-left:3px solid #C8151B}

/* Grid campos */
.row2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.fg{margin-bottom:18px}
.fg label{display:block;font-size:.7rem;font-weight:700;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:1px}
.req{color:#C8151B}
.fi{position:relative}
.fi i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#bbb;font-size:.82rem;pointer-events:none}
.fc{width:100%;padding:11px 14px 11px 38px;background:#f8f8f8;border:1.5px solid #e8e8e8;border-radius:6px;font-family:'DM Sans',sans-serif;font-size:.9rem;color:#1a1a1a;outline:none;transition:all .25s;-webkit-appearance:none}
.fc.no-icon{padding-left:14px}
.fc:focus{border-color:#C8151B;background:#fff;box-shadow:0 0 0 3px rgba(200,21,27,.08)}
.fc::placeholder{color:#bbb}
textarea.fc{min-height:90px;resize:vertical;padding-top:11px}

/* Checkbox términos */
.terms-row{display:flex;align-items:flex-start;gap:10px;margin:20px 0 24px}
.terms-check{width:18px;height:18px;accent-color:#C8151B;flex-shrink:0;margin-top:1px;cursor:pointer}
.terms-txt{font-size:.83rem;color:#666;line-height:1.6}
.terms-txt a{color:#C8151B;text-decoration:none;font-weight:500}
.terms-txt a:hover{text-decoration:underline}

/* Botón submit */
.btn-submit{width:100%;padding:15px;background:#C8151B;color:#fff;border:none;border-radius:6px;font-family:'Bebas Neue',sans-serif;font-size:1.15rem;letter-spacing:2.5px;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:12px}
.btn-submit:hover{background:#9a1015;transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,21,27,.3)}
.btn-submit:active{transform:translateY(0)}

.login-hint{text-align:center;margin-top:20px;font-size:.84rem;color:#888}
.login-hint a{color:#C8151B;font-weight:500;text-decoration:none}
.login-hint a:hover{text-decoration:underline}

/* Plan seleccionado resumen */
.plan-summary{background:#fef2f2;border:1.5px solid #fecaca;border-radius:8px;padding:14px 18px;display:flex;align-items:center;gap:12px;margin-bottom:28px;border-left:3px solid #C8151B}
.plan-summary-ico{width:36px;height:36px;background:#C8151B;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:#fff;flex-shrink:0}
.plan-summary strong{display:block;color:#1a1a1a;font-size:.9rem;font-family:'Bebas Neue',sans-serif;letter-spacing:1px}
.plan-summary span{font-size:.78rem;color:#888}

/* Seguridad badge */
.security-note{display:flex;align-items:center;gap:8px;margin-top:16px;padding:10px 14px;background:#f0fdf4;border-radius:6px;border:1px solid #bbf7d0}
.security-note i{color:#16a34a;font-size:.9rem}
.security-note span{font-size:.76rem;color:#15803d;font-weight:500}

@media(max-width:900px){
  body{display:block!important}
  .page{grid-template-columns:1fr!important;display:block!important}
  .sidebar{position:relative;height:auto;padding-bottom:8px}
  .main-inner{padding:28px 24px}
  .row2{grid-template-columns:1fr}
}
</style>
</head>
<body>

<div class="page">

  <!-- ══ SIDEBAR IZQUIERDO ══ -->
  <aside class="sidebar">

    <div class="sb-header">
      <div class="sb-logo-wrap">
        <img src="/images/logo-crisamex.jpg" alt="CRISAMEX">
      </div>
      <p class="sb-tag">Portal exclusivo de clientes</p>
      <div class="sb-title">SELECCIONA<br>TU PLAN</div>
    </div>

    <div class="sb-plans">
      <div class="sb-plans-label">Planes disponibles</div>

      <?php foreach($planes as $i => $p):
        $isSelected = ($plan_pre === $p['slug']);
        $isPopular  = $p['destacado'];
      ?>
      <div class="plan-item <?= $isSelected ? 'selected' : '' ?>"
           id="pi-<?= $p['id'] ?>"
           onclick="selectPlan(<?= $p['id'] ?>, '<?= htmlspecialchars($p['slug']) ?>', '<?= htmlspecialchars($p['nombre']) ?>', '<?= number_format($p['precio_mensual'],0,'.',',') ?>', '<?= htmlspecialchars($p['icono']) ?>')">
        <?php if($isPopular): ?>
        <span class="plan-popular">Popular</span>
        <?php endif; ?>
        <div class="plan-ico"><i class="<?= htmlspecialchars($p['icono']) ?>"></i></div>
        <div>
          <span class="plan-name"><?= htmlspecialchars($p['nombre']) ?></span>
          <div class="plan-price">$<?= number_format($p['precio_mensual'],0,'.',',') ?>/mes</div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Opción sin plan -->
      <div class="plan-item <?= (!$plan_pre) ? 'selected' : '' ?>"
           id="pi-0"
           onclick="selectPlan(0, '', 'Solo explorar', 'Gratis', 'fas fa-eye')">
        <div class="plan-ico"><i class="fas fa-eye"></i></div>
        <div>
          <span class="plan-name">Solo explorar</span>
          <div class="plan-price">Un asesor te contactará</div>
        </div>
      </div>
    </div>

    <div class="sb-footer">
      <a href="/portal/login">← Ya tengo cuenta</a>
      &nbsp;·&nbsp;
      <a href="/planes">Ver planes completos</a>
      <br><br>&copy; <?= date('Y') ?> CRISAMEX
    </div>
  </aside>

  <!-- ══ PANEL DERECHO — FORMULARIO ══ -->
  <main class="main">
    <div class="main-inner">

      <a href="/portal/login" class="back-link">
        <i class="fas fa-arrow-left"></i> Ya tengo cuenta
      </a>

      <div class="form-title">CREAR CUENTA</div>
      <p class="form-sub">Accede al portal exclusivo de clientes CRISAMEX</p>

      <?php if($error): ?>
      <div class="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <!-- Resumen del plan seleccionado -->
      <div class="plan-summary" id="plan-summary">
        <div class="plan-summary-ico" id="ps-ico"><i class="fas fa-eye" id="ps-icon-i"></i></div>
        <div>
          <strong id="ps-name">Solo explorar</strong>
          <span id="ps-price">Un asesor se pondrá en contacto</span>
        </div>
      </div>

      <form method="POST" id="reg-form">
        <input type="hidden" name="plan_id" id="plan_id_input" value="0">

        <!-- DATOS PERSONALES -->
        <div class="section-sep"><span><i class="fas fa-user"></i> Información personal</span></div>
        <div class="row2">
          <div class="fg">
            <label>Nombre <span class="req">*</span></label>
            <div class="fi"><i class="fas fa-user"></i>
              <input type="text" name="nombre" class="fc" placeholder="Tu nombre" required value="<?= htmlspecialchars($_POST['nombre']??'') ?>">
            </div>
          </div>
          <div class="fg">
            <label>Apellidos</label>
            <div class="fi"><i class="fas fa-user"></i>
              <input type="text" name="apellidos" class="fc" placeholder="Tus apellidos" value="<?= htmlspecialchars($_POST['apellidos']??'') ?>">
            </div>
          </div>
        </div>
        <div class="row2">
          <div class="fg">
            <label>Cargo / Puesto</label>
            <div class="fi"><i class="fas fa-briefcase"></i>
              <input type="text" name="cargo" class="fc" placeholder="Director, Supervisor..." value="<?= htmlspecialchars($_POST['cargo']??'') ?>">
            </div>
          </div>
          <div class="fg">
            <label>Teléfono</label>
            <div class="fi"><i class="fas fa-phone-alt"></i>
              <input type="tel" name="telefono" class="fc" placeholder="55 1234 5678" value="<?= htmlspecialchars($_POST['telefono']??'') ?>">
            </div>
          </div>
        </div>

        <!-- EMPRESA -->
        <div class="section-sep"><span><i class="fas fa-building"></i> Empresa</span></div>
        <div class="fg">
          <label>Razón Social / Empresa <span class="req">*</span></label>
          <div class="fi"><i class="fas fa-building"></i>
            <input type="text" name="empresa" class="fc" placeholder="Mi Empresa S.A. de C.V." required value="<?= htmlspecialchars($_POST['empresa']??'') ?>">
          </div>
        </div>

        <!-- ACCESO -->
        <div class="section-sep"><span><i class="fas fa-lock"></i> Datos de acceso</span></div>
        <div class="fg">
          <label>Correo electrónico <span class="req">*</span></label>
          <div class="fi"><i class="fas fa-envelope"></i>
            <input type="email" name="email" class="fc" placeholder="correo@empresa.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
          </div>
        </div>
        <div class="row2">
          <div class="fg">
            <label>Contraseña <span class="req">*</span></label>
            <div class="fi"><i class="fas fa-lock"></i>
              <input type="password" name="password" class="fc" placeholder="Mínimo 8 caracteres" required>
            </div>
          </div>
          <div class="fg">
            <label>Confirmar contraseña</label>
            <div class="fi"><i class="fas fa-lock"></i>
              <input type="password" name="password2" class="fc" placeholder="Repetir contraseña" required>
            </div>
          </div>
        </div>

        <!-- TÉRMINOS -->
        <div class="terms-row">
          <input type="checkbox" id="terms" name="terms" class="terms-check" required>
          <label for="terms" class="terms-txt">
            Acepto los <a href="/contacto">términos y condiciones</a> y el
            <a href="/contacto">aviso de privacidad</a> de CRISAMEX.
          </label>
        </div>

        <button type="submit" class="btn-submit">
          <i class="fas fa-rocket"></i> CREAR MI CUENTA
        </button>

        <div class="security-note">
          <i class="fas fa-shield-alt"></i>
          <span>Tu información está protegida con encriptación SSL de 256 bits</span>
        </div>

      </form>

      <p class="login-hint">
        ¿Ya tienes cuenta? <a href="/portal/login">Inicia sesión aquí</a>
      </p>

    </div>
  </main>

</div>

<script>
<?php
$planMap = [];
foreach($planes as $p) $planMap[$p['slug']] = ['id'=>$p['id'],'nombre'=>$p['nombre'],'precio'=>number_format($p['precio_mensual'],0,'.',','),'icono'=>$p['icono']];
?>
var planData = <?= json_encode($planMap) ?>;

function selectPlan(id, slug, nombre, precio, icono){
  // Quitar selected de todos
  document.querySelectorAll('.plan-item').forEach(function(el){ el.classList.remove('selected'); });
  // Seleccionar el clickeado
  var el = document.getElementById('pi-'+id);
  if(el) el.classList.add('selected');
  // Actualizar input hidden
  document.getElementById('plan_id_input').value = id;
  // Actualizar resumen
  document.getElementById('ps-name').textContent = nombre;
  document.getElementById('ps-price').textContent = id > 0 ? '$'+precio+'/mes · Activa tu licencia tras el registro' : 'Un asesor se pondrá en contacto contigo';
  document.getElementById('ps-icon-i').className = icono;
}

// Preseleccionar según URL
var pre = '<?= htmlspecialchars($plan_pre) ?>';
if(pre && planData[pre]){
  var d = planData[pre];
  selectPlan(d.id, pre, d.nombre, d.precio, d.icono);
} else {
  selectPlan(0,'','Solo explorar','Gratis','fas fa-eye');
}
</script>
</body>
</html>
