<?php
/**
 * CRISAMEX — Admin Dashboard
 * Archivo: src/controllers/admin/dashboard.php
 */
$pageTitle = 'Dashboard — Panel Admin CRISAMEX';
$bodyClass = 'admin-body';

require_once __DIR__ . '/../../views/partials/head.php';

$admin   = $_SESSION['admin_user'] ?? ['nombre'=>'Administrador'];
$inicial = mb_substr($admin['nombre'], 0, 1, 'UTF-8');
?>

<div class="app-layout">

  <!-- ═══ SIDEBAR ADMIN ══════════════════════════════════════ -->
  <aside class="app-sidebar" id="sidebar" role="navigation" aria-label="Menú admin">

    <div class="sidebar-header">
      <div class="sidebar-logo">
        <img src="/images/logo-crisamex.jpg" alt="CRISAMEX">
        <span class="sidebar-logo-text">ADMIN</span>
      </div>
    </div>

    <div class="sidebar-user">
      <div class="sidebar-user-info">
        <div class="sidebar-avatar"><?= htmlspecialchars($inicial, ENT_QUOTES, 'UTF-8') ?></div>
        <div>
          <div class="sidebar-user-name"><?= htmlspecialchars($admin['nombre'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="sidebar-user-role">Super Admin</div>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-section-label">Principal</div>
      <a href="/admin/dashboard"         class="sidebar-link active">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="/admin/comunicaciones"    class="sidebar-link">
        <i class="fas fa-comments"></i> Comunicaciones
        <span class="badge" id="comm-badge" style="display:none"></span>
      </a>
      <a href="/admin/clientes"          class="sidebar-link">
        <i class="fas fa-users"></i> Clientes
      </a>
      <a href="/admin/mensajes"          class="sidebar-link">
        <i class="fas fa-envelope"></i> Mensajes web
        <span class="badge" id="msg-badge" style="display:none"></span>
      </a>

      <div class="sidebar-section-label">Catálogos</div>
      <a href="/admin/servicios"         class="sidebar-link">
        <i class="fas fa-cogs"></i> Servicios
      </a>
      <a href="/admin/equipo"            class="sidebar-link">
        <i class="fas fa-users-cog"></i> Equipo
      </a>

      <div class="sidebar-section-label">Sistema</div>
      <a href="/admin/configuracion"     class="sidebar-link">
        <i class="fas fa-sliders-h"></i> Configuración
      </a>
      <a href="/" target="_blank"        class="sidebar-link">
        <i class="fas fa-external-link-alt"></i> Ver sitio
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="/admin/logout" class="sidebar-logout">
        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
      </a>
    </div>
  </aside>

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ═══ MAIN ═══════════════════════════════════════════════ -->
  <main class="app-main">

    <!-- ── TOPBAR ── -->
    <header class="app-topbar">
      <button class="topbar-menu-btn" id="menuBtn" aria-label="Abrir menú" aria-expanded="false">
        <i class="fas fa-bars" style="font-size:18px"></i>
      </button>

      <span class="topbar-title">Dashboard</span>

      <div class="topbar-actions">
        <a href="/admin/mensajes" class="topbar-icon-btn" aria-label="Mensajes">
          <i class="fas fa-envelope"></i>
          <span class="dot" id="notif-dot" style="display:none"></span>
        </a>
        <a href="/admin/comunicaciones" class="topbar-icon-btn" aria-label="Chat">
          <i class="fas fa-comments"></i>
        </a>
        <div class="topbar-user">
          <div class="topbar-avatar"><?= htmlspecialchars($inicial, ENT_QUOTES, 'UTF-8') ?></div>
          <div style="display:flex;flex-direction:column">
            <span class="topbar-user-name"><?= htmlspecialchars(explode(' ', $admin['nombre'])[0], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="topbar-user-role">Admin</span>
          </div>
        </div>
      </div>
    </header>

    <!-- ── CONTENIDO ── -->
    <div class="app-content">

      <div class="page-header page-header-row">
        <div>
          <h1>Panel de Control</h1>
          <p>Bienvenido, <?= htmlspecialchars(explode(' ', $admin['nombre'])[0], ENT_QUOTES, 'UTF-8') ?>. Aquí tienes el resumen del sistema.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <a href="/admin/clientes/nuevo" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus"></i> Nuevo cliente
          </a>
        </div>
      </div>

      <!-- KPI Grid -->
      <div class="kpi-grid" id="kpiGrid">
        <?php for($i=0;$i<4;$i++): ?>
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon sk" style="width:38px;height:38px">&nbsp;</div>
          </div>
          <div class="sk" style="height:14px;width:55%;margin:8px 0 4px">&nbsp;</div>
          <div class="sk" style="height:30px;width:42%">&nbsp;</div>
        </div>
        <?php endfor; ?>
      </div>

      <!-- Grid principal -->
      <div style="display:grid;grid-template-columns:1fr;gap:20px" id="mainGrid">

        <!-- Mensajes recientes del formulario web -->
        <div class="card">
          <div class="card-header">
            <span class="card-title">
              <i class="fas fa-envelope" style="color:#C8151B;margin-right:8px"></i>
              Mensajes de contacto
            </span>
            <a href="/admin/mensajes" class="btn btn-ghost btn-sm">Ver todos</a>
          </div>
          <div class="table-wrap" id="contactMsgs">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Empresa</th>
                  <th>Servicio</th>
                  <th>Fecha</th>
                  <th>Estado</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="contactMsgsTbody">
                <?php for($i=0;$i<4;$i++): ?>
                <tr>
                  <td><div class="sk" style="height:16px;width:120px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:100px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:130px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:80px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:60px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:40px">&nbsp;</div></td>
                </tr>
                <?php endfor; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Clientes recientes -->
        <div class="card">
          <div class="card-header">
            <span class="card-title">
              <i class="fas fa-users" style="color:#0057B7;margin-right:8px"></i>
              Clientes recientes
            </span>
            <a href="/admin/clientes" class="btn btn-ghost btn-sm">Ver todos</a>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Cliente</th>
                  <th>Plan</th>
                  <th>Licencia</th>
                  <th>Estado</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="clientesTbody">
                <?php for($i=0;$i<4;$i++): ?>
                <tr>
                  <td><div class="sk" style="height:16px;width:140px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:80px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:100px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:60px">&nbsp;</div></td>
                  <td><div class="sk" style="height:16px;width:50px">&nbsp;</div></td>
                </tr>
                <?php endfor; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /mainGrid -->
    </div><!-- /app-content -->

  </main>

  <!-- ═══ BOTTOM NAV MOBILE (Admin) ══════════════════════════ -->
  <nav class="app-bottom-nav" aria-label="Navegación móvil admin">
    <a href="/admin/dashboard"      class="bottom-nav-item active">
      <i class="fas fa-chart-line"></i><span>Dashboard</span>
    </a>
    <a href="/admin/comunicaciones" class="bottom-nav-item">
      <i class="fas fa-comments"></i><span>Chat</span>
      <span class="nav-badge" id="bnav-comm" style="display:none"></span>
    </a>
    <a href="/admin/clientes"       class="bottom-nav-item">
      <i class="fas fa-users"></i><span>Clientes</span>
    </a>
    <a href="/admin/mensajes"       class="bottom-nav-item">
      <i class="fas fa-envelope"></i><span>Mensajes</span>
      <span class="nav-badge" id="bnav-msg" style="display:none"></span>
    </a>
    <a href="/admin/configuracion"  class="bottom-nav-item">
      <i class="fas fa-cogs"></i><span>Config</span>
    </a>
  </nav>

</div><!-- /app-layout -->

<?php require_once __DIR__ . '/../../views/partials/foot.php'; ?>
