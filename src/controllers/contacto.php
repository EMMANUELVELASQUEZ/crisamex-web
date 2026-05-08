<?php
$pageTitle='Contacto — CRISAMEX';
$pageDesc='Contáctenos para solicitar asesoría en seguridad radiológica. Tel: 01 55 5650 8420. contacto@crisamex.com.';
$config=[];
$cfgRows=Database::fetchAll("SELECT clave,valor FROM site_config");
foreach($cfgRows as $r) $config[$r['clave']]=$r['valor'];
$servicios=Database::fetchAll("SELECT titulo FROM servicios WHERE activo=1 ORDER BY orden");
$srvPre=htmlspecialchars($_GET['servicio']??'');
require_once SRC_PATH.'/views/partials/header.php';
?>

<section class="ph">
  <div class="ph-glow"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <nav class="bc"><a href="/">Inicio</a><span class="sep"><i class="fas fa-chevron-right"></i></span><span>Contacto</span></nav>
    <h1 class="h1" style="color:#fff;font-size:clamp(3rem,7vw,7rem);">Contác<span style="color:var(--r);">tenos</span></h1>
    <p style="color:rgba(255,255,255,.72);margin-top:14px;">Estamos listos para atenderle. Nuestros expertos responden en menos de 24 horas.</p>
  </div>
</section>

<section class="sp" style="background:#fff;">
  <div class="container">
    <div class="cnt-grid">

      <!-- INFO CONTACTO -->
      <div>
        <div class="label lc"><i class="fas fa-map-marker-alt"></i>Información de Contacto</div>
        <h2 class="h2" style="font-size:clamp(2rem,3vw,3rem);color:#1a1a1a;margin-bottom:8px;">
          Hablemos de <span class="red">su empresa</span>
        </h2>
        <div class="rule"></div>
        <p style="color:#555;line-height:1.88;margin-bottom:32px;font-weight:300;">
          Cuéntenos sobre sus necesidades de seguridad radiológica y uno de nuestros especialistas le contactará para ofrecerle la solución más adecuada para su empresa.
        </p>

        <div class="cnt-item">
          <div class="cnt-ico"><i class="fas fa-phone-alt"></i></div>
          <div>
            <strong style="color:#1a1a1a;">Teléfono</strong>
            <a href="tel:+525556508420" style="display:block;color:#555;margin-top:2px;"><?=$config['empresa_telefono']??'01 55 5650 8420'?></a>
          </div>
        </div>
        <div class="cnt-item">
          <div class="cnt-ico"><i class="fas fa-envelope"></i></div>
          <div>
            <strong style="color:#1a1a1a;">Email</strong>
            <a href="mailto:<?=$config['empresa_email']??'contacto@crisamex.com'?>" style="display:block;color:#555;margin-top:2px;"><?=$config['empresa_email']??'contacto@crisamex.com'?></a>
          </div>
        </div>
        <div class="cnt-item">
          <div class="cnt-ico"><i class="fab fa-whatsapp"></i></div>
          <div>
            <strong style="color:#1a1a1a;">WhatsApp</strong>
            <a href="https://wa.me/<?=$config['empresa_whatsapp']??'525556508420'?>" target="_blank" style="display:block;color:#555;margin-top:2px;">Escribir por WhatsApp →</a>
          </div>
        </div>
        <div class="cnt-item">
          <div class="cnt-ico"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <strong style="color:#1a1a1a;">Ubicación</strong>
            <span style="display:block;color:#555;margin-top:2px;"><?=$config['empresa_direccion']??'Ciudad de México, México'?></span>
          </div>
        </div>
        <div class="cnt-item">
          <div class="cnt-ico"><i class="fas fa-clock"></i></div>
          <div>
            <strong style="color:#1a1a1a;">Horario de atención</strong>
            <span style="display:block;color:#555;margin-top:2px;line-height:1.7;">Lun – Vie: 9:00 AM – 6:00 PM<br>Sáb: 9:00 AM – 1:00 PM</span>
          </div>
        </div>

        <!-- Redes sociales -->
        <div style="margin-top:32px;display:flex;gap:10px;">
          <?php foreach([
            ['fab fa-facebook-f','https://www.facebook.com/crisamexx','#1877F2'],
            ['fab fa-linkedin-in','https://www.linkedin.com/company/crisamex','#0A66C2'],
            ['fab fa-whatsapp','https://wa.me/525556508420','#25D366'],
          ] as [$ico,$url,$color]): ?>
          <a href="<?=$url?>" target="_blank" rel="noopener"
             style="width:40px;height:40px;background:<?=$color?>;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;text-decoration:none;transition:all .3s;border-radius:4px;"
             onmouseover="this.style.transform='translateY(-3px)';this.style.opacity='.85'"
             onmouseout="this.style.transform='translateY(0)';this.style.opacity='1'">
            <i class="<?=$ico?>"></i>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- FORMULARIO -->
      <div class="form-box">
        <h3 style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:2px;color:#1a1a1a;margin-bottom:6px;">Envíenos un Mensaje</h3>
        <p style="color:#666;font-size:.88rem;margin-bottom:24px;font-weight:300;">Le responderemos en menos de 24 horas hábiles.</p>

        <div id="form-msg" class="fa-msg"></div>

        <form id="contact-form">
          <div class="fr2">
            <div class="fg">
              <label>Nombre completo <span style="color:#C8151B;">*</span></label>
              <input type="text" name="nombre" class="fc" placeholder="Tu nombre" required>
            </div>
            <div class="fg">
              <label>Empresa</label>
              <input type="text" name="empresa" class="fc" placeholder="Tu empresa">
            </div>
          </div>
          <div class="fr2">
            <div class="fg">
              <label>Email <span style="color:#C8151B;">*</span></label>
              <input type="email" name="email" class="fc" placeholder="correo@empresa.com" required>
            </div>
            <div class="fg">
              <label>Teléfono</label>
              <input type="tel" name="telefono" class="fc" placeholder="55 1234 5678">
            </div>
          </div>
          <div class="fg">
            <label>Servicio de interés</label>
            <select name="servicio" class="fc">
              <option value="">Seleccione un servicio...</option>
              <?php foreach($servicios as $s): ?>
              <option value="<?=htmlspecialchars($s['titulo'])?>" <?=$srvPre===$s['titulo']?'selected':''?>>
                <?=htmlspecialchars($s['titulo'])?>
              </option>
              <?php endforeach; ?>
              <option value="Información general">Información general</option>
            </select>
          </div>
          <div class="fg">
            <label>Mensaje <span style="color:#C8151B;">*</span></label>
            <textarea name="mensaje" class="fc" rows="5" placeholder="Cuéntenos sobre sus necesidades de seguridad radiológica..." required></textarea>
          </div>
          <button type="submit" class="btn btn-r" style="width:100%;justify-content:center;padding:14px;">
            <i class="fas fa-paper-plane"></i>Enviar Mensaje
          </button>
        </form>

        <p style="text-align:center;margin-top:16px;font-size:.78rem;color:#aaa;">
          <i class="fas fa-lock" style="color:#C8151B;margin-right:4px;"></i>
          Sus datos están protegidos. No compartimos su información.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- MAPA / INFO EXTRA -->
<section style="background:#f8f8f8;padding:60px 0;border-top:1px solid #e8e8e8;">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;text-align:center;">
      <?php foreach([
        ['fas fa-clock','Respuesta rápida','Respondemos en menos de 24 horas hábiles a todas las consultas.'],
        ['fas fa-users','Expertos certificados','Nuestro equipo tiene más de 25 años de experiencia comprobada.'],
        ['fas fa-map-marker-alt','Cobertura nacional','Atendemos proyectos en toda la República Mexicana.'],
      ] as [$ico,$titulo,$desc]): ?>
      <div style="background:#fff;border:1px solid #e0e0e0;padding:36px 28px;transition:all .3s;"
           onmouseover="this.style.borderColor='#C8151B';this.style.transform='translateY(-4px)'"
           onmouseout="this.style.borderColor='#e0e0e0';this.style.transform='translateY(0)'">
        <div style="width:60px;height:60px;background:rgba(200,21,27,.08);border:1px solid rgba(200,21,27,.14);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.4rem;color:#C8151B;">
          <i class="<?=$ico?>"></i>
        </div>
        <h3 style="font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:1px;color:#1a1a1a;margin-bottom:10px;"><?=$titulo?></h3>
        <p style="color:#666;font-size:.86rem;line-height:1.75;font-weight:300;"><?=$desc?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
  e.preventDefault();
  var btn = this.querySelector('button[type=submit]');
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
  btn.disabled = true;
  var data = new FormData(this);
  fetch('/contacto/enviar', { method: 'POST', body: data })
    .then(function(r){ return r.json(); })
    .then(function(res) {
      var msg = document.getElementById('form-msg');
      msg.className = 'fa-msg ' + (res.success ? 'ok' : 'err');
      msg.innerHTML = '<i class="fas fa-' + (res.success ? 'check-circle' : 'exclamation-circle') + '"></i>' + res.message;
      if(res.success) { document.getElementById('contact-form').reset(); }
    })
    .catch(function(){ 
      var msg = document.getElementById('form-msg');
      msg.className = 'fa-msg err';
      msg.innerHTML = '<i class="fas fa-exclamation-circle"></i>Error al enviar. Por favor llámenos directamente.';
    })
    .finally(function() {
      btn.innerHTML = '<i class="fas fa-paper-plane"></i>Enviar Mensaje';
      btn.disabled = false;
    });
});
</script>
<?php require_once SRC_PATH.'/views/partials/footer.php'; ?>
