<?php
/**
 * CRISAMEX — foot.php
 * Archivo: src/views/partials/foot.php
 * Scripts al final para máxima velocidad de carga
 */
?>

<!-- ── JS PRINCIPAL — defer para no bloquear render ── -->
<script src="/js/main.js" defer></script>

<!-- ── AJAX: cargar datos del dashboard ── -->
<script>
(function(){
  'use strict';

  // ── Sidebar toggle ────────────────────────────────────────
  var btn     = document.getElementById('menuBtn');
  var sidebar = document.getElementById('sidebar');
  var overlay = document.getElementById('sidebarOverlay');

  if (btn && sidebar) {
    btn.addEventListener('click', function() {
      var open = sidebar.classList.toggle('open');
      btn.setAttribute('aria-expanded', open);
      if (overlay) overlay.classList.toggle('active', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });
  }
  if (overlay) {
    overlay.addEventListener('click', function() {
      sidebar && sidebar.classList.remove('open');
      btn && btn.setAttribute('aria-expanded', 'false');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    });
  }

  // ── Swipe para cerrar sidebar ─────────────────────────────
  var startX = 0;
  if (sidebar) {
    sidebar.addEventListener('touchstart', function(e) {
      startX = e.touches[0].clientX;
    }, { passive: true });
    sidebar.addEventListener('touchend', function(e) {
      if (startX - e.changedTouches[0].clientX > 55) {
        sidebar.classList.remove('open');
        overlay && overlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    }, { passive: true });
  }

  // ── Topbar scroll behavior ────────────────────────────────
  var topbar  = document.querySelector('.app-topbar');
  var lastY   = 0;
  var ticking = false;
  if (topbar && window.innerWidth < 1024) {
    window.addEventListener('scroll', function() {
      if (!ticking) {
        requestAnimationFrame(function() {
          var y = window.scrollY;
          topbar.style.transition = 'transform .22s ease';
          if (y > lastY + 6 && y > 60) topbar.style.transform = 'translateY(-100%)';
          else if (y < lastY - 4 || y < 60) topbar.style.transform = 'translateY(0)';
          lastY   = y;
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  // ── Toggle contraseña ─────────────────────────────────────
  document.querySelectorAll('.toggle-pwd').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var wrap  = btn.closest('.field-input');
      var input = wrap && wrap.querySelector('input');
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.className = 'toggle-pwd fas fa-eye' + (show ? '-slash' : '');
    });
  });

  // ── Plan selector ─────────────────────────────────────────
  document.querySelectorAll('.plan-opt').forEach(function(opt) {
    opt.addEventListener('click', function() {
      document.querySelectorAll('.plan-opt').forEach(function(o) {
        o.classList.remove('selected');
      });
      opt.classList.add('selected');
      var radio = opt.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    });
  });

  // ── Submit loading ────────────────────────────────────────
  document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function() {
      var btn = form.querySelector('[type="submit"]');
      if (btn) {
        setTimeout(function() {
          btn.disabled = true;
          btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp;Procesando...';
        }, 10);
      }
    });
  });

  // ── Cargar KPIs del dashboard vía AJAX ───────────────────
  var kpiGrid = document.getElementById('kpiGrid');
  if (kpiGrid) {
    fetch('/portal/api/kpis', { credentials: 'same-origin' })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        kpiGrid.innerHTML = [
          { icon:'fa-comments',    color:'blue',  label:'Mensajes',   value: data.mensajes  || 0 },
          { icon:'fa-folder',      color:'amber', label:'Documentos', value: data.documentos|| 0 },
          { icon:'fa-id-card',     color:'green', label:'Licencia',   value: data.licencia  || '—' },
          { icon:'fa-bell',        color:'red',   label:'Notificaciones', value: data.notifs || 0 },
        ].map(function(k) {
          return '<div class="kpi-card animate-up">'
            + '<div class="kpi-card-header">'
            + '<div class="kpi-icon ' + k.color + '"><i class="fas ' + k.icon + '"></i></div>'
            + '</div>'
            + '<div class="kpi-label">' + k.label + '</div>'
            + '<div class="kpi-value">' + k.value + '</div>'
            + '</div>';
        }).join('');
      })
      .catch(function() {
        // Si falla AJAX mostrar igual sin datos
        kpiGrid.innerHTML = [
          { icon:'fa-comments', color:'blue',  label:'Mensajes',   value:'—' },
          { icon:'fa-folder',   color:'amber', label:'Documentos', value:'—' },
          { icon:'fa-id-card',  color:'green', label:'Licencia',   value:'—' },
          { icon:'fa-bell',     color:'red',   label:'Notificaciones', value:'—' },
        ].map(function(k) {
          return '<div class="kpi-card">'
            + '<div class="kpi-card-header">'
            + '<div class="kpi-icon ' + k.color + '"><i class="fas ' + k.icon + '"></i></div>'
            + '</div>'
            + '<div class="kpi-label">' + k.label + '</div>'
            + '<div class="kpi-value">' + k.value + '</div>'
            + '</div>';
        }).join('');
      });
  }

  // ── Marcar notificaciones como leídas ─────────────────────
  var markAll = document.getElementById('markAllRead');
  if (markAll) {
    markAll.addEventListener('click', function() {
      document.querySelectorAll('.notif-item.unread').forEach(function(item) {
        item.classList.remove('unread');
      });
      var dot = document.getElementById('notif-dot');
      if (dot) dot.style.display = 'none';
    });
  }

  // ── Chat: auto-scroll al fondo ────────────────────────────
  var msgs = document.querySelector('.chat-messages');
  if (msgs) msgs.scrollTop = msgs.scrollHeight;

  // ── Chat: Enter envía ─────────────────────────────────────
  var chatTA = document.querySelector('.chat-input-area textarea');
  if (chatTA) {
    chatTA.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        var sendBtn = document.querySelector('.chat-send-btn');
        if (sendBtn) sendBtn.click();
      }
    });
    chatTA.addEventListener('input', function() {
      chatTA.style.height = 'auto';
      chatTA.style.height = Math.min(chatTA.scrollHeight, 120) + 'px';
    });
  }

  // ── Bottom nav: marcar activo ─────────────────────────────
  var path = window.location.pathname;
  document.querySelectorAll('.bottom-nav-item').forEach(function(item) {
    var href = item.getAttribute('href') || '';
    if (href && path === href) item.classList.add('active');
    else item.classList.remove('active');
  });

  // ── Resize: cerrar sidebar en desktop ─────────────────────
  window.addEventListener('resize', function() {
    if (window.innerWidth >= 1024 && sidebar) {
      sidebar.classList.remove('open');
      overlay && overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  });

})();
</script>

<?php if (!empty($extraFoot)) echo $extraFoot; ?>
</body>
</html>
