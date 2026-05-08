<?php
$pageTitle='Servicios — CRISAMEX | Seguridad Radiológica';
$pageDesc='Servicios especializados: asesoría radiológica, trámites CNSNS/STPS/COFEPRIS, instrumentación nuclear, capacitación y más.';
$servicios=Database::fetchAll("SELECT * FROM servicios WHERE activo=1 ORDER BY orden");
require_once SRC_PATH.'/views/partials/header.php';
$imgs=[
  'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=900&q=85&fit=crop',
  'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=900&q=85&fit=crop',
  'https://images.unsplash.com/photo-1578496781197-b85385c46895?w=900&q=85&fit=crop',
  'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=900&q=85&fit=crop',
  'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=900&q=85&fit=crop',
  'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=900&q=85&fit=crop',
];
?>

<section class="ph">
  <div class="ph-glow"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <nav class="bc"><a href="/">Inicio</a><span class="sep"><i class="fas fa-chevron-right"></i></span><span>Servicios</span></nav>
    <h1 class="h1" style="color:#fff;font-size:clamp(3rem,7vw,7rem);">Nuestros<br><span style="color:var(--r);">Servicios</span></h1>
    <p style="color:rgba(255,255,255,.72);margin-top:14px;">Soluciones integrales en Seguridad y Protección Radiológica para su empresa en todo México</p>
  </div>
</section>

<?php foreach($servicios as $i=>$s):
  $even = $i%2===0;
  $img  = $imgs[$i%count($imgs)];
  $bg   = $even ? '#fff' : '#f8f8f8';
?>
<section id="<?=htmlspecialchars($s['slug'])?>"
  style="padding:90px 0;background:<?=$bg?>;border-bottom:1px solid #e8e8e8;">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;">

      <!-- IMAGEN -->
      <div style="<?=!$even?'order:2':''?>">
        <div style="position:relative;overflow:hidden;height:420px;box-shadow:0 8px 40px rgba(0,0,0,.1);">
          <img src="<?=$img?>"
               alt="<?=htmlspecialchars($s['titulo'])?>"
               style="width:100%;height:100%;object-fit:cover;display:block;">
          <!-- Overlay rojo sutil -->
          <div style="position:absolute;inset:0;background:rgba(200,21,27,.06);"></div>
          <!-- Número decorativo -->
          <div style="position:absolute;bottom:16px;right:20px;font-family:'Bebas Neue',sans-serif;font-size:5rem;color:rgba(255,255,255,.15);line-height:1;letter-spacing:2px;">0<?=$i+1?></div>
          <!-- Ícono flotante -->
          <div style="position:absolute;top:24px;left:24px;width:56px;height:56px;background:#C8151B;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;box-shadow:0 4px 20px rgba(200,21,27,.4);">
            <i class="<?=htmlspecialchars($s['icono'])?>"></i>
          </div>
        </div>
      </div>

      <!-- TEXTO -->
      <div style="<?=!$even?'order:1':''?>">
        <div style="font-size:.68rem;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:#C8151B;margin-bottom:10px;">
          Servicio <?=str_pad($i+1,2,'0',STR_PAD_LEFT)?>
        </div>
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:clamp(2rem,3.5vw,3rem);color:#1a1a1a;letter-spacing:2px;line-height:1;margin-bottom:14px;">
          <?=htmlspecialchars($s['titulo'])?>
        </h2>
        <div style="width:50px;height:3px;background:#C8151B;margin-bottom:20px;"></div>
        <p style="color:#444;line-height:1.88;margin-bottom:12px;font-size:.97rem;">
          <?=htmlspecialchars($s['descripcion_corta'])?>
        </p>
        <p style="color:#666;line-height:1.85;font-size:.9rem;font-weight:300;margin-bottom:28px;">
          <?=htmlspecialchars($s['descripcion_larga'])?>
        </p>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <a href="/contacto?servicio=<?=urlencode($s['titulo'])?>" class="btn btn-r">
            <i class="fas fa-paper-plane"></i>Solicitar servicio
          </a>
          <a href="/planes" class="btn btn-outline">
            <i class="fas fa-star"></i>Ver planes
          </a>
        </div>
      </div>

    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- CTA -->
<section class="cta-s">
  <div class="container">
    <div class="cta-inn rv">
      <h2 class="h2" style="color:#fff;">¿No encontró lo que buscaba?</h2>
      <p style="color:rgba(255,255,255,.9);margin-top:16px;">Contáctenos y diseñaremos una solución personalizada para su empresa.</p>
      <div class="cta-btns" style="margin-top:36px;">
        <a href="/contacto" class="btn btn-dark"><i class="fas fa-comments"></i>Consulta gratuita</a>
        <a href="tel:+525556508420" class="btn btn-wh"><i class="fas fa-phone-alt"></i>01 55 5650 8420</a>
      </div>
    </div>
  </div>
</section>

<?php require_once SRC_PATH.'/views/partials/footer.php'; ?>
