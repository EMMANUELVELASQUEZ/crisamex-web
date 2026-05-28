<?php
/**
 * CRISAMEX — Portal Login
 * Archivo: src/controllers/portal/login.php
 */
$pageTitle = 'Iniciar Sesión — Portal Clientes CRISAMEX';
$pageDesc  = 'Accede a tu portal exclusivo de clientes CRISAMEX.';
$bodyClass = 'auth-page-body';

require_once __DIR__ . '/../../views/partials/head.php';

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<div class="auth-page">

  <!-- ── PANEL VISUAL (solo desktop) ── -->
  <div class="auth-visual" aria-hidden="true">
    <img src="/images/logo-crisamex.jpg" alt="CRISAMEX" class="auth-visual-logo">
    <h2>Portal de Clientes</h2>
    <p>Gestiona tus servicios radiológicos, documentos y comunicación con CRISAMEX en un solo lugar.</p>
    <ul class="auth-features">
      <li><i class="fas fa-file-alt"></i> Accede a reportes y certificados</li>
      <li><i class="fas fa-comments"></i> Chat directo con CRISAMEX</li>
      <li><i class="fas fa-bell"></i> Notificaciones de cumplimiento</li>
      <li><i class="fas fa-id-card"></i> Gestión de licencia activa</li>
    </ul>
  </div>

  <!-- ── FORMULARIO ── -->
  <div class="auth-form-panel">

    <!-- Logo solo en mobile -->
    <div class="auth-logo-mobile">
      <img src="/images/logo-crisamex.jpg" alt="CRISAMEX" style="height:44px">
    </div>

    <div class="auth-heading">
      <h1>Bienvenido de vuelta</h1>
      <p>Ingresa tus credenciales para acceder al portal</p>
    </div>

    <?php if ($error): ?>
    <div class="alert-error" role="alert" style="background:#FDECEA;border-left:4px solid #C8151B;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:14px;color:#C8151B;display:flex;align-items:center;gap:10px;">
      <i class="fas fa-exclamation-circle"></i>
      <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/portal/login" id="loginForm" novalidate>
      <?= \Helpers\Security::csrfField() ?>

      <div class="field">
        <label for="email">Correo electrónico</label>
        <div class="field-input">
          <i class="fas fa-envelope"></i>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="tu@empresa.com"
            autocomplete="email"
            inputmode="email"
            required
            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
      </div>

      <div class="field">
        <label for="password">
          Contraseña
          <a href="/contacto" style="float:right;font-size:12px;color:#0057B7;font-weight:400">
            ¿La olvidaste?
          </a>
        </label>
        <div class="field-input">
          <i class="fas fa-lock"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required>
          <button type="button" class="toggle-pwd fas fa-eye" aria-label="Ver contraseña"></button>
        </div>
      </div>

      <label style="display:flex;align-items:center;gap:10px;font-size:14px;margin-bottom:8px;cursor:pointer">
        <input type="checkbox" name="remember" style="width:18px;height:18px;accent-color:#C8151B">
        Mantener sesión iniciada
      </label>

      <button type="submit" class="btn-auth" id="submitBtn">
        <i class="fas fa-sign-in-alt"></i>
        Entrar al Portal
      </button>
    </form>

    <div class="auth-divider"><hr><span>o</span><hr></div>

    <div class="auth-footer">
      ¿No tienes cuenta?
      <a href="/portal/registro">Regístrate gratis</a>
    </div>

    <div style="margin-top:16px;padding:12px 14px;background:#EFF6FF;border-radius:8px;font-size:13px;color:#1D4ED8;display:flex;align-items:center;gap:8px">
      <i class="fas fa-info-circle"></i>
      <span><strong>Demo:</strong> demo@empresa.com / Crisamex2024!</span>
    </div>

    <div style="margin-top:24px;text-align:center">
      <a href="/" style="font-size:13px;color:#64748B;display:inline-flex;align-items:center;gap:6px">
        <i class="fas fa-arrow-left"></i> Volver al sitio
      </a>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/../../views/partials/foot.php'; ?>
