<?php
$pageTitle='Planes y Precios — CRISAMEX';
$pageDesc='Planes de licencia para servicios de Seguridad Radiológica. Básico $2,500/mes, Profesional $6,500/mes, Enterprise $15,000/mes.';
$planes=Database::fetchAll("SELECT * FROM planes WHERE activo=1 ORDER BY orden");
require_once SRC_PATH.'/views/partials/header.php';
?>

<section class="ph">
  <div class="ph-glow"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <nav class="bc"><a href="/">Inicio</a><span class="sep"><i class="fas fa-chevron-right"></i></span><span>Planes</span></nav>
    <h1 class="h1" style="color:#fff;font-size:clamp(3rem,7vw,7rem);">Planes y<br><span style="color:var(--r);">Precios</span></h1>
    <p style="color:rgba(255,255,255,.72);margin-top:14px;">Elige el plan que mejor se adapte a las necesidades de seguridad radiológica de tu empresa</p>
  </div>
</section>

<!-- TOGGLE MENSUAL/ANUAL -->
<div style="background:#f8f8f8;border-bottom:1px solid #e8e8e8;padding:24px 0;text-align:center;">
  <div style="display:inline-flex;align-items:center;gap:14px;background:#fff;border:1.5px solid #e0e0e0;padding:8px 16px;border-radius:50px;box-shadow:0 2px 8px rgba(0,0,0,.06);">
    <span id="lbl-m" style="font-size:.85rem;font-weight:600;color:#1a1a1a;">Mensual</span>
    <div id="toggle-price" onclick="togglePrice()" style="width:48px;height:26px;background:#e0e0e0;border-radius:13px;cursor:pointer;position:relative;transition:background .3s;">
      <div id="toggle-dot" style="position:absolute;top:3px;left:3px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.2);transition:left .3s;"></div>
    </div>
    <span id="lbl-a" style="font-size:.85rem;font-weight:600;color:#999;">Anual <span style="background:#C8151B;color:#fff;font-size:.65rem;padding:2px 7px;border-radius:10px;margin-left:4px;">-17%</span></span>
  </div>
</div>

<!-- PLANES -->
<section style="background:#fff;padding:80px 0;">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1100px;margin:0 auto;">
      <?php foreach($planes as $i=>$p):
        $features=json_decode($p['features']??'[]',true)?:[];
        $popular=$p['destacado'];
      ?>
      <div style="background:#fff;border:2px solid <?=$popular?'#C8151B':'#e0e0e0'?>;position:relative;transition:all .3s;overflow:hidden;"
           onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 20px 60px rgba(0,0,0,.12)'"
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

        <?php if($popular): ?>
        <div style="background:#C8151B;color:#fff;text-align:center;padding:8px;font-family:'Bebas Neue',sans-serif;font-size:.85rem;letter-spacing:2px;">
          ★ MÁS POPULAR
        </div>
        <?php endif; ?>

        <!-- Cabecera del plan -->
        <div style="background:<?=$popular?'#C8151B':'#1a1a1a'?>;padding:32px 28px 28px;">
          <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;margin-bottom:16px;">
            <i class="<?=htmlspecialchars($p['icono'])?>"></i>
          </div>
          <div style="font-family:'Bebas Neue',sans-serif;font-size:1.5rem;letter-spacing:2px;color:#fff;margin-bottom:4px;">
            <?=htmlspecialchars($p['nombre'])?>
          </div>
          <div style="font-size:.8rem;color:rgba(255,255,255,.65);font-weight:300;margin-bottom:20px;">
            <?=htmlspecialchars($p['descripcion'])?>
          </div>
          <div id="pm-<?=$p['id']?>" style="display:flex;align-items:baseline;gap:4px;">
            <span style="font-family:'Bebas Neue',sans-serif;font-size:3rem;color:#fff;letter-spacing:1px;">
              $<?=number_format($p['precio_mensual'],0,'.',',')?>
            </span>
            <span style="font-size:.8rem;color:rgba(255,255,255,.6);">/mes</span>
          </div>
          <div id="pa-<?=$p['id']?>" style="display:none;align-items:baseline;gap:4px;">
            <span style="font-family:'Bebas Neue',sans-serif;font-size:3rem;color:#fff;letter-spacing:1px;">
              $<?=number_format($p['precio_anual'],0,'.',',')?>
            </span>
            <span style="font-size:.8rem;color:rgba(255,255,255,.6);">/año</span>
          </div>
        </div>

        <!-- Features -->
        <div style="padding:28px;">
          <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
            <?php foreach($features as $f): ?>
            <li style="display:flex;align-items:center;gap:10px;font-size:.87rem;color:#333;">
              <i class="fas fa-check-circle" style="color:#C8151B;flex-shrink:0;font-size:.85rem;"></i>
              <?=htmlspecialchars($f)?>
            </li>
            <?php endforeach; ?>
          </ul>
          <a href="/portal/registro?plan=<?=htmlspecialchars($p['slug'])?>"
             style="display:block;text-align:center;padding:14px;background:<?=$popular?'#C8151B':'#1a1a1a'?>;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:1rem;letter-spacing:2px;text-decoration:none;transition:all .3s;"
             onmouseover="this.style.opacity='.85';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.opacity='1';this.style.transform='translateY(0)'">
            <i class="fas fa-rocket"></i> EMPEZAR AHORA
          </a>
          <a href="/contacto" style="display:block;text-align:center;padding:10px;color:#888;font-size:.8rem;margin-top:10px;text-decoration:none;">
            ¿Tienes preguntas? Contáctanos →
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- TABLA COMPARATIVA -->
    <div style="margin-top:80px;">
      <div class="tc rv" style="margin-bottom:40px;">
        <div class="label">Detalle completo</div>
        <h2 class="h2" style="color:#1a1a1a;">Comparativa de <span class="red">Planes</span></h2>
      </div>
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
          <thead>
            <tr style="background:#1a1a1a;">
              <th style="padding:16px 20px;text-align:left;color:#fff;font-family:'Bebas Neue',sans-serif;letter-spacing:1px;width:35%;">Característica</th>
              <th style="padding:16px 20px;text-align:center;color:#fff;font-family:'Bebas Neue',sans-serif;letter-spacing:1px;">Básico</th>
              <th style="padding:16px 20px;text-align:center;color:#fff;background:#C8151B;font-family:'Bebas Neue',sans-serif;letter-spacing:1px;">Profesional</th>
              <th style="padding:16px 20px;text-align:center;color:#fff;font-family:'Bebas Neue',sans-serif;letter-spacing:1px;">Enterprise</th>
            </tr>
          </thead>
          <tbody>
            <?php $rows=[
              ['Asesoría radiológica','Básica','Completa','Completa + Dedicada'],
              ['Trámites regulatorios','Solo CNSNS','CNSNS, STPS, COFEPRIS','Todos + SCT'],
              ['Auditorías anuales','1','3','Ilimitadas'],
              ['Capacitación personal','No incluida','Incluida','Incluida + personalizada'],
              ['Instrumentación nuclear','No incluida','Incluida','Incluida'],
              ['Transporte material radiactivo','No','No','Sí'],
              ['Servicio en sitio','No','No','Sí'],
              ['Usuarios del portal','2','5','Ilimitados'],
              ['Reportes mensuales','10','Ilimitados','Ilimitados + personalizados'],
              ['Soporte','Email','Prioritario 48h','24/7 Dedicado'],
              ['Gestor de cuenta','No','No','Sí'],
              ['API de integración','No','No','Sí'],
            ]; ?>
            <?php foreach($rows as $j=>$row): ?>
            <tr style="background:<?=$j%2===0?'#fff':'#f8f8f8'?>;border-bottom:1px solid #e8e8e8;">
              <td style="padding:14px 20px;font-weight:600;color:#1a1a1a;"><?=$row[0]?></td>
              <td style="padding:14px 20px;text-align:center;color:#555;"><?=$row[1]?></td>
              <td style="padding:14px 20px;text-align:center;color:#C8151B;font-weight:600;background:rgba(200,21,27,.04);"><?=$row[2]?></td>
              <td style="padding:14px 20px;text-align:center;color:#555;"><?=$row[3]?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="background:#1a1a1a;">
              <td style="padding:16px 20px;color:#fff;font-family:'Bebas Neue',sans-serif;letter-spacing:1px;">Precio mensual</td>
              <td style="padding:16px 20px;text-align:center;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:1.1rem;">$2,500</td>
              <td style="padding:16px 20px;text-align:center;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:1.3rem;background:#C8151B;">$6,500</td>
              <td style="padding:16px 20px;text-align:center;color:#fff;font-family:'Bebas Neue',sans-serif;font-size:1.1rem;">$15,000</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- FAQ -->
    <div style="margin-top:80px;max-width:780px;margin-left:auto;margin-right:auto;">
      <div class="tc rv" style="margin-bottom:40px;">
        <div class="label">Preguntas frecuentes</div>
        <h2 class="h2" style="color:#1a1a1a;">Preguntas <span class="red">Frecuentes</span></h2>
      </div>
      <?php $faqs=[
        ['¿Puedo cambiar de plan en cualquier momento?','Sí, puedes actualizar o cambiar tu plan cuando lo necesites. El cambio aplica desde el siguiente periodo de facturación y un asesor te contactará para coordinar la transición.'],
        ['¿Qué incluye el portal de clientes?','Acceso a tus reportes y documentos, mensajería directa con CRISAMEX, notificaciones de vencimientos, seguimiento de auditorías y gestión de tu licencia.'],
        ['¿Cómo se realiza el pago?','El pago se coordina directamente con nuestro equipo de ventas. Aceptamos transferencia bancaria, cheque y facturación electrónica con todos los requisitos fiscales.'],
        ['¿Tienen cobertura fuera de la Ciudad de México?','Sí, tenemos cobertura en toda la República Mexicana y experiencia en proyectos internacionales en Estados Unidos y América Latina.'],
        ['¿Cuánto tiempo tarda en activarse mi licencia?','Una vez confirmado el pago, tu licencia se activa en 24 horas hábiles y recibirás un correo de confirmación con acceso al portal.'],
      ]; foreach($faqs as $k=>$faq): ?>
      <div style="border:1px solid #e0e0e0;margin-bottom:12px;overflow:hidden;">
        <div onclick="toggleFaq(<?=$k?>)" style="padding:18px 22px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;background:#fff;" onmouseover="this.style.background='#f8f8f8'" onmouseout="this.style.background='#fff'">
          <span style="font-weight:600;color:#1a1a1a;font-size:.93rem;"><?=$faq[0]?></span>
          <i id="faq-ico-<?=$k?>" class="fas fa-plus" style="color:#C8151B;font-size:.85rem;transition:transform .3s;flex-shrink:0;margin-left:16px;"></i>
        </div>
        <div id="faq-<?=$k?>" style="display:none;padding:0 22px 18px;color:#555;font-size:.9rem;line-height:1.75;background:#fff;">
          <?=$faq[1]?>
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
      <h2 class="h2" style="color:#fff;">¿No sabes qué plan elegir?</h2>
      <p style="color:rgba(255,255,255,.9);margin-top:16px;">Uno de nuestros expertos te ayudará a elegir la mejor opción para tu empresa sin compromiso.</p>
      <div class="cta-btns" style="margin-top:36px;">
        <a href="/contacto" class="btn btn-dark"><i class="fas fa-comments"></i>Asesoría gratuita</a>
        <a href="tel:+525556508420" class="btn btn-wh"><i class="fas fa-phone-alt"></i>01 55 5650 8420</a>
      </div>
    </div>
  </div>
</section>

<script>
var anual=false;
function togglePrice(){
  anual=!anual;
  document.getElementById('toggle-dot').style.left=anual?'25px':'3px';
  document.getElementById('toggle-price').style.background=anual?'#C8151B':'#e0e0e0';
  document.getElementById('lbl-m').style.color=anual?'#999':'#1a1a1a';
  document.getElementById('lbl-a').style.color=anual?'#1a1a1a':'#999';
  document.querySelectorAll('[id^="pm-"]').forEach(function(e){e.style.display=anual?'none':'flex';});
  document.querySelectorAll('[id^="pa-"]').forEach(function(e){e.style.display=anual?'flex':'none';});
}
function toggleFaq(k){
  var body=document.getElementById('faq-'+k);
  var ico=document.getElementById('faq-ico-'+k);
  var open=body.style.display==='block';
  body.style.display=open?'none':'block';
  ico.className=open?'fas fa-plus':'fas fa-minus';
  ico.style.transform=open?'rotate(0)':'rotate(180deg)';
}
</script>
<?php require_once SRC_PATH.'/views/partials/footer.php'; ?>
