<?php
$pageTitle='Servicios — CRISAMEX | Seguridad Radiológica';
$pageDesc='Calibración de equipos, pruebas de fuga, mantenimiento de contenedores, verificación de portales y rayos X, levantamiento de niveles y trámites CNSNS.';
$servicios=Database::fetchAll("SELECT * FROM servicios WHERE activo=1 ORDER BY orden");
require_once SRC_PATH.'/views/partials/header.php';

$data=[
  'calibracion-equipos'=>[
    'img'=>'https://images.unsplash.com/photo-1518770660439-4636190af475?w=900&q=85&fit=crop&auto=format',
    'precio'=>'$3,500',
    'puntos'=>[
      'Nos especializamos en detectores Geiger-Müller (GM)',
      'Calibración en laboratorios propios con fuentes de referencia',
      'Ajuste preciso de cada instrumento con equipos certificados',
      'Calibración anual para equipos portátiles y manuales',
      'Emisión de certificado de calibración oficial',
    ]
  ],
  'prueba-de-fuga'=>[
    'img'=>'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=900&q=85&fit=crop&auto=format',
    'precio'=>'$2,500',
    'puntos'=>[
      'Procedimiento con cotonete sobre superficie de la fuente',
      'Análisis del frotis en equipo electrónico monocanal',
      'Detección de isótopos como el Americio',
      'Identificación de fugas en la cápsula de la fuente',
      'Emisión de Certificado de Prueba de Fuga si niveles son óptimos',
    ]
  ],
  'mantenimiento-contenedores'=>[
    'img'=>'https://images.unsplash.com/photo-1581093577421-f561a654a353?w=900&q=85&fit=crop&auto=format',
    'precio'=>'$15,000',
    'puntos'=>[
      'Contenedores de plomo recubiertos con acero',
      'Prevención de oxidación y corrosión del acero',
      'Revisión estructural completa del sistema de blindaje',
      'Mantenimiento preventivo y correctivo',
      'Garantía de blindaje impenetrable y seguro',
    ]
  ],
  'verificacion-portales-rayos-x'=>[
    'img'=>'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=900&q=85&fit=crop&auto=format',
    'precio'=>'$12,000',
    'puntos'=>[
      'Portales fijos: detección de personal, vehículos y carga',
      'Sistemas de banda y túnel en aeropuertos y aduanas',
      'Radiografía industrial y Ensayos No Destructivos (END)',
      'Equipos médicos: mastógrafos, dentales y convencionales',
      'Permisos de la Secretaría de Energía y CNS',
    ]
  ],
  'levantamiento-de-niveles'=>[
    'img'=>'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=900&q=85&fit=crop&auto=format',
    'precio'=>'Cotizar',
    'puntos'=>[
      'Detectores portátiles Geiger-Müller propios',
      'Recorrido completo de sus instalaciones',
      'Verificación de efectividad del blindaje de plomo y acero',
      'Detección de radiación fuera de la fuente',
      'Reporte de cumplimiento de límites de exposición laboral',
    ]
  ],
  'capacitacion-tramites-cnsns'=>[
    'img'=>'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=900&q=85&fit=crop&auto=format',
    'precio'=>'$13,000',
    'puntos'=>[
      'Autorización para servicios a terceros — Secretaría de Energía',
      'Trámites ante CNSNS para movimiento de fuentes',
      'Permisos de manejo legal de material radiactivo',
      'Capacitación técnica para su personal',
      'Asesoría en cumplimiento normativo radiológico',
    ]
  ],
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
  $slug = $s['slug'];
  $extra = $data[$slug] ?? ['img'=>'','precio'=>'Cotizar','puntos'=>[]];
  $img = $extra['img'];
  $precio = $extra['precio'];
  $puntos = $extra['puntos'];
  $bg = $even ? '#fff' : '#f8f8f8';
?>
<section id="<?=htmlspecialchars($slug)?>"
  style="padding:90px 0;background:<?=$bg?>;border-bottom:1px solid #e8e8e8;">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;">

      <!-- IMAGEN -->
      <div style="<?=!$even?'order:2':''?>">
        <div style="position:relative;overflow:hidden;height:460px;box-shadow:0 8px 40px rgba(0,0,0,.12);background:#1a1a2e;">
          <?php if($img): ?>
          <img src="<?=$img?>"
               alt="<?=htmlspecialchars($s['titulo'])?> — CRISAMEX"
               loading="lazy"
               style="width:100%;height:100%;object-fit:cover;display:block;"
               onerror="this.style.display='none'">
          <?php endif; ?>
          <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.4) 0%,rgba(0,0,0,.1) 60%,transparent 100%);pointer-events:none;"></div>
          <!-- Número decorativo -->
          <div style="position:absolute;bottom:16px;right:20px;font-family:'Bebas Neue',sans-serif;font-size:6rem;color:rgba(255,255,255,.08);line-height:1;pointer-events:none;">0<?=$i+1?></div>
          <!-- Ícono + precio -->
          <div style="position:absolute;top:20px;left:20px;display:flex;align-items:center;gap:12px;">
            <div style="width:52px;height:52px;background:#C8151B;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;box-shadow:0 4px 20px rgba(200,21,27,.5);">
              <i class="<?=htmlspecialchars($s['icono'])?>"></i>
            </div>
            <?php if($precio!=='Cotizar'): ?>
            <div style="background:rgba(0,0,0,.7);backdrop-filter:blur(8px);padding:6px 14px;border-left:3px solid #C8151B;">
              <span style="font-family:'Bebas Neue',sans-serif;font-size:1.3rem;color:#fff;letter-spacing:1px;"><?=$precio?></span>
              <span style="font-size:.65rem;color:rgba(255,255,255,.6);display:block;letter-spacing:1px;text-transform:uppercase;">por servicio</span>
            </div>
            <?php else: ?>
            <div style="background:rgba(0,0,0,.7);backdrop-filter:blur(8px);padding:6px 14px;border-left:3px solid #C8151B;">
              <span style="font-family:'Bebas Neue',sans-serif;font-size:1rem;color:#fff;letter-spacing:1px;">Precio a Cotizar</span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- TEXTO -->
      <div style="<?=!$even?'order:1':''?>">
        <div style="font-size:.67rem;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:#C8151B;margin-bottom:8px;font-family:'DM Mono',monospace;">
          Servicio <?=str_pad($i+1,2,'0',STR_PAD_LEFT)?>
        </div>
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:clamp(1.9rem,3vw,2.8rem);color:#1a1a1a;letter-spacing:2px;line-height:1.05;margin-bottom:12px;">
          <?=htmlspecialchars($s['titulo'])?>
        </h2>
        <div style="width:48px;height:3px;background:#C8151B;margin-bottom:18px;"></div>
        <p style="color:#444;line-height:1.88;margin-bottom:20px;font-size:.96rem;">
          <?=htmlspecialchars($s['descripcion_corta'])?>
        </p>

        <!-- Puntos clave -->
        <?php if(!empty($puntos)): ?>
        <ul style="list-style:none;margin-bottom:28px;display:flex;flex-direction:column;gap:9px;">
          <?php foreach($puntos as $punto): ?>
          <li style="display:flex;align-items:flex-start;gap:10px;font-size:.88rem;color:#444;line-height:1.6;">
            <span style="width:22px;height:22px;min-width:22px;background:#C8151B;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.5rem;margin-top:1px;">
              <i class="fas fa-check"></i>
            </span>
            <?=htmlspecialchars($punto)?>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <!-- Precio destacado -->
        <div style="background:#f8f8f8;border:1px solid #e0e0e0;border-left:4px solid #C8151B;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
          <i class="fas fa-tag" style="color:#C8151B;font-size:1.1rem;"></i>
          <div>
            <div style="font-size:.65rem;color:#999;text-transform:uppercase;letter-spacing:1.5px;font-family:'DM Mono',monospace;">Precio de referencia</div>
            <div style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;color:#1a1a1a;letter-spacing:1px;"><?=$precio?> <span style="font-size:.85rem;color:#999;font-family:'DM Sans',sans-serif;font-weight:400;letter-spacing:0;">MXN + IVA</span></div>
          </div>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <a href="/contacto?servicio=<?=urlencode($s['titulo'])?>" class="btn btn-r">
            <i class="fas fa-paper-plane"></i>Solicitar servicio
          </a>
          <a href="/contacto" class="btn btn-outline">
            <i class="fas fa-phone-alt"></i>Cotizar
          </a>
        </div>
      </div>

    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- RESUMEN PRECIOS -->
<section style="background:#1a1a1a;padding:80px 0;border-top:3px solid #C8151B;">
  <div class="container">
    <div class="tc rv" style="margin-bottom:48px;">
      <div class="label" style="color:rgba(255,255,255,.7);"><i class="fas fa-tag"></i>Lista de precios</div>
      <h2 class="h2" style="color:#fff;">Tarifas de <span style="color:#C8151B;">Referencia</span></h2>
      <p style="color:rgba(255,255,255,.55);margin-top:12px;font-size:.9rem;">Precios en MXN + IVA. Sujetos a condiciones específicas de cada proyecto.</p>
    </div>
    <div style="max-width:800px;margin:0 auto;">
      <?php foreach([
        ['fas fa-tachometer-alt','Calibración de equipos','$3,500'],
        ['fas fa-microscope','Pruebas de fuga (Frotis)','$2,500'],
        ['fas fa-shield-alt','Mantenimiento a contenedores de fuentes radiactivas','$15,000'],
        ['fas fa-radiation','Verificación de portales y equipos de rayos X','$12,000'],
        ['fas fa-chart-area','Levantamiento de niveles','Cotizar'],
        ['fas fa-file-alt','Permisos ante la CNSNS','$13,000'],
      ] as $k=>[$ico,$nombre,$precio]): ?>
      <div style="display:flex;align-items:center;gap:16px;padding:16px 20px;background:<?=$k%2===0?'rgba(255,255,255,.04)':'rgba(255,255,255,.02)'?>;border-bottom:1px solid rgba(255,255,255,.07);transition:background .2s;"
           onmouseover="this.style.background='rgba(200,21,27,.08)'"
           onmouseout="this.style.background='<?=$k%2===0?'rgba(255,255,255,.04)':'rgba(255,255,255,.02)'?>'">
        <div style="width:40px;height:40px;min-width:40px;background:#C8151B;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:#fff;">
          <i class="<?=$ico?>"></i>
        </div>
        <span style="flex:1;color:rgba(255,255,255,.8);font-size:.92rem;"><?=$nombre?></span>
        <span style="font-family:'Bebas Neue',sans-serif;font-size:1.3rem;color:#fff;letter-spacing:1px;white-space:nowrap;"><?=$precio?></span>
      </div>
      <?php endforeach; ?>
      <p style="text-align:center;margin-top:20px;font-size:.78rem;color:rgba(255,255,255,.3);">* Precios en pesos mexicanos. No incluyen IVA. Sujetos a cotización específica.</p>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-s">
  <div class="container">
    <div class="cta-inn rv">
      <h2 class="h2" style="color:#fff;">¿Necesita más información?</h2>
      <p style="color:rgba(255,255,255,.9);margin-top:16px;">Contáctenos para recibir una cotización personalizada sin compromiso.</p>
      <div class="cta-btns" style="margin-top:36px;">
        <a href="/contacto" class="btn btn-dark"><i class="fas fa-paper-plane"></i>Solicitar cotización</a>
        <a href="tel:+525556508420" class="btn btn-wh"><i class="fas fa-phone-alt"></i>01 55 5650 8420</a>
      </div>
    </div>
  </div>
</section>

<?php require_once SRC_PATH.'/views/partials/footer.php'; ?>
