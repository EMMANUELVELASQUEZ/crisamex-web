<?php
$adminTitle='Mensajes'; $adminPage='mensajes';
$view=null;
if(isset($_GET['id'])){ $view=Database::fetchOne("SELECT * FROM contacto_mensajes WHERE id=?",[(int)$_GET['id']]); if($view) Database::query("UPDATE contacto_mensajes SET leido=1 WHERE id=?",[$view['id']]); }
if(isset($_GET['resp'])) { Database::query("UPDATE contacto_mensajes SET respondido=1,leido=1 WHERE id=?",[(int)$_GET['resp']]); header('Location: /admin/mensajes'); exit; }
if(isset($_GET['del']))  { Database::query("DELETE FROM contacto_mensajes WHERE id=?",[(int)$_GET['del']]);  header('Location: /admin/mensajes'); exit; }
$lista=Database::fetchAll("SELECT * FROM contacto_mensajes ORDER BY created_at DESC");
require_once SRC_PATH.'/views/admin/layout-top.php';
?>
<?php if($view): ?>
<div style="margin-bottom:14px;"><a href="/admin/mensajes" class="ba ba-g ba-sm"><i class="fas fa-arrow-left"></i> Volver</a></div>
<div class="ac">
  <div class="ac-head"><h2><i class="fas fa-envelope-open"></i> Mensaje de <?=htmlspecialchars($view['nombre'])?></h2>
    <div style="display:flex;gap:6px;">
      <?php if(!$view['respondido']): ?><a href="/admin/mensajes?resp=<?=$view['id']?>" class="ba ba-g ba-sm"><i class="fas fa-check"></i> Respondido</a><?php endif; ?>
      <a href="mailto:<?=htmlspecialchars($view['email'])?>" class="ba ba-r ba-sm"><i class="fas fa-reply"></i> Responder</a>
      <a href="/admin/mensajes?del=<?=$view['id']?>" class="ba ba-d ba-sm" data-confirm="¿Eliminar?"><i class="fas fa-trash"></i></a>
    </div>
  </div>
  <div class="ac-body">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px;">
      <div><small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;">Nombre</small><div style="color:#fff;margin-top:3px;"><?=htmlspecialchars($view['nombre'])?></div></div>
      <div><small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;">Empresa</small><div style="color:#fff;margin-top:3px;"><?=htmlspecialchars($view['empresa']?:'—')?></div></div>
      <div><small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;">Email</small><div style="margin-top:3px;"><a href="mailto:<?=htmlspecialchars($view['email'])?>" style="color:var(--blue)"><?=htmlspecialchars($view['email'])?></a></div></div>
      <div><small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;">Teléfono</small><div style="color:#fff;margin-top:3px;"><?=htmlspecialchars($view['telefono']?:'—')?></div></div>
      <div><small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;">Servicio</small><div style="color:#fff;margin-top:3px;"><?=htmlspecialchars($view['servicio_interes']?:'—')?></div></div>
      <div><small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;">Fecha</small><div style="color:#fff;margin-top:3px;"><?=date('d/m/Y H:i',strtotime($view['created_at']))?></div></div>
    </div>
    <div style="background:rgba(255,255,255,.03);border:1px solid var(--brd);padding:18px;border-radius:6px;">
      <small style="color:var(--txt2);text-transform:uppercase;letter-spacing:1px;font-size:.66rem;display:block;margin-bottom:10px;">Mensaje</small>
      <p style="white-space:pre-wrap;color:rgba(255,255,255,.7);font-size:.9rem;line-height:1.8;font-weight:300;"><?=htmlspecialchars($view['mensaje'])?></p>
    </div>
  </div>
</div>
<?php else: ?>
<div class="ac">
  <div class="ac-head"><h2><i class="fas fa-inbox"></i> Todos los Mensajes</h2><span class="badge bb"><?=count($lista)?> total</span></div>
  <div style="overflow-x:auto;"><table class="at">
    <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Servicio</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
      <?php if(empty($lista)): ?><tr><td colspan="7" style="text-align:center;padding:40px;color:var(--txt3);">Sin mensajes aún</td></tr>
      <?php else: foreach($lista as $m): ?>
      <tr style="<?=!$m['leido']?'background:rgba(200,21,27,.04);':''?>">
        <td><strong style="color:<?=!$m['leido']?'#fff':'var(--txt)'?>"><?=htmlspecialchars($m['nombre'])?></strong></td>
        <td><a href="mailto:<?=htmlspecialchars($m['email'])?>" style="color:var(--blue)"><?=htmlspecialchars($m['email'])?></a></td>
        <td style="color:var(--txt2)"><?=htmlspecialchars($m['telefono']?:'—')?></td>
        <td style="color:var(--txt2);font-size:.78rem;"><?=htmlspecialchars($m['servicio_interes']?:'—')?></td>
        <td style="white-space:nowrap;color:var(--txt2);font-size:.76rem;"><?=date('d/m/Y H:i',strtotime($m['created_at']))?></td>
        <td><?php if(!$m['leido']):?><span class="badge br">Nuevo</span><?php elseif(!$m['respondido']):?><span class="badge by">Pendiente</span><?php else:?><span class="badge bg">Respondido</span><?php endif;?></td>
        <td style="display:flex;gap:4px;">
          <a href="/admin/mensajes?id=<?=$m['id']?>" class="ba ba-r ba-sm"><i class="fas fa-eye"></i></a>
          <a href="mailto:<?=htmlspecialchars($m['email'])?>" class="ba ba-g ba-sm"><i class="fas fa-reply"></i></a>
          <a href="/admin/mensajes?del=<?=$m['id']?>" class="ba ba-d ba-sm" data-confirm="¿Eliminar?"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
<?php endif; ?>
<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
