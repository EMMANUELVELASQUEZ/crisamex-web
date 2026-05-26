<?php
$config = [];
foreach(Database::fetchAll("SELECT clave,valor FROM site_config") as $r) $config[$r['clave']]=$r['valor'];
$pageTitle    = $pageTitle    ?? 'CRISAMEX — Control de Radiaciones e Ingeniería S.A. de C.V.';
$pageDesc     = $pageDesc     ?? 'Expertos mexicanos certificados con 25 años de experiencia en Seguridad y Protección Radiológica. Servicios ante CNSNS, STPS, COFEPRIS y SCT.';
$pageKeywords = $pageKeywords ?? 'seguridad radiológica, protección radiológica, CNSNS, CRISAMEX, México';
$curUri = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="CRISAMEX">
<meta name="application-name" content="CRISAMEX">
<meta name="msapplication-TileColor" content="#C8151B">
<meta name="theme-color" content="#C8151B" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
<title><?= htmlspecialchars($pageTitle) ?></title>
<meta name="description"  content="<?= htmlspecialchars($pageDesc) ?>">
<meta name="keywords"     content="<?= htmlspecialchars($pageKeywords) ?>">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>">
<meta property="og:type"  content="website">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="/css/style.css">
<!-- Favicon / Pestaña -->
<link rel="icon"            type="image/x-icon"    href="/images/favicon.ico">
<link rel="icon"            type="image/png" sizes="32x32"  href="/images/favicon-32.png">
<link rel="apple-touch-icon" sizes="192x192" href="/images/favicon-192.png">
<meta name="theme-color" content="#C8151B">
<?php if(!empty($config['google_analytics'])): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $config['google_analytics'] ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= $config['google_analytics'] ?>');</script>
<?php endif; ?>
</head>
<body>

<!-- CURSOR -->
<div id="cur"></div>
<div id="cur-r"></div>

<!-- BOTONES FLOTANTES REDES SOCIALES -->
<div class="float-bar">
  <a href="https://www.facebook.com/crisamexx" target="_blank" rel="noopener"
     class="f-btn fb" data-tip="Facebook" title="Facebook CRISAMEX">
    <i class="fab fa-facebook-f"></i>
  </a>
  <a href="https://wa.me/525556508420?text=Hola%20CRISAMEX%2C%20me%20interesa%20informaci%C3%B3n%20sobre%20sus%20servicios."
     target="_blank" rel="noopener"
     class="f-btn wa" data-tip="WhatsApp" title="WhatsApp">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="https://mx.linkedin.com/company/crisamex" target="_blank" rel="noopener"
     class="f-btn li" data-tip="LinkedIn" title="LinkedIn CRISAMEX">
    <i class="fab fa-linkedin-in"></i>
  </a>
  <a href="tel:+525556508420"
     class="f-btn ph" data-tip="Llamar" title="Llamar a CRISAMEX">
    <i class="fas fa-phone-alt"></i>
  </a>
</div>

<!-- SCROLL TOP -->
<button class="scroll-top-btn" id="stb" aria-label="Ir arriba">
  <i class="fas fa-chevron-up"></i>
</button>

<!-- TOPBAR oscuro con info de contacto -->
<div class="topbar">
  <div class="container">
    <div class="topbar-i">
      <div class="topbar-l">
        <a href="tel:+525556508420"><i class="fas fa-phone-alt"></i>01 55 5650 8420</a>
        <a href="mailto:contacto@crisamex.com"><i class="fas fa-envelope"></i>contacto@crisamex.com</a>
        <span><i class="fas fa-map-marker-alt"></i>Ciudad de México, México</span>
      </div>
      <div class="topbar-r">
        <div class="t-soc">
          <a href="https://www.facebook.com/crisamexx" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="https://mx.linkedin.com/company/crisamex" target="_blank" rel="noopener" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          <a href="https://wa.me/525556508420" target="_blank" rel="noopener" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
          <a href="mailto:contacto@crisamex.com" title="Email"><i class="fas fa-envelope"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- NAVBAR BLANCO -->
<nav id="nav">
  <div class="container">
    <div class="nav-i">

      <!-- LOGO REAL -->
      <a href="/">
        <img src="/images/logo-crisamex.jpg"
             class="nav-logo-img"
             alt="CRISAMEX - Control de Radiaciones e Ingeniería S.A. de C.V.">
      </a>

      <!-- LINKS -->
      <ul class="nav-links" id="nav-links">
        <li><a href="/"              class="<?= $curUri==='/'?'on':'' ?>">Inicio</a></li>
        <li><a href="/quienes-somos" class="<?= strpos($curUri,'/quienes')!==false?'on':'' ?>">Quiénes Somos</a></li>
        <li><a href="/servicios"     class="<?= strpos($curUri,'/servicios')!==false?'on':'' ?>">Servicios</a></li>
        <li><a href="/certificaciones" class="<?= strpos($curUri,'/cert')!==false?'on':'' ?>">Certificaciones</a></li>
        <li><a href="/planes"        class="<?= strpos($curUri,'/planes')!==false?'on':'' ?>">Planes</a></li>
        <li><a href="/contacto"      class="<?= strpos($curUri,'/contacto')!==false?'on':'' ?>">Contacto</a></li>
      </ul>

      <!-- BOTONES NAV -->
      <div class="nav-cta-wrap">
        <a href="/portal/login" class="btn btn-outline btn-sm">
          <i class="fas fa-user-circle"></i>Portal Clientes
        </a>
        <a href="tel:+525556508420" class="btn btn-r btn-sm">
          <i class="fas fa-phone-alt"></i>55 5650 8420
        </a>
      </div>

      <!-- HAMBURGER -->
      <button class="nav-ham" id="ham" aria-label="Menú">
        <span></span><span></span><span></span>
      </button>

    </div>
  </div>
</nav>
