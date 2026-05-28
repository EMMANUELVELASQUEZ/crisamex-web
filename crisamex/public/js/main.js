/* CRISAMEX v9 — JavaScript */
'use strict';

document.addEventListener('DOMContentLoaded', function(){

  /* ── CURSOR ── */
  var cur = document.getElementById('cur');
  var ring = document.getElementById('cur-r');
  if(cur && ring && window.innerWidth > 900){
    var mx=0, my=0, rx=0, ry=0;
    document.addEventListener('mousemove', function(e){
      mx=e.clientX; my=e.clientY;
      cur.style.left=mx+'px'; cur.style.top=my+'px';
    });
    (function loop(){
      rx+=(mx-rx)*.1; ry+=(my-ry)*.1;
      ring.style.left=rx+'px'; ring.style.top=ry+'px';
      requestAnimationFrame(loop);
    })();
    document.querySelectorAll('a,button,.btn,.srv-card,.val-c,.eq-c,.cert-c,.cc').forEach(function(el){
      el.addEventListener('mouseenter', function(){ cur.classList.add('hov'); ring.classList.add('hov'); });
      el.addEventListener('mouseleave', function(){ cur.classList.remove('hov'); ring.classList.remove('hov'); });
    });
  }

  /* ── NAVBAR ── */
  var nav = document.getElementById('nav');
  if(nav){
    function updateNav(){ nav.classList.toggle('sc', window.scrollY > 60); }
    window.addEventListener('scroll', updateNav, {passive:true});
    updateNav();
  }

  /* ── HAMBURGER ── */
  var ham = document.getElementById('ham');
  var nl  = document.getElementById('nav-links');
  if(ham && nl){
    ham.addEventListener('click', function(){
      nl.classList.toggle('open');
      document.body.style.overflow = nl.classList.contains('open') ? 'hidden' : '';
    });
    nl.querySelectorAll('a').forEach(function(a){
      a.addEventListener('click', function(){
        nl.classList.remove('open');
        document.body.style.overflow = '';
      });
    });
  }

  /* ── PARALLAX HERO ── */
  var hbg = document.querySelector('.hero-bg-img');
  if(hbg){
    window.addEventListener('scroll', function(){
      hbg.style.transform = 'translateY('+(window.scrollY*.3)+'px)';
    }, {passive:true});
  }

  /* ── PARTÍCULAS ── */
  var cv = document.getElementById('pcv');
  if(cv){
    var ctx = cv.getContext('2d');
    var W, H, pts = [];
    function rsz(){ W=cv.width=cv.offsetWidth; H=cv.height=cv.offsetHeight; }
    rsz();
    if(window.ResizeObserver) new ResizeObserver(rsz).observe(cv.parentElement);

    function Dot(){
      this.x=Math.random()*W; this.y=Math.random()*H;
      this.vx=(Math.random()-.5)*.22; this.vy=(Math.random()-.5)*.22;
      this.r=Math.random()*1.3+.3; this.op=Math.random()*.25+.07; this.red=Math.random()>.72;
    }
    Dot.prototype.update=function(){
      this.x+=this.vx; this.y+=this.vy;
      if(this.x<0||this.x>W) this.vx*=-1;
      if(this.y<0||this.y>H) this.vy*=-1;
    };
    Dot.prototype.draw=function(){
      ctx.beginPath(); ctx.arc(this.x,this.y,this.r,0,Math.PI*2);
      ctx.fillStyle=this.red?'rgba(200,21,27,'+this.op+')':'rgba(255,255,255,'+(this.op*.4)+')';
      ctx.fill();
    };
    for(var i=0;i<80;i++) pts.push(new Dot());
    (function loop(){
      ctx.clearRect(0,0,W,H);
      pts.forEach(function(p){ p.update(); p.draw(); });
      for(var a=0;a<pts.length;a++){
        for(var b=a+1;b<pts.length;b++){
          var dx=pts[a].x-pts[b].x, dy=pts[a].y-pts[b].y, d=Math.sqrt(dx*dx+dy*dy);
          if(d<110){
            ctx.beginPath();
            ctx.strokeStyle='rgba(200,21,27,'+(.05*(1-d/110))+')';
            ctx.lineWidth=.4;
            ctx.moveTo(pts[a].x,pts[a].y); ctx.lineTo(pts[b].x,pts[b].y);
            ctx.stroke();
          }
        }
      }
      requestAnimationFrame(loop);
    })();
  }

  /* ── CONTADORES ── */
  if('IntersectionObserver' in window){
    var co = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(!e.isIntersecting) return;
        var el=e.target, t=parseInt(el.dataset.target,10), pre=el.dataset.prefix||'', c=0, s=t/60;
        var ti=setInterval(function(){
          c+=s; if(c>=t){c=t;clearInterval(ti);}
          el.textContent=pre+Math.floor(c).toLocaleString('es-MX');
        },28);
        co.unobserve(el);
      });
    },{threshold:.5});
    document.querySelectorAll('[data-target]').forEach(function(el){ co.observe(el); });
  }

  /* ── REVEAL ── */
  if('IntersectionObserver' in window){
    var ro = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){ e.target.classList.add('on'); ro.unobserve(e.target); }
      });
    },{threshold:.1, rootMargin:'0px 0px -60px 0px'});
    document.querySelectorAll('.rv,.rv-l,.rv-r').forEach(function(el){ ro.observe(el); });
  }

  /* ── SCROLL TOP ── */
  var stb = document.getElementById('stb');
  if(stb){
    window.addEventListener('scroll', function(){
      stb.classList.toggle('vis', window.scrollY>400);
    },{passive:true});
    stb.addEventListener('click', function(){ window.scrollTo({top:0,behavior:'smooth'}); });
  }

  /* ── FORMULARIO AJAX ── */
  var form = document.getElementById('cf');
  if(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var btn=form.querySelector('[type=submit]'), al=document.getElementById('fa');
      btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Enviando...';
      fetch('/contacto/enviar',{method:'POST',body:new FormData(form)})
        .then(function(r){return r.json();})
        .then(function(d){
          al.className='fa-msg '+(d.success?'ok':'err');
          al.innerHTML='<i class="fas fa-'+(d.success?'check-circle':'exclamation-circle')+'"></i> '+d.message;
          al.style.display='flex';
          if(d.success) form.reset();
        })
        .catch(function(){
          al.className='fa-msg err';
          al.innerHTML='<i class="fas fa-exclamation-circle"></i> Error al enviar. Intenta de nuevo.';
          al.style.display='flex';
        })
        .finally(function(){
          btn.disabled=false; btn.innerHTML='<i class="fas fa-paper-plane"></i>Enviar Mensaje';
        });
    });
  }

  /* ── CONFIRMAR BORRADO (admin/portal) ── */
  document.querySelectorAll('[data-confirm]').forEach(function(b){
    b.addEventListener('click', function(e){
      if(!confirm(b.dataset.confirm)) e.preventDefault();
    });
  });

}); // fin DOMContentLoaded
/* ================================================================
   CRISAMEX — Mobile Menu & Navigation JS
   Agregar al FINAL de: crisamex/public/js/main.js
   ================================================================ */

(function () {
  'use strict';

  /* ── UTILIDADES ──────────────────────────────────────────────── */
  const qs  = (sel, ctx = document) => ctx.querySelector(sel);
  const qsa = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];
  const isMobile = () => window.innerWidth < 768;

  /* ================================================================
     1. NAVBAR HAMBURGUESA
     ================================================================ */
  function initNavbar() {
    const toggle   = qs('.navbar-toggle, .hamburger, .menu-toggle, .mobile-menu-btn');
    const menu     = qs('.navbar-menu, .nav-menu, .nav-links');
    const overlay  = qs('.nav-overlay') || createOverlay('nav-overlay', 998);

    if (!toggle || !menu) return;

    function openMenu() {
      menu.classList.add('active', 'open');
      toggle.setAttribute('aria-expanded', 'true');
      toggle.classList.add('active');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
      // Animar hamburguesa → X
      animateHamburger(toggle, true);
    }

    function closeMenu() {
      menu.classList.remove('active', 'open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.classList.remove('active');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
      animateHamburger(toggle, false);
    }

    toggle.addEventListener('click', () => {
      const isOpen = menu.classList.contains('active');
      isOpen ? closeMenu() : openMenu();
    });

    overlay.addEventListener('click', closeMenu);

    // Cerrar al hacer tap en un link
    qsa('a', menu).forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // Cerrar al rotar a landscape
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) closeMenu();
    });

    // Escape key
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeMenu();
    });
  }

  function animateHamburger(btn, open) {
    const bars = qsa('.bar, .hamburger-bar, span', btn);
    if (bars.length < 3) return;
    if (open) {
      bars[0].style.transform = 'translateY(8px) rotate(45deg)';
      bars[1].style.opacity   = '0';
      bars[2].style.transform = 'translateY(-8px) rotate(-45deg)';
    } else {
      bars[0].style.transform = '';
      bars[1].style.opacity   = '';
      bars[2].style.transform = '';
    }
  }

  /* ================================================================
     2. SIDEBAR DEL PORTAL / ADMIN
     ================================================================ */
  function initSidebar() {
    const toggleBtns = qsa('.portal-menu-btn, .admin-menu-btn, .sidebar-toggle');
    const sidebar    = qs('.portal-sidebar, .admin-sidebar');
    if (!sidebar) return;

    const overlay = qs('.sidebar-overlay') || createOverlay('sidebar-overlay', 898);

    function openSidebar() {
      sidebar.classList.add('open');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
      sidebar.classList.remove('open');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }

    toggleBtns.forEach(btn => btn.addEventListener('click', () => {
      sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    }));

    overlay.addEventListener('click', closeSidebar);

    // Cerrar al hacer tap en nav item
    qsa('a, .nav-item', sidebar).forEach(item => {
      item.addEventListener('click', () => {
        if (isMobile()) closeSidebar();
      });
    });

    // Swipe izquierda para cerrar
    let startX = 0;
    sidebar.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
    sidebar.addEventListener('touchend', e => {
      const diff = startX - e.changedTouches[0].clientX;
      if (diff > 60) closeSidebar();
    }, { passive: true });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeSidebar();
    });
  }

  /* ================================================================
     3. BOTTOM NAVIGATION (Portal y Admin)
     ================================================================ */
  function initBottomNav() {
    const portalNav = qs('.portal-bottom-nav');
    const adminNav  = qs('.admin-bottom-nav');
    const nav = portalNav || adminNav;
    if (!nav || !isMobile()) return;

    // Marcar item activo según URL actual
    const currentPath = window.location.pathname;
    qsa('.bottom-nav-item', nav).forEach(item => {
      const href = item.getAttribute('href') || item.getAttribute('data-href') || '';
      if (href && currentPath.includes(href)) {
        item.classList.add('active');
      }
    });

    // Feedback táctil
    qsa('.bottom-nav-item', nav).forEach(item => {
      item.addEventListener('touchstart', () => {
        item.style.transform = 'scale(0.92)';
        item.style.opacity   = '0.8';
      }, { passive: true });
      item.addEventListener('touchend', () => {
        item.style.transform = '';
        item.style.opacity   = '';
      }, { passive: true });
    });
  }

  /* ================================================================
     4. SCROLL SUAVE Y HEADER STICKY
     ================================================================ */
  function initStickyHeader() {
    const header = qs('.navbar, .portal-header, .admin-header');
    if (!header) return;

    let lastY = 0;
    let ticking = false;

    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const currentY = window.scrollY;

          if (isMobile()) {
            // Ocultar navbar al scrollear hacia abajo (más de 60px)
            if (currentY > lastY + 10 && currentY > 60) {
              header.style.transform = 'translateY(-100%)';
            } else if (currentY < lastY - 5 || currentY < 60) {
              header.style.transform = 'translateY(0)';
            }
            header.style.transition = 'transform 0.25s ease';
          }

          // Sombra al scrollear
          if (currentY > 10) {
            header.classList.add('scrolled');
          } else {
            header.classList.remove('scrolled');
          }

          lastY = currentY;
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  /* ================================================================
     5. INPUTS — Prevenir zoom en iOS al enfocar
     ================================================================ */
  function initInputFix() {
    // iOS hace zoom si font-size < 16px — forzamos 16px en focus
    const inputs = qsa('input[type="text"], input[type="email"], input[type="password"], input[type="tel"], select, textarea');
    inputs.forEach(input => {
      const originalSize = input.style.fontSize;
      input.addEventListener('focus', () => {
        if (isMobile()) input.style.fontSize = '16px';
      });
      input.addEventListener('blur', () => {
        input.style.fontSize = originalSize;
      });
    });
  }

  /* ================================================================
     6. MODALES — Bottom sheet en mobile
     ================================================================ */
  function initModals() {
    // Prevenir scroll del fondo cuando modal está abierto
    const observer = new MutationObserver(() => {
      const open = qs('.modal.show, .modal.active, .modal[style*="display: block"]');
      document.body.style.overflow = open && isMobile() ? 'hidden' : '';
    });

    const container = qs('.modal-container, body');
    if (container) {
      observer.observe(container, { attributes: true, childList: true, subtree: true });
    }
  }

  /* ================================================================
     7. SWIPE TO REFRESH (Portal)
     ================================================================ */
  function initPullToRefresh() {
    if (!isMobile()) return;
    const content = qs('.portal-content, .admin-content');
    if (!content) return;

    let startY = 0;
    let pulling = false;
    let indicator = null;

    content.addEventListener('touchstart', e => {
      if (content.scrollTop === 0) {
        startY = e.touches[0].clientY;
        pulling = true;
      }
    }, { passive: true });

    content.addEventListener('touchmove', e => {
      if (!pulling) return;
      const diff = e.touches[0].clientY - startY;
      if (diff > 60 && !indicator) {
        indicator = document.createElement('div');
        indicator.className = 'pull-refresh-indicator';
        indicator.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Actualizando...';
        indicator.style.cssText = 'position:fixed;top:70px;left:50%;transform:translateX(-50%);background:#C8151B;color:#fff;padding:8px 18px;border-radius:20px;font-size:13px;z-index:9999;display:flex;align-items:center;gap:8px;';
        document.body.appendChild(indicator);
      }
    }, { passive: true });

    content.addEventListener('touchend', e => {
      const diff = e.changedTouches[0].clientY - startY;
      if (pulling && diff > 80 && indicator) {
        setTimeout(() => {
          window.location.reload();
        }, 500);
      }
      if (indicator) {
        indicator.remove();
        indicator = null;
      }
      pulling = false;
    }, { passive: true });
  }

  /* ================================================================
     8. LAZY LOADING DE IMÁGENES
     ================================================================ */
  function initLazyImages() {
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
              img.classList.add('loaded');
            }
            observer.unobserve(img);
          }
        });
      }, { rootMargin: '50px 0px' });

      qsa('img[data-src]').forEach(img => observer.observe(img));
    }
  }

  /* ================================================================
     UTILIDADES INTERNAS
     ================================================================ */
  function createOverlay(cls, zIndex) {
    const div = document.createElement('div');
    div.className = cls;
    div.style.cssText = `
      display:none;position:fixed;inset:0;
      background:rgba(0,0,0,.52);z-index:${zIndex};
      backdrop-filter:blur(2px);
    `;
    div.style.setProperty('--active-display', 'block');
    document.body.appendChild(div);

    // CSS para .active
    const style = document.createElement('style');
    style.textContent = `.${cls}.active{display:block!important}`;
    document.head.appendChild(style);

    return div;
  }

  /* ================================================================
     INIT
     ================================================================ */
  function init() {
    initNavbar();
    initSidebar();
    initBottomNav();
    initStickyHeader();
    initInputFix();
    initModals();
    initLazyImages();

    if (isMobile()) {
      initPullToRefresh();
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
/* ── FIN CRISAMEX Mobile JS ──────────────────────────────────── */
