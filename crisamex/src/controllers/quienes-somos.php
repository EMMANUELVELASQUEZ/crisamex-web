<?php
$pageTitle='Quiénes Somos — CRISAMEX';
$pageDesc='Conoce al equipo de expertos certificados de CRISAMEX con 25 años en Seguridad Radiológica.';
$equipo = Database::fetchAll("SELECT * FROM equipo WHERE activo=1 ORDER BY orden");
$valores= Database::fetchAll("SELECT * FROM valores WHERE activo=1 ORDER BY orden");
$stats  = Database::fetchAll("SELECT * FROM estadisticas WHERE activo=1 ORDER BY orden");
require_once SRC_PATH.'/views/partials/header.php';

$teamPhotos=[
  'https://images.unsplash.com/photo-1622902046580-2b47f47f5471?w=500&q=80&fit=crop&crop=face',
  'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=500&q=80&fit=crop&crop=face',
  'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=500&q=80&fit=crop&crop=face',
  'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=500&q=80&fit=crop&crop=face',
];
$placeholders=[
  ['Dr. Roberto Sánchez','Director General','Físico nuclear con más de 25 años de experiencia en seguridad radiológica. Doctor en Física por la UNAM y certificado por organismos internacionales.'],
  ['Ing. María López','Directora Técnica','Ingeniera nuclear certificada con especialidad en protección radiológica industrial y médica. Auditora líder ISO 9001.'],
  ['Lic. Carlos Mendoza','Supervisor Radiológico Senior','Supervisor radiológico con 15 años de experiencia en plantas industriales, hospitales y centros de investigación.'],
  ['Ing. Ana García','Especialista en Instrumentación','Experta en calibración y mantenimiento de equipos de detección de radiación. Certificada por fabricantes internacionales.'],
];
?>

<!-- PAGE HEADER -->
<section class="ph">
  <div class="ph-glow"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <nav class="bc"><a href="/">Inicio</a><span class="sep"><i class="fas fa-chevron-right"></i></span><span>Quiénes Somos</span></nav>
    <h1 class="h1" style="color:#fff;font-size:clamp(3rem,7vw,7rem);">Quiénes<br><span style="color:var(--r);">Somos</span></h1>
    <p style="color:rgba(255,255,255,.72);margin-top:14px;">Conoce nuestra historia, misión y el equipo de expertos que respalda tu seguridad radiológica desde 1999</p>
  </div>
</section>

<!-- EMPRESA — BLANCO -->
<section class="sp" style="background:#fff;">
  <div class="container">
    <div class="qs-grid">
      <div class="rv-l">
        <div class="qs-imgs">
          <img class="qs-main"
            src="https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&q=85&fit=crop"
            alt="Laboratorio CRISAMEX"
            style="width:100%;height:490px;object-fit:cover;box-shadow:0 8px 40px rgba(0,0,0,.12);">
          <img class="qs-sm"
            src="https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=300&q=80&fit=crop"
            alt="Instrumentación nuclear CRISAMEX">
          <div class="qs-badge"><strong>+25</strong><span>Años de<br>Experiencia</span></div>
          <div class="qs-bar"></div>
        </div>
      </div>
      <div class="rv-r">
        <div class="label lc"><i class="fas fa-building"></i>Nuestra Empresa</div>
        <h2 class="h2" style="color:#1a1a1a;font-size:clamp(2rem,3.5vw,3.2rem);">
          Control de Radiaciones<br>e <span class="red">Ingeniería</span>, S.A. de C.V.
        </h2>
        <div class="rule"></div>
        <p style="color:#444;line-height:1.88;margin-bottom:14px;">
          Somos un equipo de <strong style="color:#1a1a1a;font-weight:600;">expertos mexicanos certificados</strong> con más de 25 años de experiencia en ofrecer servicios integrales en Seguridad y Protección Radiológica, dando soporte en los usos pacíficos de la energía nuclear y radiaciones ionizantes a todo México y al extranjero.
        </p>
        <p style="color:#666;line-height:1.85;font-size:.93rem;font-weight:300;margin-bottom:24px;">
          <strong style="color:#333;">CRISAMEX</strong> es reconocido entre los órganos reguladores en materia de energía nuclear de nuestro país, contando con personal especializado y actualizado en todas las áreas de aplicación, infraestructura, tecnología y alianzas estratégicas.
        </p>
        <ul class="check-list">
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Autorización ante CNSNS, STPS, COFEPRIS y SCT</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Certificación ISO 9001-2008 en Sistema de Gestión de Calidad</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Personal altamente especializado y actualizado</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Cobertura en todo México y proyectos internacionales</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Infraestructura y tecnología de primer nivel</span></li>
        </ul>
        <a href="/contacto" class="btn btn-r"><i class="fas fa-paper-plane"></i>Contáctenos</a>
      </div>
    </div>
  </div>
</section>

<!-- MISIÓN VISIÓN — GRIS -->
<section class="sp" style="background:#f8f8f8;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px;">
      <div class="label">Nuestra Esencia</div>
      <h2 class="h2" style="color:#1a1a1a;">Misión, Visión y <span class="red">Compromiso</span></h2>
    </div>
    <div class="mv-grid">
      <?php foreach([
        ['fas fa-bullseye','Misión','Proporcionar servicios integrales de seguridad y protección radiológica de la más alta calidad, garantizando el cumplimiento normativo de nuestros clientes y contribuyendo al uso pacífico y seguro de la energía nuclear en México y el mundo.','01'],
        ['fas fa-eye','Visión','Ser la empresa líder en México en servicios de seguridad radiológica, reconocida por la excelencia de nuestros profesionales, la innovación tecnológica y el compromiso irrestricto con la seguridad de las personas y el medio ambiente.','02'],
        ['fas fa-handshake','Compromiso','Ofrecer una excelente experiencia con alta calidad siendo flexibles y responsables en cada punto de contacto, garantizando la operación segura de las fuentes de radiación y el cumplimiento normativo nacional e internacional.','03'],
      ] as $k=>[$ico,$titulo,$texto,$num]): ?>
      <div class="mv-c rv d<?=$k+1?>">
        <div class="mv-c-line"></div>
        <div class="mv-bg" style="color:rgba(0,0,0,.04);"><?=$num?></div>
        <div class="mv-ico"><i class="<?=$ico?>"></i></div>
        <h3 style="color:#1a1a1a;"><?=$titulo?></h3>
        <p style="color:#555;"><?=$texto?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ESTADÍSTICAS -->
<section class="stats-s">
  <div class="container">
    <div class="stats-grid">
      <?php foreach($stats as $s):
        $n=preg_replace('/[^0-9]/','', $s['numero']);
        $p=preg_match('/^\+/',$s['numero'])?'+':'';
      ?>
      <div class="st-blk rv">
        <i class="<?=htmlspecialchars($s['icono'])?> st-ico"></i>
        <span class="st-num" data-target="<?=$n?>" data-prefix="<?=$p?>"><?=htmlspecialchars($s['numero'])?></span>
        <span class="st-lbl"><?=htmlspecialchars($s['etiqueta'])?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- VALORES — BLANCO -->
<section class="sp" style="background:#fff;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px;">
      <div class="label">Lo que nos define</div>
      <h2 class="h2" style="color:#1a1a1a;">Nuestros <span class="red">Valores</span></h2>
    </div>
    <div class="val-grid">
      <?php foreach($valores as $i=>$v): ?>
      <div class="val-c rv d<?=($i%3)+1?>">
        <div class="val-ico"><i class="<?=htmlspecialchars($v['icono'])?>"></i></div>
        <h3 style="color:#1a1a1a;"><?=htmlspecialchars($v['titulo'])?></h3>
        <p style="color:#555;"><?=htmlspecialchars($v['descripcion'])?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- EQUIPO — GRIS -->
<section class="sp" style="background:#f8f8f8;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px;">
      <div class="label">Nuestro Equipo</div>
      <h2 class="h2" style="color:#1a1a1a;">Expertos que nos <span class="red">Respaldan</span></h2>
      <p class="sub" style="margin-top:16px;color:#666;">Profesionales certificados y actualizados en todas las áreas de la seguridad radiológica.</p>
    </div>
    <div class="eq-grid">
      <?php
      $lista = !empty($equipo) ? $equipo : array_map(function($p,$i){ return ['nombre'=>$p[0],'puesto'=>$p[1],'descripcion'=>$p[2],'foto'=>'','linkedin'=>'']; }, $placeholders, array_keys($placeholders));
      foreach($lista as $i=>$m):
        $foto = !empty($m['foto']) ? '/uploads/'.$m['foto'] : $teamPhotos[$i % count($teamPhotos)];
      ?>
      <div class="eq-c rv d<?=($i%4)+1?>">
        <div class="eq-img">
          <img src="<?=$foto?>"
               alt="<?=htmlspecialchars($m['nombre'])?>"
               loading="lazy"
               style="width:100%;height:220px;object-fit:cover;object-position:top;"
               onerror="this.parentElement.innerHTML='<div style=\'height:220px;background:#f2f2f2;display:flex;align-items:center;justify-content:center;font-family:Bebas Neue,sans-serif;font-size:4rem;color:#C8151B;\'><?=strtoupper(substr($m['nombre'],0,1))?></div>'">
          <div class="eq-ov"></div>
        </div>
        <div class="eq-info" style="background:#fff;padding:20px 18px;">
          <h3 style="color:#1a1a1a;"><?=htmlspecialchars($m['nombre'])?></h3>
          <div class="eq-puesto" style="color:#C8151B;"><?=htmlspecialchars($m['puesto'])?></div>
          <p style="color:#555;font-size:.82rem;line-height:1.65;margin-top:8px;"><?=htmlspecialchars($m['descripcion'])?></p>
          <?php if(!empty($m['linkedin'])): ?>
          <a href="<?=htmlspecialchars($m['linkedin'])?>" target="_blank" style="color:#0A66C2;font-size:.78rem;display:inline-flex;align-items:center;gap:5px;margin-top:10px;">
            <i class="fab fa-linkedin"></i>LinkedIn
          </a>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-s">
  <div class="container">
    <div class="cta-inn rv">
      <h2 class="h2" style="color:#fff;">¿Listo para trabajar con<br>los <span style="text-decoration:underline;text-underline-offset:6px;">mejores?</span></h2>
      <p style="color:rgba(255,255,255,.9);margin-top:16px;">Uno de nuestros especialistas le atenderá a la brevedad posible.</p>
      <div class="cta-btns" style="margin-top:36px;">
        <a href="/contacto" class="btn btn-dark"><i class="fas fa-arrow-right"></i>Solicitar información</a>
        <a href="/planes" class="btn btn-wh"><i class="fas fa-star"></i>Ver planes</a>
      </div>
    </div>
  </div>
</section>

<?php require_once SRC_PATH.'/views/partials/footer.php'; ?>
