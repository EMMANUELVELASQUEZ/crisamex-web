<?php
$cliente_id = $_SESSION['cliente_id'];
$noLeidas = Database::fetchOne("SELECT COUNT(*) as c FROM portal_notificaciones WHERE cliente_id=? AND leida=0",[$cliente_id])['c']??0;
$noLeidos = Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE cliente_id=? AND leido_cliente=0 AND de='admin'",[$cliente_id])['c']??0;
$curUri = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= htmlspecialchars($portalTitle??'Portal') ?> — CRISAMEX</title>
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
<meta name="theme-color" content="#C8151B">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="/css/admin.css">
<style>
/* Portal overrides — fondo blanco */
body { background: #f5f5f5; }
.sb { background: #1a1a1a; }
.adm-top { background: #fff; }
.adm-content { background: #f5f5f5; }
.ac { background: #fff; }
.ac-head { background: #f8f8f8; }
.at th { background: #f8f8f8; }
.st-card { background: #fff; }

/* Portal status badges */
.badge-activa  { background: rgba(22,163,74,.1); color: #16a34a; border: 1px solid rgba(22,163,74,.2); padding: 3px 9px; border-radius: 20px; font-size: .68rem; font-weight: 700; }
.badge-trial   { background: rgba(217,119,6,.1); color: #d97706; border: 1px solid rgba(217,119,6,.2); padding: 3px 9px; border-radius: 20px; font-size: .68rem; font-weight: 700; }
.badge-vencida { background: rgba(200,21,27,.1); color: #C8151B; border: 1px solid rgba(200,21,27,.2); padding: 3px 9px; border-radius: 20px; font-size: .68rem; font-weight: 700; }
</style>
</head>
<body>

<!-- SIDEBAR PORTAL -->
<aside class="sb">
  <div class="sb-head">
    <div class="sb-logo-box">
      <img src="/images/logo-crisamex.jpg" alt="CRISAMEX"
           style="height:34px!important;width:auto!important;max-width:176px!important;display:block!important;object-fit:contain!important;">
    </div>
  </div>

  <nav class="sb-nav">
    <div class="sb-sec">Portal</div>
    <a href="/portal" class="sb-link <?= $curUri==='/portal'?'act':'' ?>">
      <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="/portal/mensajes" class="sb-link <?= strpos($curUri,'/portal/mensajes')!==false?'act':'' ?>">
      <i class="fas fa-comments"></i> Mensajes
      <?php if($noLeidos>0): ?><span class="sb-cnt"><?= $noLeidos ?></span><?php endif; ?>
    </a>
    <a href="/portal/documentos" class="sb-link <?= strpos($curUri,'/portal/documentos')!==false?'act':'' ?>">
      <i class="fas fa-folder"></i> Documentos
    </a>

    <div class="sb-sec">Mi Cuenta</div>
    <a href="/portal/licencia" class="sb-link <?= strpos($curUri,'/portal/licencia')!==false?'act':'' ?>">
      <i class="fas fa-id-card"></i> Mi Licencia
    </a>
    <a href="/portal/perfil" class="sb-link <?= strpos($curUri,'/portal/perfil')!==false?'act':'' ?>">
      <i class="fas fa-user-cog"></i> Mi Perfil
    </a>

    <div class="sb-sec">Explorar</div>
    <a href="/planes" class="sb-link">
      <i class="fas fa-star"></i> Ver Planes
    </a>
    <a href="/" target="_blank" class="sb-link">
      <i class="fas fa-external-link-alt"></i> Sitio web
    </a>
  </nav>

  <div class="sb-foot">
    <div class="sb-av"><?= strtoupper(substr($_SESSION['cliente_nombre']??'C',0,1)) ?></div>
    <div>
      <div class="sb-uname" style="font-size:.78rem;"><?= htmlspecialchars($_SESSION['cliente_nombre']??'') ?></div>
      <div class="sb-urole"><?= htmlspecialchars($_SESSION['cliente_plan']??'Trial') ?></div>
    </div>
    <a href="/portal/logout" class="sb-out" title="Cerrar sesión">
      <i class="fas fa-sign-out-alt"></i>
    </a>
  </div>
</aside>

<!-- MAIN PORTAL -->
<main class="adm-main">
  <div class="adm-top">
    <h1><?= htmlspecialchars($portalTitle??'Dashboard') ?></h1>
    <div class="adm-top-r">
      <?php if($noLeidas>0||$noLeidos>0): ?>
      <a href="/portal/mensajes" style="position:relative;color:var(--txt2);text-decoration:none;" title="<?= $noLeidas+$noLeidos ?> notificaciones">
        <i class="fas fa-bell" style="font-size:1.1rem;"></i>
        <span style="position:absolute;top:-5px;right:-5px;width:9px;height:9px;background:#C8151B;border-radius:50%;border:2px solid #fff;"></span>
      </a>
      <?php endif; ?>
      <span class="<?= 'badge-'.($_SESSION['cliente_status']??'trial') ?>">
        <?= ucfirst($_SESSION['cliente_status']??'trial') ?>
      </span>
      <span style="color:var(--txt3);font-size:.76rem;"><?= date('d/m/Y') ?></span>
    </div>
  </div>
  <div class="adm-content">
