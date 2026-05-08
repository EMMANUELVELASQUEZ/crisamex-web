<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= htmlspecialchars($adminTitle??'Admin') ?> — CRISAMEX</title>
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
<meta name="theme-color" content="#C8151B">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="/css/admin.css">
</head>
<body>

<aside class="sb">
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
  <div class="adm-top">
    <h1><?= htmlspecialchars($adminTitle??'Dashboard') ?></h1>
    <div class="adm-top-r">
      <a href="/admin/comunicaciones" style="color:var(--txt2);text-decoration:none;position:relative;" title="Chat clientes">
        <i class="fas fa-comments" style="font-size:1.1rem;"></i>
        <?php $totalPend=Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE de='cliente' AND leido_admin=0"); if($totalPend&&$totalPend['c']>0): ?>
        <span style="position:absolute;top:-5px;right:-5px;width:9px;height:9px;background:var(--red);border-radius:50%;border:2px solid var(--bg2);"></span>
        <?php endif; ?>
      </a>
      <span class="top-pill"><?= ucfirst($_SESSION['admin_rol']??'') ?></span>
      <span style="color:var(--txt2);font-size:.76rem;"><?= date('d/m/Y') ?></span>
    </div>
  </div>
  <div class="adm-content">
