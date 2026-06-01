<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover">
<title><?= htmlspecialchars($adminTitle??'Admin') ?> — CRISAMEX</title>
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<meta name="theme-color" content="#C8151B">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="/css/admin.css">
<style>
/* ── FIX TOPBAR OVERFLOW ─────────────────────────────────── */
.adm-top {
  display: flex !important;
  align-items: center !important;
  gap: 10px !important;
  padding: 0 16px !important;
  height: 60px !important;
  overflow: hidden !important;
  position: sticky !important;
  top: 0 !important;
  z-index: 200 !important;
  background: #fff !important;
  border-bottom: 1px solid #E2E8F0 !important;
  box-shadow: 0 1px 3px rgba(0,0,0,.06) !important;
}
.adm-top h1 {
  flex: 1 !important;
  font-size: 17px !important;
  font-weight: 800 !important;
  color: #1E293B !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
  min-width: 0 !important;
  margin: 0 !important;
}
.adm-top-r {
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  flex-shrink: 0 !important;
  margin-left: auto !important;
}
/* Ocultar fecha en mobile */
@media(max-width:600px){
  .top-date { display: none !important; }
  .top-pill { display: none !important; }
}
/* Hamburguesa */
.sb-toggle {
  display: none;
  width: 40px; height: 40px;
  border: none; background: none;
  border-radius: 8px; cursor: pointer;
  align-items: center; justify-content: center;
  color: #64748B; font-size: 18px;
  flex-shrink: 0;
  -webkit-tap-highlight-color: transparent;
  transition: background .18s;
}
.sb-toggle:hover { background: #F1F5F9; }
@media(max-width:1023px){ .sb-toggle { display: flex !important; } }
/* Sidebar mobile */
@media(max-width:1023px){
  body { display: block !important; }
  .sb {
    position: fixed !important;
    transform: translateX(-100%) !important;
    height: 100% !important;
    z-index: 500 !important;
    transition: transform .25s ease !important;
    box-shadow: 4px 0 24px rgba(0,0,0,.3) !important;
  }
  .sb.open { transform: translateX(0) !important; }
  .adm-main { width: 100% !important; margin-left: 0 !important; }
  .adm-content { padding-bottom: 80px !important; }
}
/* Overlay */
.sb-ov {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.5); z-index: 499;
  backdrop-filter: blur(2px);
}
.sb-ov.on { display: block; }
/* Bottom nav mobile */
.adm-bnav {
  display: none;
  position: fixed; bottom: 0; left: 0; right: 0;
  background: #fff; border-top: 1px solid #E2E8F0;
  z-index: 300; box-shadow: 0 -2px 12px rgba(0,0,0,.08);
  padding-bottom: env(safe-area-inset-bottom, 0);
}
@media(max-width:1023px){ .adm-bnav { display: flex; } }
.bn-it {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 3px; font-size: 10px; font-weight: 600;
  color: #94A3B8; padding: 8px 4px; min-height: 56px;
  text-decoration: none; position: relative;
  transition: color .18s; -webkit-tap-highlight-color: transparent;
}
.bn-it:active { transform: scale(.9); }
.bn-it.act    { color: #C8151B; }
.bn-it i      { font-size: 20px; }
.bn-dot {
  position: absolute; top: 7px; right: calc(50% - 14px);
  width: 8px; height: 8px; border-radius: 50%;
  background: #C8151B; border: 2px solid #fff;
}
</style>
</head>
<body>

<!-- Overlay sidebar -->
<div class="sb-ov" id="sbOv"></div>

<aside class="sb" id="adminSb">
  <div class="sb-head">
    <div class="sb-logo-box">
      <img src="/images/logo-crisamex.jpg" alt="CRISAMEX"
           style="height:34px!important;width:auto!important;max-width:176px!important;display:block!important;object-fit:contain!important;">
    </div>
  </div>

  <nav class="sb-nav">
    <div class="sb-sec">General</div>
    <a href="/admin" class="sb-link <?= ($adminPage??'')==='dashboard'?'act':'' ?>">
      <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="/admin/mensajes" class="sb-link <?= ($adminPage??'')==='mensajes'?'act':'' ?>">
      <i class="fas fa-inbox"></i> Mensajes web
      <?php $nm=Database::fetchOne("SELECT COUNT(*) as c FROM contacto_mensajes WHERE leido=0"); if($nm&&$nm['c']>0): ?>
      <span class="sb-cnt"><?= $nm['c'] ?></span>
      <?php endif; ?>
    </a>

    <div class="sb-sec">Comunicaciones</div>
    <a href="/admin/comunicaciones" class="sb-link <?= ($adminPage??'')==='comunicaciones'?'act':'' ?>">
      <i class="fas fa-comments"></i> Chat Clientes
      <?php $nc=Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE de='cliente' AND leido_admin=0"); if($nc&&$nc['c']>0): ?>
      <span class="sb-cnt"><?= $nc['c'] ?></span>
      <?php endif; ?>
    </a>

    <div class="sb-sec">Clientes</div>
    <a href="/admin/clientes" class="sb-link <?= ($adminPage??'')==='clientes'?'act':'' ?>">
      <i class="fas fa-users-cog"></i> Clientes
      <?php $ntr=Database::fetchOne("SELECT COUNT(*) as c FROM clientes WHERE licencia_status='trial'"); if($ntr&&$ntr['c']>0): ?>
      <span class="sb-cnt"><?= $ntr['c'] ?></span>
      <?php endif; ?>
    </a>
    <a href="/admin/planes" class="sb-link <?= ($adminPage??'')==='planes'?'act':'' ?>">
      <i class="fas fa-star"></i> Planes y Licencias
    </a>

    <div class="sb-sec">Contenido</div>
    <a href="/admin/servicios" class="sb-link <?= ($adminPage??'')==='servicios'?'act':'' ?>">
      <i class="fas fa-cogs"></i> Servicios
    </a>
    <a href="/admin/equipo" class="sb-link <?= ($adminPage??'')==='equipo'?'act':'' ?>">
      <i class="fas fa-users"></i> Equipo
    </a>
    <a href="/admin/configuracion" class="sb-link <?= ($adminPage??'')==='configuracion'?'act':'' ?>">
      <i class="fas fa-sliders-h"></i> Configuración
    </a>

    <div class="sb-sec">Sitio</div>
    <a href="/" target="_blank" class="sb-link">
      <i class="fas fa-external-link-alt"></i> Ver sitio web
    </a>
    <a href="/portal" target="_blank" class="sb-link">
      <i class="fas fa-user-circle"></i> Portal clientes
    </a>
  </nav>

  <div class="sb-foot">
    <div class="sb-av"><?= strtoupper(substr($_SESSION['admin_nombre']??'A',0,1)) ?></div>
    <div>
      <div class="sb-uname"><?= htmlspecialchars($_SESSION['admin_nombre']??'Admin') ?></div>
      <div class="sb-urole"><?= htmlspecialchars($_SESSION['admin_rol']??'') ?></div>
    </div>
    <a href="/admin/logout" class="sb-out" title="Cerrar sesión">
      <i class="fas fa-sign-out-alt"></i>
    </a>
  </div>
</aside>

<main class="adm-main">
  <!-- ── TOPBAR ARREGLADO ── -->
  <div class="adm-top">
    <!-- Botón hamburguesa mobile -->
    <button class="sb-toggle" id="sbToggle" aria-label="Menú">
      <i class="fas fa-bars"></i>
    </button>

    <h1><?= htmlspecialchars($adminTitle??'Dashboard') ?></h1>

    <div class="adm-top-r">
      <!-- Chat con badge -->
      <a href="/admin/comunicaciones"
         style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#64748B;text-decoration:none;border-radius:8px;transition:background .18s;position:relative;"
         title="Chat clientes">
        <i class="fas fa-comments" style="font-size:17px;"></i>
        <?php $totalPend=Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE de='cliente' AND leido_admin=0"); if($totalPend&&$totalPend['c']>0): ?>
        <span style="position:absolute;top:5px;right:5px;width:8px;height:8px;background:#C8151B;border-radius:50%;border:2px solid #fff;"></span>
        <?php endif; ?>
      </a>
      <!-- Rol pill -->
      <span class="top-pill"><?= ucfirst($_SESSION['admin_rol']??'') ?></span>
      <!-- Fecha -->
      <span class="top-date" style="color:#94A3B8;font-size:12px;white-space:nowrap;"><?= date('d/m/Y') ?></span>
    </div>
  </div>

  <div class="adm-content">
