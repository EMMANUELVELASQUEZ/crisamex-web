<?php
/**
 * CRISAMEX — Portal Dashboard
 * Archivo: src/controllers/portal/dashboard.php
 */
$pageTitle = 'Mi Dashboard — Portal CRISAMEX';
$bodyClass = 'portal-body';
$extraHead = '<link rel="stylesheet" href="/css/style.css">';

require_once __DIR__ . '/../../views/partials/head.php';

// Datos del cliente (desde sesión/BD)
$cliente   = $_SESSION['portal_user'] ?? ['nombre'=>'Cliente Demo','empresa'=>'Empresa Demo','plan'=>'Profesional'];
$inicial   = mb_substr($cliente['nombre'], 0, 1, 'UTF-8');
?>

<div class="app-layout">

  <!-- ═══ SIDEBAR ═══════════════════════════════════════════ -->
  <aside class="app-sidebar" id="sidebar" role="navigation" aria-label="Menú del portal">

    <div class="sidebar-header">
      <div class="sidebar-logo">
        <img src="/images/logo-crisamex.jpg" alt="CRISAMEX">
        <span class="sidebar-logo-text">PORTAL</span>
      </div>
    </div>

    <div class="sidebar-user">
      <div class="sidebar-user-info">
        <div class="sidebar-avatar"><?= htmlspecialchars($inicial, ENT_QUOTES, 'UTF-8') ?></div>
        <div>
          <div class="sidebar-user-name"><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="sidebar-user-role">Plan <?= htmlspecialchars($cliente['plan'] ?? 'Básico', ENT_QUOTES, 'UTF-8') ?></div>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-section-label">Principal</div>
      <a href="/portal/dashboard"  class="sidebar-link active">
        <i class="fas fa-th-large"></i> Dashboard
      </a>
      <a href="/portal/mensajes"   class="sidebar-link">
        <i class="fas fa-comments"></i> Mensajes
        <span class="badge" id="msg-count" style="display:none"></span>
      </a>
      <a href="/portal/documentos" class="sidebar-link">
        <i class="fas fa-folder-open"></i> Documentos
      </a>
      <a href="/portal/licencia"   class="sidebar-link">
        <i class="fas fa-id-card"></i> Mi Licencia
      </a>

      <div class="sidebar-section-label">Cuenta</div>
      <a href="/portal/perfil"     class="sidebar-link">
        <i class="fas fa-user-circle"></i> Mi Perfil
      </a>
      <a href="/planes"            class="sidebar-link">
        <i class="fas fa-rocket"></i> Planes
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="/portal/logout" class="sidebar-logout">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
  </aside>

  <!-- Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ═══ MAIN ═══════════════════════════════════════════════ -->
  <main class="app-main" id="main">

    <!-- ── TOPBAR ── -->
    <header class="app-topbar">
      <button class="topbar-menu-btn" id="menuBtn" aria-label="Abrir menú" aria-expanded="false" aria-controls="sidebar">
        <i class="fas fa-bars" style="font-size:18px"></i>
      </button>

      <span class="topbar-title">Mi Dashboard</span>

      <div class="topbar-actions">
        <a href="/portal/mensajes" class="topbar-icon-btn" aria-label="Mensajes">
          <i class="fas fa-comments"></i>
          <span class="dot" id="notif-dot" style="display:none"></span>
        </a>
        <a href="/portal/perfil" class="topbar-user">
          <div class="topbar-avatar"><?= htmlspecialchars($inicial, ENT_QUOTES, 'UTF-8') ?></div>
          <div style="display:flex;flex-direction:column">
            <span class="topbar-user-name"><?= htmlspecialchars(explode(' ', $cliente['nombre'])[0], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="topbar-user-role">Cliente</span>
          </div>
        </a>
      </div>
    </header>

    <!-- ── CONTENIDO ── -->
    <div class="app-content">

      <div class="page-header">
        <h1>Bienvenido, <?= htmlspecialchars(explode(' ', $cliente['nombre'])[0], ENT_QUOTES, 'UTF-8') ?> 👋</h1>
        <p>Aquí tienes un resumen de tu cuenta y servicios activos.</p>
      </div>

      <!-- KPI Cards -->
      <div class="kpi-grid" id="kpiGrid">
        <!-- Skeleton mientras cargan -->
        <?php for($i=0;$i<4;$i++): ?>
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon sk" style="width:38px;height:38px">&nbsp;</div>
          </div>
          <div class="sk" style="height:14px;width:60%;margin:8px 0 4px">&nbsp;</div>
          <div class="sk" style="height:30px;width:40%">&nbsp;</div>
        </div>
        <?php endfor; ?>
      </div>

      <div class="grid-2col" style="display:grid;grid-template-columns:1fr;gap:20px">

        <!-- Mensajes recientes -->
        <div class="card">
          <div class="card-header">
            <span class="card-title"><i class="fas fa-comments" style="color:#0057B7;margin-right:8px"></i>Mensajes recientes</span>
            <a href="/portal/mensajes" class="btn btn-ghost btn-sm">Ver todos</a>
          </div>
          <div class="card-body" id="msgPreview" style="padding:0">
            <!-- Se carga vía AJAX -->
            <div style="padding:20px">
              <div class="sk" style="height:60px;margin-bottom:10px">&nbsp;</div>
              <div class="sk" style="height:60px;margin-bottom:10px">&nbsp;</div>
              <div class="sk" style="height:60px">&nbsp;</div>
            </div>
          </div>
        </div>

        <!-- Documentos recientes -->
        <div class="card">
          <div class="card-header">
            <span class="card-title"><i class="fas fa-folder" style="color:#D97706;margin-right:8px"></i>Documentos recientes</span>
            <a href="/portal/documentos" class="btn btn-ghost btn-sm">Ver todos</a>
          </div>
          <div class="card-body" id="docPreview" style="padding:0">
            <div style="padding:20px">
              <div class="sk" style="height:50px;margin-bottom:10px">&nbsp;</div>
              <div class="sk" style="height:50px;margin-bottom:10px">&nbsp;</div>
              <div class="sk" style="height:50px">&nbsp;</div>
            </div>
          </div>
        </div>

      </div>

      <!-- Licencia card -->
      <div style="margin-top:20px" id="licCard">
        <div class="sk" style="height:160px;border-radius:12px">&nbsp;</div>
      </div>

      <!-- Notificaciones -->
      <div class="card" style="margin-top:20px">
        <div class="card-header">
          <span class="card-title"><i class="fas fa-bell" style="color:#C8151B;margin-right:8px"></i>Notificaciones</span>
          <button class="btn btn-ghost btn-sm" id="markAllRead">Marcar leídas</button>
        </div>
        <div class="notif-list" id="notifList">
          <div style="padding:20px">
            <div class="sk" style="height:56px;margin-bottom:8px">&nbsp;</div>
            <div class="sk" style="height:56px;margin-bottom:8px">&nbsp;</div>
            <div class="sk" style="height:56px">&nbsp;</div>
          </div>
        </div>
      </div>

    </div><!-- /app-content -->
  </main><!-- /app-main -->

  <!-- ═══ BOTTOM NAV MOBILE ══════════════════════════════════ -->
  <nav class="app-bottom-nav" aria-label="Navegación móvil">
    <a href="/portal/dashboard"  class="bottom-nav-item active">
      <i class="fas fa-th-large"></i><span>Inicio</span>
    </a>
    <a href="/portal/mensajes"   class="bottom-nav-item">
      <i class="fas fa-comments"></i><span>Mensajes</span>
      <span class="nav-badge" id="bnav-msg" style="display:none"></span>
    </a>
    <a href="/portal/documentos" class="bottom-nav-item">
      <i class="fas fa-folder"></i><span>Docs</span>
    </a>
    <a href="/portal/licencia"   class="bottom-nav-item">
      <i class="fas fa-id-card"></i><span>Licencia</span>
    </a>
    <a href="/portal/perfil"     class="bottom-nav-item">
      <i class="fas fa-user"></i><span>Perfil</span>
    </a>
  </nav>

</div><!-- /app-layout -->

<?php require_once __DIR__ . '/../../views/partials/foot.php'; ?>
