  </div><!-- /adm-content -->
</main><!-- /adm-main -->

<!-- Bottom nav mobile -->
<nav class="adm-bnav">
  <a href="/admin" class="bn-it <?= ($adminPage??'')==='dashboard'?'act':'' ?>">
    <i class="fas fa-tachometer-alt"></i><span>Inicio</span>
  </a>
  <a href="/admin/comunicaciones" class="bn-it <?= ($adminPage??'')==='comunicaciones'?'act':'' ?>">
    <i class="fas fa-comments"></i><span>Chat</span>
    <?php $np=Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE de='cliente' AND leido_admin=0"); if($np&&$np['c']>0): ?>
    <span class="bn-dot"></span>
    <?php endif; ?>
  </a>
  <a href="/admin/clientes" class="bn-it <?= ($adminPage??'')==='clientes'?'act':'' ?>">
    <i class="fas fa-users-cog"></i><span>Clientes</span>
  </a>
  <a href="/admin/mensajes" class="bn-it <?= ($adminPage??'')==='mensajes'?'act':'' ?>">
    <i class="fas fa-inbox"></i><span>Mensajes</span>
    <?php $nn=Database::fetchOne("SELECT COUNT(*) as c FROM contacto_mensajes WHERE leido=0"); if($nn&&$nn['c']>0): ?>
    <span class="bn-dot"></span>
    <?php endif; ?>
  </a>
  <a href="/admin/configuracion" class="bn-it <?= ($adminPage??'')==='configuracion'?'act':'' ?>">
    <i class="fas fa-sliders-h"></i><span>Config</span>
  </a>
</nav>

</body>
</html>
