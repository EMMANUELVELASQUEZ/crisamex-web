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
/* ================================================================
   CRISAMEX — Portal & Admin JS v3.0
   Agregar al final de: crisamex/public/js/main.js
   ================================================================ */
(function(){
'use strict';

const $ = s => document.querySelector(s);
const $$ = s => [...document.querySelectorAll(s)];
const isMobile = () => window.innerWidth < 1024;

/* ── 1. SIDEBAR DRAWER ─────────────────────────────────────── */
function initSidebar(){
  const sidebar  = $('.app-sidebar');
  const overlay  = $('.sidebar-overlay');
  const openBtns = $$('.topbar-menu-btn, .sidebar-toggle');
  if(!sidebar) return;

  const open  = ()=>{ sidebar.classList.add('open');    overlay&&overlay.classList.add('active');    document.body.style.overflow='hidden'; };
  const close = ()=>{ sidebar.classList.remove('open'); overlay&&overlay.classList.remove('active'); document.body.style.overflow=''; };

  openBtns.forEach(b=>b.addEventListener('click',()=>sidebar.classList.contains('open')?close():open()));
  overlay&&overlay.addEventListener('click', close);

  // Cerrar al navegar (mobile)
  $$('.sidebar-link', sidebar).forEach(link=>{
    link.addEventListener('click',()=>{ if(isMobile()) close(); });
  });

  // Swipe derecha → izquierda para cerrar
  let startX=0;
  sidebar.addEventListener('touchstart',e=>{ startX=e.touches[0].clientX; },{passive:true});
  sidebar.addEventListener('touchend',e=>{
    if(startX - e.changedTouches[0].clientX > 60) close();
  },{passive:true});

  document.addEventListener('keydown',e=>{ if(e.key==='Escape') close(); });
}

/* ── 2. BOTTOM NAVIGATION ACTIVO ──────────────────────────── */
function initBottomNav(){
  const nav = $('.app-bottom-nav');
  if(!nav) return;
  const path = location.pathname;
  $$('.bottom-nav-item',nav).forEach(item=>{
    const href = item.getAttribute('href')||'';
    if(href && path.includes(href)) item.classList.add('active');
    item.addEventListener('touchstart',()=>{
      item.style.transform='scale(0.88)'; item.style.transition='transform .1s';
    },{passive:true});
    item.addEventListener('touchend',()=>{ item.style.transform=''; },{passive:true});
  });
}

/* ── 3. CHAT ───────────────────────────────────────────────── */
function initChat(){
  const layout   = $('.chat-layout');
  if(!layout) return;

  const list     = $('.chat-list');
  const thread   = $('.chat-thread');
  const backBtn  = $('.chat-back-btn');
  const textarea = $('.chat-input-area textarea');
  const sendBtn  = $('.chat-send-btn');
  const messages = $('.chat-messages');

  // Mobile: mostrar lista primero, thread al seleccionar
  function showThread(){
    if(isMobile()){
      list&&list.classList.add('hidden');
      thread&&thread.classList.remove('hidden');
    }
  }
  function showList(){
    if(isMobile()){
      list&&list.classList.remove('hidden');
      thread&&thread.classList.add('hidden');
    }
  }

  $$('.chat-item').forEach(item=>{
    item.addEventListener('click',()=>{
      $$('.chat-item').forEach(i=>i.classList.remove('active'));
      item.classList.add('active');
      item.querySelector('.chat-item-badge')?.remove();
      showThread();
    });
  });

  backBtn&&backBtn.addEventListener('click', showList);

  // Auto-resize textarea
  if(textarea){
    textarea.addEventListener('input',()=>{
      textarea.style.height='auto';
      textarea.style.height = Math.min(textarea.scrollHeight, 120)+'px';
    });
    // Enter envía (Shift+Enter = nueva línea)
    textarea.addEventListener('keydown',e=>{
      if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); sendMsg(); }
    });
  }

  // Enviar mensaje
  function sendMsg(){
    if(!textarea||!textarea.value.trim()) return;
    const text = textarea.value.trim();
    textarea.value=''; textarea.style.height='auto';
    if(!messages) return;
    const now = new Date().toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'});
    const bubble = document.createElement('div');
    bubble.className='msg-bubble sent animate-up';
    bubble.innerHTML=`
      <div class="msg-content">
        <div class="msg-text">${escHtml(text)}</div>
        <div class="msg-meta">${now}</div>
      </div>`;
    messages.appendChild(bubble);
    messages.scrollTo({top:messages.scrollHeight,behavior:'smooth'});
  }

  sendBtn&&sendBtn.addEventListener('click', sendMsg);

  // Scroll al fondo al cargar
  if(messages) messages.scrollTop = messages.scrollHeight;
}

/* ── 4. TOPBAR — HIDE ON SCROLL DOWN ───────────────────────── */
function initTopbar(){
  const topbar = $('.app-topbar');
  if(!topbar||!isMobile()) return;
  let lastY=0, ticking=false;
  window.addEventListener('scroll',()=>{
    if(!ticking){
      requestAnimationFrame(()=>{
        const y = window.scrollY;
        topbar.style.transition='transform .25s ease';
        if(y>lastY+8 && y>60) topbar.style.transform='translateY(-100%)';
        else if(y<lastY-5||y<60) topbar.style.transform='translateY(0)';
        lastY=y; ticking=false;
      });
      ticking=true;
    }
  },{passive:true});
}

/* ── 5. MODALES (bottom sheet en mobile) ───────────────────── */
function initModals(){
  $$('[data-modal]').forEach(btn=>{
    const id = btn.dataset.modal;
    const modal = document.getElementById(id);
    if(!modal) return;
    btn.addEventListener('click',()=>{
      modal.style.display='flex';
      requestAnimationFrame(()=>modal.classList.add('open'));
      document.body.style.overflow='hidden';
    });
    modal.addEventListener('click',e=>{
      if(e.target===modal) closeModal(modal);
    });
    modal.querySelector('[data-close]')?.addEventListener('click',()=>closeModal(modal));
  });
  document.addEventListener('keydown',e=>{
    if(e.key==='Escape') $$('.modal.open').forEach(closeModal);
  });
  function closeModal(m){
    m.classList.remove('open');
    setTimeout(()=>{ m.style.display='none'; document.body.style.overflow=''; },250);
  }
}

/* ── 6. FORMULARIOS — feedback visual ─────────────────────── */
function initForms(){
  // Auto-focus primer campo en desktop
  if(!isMobile()){
    const first = $('input:not([type=hidden]):not([type=checkbox])');
    first&&first.focus();
  }

  // Toggle password visibility
  $$('.toggle-pwd').forEach(btn=>{
    const input = btn.previousElementSibling||btn.closest('.field-input')?.querySelector('input');
    if(!input) return;
    btn.addEventListener('click',()=>{
      const isText = input.type==='text';
      input.type = isText?'password':'text';
      btn.className = `toggle-pwd fas fa-eye${isText?'':'-slash'}`;
    });
  });

  // Plan selector
  $$('.plan-opt').forEach(opt=>{
    opt.addEventListener('click',()=>{
      $$('.plan-opt').forEach(o=>o.classList.remove('selected'));
      opt.classList.add('selected');
      const radio = opt.querySelector('input[type=radio]');
      if(radio) radio.checked=true;
    });
  });

  // Submit con loading state
  $$('form').forEach(form=>{
    form.addEventListener('submit',()=>{
      const btn = form.querySelector('[type=submit]');
      if(btn){
        btn.disabled=true;
        btn.innerHTML=`<i class="fas fa-spinner fa-spin"></i>&nbsp;Procesando...`;
      }
    });
  });
}

/* ── 7. TABLAS — selección y acciones ─────────────────────── */
function initTables(){
  const checkAll = $('[data-check-all]');
  if(checkAll){
    checkAll.addEventListener('change',()=>{
      $$('[data-check-row]').forEach(cb=>{ cb.checked=checkAll.checked; });
      updateBulkActions();
    });
    $$('[data-check-row]').forEach(cb=>{
      cb.addEventListener('change', updateBulkActions);
    });
  }
  function updateBulkActions(){
    const selected = $$('[data-check-row]:checked').length;
    const bar = $('.bulk-actions');
    if(bar) bar.style.display = selected>0?'flex':'none';
    const count = $('.bulk-count');
    if(count) count.textContent=`${selected} seleccionados`;
  }
}

/* ── 8. NOTIFICACIONES — marcar como leída ─────────────────── */
function initNotifications(){
  $$('.notif-item.unread').forEach(item=>{
    item.addEventListener('click',()=>{
      item.classList.remove('unread');
      const dot = $('.topbar-icon-btn .dot');
      const unreadCount = $$('.notif-item.unread').length;
      if(dot && unreadCount===0) dot.style.display='none';
    });
  });
}

/* ── 9. RESIZE ─────────────────────────────────────────────── */
window.addEventListener('resize',()=>{
  if(!isMobile()){
    const sidebar = $('.app-sidebar');
    const overlay = $('.sidebar-overlay');
    sidebar&&sidebar.classList.remove('open');
    overlay&&overlay.classList.remove('active');
    document.body.style.overflow='';
  }
});

/* ── UTILS ──────────────────────────────────────────────────── */
function escHtml(t){
  const d=document.createElement('div');
  d.appendChild(document.createTextNode(t));
  return d.innerHTML;
}

/* ── INIT ───────────────────────────────────────────────────── */
function init(){
  initSidebar();
  initBottomNav();
  initChat();
  initTopbar();
  initModals();
  initForms();
  initTables();
  initNotifications();
}

if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init);
else init();

})();
/* ── FIN CRISAMEX Portal & Admin JS v3.0 ─────────────────────── */
/* ================================================================
   CRISAMEX — Portal & Admin JS v3.0
   Agregar al final de: crisamex/public/js/main.js
   ================================================================ */
(function(){
'use strict';

const $ = s => document.querySelector(s);
const $$ = s => [...document.querySelectorAll(s)];
const isMobile = () => window.innerWidth < 1024;

/* ── 1. SIDEBAR DRAWER ─────────────────────────────────────── */
function initSidebar(){
  const sidebar  = $('.app-sidebar');
  const overlay  = $('.sidebar-overlay');
  const openBtns = $$('.topbar-menu-btn, .sidebar-toggle');
  if(!sidebar) return;

  const open  = ()=>{ sidebar.classList.add('open');    overlay&&overlay.classList.add('active');    document.body.style.overflow='hidden'; };
  const close = ()=>{ sidebar.classList.remove('open'); overlay&&overlay.classList.remove('active'); document.body.style.overflow=''; };

  openBtns.forEach(b=>b.addEventListener('click',()=>sidebar.classList.contains('open')?close():open()));
  overlay&&overlay.addEventListener('click', close);

  // Cerrar al navegar (mobile)
  $$('.sidebar-link', sidebar).forEach(link=>{
    link.addEventListener('click',()=>{ if(isMobile()) close(); });
  });

  // Swipe derecha → izquierda para cerrar
  let startX=0;
  sidebar.addEventListener('touchstart',e=>{ startX=e.touches[0].clientX; },{passive:true});
  sidebar.addEventListener('touchend',e=>{
    if(startX - e.changedTouches[0].clientX > 60) close();
  },{passive:true});

  document.addEventListener('keydown',e=>{ if(e.key==='Escape') close(); });
}

/* ── 2. BOTTOM NAVIGATION ACTIVO ──────────────────────────── */
function initBottomNav(){
  const nav = $('.app-bottom-nav');
  if(!nav) return;
  const path = location.pathname;
  $$('.bottom-nav-item',nav).forEach(item=>{
    const href = item.getAttribute('href')||'';
    if(href && path.includes(href)) item.classList.add('active');
    item.addEventListener('touchstart',()=>{
      item.style.transform='scale(0.88)'; item.style.transition='transform .1s';
    },{passive:true});
    item.addEventListener('touchend',()=>{ item.style.transform=''; },{passive:true});
  });
}

/* ── 3. CHAT ───────────────────────────────────────────────── */
function initChat(){
  const layout   = $('.chat-layout');
  if(!layout) return;

  const list     = $('.chat-list');
  const thread   = $('.chat-thread');
  const backBtn  = $('.chat-back-btn');
  const textarea = $('.chat-input-area textarea');
  const sendBtn  = $('.chat-send-btn');
  const messages = $('.chat-messages');

  // Mobile: mostrar lista primero, thread al seleccionar
  function showThread(){
    if(isMobile()){
      list&&list.classList.add('hidden');
      thread&&thread.classList.remove('hidden');
    }
  }
  function showList(){
    if(isMobile()){
      list&&list.classList.remove('hidden');
      thread&&thread.classList.add('hidden');
    }
  }

  $$('.chat-item').forEach(item=>{
    item.addEventListener('click',()=>{
      $$('.chat-item').forEach(i=>i.classList.remove('active'));
      item.classList.add('active');
      item.querySelector('.chat-item-badge')?.remove();
      showThread();
    });
  });

  backBtn&&backBtn.addEventListener('click', showList);

  // Auto-resize textarea
  if(textarea){
    textarea.addEventListener('input',()=>{
      textarea.style.height='auto';
      textarea.style.height = Math.min(textarea.scrollHeight, 120)+'px';
    });
    // Enter envía (Shift+Enter = nueva línea)
    textarea.addEventListener('keydown',e=>{
      if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); sendMsg(); }
    });
  }

  // Enviar mensaje
  function sendMsg(){
    if(!textarea||!textarea.value.trim()) return;
    const text = textarea.value.trim();
    textarea.value=''; textarea.style.height='auto';
    if(!messages) return;
    const now = new Date().toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'});
    const bubble = document.createElement('div');
    bubble.className='msg-bubble sent animate-up';
    bubble.innerHTML=`
      <div class="msg-content">
        <div class="msg-text">${escHtml(text)}</div>
        <div class="msg-meta">${now}</div>
      </div>`;
    messages.appendChild(bubble);
    messages.scrollTo({top:messages.scrollHeight,behavior:'smooth'});
  }

  sendBtn&&sendBtn.addEventListener('click', sendMsg);

  // Scroll al fondo al cargar
  if(messages) messages.scrollTop = messages.scrollHeight;
}

/* ── 4. TOPBAR — HIDE ON SCROLL DOWN ───────────────────────── */
function initTopbar(){
  const topbar = $('.app-topbar');
  if(!topbar||!isMobile()) return;
  let lastY=0, ticking=false;
  window.addEventListener('scroll',()=>{
    if(!ticking){
      requestAnimationFrame(()=>{
        const y = window.scrollY;
        topbar.style.transition='transform .25s ease';
        if(y>lastY+8 && y>60) topbar.style.transform='translateY(-100%)';
        else if(y<lastY-5||y<60) topbar.style.transform='translateY(0)';
        lastY=y; ticking=false;
      });
      ticking=true;
    }
  },{passive:true});
}

/* ── 5. MODALES (bottom sheet en mobile) ───────────────────── */
function initModals(){
  $$('[data-modal]').forEach(btn=>{
    const id = btn.dataset.modal;
    const modal = document.getElementById(id);
    if(!modal) return;
    btn.addEventListener('click',()=>{
      modal.style.display='flex';
      requestAnimationFrame(()=>modal.classList.add('open'));
      document.body.style.overflow='hidden';
    });
    modal.addEventListener('click',e=>{
      if(e.target===modal) closeModal(modal);
    });
    modal.querySelector('[data-close]')?.addEventListener('click',()=>closeModal(modal));
  });
  document.addEventListener('keydown',e=>{
    if(e.key==='Escape') $$('.modal.open').forEach(closeModal);
  });
  function closeModal(m){
    m.classList.remove('open');
    setTimeout(()=>{ m.style.display='none'; document.body.style.overflow=''; },250);
  }
}

/* ── 6. FORMULARIOS — feedback visual ─────────────────────── */
function initForms(){
  // Auto-focus primer campo en desktop
  if(!isMobile()){
    const first = $('input:not([type=hidden]):not([type=checkbox])');
    first&&first.focus();
  }

  // Toggle password visibility
  $$('.toggle-pwd').forEach(btn=>{
    const input = btn.previousElementSibling||btn.closest('.field-input')?.querySelector('input');
    if(!input) return;
    btn.addEventListener('click',()=>{
      const isText = input.type==='text';
      input.type = isText?'password':'text';
      btn.className = `toggle-pwd fas fa-eye${isText?'':'-slash'}`;
    });
  });

  // Plan selector
  $$('.plan-opt').forEach(opt=>{
    opt.addEventListener('click',()=>{
      $$('.plan-opt').forEach(o=>o.classList.remove('selected'));
      opt.classList.add('selected');
      const radio = opt.querySelector('input[type=radio]');
      if(radio) radio.checked=true;
    });
  });

  // Submit con loading state
  $$('form').forEach(form=>{
    form.addEventListener('submit',()=>{
      const btn = form.querySelector('[type=submit]');
      if(btn){
        btn.disabled=true;
        btn.innerHTML=`<i class="fas fa-spinner fa-spin"></i>&nbsp;Procesando...`;
      }
    });
  });
}

/* ── 7. TABLAS — selección y acciones ─────────────────────── */
function initTables(){
  const checkAll = $('[data-check-all]');
  if(checkAll){
    checkAll.addEventListener('change',()=>{
      $$('[data-check-row]').forEach(cb=>{ cb.checked=checkAll.checked; });
      updateBulkActions();
    });
    $$('[data-check-row]').forEach(cb=>{
      cb.addEventListener('change', updateBulkActions);
    });
  }
  function updateBulkActions(){
    const selected = $$('[data-check-row]:checked').length;
    const bar = $('.bulk-actions');
    if(bar) bar.style.display = selected>0?'flex':'none';
    const count = $('.bulk-count');
    if(count) count.textContent=`${selected} seleccionados`;
  }
}

/* ── 8. NOTIFICACIONES — marcar como leída ─────────────────── */
function initNotifications(){
  $$('.notif-item.unread').forEach(item=>{
    item.addEventListener('click',()=>{
      item.classList.remove('unread');
      const dot = $('.topbar-icon-btn .dot');
      const unreadCount = $$('.notif-item.unread').length;
      if(dot && unreadCount===0) dot.style.display='none';
    });
  });
}

/* ── 9. RESIZE ─────────────────────────────────────────────── */
window.addEventListener('resize',()=>{
  if(!isMobile()){
    const sidebar = $('.app-sidebar');
    const overlay = $('.sidebar-overlay');
    sidebar&&sidebar.classList.remove('open');
    overlay&&overlay.classList.remove('active');
    document.body.style.overflow='';
  }
});

/* ── UTILS ──────────────────────────────────────────────────── */
function escHtml(t){
  const d=document.createElement('div');
  d.appendChild(document.createTextNode(t));
  return d.innerHTML;
}

/* ── INIT ───────────────────────────────────────────────────── */
function init(){
  initSidebar();
  initBottomNav();
  initChat();
  initTopbar();
  initModals();
  initForms();
  initTables();
  initNotifications();
}

if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init);
else init();

})();
/* ── FIN CRISAMEX Portal & Admin JS v3.0 ─────────────────────── */
/* ================================================================
   CRISAMEX PORTAL — JS Premium v4.0
   Todos los botones funcionan · Sin bugs en mobile
   ================================================================ */
(function(){
'use strict';

/* ── UTILS ──────────────────────────────────────────────────── */
var $ = function(s,ctx){ return (ctx||document).querySelector(s); };
var $$ = function(s,ctx){ return [].slice.call((ctx||document).querySelectorAll(s)); };
var isMob = function(){ return window.innerWidth < 1024; };
var on = function(el,ev,fn,opts){ if(el) el.addEventListener(ev,fn,opts||false); };

/* ── 1. SIDEBAR ─────────────────────────────────────────────── */
function initSidebar(){
  var sb  = $('.ap-sb');
  var ov  = $('.sb-ov');
  var btn = $('.top-mb');
  if(!sb) return;

  function open(){
    sb.classList.add('open');
    ov && ov.classList.add('on');
    document.body.style.overflow = 'hidden';
    btn && btn.setAttribute('aria-expanded','true');
    // Animar ≡ → ✕
    animBurger(btn, true);
  }
  function close(){
    sb.classList.remove('open');
    ov && ov.classList.remove('on');
    document.body.style.overflow = '';
    btn && btn.setAttribute('aria-expanded','false');
    animBurger(btn, false);
  }

  on(btn, 'click', function(){ sb.classList.contains('open') ? close() : open(); });
  on(ov,  'click', close);

  // Cerrar al tocar link del sidebar en mobile
  $$('.sb-lnk', sb).forEach(function(a){
    on(a,'click',function(){ if(isMob()) setTimeout(close,120); });
  });

  // Swipe izquierda para cerrar
  var sx = 0;
  on(sb,'touchstart',function(e){ sx = e.touches[0].clientX; },{passive:true});
  on(sb,'touchend',function(e){
    if(sx - e.changedTouches[0].clientX > 55) close();
  },{passive:true});

  on(document,'keydown',function(e){ if(e.key==='Escape') close(); });

  // Resize: cerrar en desktop
  on(window,'resize',function(){
    if(!isMob()){ close(); document.body.style.overflow=''; }
  });
}

function animBurger(btn, open){
  if(!btn) return;
  var icon = $('i', btn) || $('span', btn);
  if(!icon) return;
  icon.className = open ? 'fas fa-times' : 'fas fa-bars';
  icon.style.fontSize = '18px';
}

/* ── 2. TOPBAR — ocultar al scrollear hacia abajo ───────────── */
function initTopbar(){
  var top = $('.ap-top');
  if(!top || !isMob()) return;
  var lastY = 0, tick = false;
  on(window,'scroll',function(){
    if(!tick){
      requestAnimationFrame(function(){
        var y = window.scrollY;
        top.style.transition = 'transform .22s ease';
        if(y > lastY + 8 && y > 80)  top.style.transform = 'translateY(-100%)';
        else if(y < lastY - 5 || y < 80) top.style.transform = 'translateY(0)';
        lastY = y; tick = false;
      });
      tick = true;
    }
  },{passive:true});
}

/* ── 3. BOTTOM NAV — marcar activo ─────────────────────────── */
function initBottomNav(){
  var nav = $('.ap-bnav');
  if(!nav) return;
  var path = window.location.pathname;
  $$('.bn-it', nav).forEach(function(item){
    var href = item.getAttribute('href') || '';
    if(href && (path === href || path.startsWith(href + '/')))
      item.classList.add('act');
    // Feedback táctil
    on(item,'touchstart',function(){
      item.style.transition='transform .08s';
      item.style.transform='scale(.88)';
    },{passive:true});
    on(item,'touchend',function(){
      setTimeout(function(){ item.style.transform=''; },120);
    },{passive:true});
  });
}

/* ── 4. CHAT ────────────────────────────────────────────────── */
function initChat(){
  var wrap   = $('.chat-wrap');
  if(!wrap) return;
  var list   = $('.ch-list');
  var thread = $('.ch-thread');
  var back   = $('.ch-back');
  var ta     = $('textarea', $('.ch-inp'));
  var send   = $('.ch-send');
  var msgs   = $('.ch-msgs');

  // Mobile: lista ↔ thread
  function showThread(){
    if(isMob()){
      list   && list.classList.add('hide');
      thread && thread.classList.remove('hide');
    }
  }
  function showList(){
    if(isMob()){
      list   && list.classList.remove('hide');
      thread && thread.classList.add('hide');
    }
  }

  $$('.ch-item').forEach(function(item){
    on(item,'click',function(){
      $$('.ch-item').forEach(function(i){ i.classList.remove('act'); });
      item.classList.add('act');
      // Quitar badge de no leído
      var bd = item.querySelector('.ch-bd');
      if(bd) bd.remove();
      item.classList.remove('unread');
      showThread();
    });
  });

  on(back,'click', showList);

  // Auto-resize textarea
  on(ta,'input',function(){
    ta.style.height = 'auto';
    ta.style.height = Math.min(ta.scrollHeight, 110) + 'px';
  });

  // Enter envía, Shift+Enter = nueva línea
  on(ta,'keydown',function(e){
    if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); doSend(); }
  });

  on(send,'click', doSend);

  function doSend(){
    if(!ta || !ta.value.trim() || !msgs) return;
    var txt = ta.value.trim();
    ta.value = ''; ta.style.height = 'auto';
    var now = new Date().toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'});
    var div = document.createElement('div');
    div.className = 'msg out slide-up';
    div.innerHTML = '<div class="msg-body">'
      + '<div class="msg-txt">' + esc(txt) + '</div>'
      + '<div class="msg-mt">' + now + '</div>'
      + '</div>';
    msgs.appendChild(div);
    msgs.scrollTo({top:msgs.scrollHeight, behavior:'smooth'});
    ta.focus();
  }

  // Scroll al fondo al cargar
  if(msgs) msgs.scrollTop = msgs.scrollHeight;
}

/* ── 5. TOGGLE PASSWORD ─────────────────────────────────────── */
function initPasswordToggle(){
  $$('.pwd-eye').forEach(function(btn){
    on(btn,'click',function(){
      var wrap  = btn.closest('.fld-wrap');
      var input = wrap && wrap.querySelector('input');
      if(!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.className = 'pwd-eye fas fa-eye' + (show ? '-slash' : '');
    });
  });
}

/* ── 6. PLAN SELECTOR ───────────────────────────────────────── */
function initPlanSelector(){
  $$('.plan-opt').forEach(function(opt){
    on(opt,'click',function(){
      $$('.plan-opt').forEach(function(o){ o.classList.remove('sel'); });
      opt.classList.add('sel');
      var radio = opt.querySelector('input[type="radio"]');
      if(radio) radio.checked = true;
    });
  });
}

/* ── 7. FORMULARIOS — loading state ────────────────────────── */
function initForms(){
  $$('form').forEach(function(form){
    on(form,'submit',function(){
      var btn = form.querySelector('[type="submit"]');
      if(!btn) return;
      setTimeout(function(){
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp;Procesando...';
      },10);
    });
  });

  // Auto-focus primer campo en desktop
  if(!isMob()){
    var first = $('input:not([type="hidden"]):not([type="checkbox"])');
    if(first) setTimeout(function(){ first.focus(); },300);
  }

  // Prevenir doble submit
  $$('[data-once]').forEach(function(btn){
    on(btn,'click',function(){
      if(btn.dataset.clicked) return false;
      btn.dataset.clicked = '1';
    });
  });
}

/* ── 8. NOTIFICACIONES ──────────────────────────────────────── */
function initNotifs(){
  var markAll = $('#markAllRead');
  on(markAll,'click',function(){
    $$('.notif-it.unread').forEach(function(it){ it.classList.remove('unread'); });
    var dot = $('.top-dot');
    if(dot) dot.style.display = 'none';
    // Actualizar badge bottom nav
    var bnav = $('.bn-bd');
    if(bnav) bnav.style.display = 'none';
  });

  // Marcar individual al tocar
  $$('.notif-it.unread').forEach(function(it){
    on(it,'click',function(){
      it.classList.remove('unread');
      var left = $$('.notif-it.unread').length;
      if(left === 0){
        var dot = $('.top-dot');
        if(dot) dot.style.display = 'none';
      }
    });
  });
}

/* ── 9. MODALES — bottom sheet en mobile ───────────────────── */
function initModals(){
  $$('[data-modal-open]').forEach(function(btn){
    var id = btn.getAttribute('data-modal-open');
    var modal = document.getElementById(id);
    if(!modal) return;
    on(btn,'click',function(){
      modal.style.display = 'flex';
      requestAnimationFrame(function(){ modal.classList.add('open'); });
      document.body.style.overflow = 'hidden';
    });
    // Cerrar al tocar overlay
    on(modal,'click',function(e){
      if(e.target === modal) closeModal(modal);
    });
    $$('[data-modal-close]', modal).forEach(function(x){
      on(x,'click',function(){ closeModal(modal); });
    });
  });

  on(document,'keydown',function(e){
    if(e.key==='Escape') $$('.modal.open').forEach(closeModal);
  });

  function closeModal(m){
    m.classList.remove('open');
    setTimeout(function(){ m.style.display='none'; document.body.style.overflow=''; },250);
  }
}

/* ── 10. TABLAS — checkbox selección múltiple ───────────────── */
function initTables(){
  var chkAll = $('[data-check-all]');
  if(!chkAll) return;
  on(chkAll,'change',function(){
    $$('[data-check-row]').forEach(function(c){ c.checked = chkAll.checked; });
    updateBulk();
  });
  $$('[data-check-row]').forEach(function(c){
    on(c,'change', updateBulk);
  });
  function updateBulk(){
    var n = $$('[data-check-row]:checked').length;
    var bar = $('.bulk-bar');
    if(bar) bar.style.display = n>0 ? 'flex' : 'none';
    var cnt = $('.bulk-count');
    if(cnt) cnt.textContent = n + ' seleccionado' + (n===1?'':'s');
  }
}

/* ── 11. SKELETONS → cargar datos reales ────────────────────── */
function initSkeletons(){
  // Cargar KPIs si existe el contenedor
  var kpiG = $('#kpiGrid');
  if(!kpiG) return;

  fetch('/portal/api/stats', { credentials:'same-origin' })
    .then(function(r){ return r.ok ? r.json() : Promise.reject(); })
    .then(function(d){
      renderKPIs(kpiG, [
        {ic:'fa-comments', color:'b', label:'Mensajes nuevos', val: d.mensajes||0, sub:'en tu bandeja'},
        {ic:'fa-folder',   color:'a', label:'Documentos',      val: d.documentos||0, sub:'disponibles'},
        {ic:'fa-bell',     color:'r', label:'Notificaciones',  val: d.notifs||0, sub:'sin leer'},
        {ic:'fa-id-card',  color:'g', label:'Mi licencia',     val: d.licencia||'—', sub:'estado actual'},
      ]);
    })
    .catch(function(){
      // Sin conexión: renderizar con placeholder
      renderKPIs(kpiG, [
        {ic:'fa-comments', color:'b', label:'Mensajes',    val:'—', sub:''},
        {ic:'fa-folder',   color:'a', label:'Documentos',  val:'—', sub:''},
        {ic:'fa-bell',     color:'r', label:'Notificaciones',val:'—',sub:''},
        {ic:'fa-id-card',  color:'g', label:'Licencia',    val:'—', sub:''},
      ]);
    });
}

function renderKPIs(grid, items){
  grid.innerHTML = items.map(function(k){
    return '<div class="kpi fade-in">'
      + '<div class="kpi-top"><div class="kpi-ic ' + k.color + '"><i class="fas ' + k.ic + '"></i></div></div>'
      + '<div class="kpi-lbl">' + k.label + '</div>'
      + '<div class="kpi-val">' + k.val + '</div>'
      + (k.sub ? '<div class="kpi-sub">' + k.sub + '</div>' : '')
      + '</div>';
  }).join('');
}

/* ── 12. CONFIRMACIÓN DE ACCIONES DESTRUCTIVAS ──────────────── */
function initConfirm(){
  $$('[data-confirm]').forEach(function(btn){
    on(btn,'click',function(e){
      var msg = btn.getAttribute('data-confirm') || '¿Estás seguro?';
      if(!confirm(msg)) e.preventDefault();
    });
  });
}

/* ── 13. TOOLTIPS MOBILE (tap para ver) ─────────────────────── */
function initTooltips(){
  $$('[data-tip]').forEach(function(el){
    on(el,'touchstart',function(e){
      e.stopPropagation();
      var tip = el.getAttribute('data-tip');
      if(!tip) return;
      var tt = document.createElement('div');
      tt.className = 'tooltip-m';
      tt.textContent = tip;
      tt.style.cssText = 'position:absolute;background:#1E293B;color:#fff;font-size:12px;'
        + 'padding:6px 10px;border-radius:6px;z-index:9999;white-space:nowrap;pointer-events:none;';
      var r = el.getBoundingClientRect();
      tt.style.left = r.left + 'px';
      tt.style.top  = (r.top + window.scrollY - 36) + 'px';
      document.body.appendChild(tt);
      setTimeout(function(){ tt.remove(); },2000);
    },{passive:true});
  });
}

/* ── ESCAPE HELPER ──────────────────────────────────────────── */
function esc(t){
  var d = document.createElement('div');
  d.appendChild(document.createTextNode(t));
  return d.innerHTML;
}

/* ── INIT ───────────────────────────────────────────────────── */
function init(){
  initSidebar();
  initTopbar();
  initBottomNav();
  initChat();
  initPasswordToggle();
  initPlanSelector();
  initForms();
  initNotifs();
  initModals();
  initTables();
  initSkeletons();
  initConfirm();
  initTooltips();
}

if(document.readyState === 'loading')
  document.addEventListener('DOMContentLoaded', init);
else
  init();

})();
/* ── FIN CRISAMEX Portal JS v4.0 ─────────────────────────────── */
