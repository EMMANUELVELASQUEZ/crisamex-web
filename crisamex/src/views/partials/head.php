<?php
/**
 * CRISAMEX — head.php optimizado para velocidad
 * Archivo: src/views/partials/head.php
 */
$pageTitle = $pageTitle ?? 'CRISAMEX — Seguridad Radiológica';
$pageDesc  = $pageDesc  ?? 'Expertos en Seguridad Radiológica en México. 25+ años.';
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover,maximum-scale=5.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="theme-color" content="#C8151B">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="icon" href="/favicon.ico" sizes="any">
  <link rel="apple-touch-icon" href="/images/logo-crisamex.jpg">

  <!-- Preconnect para velocidad máxima -->
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

  <!-- Fuentes sin bloqueo de render -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;600;700;800&display=swap&subset=latin,latin-ext"
    media="print" onload="this.media='all'">
  <noscript>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
  </noscript>

  <!-- Font Awesome sin bloqueo -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    media="print" onload="this.media='all'" crossorigin="anonymous">
  <noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  </noscript>

  <!-- CSS CRÍTICO inline — evita FOUC y layout shift -->
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{font-size:16px;-webkit-text-size-adjust:100%;scroll-behavior:smooth}
    body{font-family:'DM Sans',-apple-system,BlinkMacSystemFont,sans-serif;
         background:#F8FAFC;color:#1E293B;line-height:1.6;overflow-x:hidden}
    img{max-width:100%;height:auto;display:block}
    /* Page loader */
    #pl{position:fixed;inset:0;background:#fff;z-index:9999;
        display:flex;align-items:center;justify-content:center;
        transition:opacity .35s ease}
    #pl.done{opacity:0;pointer-events:none}
    .sp{width:36px;height:36px;border:3px solid #f0f0f0;
        border-top-color:#C8151B;border-radius:50%;
        animation:sp .7s linear infinite}
    @keyframes sp{to{transform:rotate(360deg)}}
    /* Skeleton */
    .sk{background:linear-gradient(90deg,#f0f0f0 25%,#e8e8e8 50%,#f0f0f0 75%);
        background-size:400% 100%;animation:sh 1.4s infinite;border-radius:8px}
    @keyframes sh{0%{background-position:400% 0}100%{background-position:-400% 0}}
    /* App layout base */
    .app-layout{display:flex;min-height:100svh}
    .app-main{flex:1;display:flex;flex-direction:column;overflow:hidden}
    .app-topbar{height:64px;background:#fff;border-bottom:1px solid #E2E8F0;
                display:flex;align-items:center;padding:0 20px;gap:14px;
                position:sticky;top:0;z-index:100}
    .app-content{flex:1;padding:24px 20px;overflow-y:auto}
  </style>

  <!-- CSS principal -->
  <link rel="stylesheet" href="/css/style.css">
  <?php if (!empty($extraHead)) echo $extraHead; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">

<!-- Loader de página -->
<div id="pl" aria-hidden="true"><div class="sp"></div></div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var p = document.getElementById('pl');
    if (p) setTimeout(function() { p.classList.add('done'); }, 120);
  });
</script>
