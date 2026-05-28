<?php
$portalTitle = 'Mensajes';
$cid = $_SESSION['cliente_id'];

if(isset($_GET['mark'])){
  Database::query("UPDATE portal_notificaciones SET leida=1 WHERE id=? AND cliente_id=?",[(int)$_GET['mark'],$cid]);
  echo 'ok'; exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $asunto  = trim($_POST['asunto']??'Sin asunto');
  $mensaje = trim($_POST['mensaje']??'');
  if(!empty($mensaje)){
    Database::query("INSERT INTO portal_mensajes(cliente_id,asunto,mensaje,de,leido_admin) VALUES(?,?,?,'cliente',0)",[$cid,$asunto,$mensaje]);
  }
  header('Location: /portal/mensajes?ok=1'); exit;
}

Database::query("UPDATE portal_mensajes SET leido_cliente=1 WHERE cliente_id=? AND de='admin'",[$cid]);
$msgs    = Database::fetchAll("SELECT * FROM portal_mensajes WHERE cliente_id=? ORDER BY created_at ASC",[$cid]);
$cliente = Database::fetchOne("SELECT nombre FROM clientes WHERE id=?",[$cid]);

require_once SRC_PATH.'/views/portal/layout-top.php';
?>

<style>
/* ── CHAT LAYOUT COMPLETO ─────────────────────────────────── */
.chat-page {
  display: flex;
  flex-direction: column;
  height: calc(100svh - var(--portal-top-h, 60px));
  max-height: calc(100svh - var(--portal-top-h, 60px));
  overflow: hidden;
  gap: 0;
}

/* Desktop: 2 columnas */
@media (min-width: 768px) {
  .chat-page {
    flex-direction: row;
    align-items: stretch;
    height: calc(100vh - 110px);
  }
}

/* ── CHAT PRINCIPAL ───────────────────────────────────────── */
.chat-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #E2E8F0;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.06);
  min-height: 0; /* crucial para flex */
}
@media (max-width: 767px) {
  .chat-main {
    border-radius: 12px 12px 0 0;
    border-bottom: none;
    height: 100%;
  }
}

/* Header del chat */
.chat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 18px;
  border-bottom: 1px solid #F1F5F9;
  background: #fff;
  flex-shrink: 0;
  gap: 12px;
}
.chat-header-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.chat-avatar {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #003087, #C8151B);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 16px; font-weight: 800;
  flex-shrink: 0;
}
.chat-header-info h2 {
  font-size: 15px; font-weight: 700;
  color: #1E293B; margin: 0 0 2px;
}
.chat-header-info .online {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: #16A34A; font-weight: 600;
}
.chat-header-info .online::before {
  content: '';
  width: 7px; height: 7px;
  background: #22C55E; border-radius: 50%;
  animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
  0%,100%{ opacity:1; } 50%{ opacity:.4; }
}

/* Área de mensajes */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  background: #F8FAFC;
  min-height: 0;
  scroll-behavior: smooth;
}

/* Separador de fecha */
.chat-date-sep {
  text-align: center;
  margin: 8px 0;
  flex-shrink: 0;
}
.chat-date-sep span {
  background: #E2E8F0;
  color: #94A3B8;
  font-size: 11px; font-weight: 600;
  letter-spacing: .8px; text-transform: uppercase;
  padding: 3px 12px; border-radius: 12px;
}

/* Burbuja de mensaje */
.chat-bubble-wrap {
  display: flex;
  flex-direction: column;
  max-width: 78%;
  flex-shrink: 0;
}
.chat-bubble-wrap.mine  { align-self: flex-end; align-items: flex-end; }
.chat-bubble-wrap.theirs{ align-self: flex-start; align-items: flex-start; }

.chat-bubble-meta {
  font-size: 11px; color: #94A3B8;
  margin-bottom: 4px; font-weight: 500;
  display: flex; align-items: center; gap: 4px;
}

.chat-bubble {
  padding: 10px 14px;
  font-size: 14px; line-height: 1.55;
  word-break: break-word;
  max-width: 100%;
}
.chat-bubble-wrap.mine .chat-bubble {
  background: #C8151B; color: #fff;
  border-radius: 16px 16px 4px 16px;
  box-shadow: 0 2px 8px rgba(200,21,27,.25);
}
.chat-bubble-wrap.theirs .chat-bubble {
  background: #fff; color: #1E293B;
  border-radius: 16px 16px 16px 4px;
  box-shadow: 0 1px 4px rgba(0,0,0,.08);
  border: 1px solid #F1F5F9;
}

/* Empty state */
.chat-empty {
  flex: 1; display: flex;
  flex-direction: column;
  align-items: center; justify-content: center;
  padding: 40px 20px; text-align: center;
  color: #94A3B8;
}
.chat-empty i     { font-size: 48px; margin-bottom: 16px; opacity: .3; }
.chat-empty h3    { font-size: 16px; font-weight: 700; color: #64748B; margin-bottom: 6px; }
.chat-empty p     { font-size: 14px; max-width: 240px; line-height: 1.6; }

/* Input del chat */
.chat-input-bar {
  padding: 12px 14px;
  border-top: 1px solid #F1F5F9;
  background: #fff;
  flex-shrink: 0;
  padding-bottom: max(12px, calc(12px + env(safe-area-inset-bottom, 0px)));
}
.chat-input-wrap {
  display: flex;
  align-items: flex-end;
  gap: 10px;
  background: #F8FAFC;
  border: 1.5px solid #E2E8F0;
  border-radius: 24px;
  padding: 8px 8px 8px 16px;
  transition: border-color .2s;
}
.chat-input-wrap:focus-within {
  border-color: #C8151B;
  box-shadow: 0 0 0 3px rgba(200,21,27,.08);
}
.chat-input-wrap textarea {
  flex: 1; border: none; background: transparent;
  font-family: 'DM Sans', sans-serif;
  font-size: 15px; /* 16px evita zoom iOS */
  color: #1E293B; resize: none;
  min-height: 28px; max-height: 100px;
  outline: none; padding: 2px 0;
  line-height: 1.5;
  -webkit-appearance: none;
}
.chat-input-wrap textarea::placeholder { color: #94A3B8; }
.chat-send-btn {
  width: 38px; height: 38px;
  border-radius: 50%; border: none;
  background: #C8151B; color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; cursor: pointer; flex-shrink: 0;
  transition: all .18s;
  -webkit-tap-highlight-color: transparent;
}
.chat-send-btn:hover  { background: #9B0F13; transform: scale(1.08); }
.chat-send-btn:active { transform: scale(.93); }
.chat-hint {
  font-size: 11px; color: #CBD5E1;
  text-align: center; margin-top: 6px;
}

/* ── PANEL LATERAL ────────────────────────────────────────── */
.chat-sidebar {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex-shrink: 0;
}
@media (min-width: 768px) {
  .chat-sidebar {
    width: 300px;
    margin-left: 14px;
    overflow-y: auto;
  }
}
/* Mobile: panel colapsable */
@media (max-width: 767px) {
  .chat-sidebar {
    max-height: 0;
    overflow: hidden;
    transition: max-height .3s ease;
    padding: 0;
  }
  .chat-sidebar.expanded {
    max-height: 400px;
    padding-top: 12px;
  }
  .chat-page {
    padding-bottom: 0;
  }
}

/* Botón toggle info en mobile */
.chat-info-toggle {
  display: none;
  width: 100%;
  padding: 10px 16px;
  background: #F8FAFC;
  border: 1px solid #E2E8F0;
  border-radius: 10px;
  font-size: 13px; font-weight: 600;
  color: #64748B; text-align: center;
  cursor: pointer; margin-top: 10px;
  -webkit-tap-highlight-color: transparent;
  transition: all .18s;
}
.chat-info-toggle:active { background: #E2E8F0; }
@media (max-width: 767px) {
  .chat-info-toggle { display: block; }
}

/* Cards del sidebar */
.chat-info-card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #E2E8F0;
  box-shadow: 0 1px 3px rgba(0,0,0,.05);
  overflow: hidden;
}
.chat-info-card .cic-head {
  padding: 14px 16px;
  border-bottom: 1px solid #F1F5F9;
  font-size: 13px; font-weight: 700;
  color: #1E293B;
  display: flex; align-items: center; gap: 8px;
}
.chat-info-card .cic-head i { color: #64748B; font-size: 13px; }
.chat-info-card .cic-body { padding: 14px 16px; }

/* Botones de contacto */
.contact-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 14px;
  border-radius: 8px;
  font-size: 13px; font-weight: 600;
  text-decoration: none;
  transition: all .18s; margin-bottom: 8px;
  min-height: 46px;
  -webkit-tap-highlight-color: transparent;
  border: 1px solid transparent;
}
.contact-btn:last-child { margin-bottom: 0; }
.contact-btn:active { transform: scale(.97); }
.contact-btn.phone {
  background: rgba(200,21,27,.06);
  border-color: rgba(200,21,27,.2);
  color: #C8151B;
}
.contact-btn.phone:hover { background: #C8151B; color: #fff; }
.contact-btn.whatsapp {
  background: rgba(37,211,102,.07);
  border-color: rgba(37,211,102,.2);
  color: #16A34A;
}
.contact-btn.whatsapp:hover { background: #25D366; color: #fff; }
.contact-btn.email {
  background: #F8FAFC;
  border-color: #E2E8F0;
  color: #64748B;
}
.contact-btn.email:hover { background: #E2E8F0; }

/* Horario */
.horario-row {
  display: flex; justify-content: space-between;
  align-items: center;
  padding: 7px 0;
  border-bottom: 1px solid #F8FAFC;
  font-size: 13px;
}
.horario-row:last-child { border-bottom: none; padding-bottom: 0; }
.horario-row .dia  { color: #64748B; font-weight: 500; }
.horario-row .hora { color: #1E293B; font-weight: 600; font-size: 12px; }
.horario-row .cerrado { color: #C8151B; font-weight: 600; font-size: 12px; }

/* Alert success */
.aa-ok {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px; border-radius: 10px;
  background: #DCFCE7; color: #16A34A;
  font-size: 14px; font-weight: 600;
  margin-bottom: 14px; flex-shrink: 0;
  border: 1px solid rgba(22,163,74,.2);
}
</style>

<?php if(isset($_GET['ok'])): ?>
<div class="aa-ok"><i class="fas fa-check-circle"></i> Mensaje enviado correctamente. Te responderemos pronto.</div>
<?php endif; ?>

<div class="chat-page">

  <!-- ═══ CHAT PRINCIPAL ═════════════════════════════════════ -->
  <div class="chat-main">

    <!-- Header -->
    <div class="chat-header">
      <div class="chat-header-left">
        <div class="chat-avatar">C</div>
        <div class="chat-header-info">
          <h2>CRISAMEX</h2>
          <div class="online">En línea</div>
        </div>
      </div>
      <!-- Botón info en mobile (abre panel) -->
      <button class="chat-info-toggle" id="toggleInfo" onclick="toggleSidebar()" style="width:auto;margin:0;padding:8px 14px;display:none" aria-label="Ver información">
        <i class="fas fa-info-circle"></i>
      </button>
      <style>
        @media(max-width:767px){
          button#toggleInfo{ display:inline-flex!important; align-items:center; gap:6px; font-size:13px; }
        }
      </style>
    </div>

    <!-- Mensajes -->
    <div class="chat-messages" id="chat-box">

      <?php if(empty($msgs)): ?>
      <div class="chat-empty">
        <i class="fas fa-comment-dots"></i>
        <h3>Sin mensajes aún</h3>
        <p>Escríbenos y te responderemos en menos de 24 horas hábiles.</p>
      </div>
      <?php else:
        $lastDate = '';
        foreach($msgs as $m):
          $mDate = date('d/m/Y', strtotime($m['created_at']));
          $mTime = date('H:i',   strtotime($m['created_at']));
          $mine  = $m['de'] === 'cliente';

          if($mDate !== $lastDate):
            $lastDate = $mDate;
            $lbl = ($mDate === date('d/m/Y')) ? 'Hoy'
                 : ($mDate === date('d/m/Y', strtotime('-1 day')) ? 'Ayer' : $mDate);
      ?>
        <div class="chat-date-sep">
          <span><?= htmlspecialchars($lbl, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
      <?php endif; ?>

        <div class="chat-bubble-wrap <?= $mine ? 'mine' : 'theirs' ?>">
          <div class="chat-bubble-meta">
            <?= $mine ? 'Tú' : 'CRISAMEX' ?> · <?= $mTime ?>
            <?php if($mine && $m['leido_admin']): ?>
              <i class="fas fa-check-double" style="color:#60A5FA;font-size:10px;" title="Leído"></i>
            <?php endif; ?>
          </div>
          <div class="chat-bubble">
            <?= nl2br(htmlspecialchars($m['mensaje'], ENT_QUOTES, 'UTF-8')) ?>
          </div>
        </div>

      <?php endforeach; endif; ?>
    </div>

    <!-- Input -->
    <form method="POST" class="chat-input-bar" id="chatForm">
      <input type="hidden" name="asunto" value="Consulta del cliente">
      <div class="chat-input-wrap">
        <textarea
          name="mensaje"
          id="msg-ta"
          placeholder="Escribe tu mensaje..."
          required
          rows="1"
          aria-label="Escribe tu mensaje"></textarea>
        <button type="submit" class="chat-send-btn" aria-label="Enviar">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
      <div class="chat-hint">Enter para enviar &nbsp;·&nbsp; Shift+Enter para nueva línea</div>
    </form>

  </div><!-- /chat-main -->

  <!-- ═══ PANEL LATERAL ══════════════════════════════════════ -->
  <div class="chat-sidebar" id="chatSidebar">

    <!-- Soporte -->
    <div class="chat-info-card">
      <div class="cic-head"><i class="fas fa-headset"></i> Soporte CRISAMEX</div>
      <div class="cic-body">
        <p style="font-size:13px;color:#64748B;margin-bottom:12px;line-height:1.6;">
          Respondemos en menos de 24 horas hábiles. También puedes contactarnos directamente:
        </p>
        <a href="tel:+525556508420" class="contact-btn phone">
          <i class="fas fa-phone-alt"></i>
          55 5650 8420
        </a>
        <a href="https://wa.me/525556508420" target="_blank" rel="noopener" class="contact-btn whatsapp">
          <i class="fab fa-whatsapp"></i>
          WhatsApp
        </a>
        <a href="mailto:contacto@crisamex.com" class="contact-btn email">
          <i class="fas fa-envelope"></i>
          contacto@crisamex.com
        </a>
      </div>
    </div>

    <!-- Horario -->
    <div class="chat-info-card">
      <div class="cic-head"><i class="fas fa-clock"></i> Horario de atención</div>
      <div class="cic-body">
        <div class="horario-row">
          <span class="dia">Lun – Vie</span>
          <span class="hora">9:00 AM – 6:00 PM</span>
        </div>
        <div class="horario-row">
          <span class="dia">Sábado</span>
          <span class="hora">9:00 AM – 1:00 PM</span>
        </div>
        <div class="horario-row">
          <span class="dia">Domingo</span>
          <span class="cerrado">Cerrado</span>
        </div>
      </div>
    </div>

    <!-- Total mensajes -->
    <?php $total = count($msgs); if($total > 0): ?>
    <div class="chat-info-card">
      <div class="cic-body" style="display:flex;align-items:center;gap:12px;">
        <div style="width:42px;height:42px;border-radius:10px;background:#EEF4FC;display:flex;align-items:center;justify-content:center;font-size:18px;color:#0057B7;flex-shrink:0;">
          <i class="fas fa-comments"></i>
        </div>
        <div>
          <div style="font-size:22px;font-weight:800;color:#1E293B;line-height:1;"><?= $total ?></div>
          <div style="font-size:12px;color:#64748B;margin-top:2px;">mensajes en total</div>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- /chat-sidebar -->

  <!-- Toggle info mobile (fuera del sidebar) -->
  <button class="chat-info-toggle" id="toggleInfo2" onclick="toggleSidebar()">
    <i class="fas fa-chevron-down" id="toggleIcon"></i>
    Información de contacto
  </button>

</div><!-- /chat-page -->

<script>
(function(){

  /* ── Scroll al fondo ──────────────────────────────────── */
  var box = document.getElementById('chat-box');
  if(box) box.scrollTop = box.scrollHeight;

  /* ── Auto-resize textarea ─────────────────────────────── */
  var ta = document.getElementById('msg-ta');
  if(ta){
    ta.addEventListener('input', function(){
      this.style.height = 'auto';
      this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    /* Enter envía, Shift+Enter nueva línea */
    ta.addEventListener('keydown', function(e){
      if(e.key === 'Enter' && !e.shiftKey){
        e.preventDefault();
        var form = document.getElementById('chatForm');
        if(form && this.value.trim()) form.submit();
      }
    });

    /* Focus en desktop */
    if(window.innerWidth >= 768) ta.focus();
  }

  /* ── Toggle panel info en mobile ─────────────────────── */
  window.toggleSidebar = function(){
    var sb   = document.getElementById('chatSidebar');
    var icon = document.getElementById('toggleIcon');
    var btn2 = document.getElementById('toggleInfo2');
    if(!sb) return;
    var open = sb.classList.toggle('expanded');
    if(icon) icon.className = open ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
    if(btn2) btn2.querySelector('span') && (btn2.lastChild.textContent = open ? ' Ocultar' : ' Información de contacto');
  };

  /* ── Envío con loading state ──────────────────────────── */
  var form = document.getElementById('chatForm');
  if(form){
    form.addEventListener('submit', function(){
      var btn = this.querySelector('.chat-send-btn');
      if(btn){
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
      }
    });
  }

})();
</script>

<?php require_once SRC_PATH.'/views/portal/layout-bottom.php'; ?>
