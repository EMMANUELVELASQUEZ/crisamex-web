<?php
$pageTitle='Certificaciones — CRISAMEX';
$pageDesc='CRISAMEX cuenta con certificaciones ISO 9001 y autorizaciones ante CNSNS, STPS, COFEPRIS y SCT.';
$certs=Database::fetchAll("SELECT * FROM certificaciones WHERE activo=1 ORDER BY orden");
require_once SRC_PATH.'/views/partials/header.php';
$certIcons=['fas fa-certificate','fas fa-atom','fas fa-hard-hat','fas fa-heartbeat','fas fa-truck','fas fa-shield-alt'];
$certColors=['#C8151B','#2563eb','#d97706','#16a34a','#7c3aed','#C8151B'];
?>

<section class="ph">
  <div class="ph-glow"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <nav class="bc"><a href="/">Inicio</a><span class="sep"><i class="fas fa-chevron-right"></i></span><span>Certificaciones</span></nav>
    <h1 class="h1" style="color:#fff;font-size:clamp(3rem,7vw,7rem);">Nuestras<br><span style="color:var(--r);">Certificaciones</span></h1>
    <p style="color:rgba(255,255,255,.72);margin-top:14px;">Avales de calidad que respaldan nuestra excelencia en seguridad radiológica</p>
  </div>
</section>

<!-- GRID CERTIFICACIONES — BLANCO -->
<section class="sp" style="background:#fff;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px;">
      <div class="label">Nuestros Avales</div>
      <h2 class="h2" style="color:#1a1a1a;">Reconocidos por los <span class="red">Mejores</span></h2>
      <p class="sub" style="color:#666;margin-top:16px;">Contamos con todas las autorizaciones y certificaciones requeridas por las autoridades mexicanas e internacionales.</p>
    </div>
    <div class="cert-grid">
      <?php foreach($certs as $i=>$c): $col=$certColors[$i%count($certColors)]; $ico=$certIcons[$i%count($certIcons)]; ?>
      <div class="cert-c rv d<?=($i%5)+1?>">
        <div class="cert-ico" style="background:rgba(200,21,27,.07);border-color:rgba(200,21,27,.14);">
          <i class="<?=$ico?>" style="color:#C8151B;"></i>
        </div>
        <h3 style="color:#1a1a1a;"><?=htmlspecialchars($c['nombre'])?></h3>
        <p style="color:#666;"><?=htmlspecialchars($c['descripcion_corta'])?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ISO 9001 DESTAQUE — GRIS -->
<section class="sp" style="background:#f8f8f8;">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;">
      <div class="rv-l">
        <div style="position:relative;overflow:hidden;height:420px;box-shadow:0 8px 40px rgba(0,0,0,.1);">
          <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=85&fit=crop"
               alt="Certificación ISO 9001 CRISAMEX"
               style="width:100%;height:100%;object-fit:cover;">
          <div style="position:absolute;inset:0;background:rgba(200,21,27,.08);"></div>
          <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.5),transparent);padding:28px;">
            <div style="font-family:'Bebas Neue',sans-serif;font-size:1.5rem;letter-spacing:2px;color:#fff;">ISO 9001:2008</div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.8);">Sistema de Gestión de Calidad Certificado</div>
          </div>
        </div>
      </div>
      <div class="rv-r">
        <div class="label lc"><i class="fas fa-award"></i>Certificación Principal</div>
        <h2 class="h2" style="color:#1a1a1a;font-size:clamp(2rem,3vw,3rem);">
          Sistema de Gestión<br>de <span class="red">Calidad</span> ISO 9001
        </h2>
        <div class="rule"></div>
        <p style="color:#444;line-height:1.88;margin-bottom:14px;">
          CRISAMEX cuenta con la <strong style="color:#1a1a1a;">Certificación ISO 9001:2008</strong> en Sistema de Gestión de Calidad, lo que garantiza que todos nuestros procesos y servicios cumplen con los más altos estándares internacionales.
        </p>
        <p style="color:#666;line-height:1.85;font-size:.93rem;font-weight:300;margin-bottom:24px;">
          Esta certificación es el aval de que nuestra empresa opera bajo procedimientos documentados, mejora continua y enfoque total en la satisfacción del cliente y el cumplimiento normativo.
        </p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:28px;">
          <?php foreach(['CNSNS','STPS','COFEPRIS','SCT'] as $aut): ?>
          <div style="background:#fff;border:1px solid #e0e0e0;padding:14px 16px;display:flex;align-items:center;gap:10px;border-left:3px solid #C8151B;">
            <i class="fas fa-check-circle" style="color:#C8151B;font-size:.9rem;flex-shrink:0;"></i>
            <span style="font-family:'Bebas Neue',sans-serif;font-size:.9rem;letter-spacing:1px;color:#1a1a1a;"><?=$aut?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="/contacto" class="btn btn-r"><i class="fas fa-paper-plane"></i>Solicitar información</a>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-s">
  <div class="container">
    <div class="cta-inn rv">
      <h2 class="h2" style="color:#fff;">Confíe en los <span style="text-decoration:underline;text-underline-offset:6px;">Certificados</span></h2>
      <p style="color:rgba(255,255,255,.9);margin-top:16px;">Nuestras certificaciones respaldan cada servicio que ofrecemos.</p>
      <div class="cta-btns" style="margin-top:36px;">
        <a href="/contacto" class="btn btn-dark"><i class="fas fa-arrow-right"></i>Contáctenos</a>
        <a href="/servicios" class="btn btn-wh"><i class="fas fa-list"></i>Ver servicios</a>
      </div>
    </div>
  </div>
</section>

<?php require_once SRC_PATH.'/views/partials/footer.php'; ?>
