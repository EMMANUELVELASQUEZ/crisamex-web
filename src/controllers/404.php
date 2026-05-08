<?php
$pageTitle = '404 — CRISAMEX';
require_once SRC_PATH . '/views/partials/header.php';
?>
<section class="page-header" style="padding:180px 0 100px;">
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <h1 style="font-size:8rem;color:var(--red);line-height:1;">404</h1>
    <h2 style="color:white;margin-top:8px;font-family:'Roboto Condensed',sans-serif;text-transform:uppercase;">Página no encontrada</h2>
    <p style="color:rgba(255,255,255,.5);margin-top:12px;">La página que buscas no existe o fue movida.</p>
    <a href="/" class="btn btn-red" style="margin-top:28px;display:inline-flex;"><i class="fas fa-home"></i>Volver al inicio</a>
  </div>
</section>
<?php require_once SRC_PATH . '/views/partials/footer.php'; ?>
