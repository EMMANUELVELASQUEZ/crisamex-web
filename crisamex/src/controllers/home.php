<?php
$pageTitle    = 'CRISAMEX — Control de Radiaciones e Ingeniería S.A. de C.V.';
$pageDesc     = 'Expertos mexicanos certificados con 25 años de experiencia en Seguridad y Protección Radiológica. Servicios ante CNSNS, STPS, COFEPRIS y SCT en todo México.';
$pageKeywords = 'seguridad radiológica, protección radiológica, CNSNS, CRISAMEX, instrumentación nuclear, transporte radiactivo, México';

$servicios    = Database::fetchAll("SELECT * FROM servicios WHERE activo=1 ORDER BY orden LIMIT 6");
$estadisticas = Database::fetchAll("SELECT * FROM estadisticas WHERE activo=1 ORDER BY orden");
$valores      = Database::fetchAll("SELECT * FROM valores WHERE activo=1 ORDER BY orden LIMIT 6");

require_once SRC_PATH . '/views/partials/header.php';
require_once SRC_PATH . '/views/pages/home.php';
require_once SRC_PATH . '/views/partials/footer.php';
