<?php
$adminTitle = 'Dashboard';
$adminPage  = 'dashboard';

$msgs    = Database::fetchOne("SELECT COUNT(*) as c FROM contacto_mensajes")['c'] ?? 0;
$nuevos  = Database::fetchOne("SELECT COUNT(*) as c FROM contacto_mensajes WHERE leido=0")['c'] ?? 0;
$srvs    = Database::fetchOne("SELECT COUNT(*) as c FROM servicios WHERE activo=1")['c'] ?? 0;
$clientes= Database::fetchOne("SELECT COUNT(*) as c FROM clientes WHERE activo=1")['c'] ?? 0;
$msgCli  = Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE de='cliente' AND leido_admin=0")['c'] ?? 0;
$ultims  = Database::fetchAll("SELECT * FROM contacto_mensajes ORDER BY created_at DESC LIMIT 6");
$ultCli  = Database::fetchAll("
  SELECT pm.*, c.nombre, c.empresa
  FROM portal_mensajes pm
  JOIN clientes c ON pm.cliente_id=c.id
  WHERE pm.de='cliente'
  ORDER BY pm.created_at DESC LIMIT 5
");

require_once SRC_PATH.'/views/admin/layout-top.php';
?>

<div class="st-row">
  <div class="st-card">
    <div class="st-ico r"><i class="fas fa-inbox"></i></div>
    <div><div class="st-n"><?= $msgs ?></div><div class="st-l">Mensajes Web</div></div>
  </div>
  <div class="st-card">
    <div class="st-ico y"><i class="fas fa-comments"></i></div>
    <div><div class="st-n"><?= $msgCli ?></div><div class="st-l">Clientes sin leer</div></div>
  </div>
  <div class="st-card">
    <div class="st-ico b"><i class="fas fa-users-cog"></i></div>
    <div><div class="st-n"><?= $clientes ?></div><div class="st-l">Clientes</div></div>
  </div>
  <div class="st-card">
    <div class="st-ico g"><i class="fas fa-cogs"></i></div>
    <div><div class="st-n"><?= $srvs ?></div><div class="st-l">Servicios</div></div>
  </div>
</div>

<!-- ACCESOS RÁPIDOS -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
  <a href="/admin/comunicaciones" style="text-decoration:none;">
    <div class="ac" style="margin:0;padding:14px;display:flex;align-items:center;gap:12px;border-left:3px solid var(--red);cursor:pointer;">
      <div class="st-ico r" style="flex-shrink:0"><i class="fas fa-comments"></i></div>
      <div><div style="font-weight:700;font-size:.85rem;">Chat Clientes</div>
      <div style="font-size:.72rem;color:var(--txt3);"><?= $msgCli ?> sin leer</div></div>
    </div>
  </a>
  <a href="/admin/clientes" style="text-decoration:none;">
    <div class="ac" style="margin:0;padding:14px;display:flex;align-items:center;gap:12px;cursor:pointer;">
      <div class="st-ico b" style="flex-shrink:0"><i class="fas fa-users-cog"></i></div>
      <div><div style="font-weight:700;font-size:.85rem;">Clientes</div>
      <div style="font-size:.72rem;color:var(--txt3);">Gestionar cuentas</div></div>
    </div>
  </a>
  <a href="/admin/servicios" style="text-decoration:none;">
    <div class="ac" style="margin:0;padding:14px;display:flex;align-items:center;gap:12px;cursor:pointer;">
      <div class="st-ico g" style="flex-shrink:0"><i class="fas fa-cogs"></i></div>
      <div><div style="font-weight:700;font-size:.85rem;">Servicios</div>
      <div style="font-size:.72rem;color:var(--txt3);">Editar contenido</div></div>
    </div>
  </a>
  <a href="/admin/configuracion" style="text-decoration:none;">
    <div class="ac" style="margin:0;padding:14px;display:flex;align-items:center;gap:12px;cursor:pointer;">
      <div class="st-ico y" style="flex-shrink:0"><i class="fas fa-sliders-h"></i></div>
      <div><div style="font-weight:700;font-size:.85rem;">Configuración</div>
      <div style="font-size:.72rem;color:var(--txt3);">Datos del sitio</div></div>
    </div>
  </a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

  <!-- Mensajes de clientes del portal -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-comments"></i> Chat con Clientes</h2>
      <a href="/admin/comunicaciones" class="ba ba-r ba-sm"><i class="fas fa-external-link-alt"></i> Abrir chat</a>
    </div>
    <?php if(empty($ultCli)): ?>
    <div style="padding:30px;text-align:center;color:var(--txt3);font-size:.85rem;">
      <i class="fas fa-comment-dots" style="font-size:2rem;display:block;margin-bottom:10px;color:#e0e0e0;"></i>
      Sin mensajes de clientes aún
    </div>
    <?php else: foreach($ultCli as $m): ?>
    <div style="padding:12px 16px;border-bottom:1px solid rgba(0,0,0,.05);display:flex;align-items:flex-start;gap:12px;">
      <div style="width:36px;height:36px;background:rgba(200,21,27,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#C8151B;flex-shrink:0;font-size:.85rem;">
        <?= strtoupper(substr($m['nombre'],0,1)) ?>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:.84rem;font-weight:600;"><?= htmlspecialchars($m['nombre']) ?> <span style="color:var(--txt3);font-weight:400;font-size:.76rem;">· <?= htmlspecialchars($m['empresa']) ?></span></div>
        <div style="font-size:.78rem;color:var(--txt3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars(substr($m['mensaje'],0,60)) ?>...</div>
      </div>
      <div style="font-size:.68rem;color:var(--txt3);flex-shrink:0;"><?= date('H:i',strtotime($m['created_at'])) ?></div>
    </div>
    <?php endforeach; endif; ?>
  </div>

  <!-- Mensajes web del formulario -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-inbox"></i> Mensajes Web</h2>
      <a href="/admin/mensajes" class="ba ba-g ba-sm">Ver todos</a>
    </div>
    <?php if(empty($ultims)): ?>
    <div style="padding:30px;text-align:center;color:var(--txt3);font-size:.85rem;">
      <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;color:#e0e0e0;"></i>Sin mensajes
    </div>
    <?php else: foreach($ultims as $m): ?>
    <div style="padding:11px 16px;border-bottom:1px solid rgba(0,0,0,.05);display:flex;align-items:center;gap:10px;">
      <div style="flex:1;min-width:0;">
        <div style="font-size:.83rem;font-weight:<?= !$m['leido']?'700':'400' ?>;"><?= htmlspecialchars($m['nombre']) ?></div>
        <div style="font-size:.76rem;color:var(--txt3);"><?= htmlspecialchars($m['email']) ?></div>
      </div>
      <div style="display:flex;align-items:center;gap:6px;">
        <?php if(!$m['leido']): ?><span class="badge br">Nuevo</span><?php elseif(!$m['respondido']): ?><span class="badge by">Pendiente</span><?php else: ?><span class="badge bg">Respondido</span><?php endif; ?>
        <a href="/admin/mensajes?id=<?= $m['id'] ?>" class="ba ba-g ba-sm"><i class="fas fa-eye"></i></a>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>

</div>

<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
