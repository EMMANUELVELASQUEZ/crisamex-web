<!-- ═══ HERO ═══ -->
<section class="hero">
  <div class="hero-bg-img" id="hero-bg"></div>
  <div class="hero-ov1"></div><div class="hero-ov2"></div>
  <canvas id="pcv"></canvas>
  <div class="hero-vline v1"></div><div class="hero-vline v2"></div>
  <div class="hero-deco-num">1999</div>
  <div class="container">
    <div class="hero-content">
      <div class="hero-eyebrow">
        <div class="hero-eye-line"></div>
        <span class="hero-eye-txt">ISO 9001 · CNSNS · STPS · COFEPRIS · SCT — Desde 1999</span>
        <div class="hero-eye-line"></div>
      </div>
      <h1 class="h1">
        <span class="red">SEGURIDAD</span><br>
        <span class="stroke">RADIO</span>LÓGICA<br>
        <span style="font-size:.52em;letter-spacing:4px;color:rgba(255,255,255,.4)">ES NUESTRA MISIÓN</span>
      </h1>
      <p class="hero-desc" style="margin-top:20px;">
        Expertos mexicanos certificados con más de <strong style="color:#fff;font-weight:600;">25 años</strong> en Seguridad y Protección Radiológica a todo México y el extranjero.
      </p>
      <div class="hero-btns">
        <a href="/servicios" class="btn btn-r"><i class="fas fa-th-large"></i>Nuestros Servicios</a>
        <a href="/contacto"  class="btn btn-outline" style="border-color:rgba(255,255,255,.5);color:#fff;"><i class="fas fa-envelope"></i>Contáctenos</a>
      </div>
      <div class="hero-stats">
        <?php foreach($estadisticas as $s):
          $n=preg_replace('/[^0-9]/','', $s['numero']);
          $p=preg_match('/^\+/',$s['numero'])?'+':'';
        ?>
        <div class="hs">
          <span class="hs-n" data-target="<?=$n?>" data-prefix="<?=$p?>"><?=htmlspecialchars($s['numero'])?></span>
          <span class="hs-l"><?=htmlspecialchars($s['etiqueta'])?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="hero-scroll"><span>Scroll</span><div class="scroll-mouse"></div></div>
</section>

<!-- ═══ AVISO ROJO ═══ -->
<div class="aviso">
  <div class="container">
    <div class="aviso-i">
      <div class="av"><i class="fas fa-certificate"></i>ISO 9001-2008 Certificado</div><div class="av-s"></div>
      <div class="av"><i class="fas fa-atom"></i>Autorización CNSNS</div><div class="av-s"></div>
      <div class="av"><i class="fas fa-hard-hat"></i>Autorización STPS</div><div class="av-s"></div>
      <div class="av"><i class="fas fa-heartbeat"></i>Autorización COFEPRIS</div><div class="av-s"></div>
      <div class="av"><i class="fas fa-truck"></i>Autorización SCT</div>
    </div>
  </div>
</div>

<!-- ═══ QUIÉNES SOMOS — FONDO BLANCO ═══ -->
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
            src="https://images.unsplash.com/photo-1578496781197-b85385c46895?w=300&q=80&fit=crop"
            alt="Instrumentación nuclear">
          <div class="qs-badge"><strong>+25</strong><span>Años de<br>Experiencia</span></div>
          <div class="qs-bar"></div>
        </div>
      </div>
      <div class="rv-r">
        <div class="label lc"><i class="fas fa-radiation-alt"></i>Quiénes Somos</div>
        <h2 class="h2" style="font-size:clamp(2.2rem,4vw,3.8rem);color:#1a1a1a;">
          Control de Radiaciones<br>e <span class="red">Ingeniería</span>, S.A. de C.V.
        </h2>
        <div class="rule"></div>
        <p style="color:#444;line-height:1.88;margin-bottom:14px;">
          <strong style="color:#1a1a1a;">CRISAMEX</strong> es reconocido entre los órganos reguladores en materia de energía nuclear de nuestro país. Contamos con personal especializado y actualizado en todas las áreas de aplicación, infraestructura, tecnología y alianzas estratégicas.
        </p>
        <p style="color:#666;line-height:1.85;margin-bottom:24px;font-size:.93rem;font-weight:300;">
          Estamos comprometidos con nuestros clientes en ofrecer una excelente experiencia con alta calidad, garantizando cabalmente la operación segura de sus fuentes de radiación y el cumplimiento normativo requerido nacional e internacionalmente.
        </p>
        <ul class="check-list">
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Autorización ante CNSNS, STPS, COFEPRIS y SCT</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Certificación ISO 9001-2008 en Sistema de Gestión de Calidad</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Personal altamente especializado y certificado</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Cobertura en todo México y proyectos internacionales</span></li>
          <li><span class="ck"><i class="fas fa-check"></i></span><span style="color:#333;">Infraestructura y tecnología de primer nivel</span></li>
        </ul>
        <div style="display:flex;gap:14px;flex-wrap:wrap;">
          <a href="/quienes-somos" class="btn btn-r"><i class="fas fa-users"></i>Conocernos</a>
          <a href="/certificaciones" class="btn btn-outline"><i class="fas fa-certificate"></i>Certificaciones</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SERVICIOS — FONDO GRIS CLARO ═══ -->
<section class="sp" style="background:#f8f8f8;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px">
      <div class="label">Lo que ofrecemos</div>
      <h2 class="h2" style="color:#1a1a1a;">Servicios <span class="red">Especializados</span></h2>
      <p class="sub" style="margin-top:16px;color:#666;">Soluciones integrales en Seguridad y Protección Radiológica para empresas e instituciones en todo México y el extranjero.</p>
    </div>
    <?php
    $srvImgs=[
      'https://images.unsplash.com/photo-1581093577421-f561a654a353?w=700&q=80&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=700&q=80&fit=crop',
      'https://images.unsplash.com/photo-1518770660439-4636190af475?w=700&q=80&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=700&q=80&fit=crop',
      'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=700&q=80&fit=crop',
      'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=700&q=80&fit=crop',
    ];
    ?>
    <div class="srv-grid">
      <?php foreach($servicios as $i=>$s): ?>
      <div class="srv-card rv d<?=($i%3)+1?>">
        <div class="srv-card-img">
          <img src="<?=$srvImgs[$i%count($srvImgs)]?>" alt="<?=htmlspecialchars($s['titulo'])?>">
          <div class="srv-n">0<?=$i+1?></div>
        </div>
        <div class="srv-body">
          <div class="srv-ico"><i class="<?=htmlspecialchars($s['icono'])?>"></i></div>
          <h3 style="color:#1a1a1a;"><?=htmlspecialchars($s['titulo'])?></h3>
          <p style="color:#555;"><?=htmlspecialchars($s['descripcion_corta'])?></p>
          <a href="/servicios#<?=htmlspecialchars($s['slug'])?>" class="srv-link">Conocer más <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="srv-ln"></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="tc" style="margin-top:52px">
      <a href="/servicios" class="btn btn-r rv"><i class="fas fa-list"></i>Ver todos los servicios</a>
    </div>
  </div>
</section>

<!-- ═══ ESTADÍSTICAS — SIEMPRE OSCURO ═══ -->
<section class="stats-s">
  <div class="container">
    <div class="stats-grid">
      <?php foreach($estadisticas as $s):
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

<!-- ═══ COMPROMISO — FONDO BLANCO SPLIT ═══ -->
<section style="padding:0;background:#fff;">
  <div class="split-grid">
    <div class="split-img rv-l">
      <img src="https://images.unsplash.com/photo-1581093577421-f561a654a353?w=900&q=85&fit=crop"
           alt="Laboratorio de seguridad radiológica CRISAMEX">
      <div class="split-ov" style="background:linear-gradient(to right,transparent 50%,#fff);"></div>
      <div class="split-float">
        <div class="split-f-ico"><i class="fas fa-award"></i></div>
        <div class="split-f-txt">
          <strong style="color:#1a1a1a;">ISO 9001 Certificado</strong>
          <span style="color:#666;">Sistema de Gestión de Calidad</span>
        </div>
      </div>
    </div>
    <div class="split-cnt rv-r" style="background:#fff;">
      <div class="label lc"><i class="fas fa-handshake"></i>Nuestro Compromiso</div>
      <h2 class="h2" style="font-size:clamp(2rem,3.5vw,3.5rem);color:#1a1a1a;">
        Comprometidos<br>con <span class="red">Nuestros</span><br>Clientes
      </h2>
      <div class="rule"></div>
      <p style="color:#555;line-height:1.88;margin-bottom:14px;font-weight:300;">
        Ofrecemos una excelente experiencia con alta calidad siendo flexibles y responsables en cada punto de contacto, garantizando la operación segura de las fuentes de radiación y el cumplimiento normativo requerido nacional e internacionalmente.
      </p>
      <div class="comp-cards">
        <div class="cc"><div class="cc-ico"><i class="fas fa-shield-alt"></i></div><div><strong style="color:#1a1a1a;">Seguridad</strong><p style="color:#666;">Operación segura de fuentes de radiación garantizada</p></div></div>
        <div class="cc"><div class="cc-ico"><i class="fas fa-award"></i></div><div><strong style="color:#1a1a1a;">Calidad</strong><p style="color:#666;">ISO 9001 respalda cada proceso y servicio</p></div></div>
        <div class="cc"><div class="cc-ico"><i class="fas fa-balance-scale"></i></div><div><strong style="color:#1a1a1a;">Cumplimiento</strong><p style="color:#666;">Normatividad nacional e internacional cubierta</p></div></div>
        <div class="cc"><div class="cc-ico"><i class="fas fa-lightbulb"></i></div><div><strong style="color:#1a1a1a;">Innovación</strong><p style="color:#666;">Mejores prácticas y tecnología de vanguardia</p></div></div>
      </div>
      <div style="margin-top:32px;display:flex;gap:12px;flex-wrap:wrap;">
        <a href="/quienes-somos" class="btn btn-r"><i class="fas fa-arrow-right"></i>Conocer más</a>
        <a href="/contacto" class="btn btn-outline"><i class="fas fa-phone-alt"></i>Contactar</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ MISIÓN VISIÓN — FONDO GRIS ═══ -->
<section class="sp" style="background:#f8f8f8;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px">
      <div class="label">Nuestra Esencia</div>
      <h2 class="h2" style="color:#1a1a1a;">Misión, Visión y <span class="red">Valores</span></h2>
    </div>
    <div class="mv-grid">
      <div class="mv-c rv d1">
        <div class="mv-c-line"></div>
        <div class="mv-bg" style="color:rgba(0,0,0,.04);">01</div>
        <div class="mv-ico"><i class="fas fa-bullseye"></i></div>
        <h3 style="color:#1a1a1a;">Misión</h3>
        <p style="color:#555;">Proporcionar servicios integrales de seguridad y protección radiológica de la más alta calidad, garantizando el cumplimiento normativo y contribuyendo al uso pacífico y seguro de la energía nuclear en México.</p>
      </div>
      <div class="mv-c rv d2">
        <div class="mv-c-line"></div>
        <div class="mv-bg" style="color:rgba(0,0,0,.04);">02</div>
        <div class="mv-ico"><i class="fas fa-eye"></i></div>
        <h3 style="color:#1a1a1a;">Visión</h3>
        <p style="color:#555;">Ser la empresa líder en México en servicios de seguridad radiológica, reconocida por la excelencia de nuestros profesionales, la innovación tecnológica y el compromiso irrestricto con la seguridad de las personas.</p>
      </div>
      <div class="mv-c rv d3">
        <div class="mv-c-line"></div>
        <div class="mv-bg" style="color:rgba(0,0,0,.04);">03</div>
        <div class="mv-ico"><i class="fas fa-star"></i></div>
        <h3 style="color:#1a1a1a;">Valores</h3>
        <p style="color:#555;">Seguridad, Calidad, Confiabilidad, Compromiso, Innovación y Responsabilidad son los principios que guían cada decisión y servicio que ofrecemos a nuestros clientes en todo México.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ VALORES — FONDO BLANCO ═══ -->
<section class="sp" style="background:#fff;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:60px">
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

<!-- ═══ CTA ROJO ═══ -->
<section class="cta-s">
  <div class="container">
    <div class="cta-inn rv">
      <div class="label" style="justify-content:center;margin-bottom:18px;color:rgba(255,255,255,.9);"><i class="fas fa-radiation-alt"></i>¿Necesita asesoría?</div>
      <h2 class="h2" style="color:#fff;">¿Necesita asesoría en<br>Seguridad Radiológica?</h2>
      <p style="color:rgba(255,255,255,.9);margin-top:16px;margin-bottom:36px;">Nuestros expertos certificados están listos para atenderle. Contáctenos hoy mismo.</p>
      <div class="cta-btns">
        <a href="/contacto" class="btn btn-dark"><i class="fas fa-paper-plane"></i>Solicitar asesoría gratuita</a>
        <a href="tel:+525556508420" class="btn btn-wh"><i class="fas fa-phone-alt"></i>01 55 5650 8420</a>
        <a href="/planes" class="btn" style="background:transparent;border:2px solid rgba(255,255,255,.5);color:#fff;"><i class="fas fa-star"></i>Ver Planes</a>
      </div>
    </div>
  </div>
</section>
