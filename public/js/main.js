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
