<?php
$portalTitle = 'Dashboard';
$cid = $_SESSION['cliente_id'];

$cliente = Database::fetchOne(
  "SELECT c.*, p.nombre as pn, p.precio_mensual as pm, p.icono as pi, p.features as pf
   FROM clientes c LEFT JOIN planes p ON c.plan_id=p.id WHERE c.id=?", [$cid]
);
$notifs  = Database::fetchAll("SELECT * FROM portal_notificaciones WHERE cliente_id=? ORDER BY created_at DESC LIMIT 5", [$cid]);
$msgs    = Database::fetchAll("SELECT * FROM portal_mensajes WHERE cliente_id=? ORDER BY created_at DESC LIMIT 4", [$cid]);
$docs    = Database::fetchAll("SELECT * FROM portal_documentos WHERE cliente_id=? ORDER BY created_at DESC LIMIT 4", [$cid]);
$noLeidosMsg  = Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE cliente_id=? AND leido_cliente=0 AND de='admin'",[$cid])['c']??0;
$noLeidasNotif= Database::fetchOne("SELECT COUNT(*) as c FROM portal_notificaciones WHERE cliente_id=? AND leida=0",[$cid])['c']??0;
$totalDocs    = Database::fetchOne("SELECT COUNT(*) as c FROM portal_documentos WHERE cliente_id=?",[$cid])['c']??0;
$features     = json_decode($cliente['pf']??'[]', true) ?: [];

$dias_rest = null;
if($cliente['licencia_fin']){
  $diff = (new DateTime())->diff(new DateTime($cliente['licencia_fin']));
  $dias_rest = $diff->invert ? -$diff->days : $diff->days;
}
$bienvenido = isset($_GET['bienvenido']);
require_once SRC_PATH.'/views/portal/layout-top.php';
?>

<?php if($bienvenido): ?>
<div class="aa aa-ok" style="margin-bottom:18px;">
  <i class="fas fa-check-circle"></i>
  <strong>¡Bienvenido a CRISAMEX Portal, <?= htmlspecialchars($cliente['nombre']) ?>!</strong>
  &nbsp;Tu cuenta fue creada. Un asesor te contactará pronto.
</div>
<?php endif; ?>

<!-- STATS -->
<div class="st-row" style="margin-bottom:18px;">
  <div class="st-card" style="border-left:3px solid #C8151B;">
    <div class="st-ico r"><i class="fas fa-id-card"></i></div>
    <div>
      <div class="st-n" style="font-size:1.1rem;"><?= htmlspecialchars($cliente['pn']??'Trial') ?></div>
      <div class="st-l">Plan Activo</div>
      <div style="margin-top:4px;"><span class="<?= 'badge-'.($cliente['licencia_status']??'trial') ?>"><?= ucfirst($cliente['licencia_status']??'trial') ?></span></div>
    </div>
  </div>
  <div class="st-card">
    <div class="st-ico y"><i class="fas fa-calendar-alt"></i></div>
    <div>
      <div class="st-n"><?= $dias_rest !== null ? abs($dias_rest) : '—' ?></div>
      <div class="st-l"><?= $dias_rest === null ? 'Sin fecha' : ($dias_rest >= 0 ? 'Días restantes' : 'Días vencida') ?></div>
    </div>
  </div>
  <div class="st-card" style="cursor:pointer;" onclick="location='/portal/mensajes'">
    <div class="st-ico <?= $noLeidosMsg>0?'r':'b' ?>"><i class="fas fa-comments"></i></div>
    <div>
      <div class="st-n"><?= $noLeidosMsg ?></div>
      <div class="st-l">Mensajes nuevos</div>
    </div>
  </div>
  <div class="st-card" style="cursor:pointer;" onclick="location='/portal/documentos'">
    <div class="st-ico g"><i class="fas fa-folder"></i></div>
    <div>
      <div class="st-n"><?= $totalDocs ?></div>
      <div class="st-l">Documentos</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

  <!-- Notificaciones -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-bell"></i> Notificaciones <?php if($noLeidasNotif): ?><span class="badge br"><?= $noLeidasNotif ?></span><?php endif; ?></h2>
    </div>
    <?php if(empty($notifs)): ?>
    <div style="padding:28px;text-align:center;color:var(--txt3);font-size:.85rem;">
      <i class="fas fa-bell-slash" style="font-size:2rem;display:block;margin-bottom:10px;color:#e0e0e0;"></i>Sin notificaciones
    </div>
    <?php else: foreach($notifs as $n):
      $cols = ['success'=>['#dcfce7','#16a34a','fa-check-circle'],'info'=>['#dbeafe','#2563eb','fa-info-circle'],'warning'=>['#fef3c7','#d97706','fa-exclamation-triangle'],'danger'=>['#fef2f2','#C8151B','fa-times-circle']];
      [$bg,$cl,$ic] = $cols[$n['tipo']] ?? ['#f3f4f6','#666','fa-circle'];
    ?>
    <div style="padding:12px 16px;border-bottom:1px solid rgba(0,0,0,.04);display:flex;gap:12px;align-items:flex-start;background:<?= !$n['leida']?'rgba(200,21,27,.02)':'transparent' ?>;">
      <div style="width:34px;height:34px;background:<?= $bg ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas <?= $ic ?>" style="color:<?= $cl ?>;font-size:.82rem;"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:.84rem;font-weight:<?= !$n['leida']?'700':'500' ?>;"><?= htmlspecialchars($n['titulo']) ?></div>
        <div style="font-size:.76rem;color:var(--txt3);line-height:1.5;margin-top:2px;"><?= htmlspecialchars($n['mensaje']) ?></div>
        <div style="font-size:.68rem;color:#bbb;margin-top:4px;"><?= date('d/m/Y H:i',strtotime($n['created_at'])) ?></div>
      </div>
      <?php if(!$n['leida']): ?><div style="width:8px;height:8px;background:#C8151B;border-radius:50%;flex-shrink:0;margin-top:6px;"></div><?php endif; ?>
    </div>
    <?php endforeach; endif; ?>
  </div>

  <!-- Mensajes recientes -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-comments"></i> Mensajes Recientes</h2>
      <a href="/portal/mensajes" class="ba ba-r ba-sm"><i class="fas fa-plus"></i> Nuevo</a>
    </div>
    <?php if(empty($msgs)): ?>
    <div style="padding:28px;text-align:center;">
      <i class="fas fa-comment-dots" style="font-size:2rem;display:block;margin-bottom:12px;color:#e0e0e0;"></i>
      <p style="font-size:.85rem;color:var(--txt3);margin-bottom:14px;">Sin mensajes aún. ¡Escríbenos!</p>
      <a href="/portal/mensajes" class="ba ba-r"><i class="fas fa-paper-plane"></i> Enviar mensaje</a>
    </div>
    <?php else: foreach($msgs as $m): ?>
    <div style="padding:12px 16px;border-bottom:1px solid rgba(0,0,0,.04);display:flex;gap:12px;align-items:flex-start;">
      <div style="width:34px;height:34px;background:<?= $m['de']==='admin'?'rgba(200,21,27,.1)':'#f0f0f0' ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas <?= $m['de']==='admin'?'fa-building':'fa-user' ?>" style="color:<?= $m['de']==='admin'?'#C8151B':'#888' ?>;font-size:.78rem;"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:.82rem;font-weight:600;"><?= htmlspecialchars($m['asunto']) ?> <span style="font-size:.72rem;color:var(--txt3);font-weight:400;">· <?= $m['de']==='admin'?'CRISAMEX':'Tú' ?></span></div>
        <div style="font-size:.76rem;color:var(--txt3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars(substr($m['mensaje'],0,55)) ?>...</div>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>

  <!-- Mi Licencia -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-id-card"></i> Mi Licencia</h2>
      <a href="/planes" class="ba ba-g ba-sm"><i class="fas fa-arrow-up"></i> Planes</a>
    </div>
    <div class="ac-body">
      <?php if($cliente['plan_id']): ?>
      <div style="display:flex;align-items:center;gap:14px;padding:14px;background:rgba(200,21,27,.04);border:1px solid rgba(200,21,27,.12);border-radius:6px;margin-bottom:14px;">
        <div style="width:46px;height:46px;background:#C8151B;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;flex-shrink:0;border-radius:6px;">
          <i class="<?= htmlspecialchars($cliente['pi']??'fas fa-star') ?>"></i>
        </div>
        <div>
          <div style="font-weight:700;font-size:.95rem;"><?= htmlspecialchars($cliente['pn']) ?></div>
          <div style="font-size:.78rem;color:#C8151B;">$<?= number_format($cliente['pm'],0,'.',',') ?>/mes</div>
        </div>
        <div style="margin-left:auto;"><span class="<?= 'badge-'.($cliente['licencia_status']??'trial') ?>"><?= ucfirst($cliente['licencia_status']) ?></span></div>
      </div>
      <ul style="list-style:none;display:flex;flex-direction:column;gap:7px;">
        <?php foreach(array_slice($features,0,5) as $f): ?>
        <li style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:var(--txt2);">
          <i class="fas fa-check-circle" style="color:#C8151B;font-size:.78rem;flex-shrink:0;"></i><?= htmlspecialchars($f) ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <div style="text-align:center;padding:20px;">
        <i class="fas fa-star" style="font-size:2rem;color:#e0e0e0;display:block;margin-bottom:10px;"></i>
        <p style="color:var(--txt3);font-size:.85rem;margin-bottom:14px;">Sin plan activo</p>
        <a href="/planes" class="ba ba-r"><i class="fas fa-rocket"></i> Ver planes</a>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Documentos -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-folder"></i> Documentos Recientes</h2>
      <a href="/portal/documentos" class="ba ba-g ba-sm">Ver todos</a>
    </div>
    <?php if(empty($docs)): ?>
    <div style="padding:28px;text-align:center;color:var(--txt3);font-size:.85rem;">
      <i class="fas fa-folder-open" style="font-size:2rem;display:block;margin-bottom:10px;color:#e0e0e0;"></i>
      CRISAMEX subirá aquí tus reportes y certificados
    </div>
    <?php else:
      $tipo_icons=['reporte'=>'fa-chart-bar','certificado'=>'fa-certificate','factura'=>'fa-file-invoice','contrato'=>'fa-file-contract','otro'=>'fa-file'];
      foreach($docs as $d): $ico=$tipo_icons[$d['tipo']]??'fa-file';
    ?>
    <div style="padding:11px 16px;border-bottom:1px solid rgba(0,0,0,.04);display:flex;align-items:center;gap:12px;">
      <div style="width:36px;height:36px;background:rgba(200,21,27,.08);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas <?= $ico ?>" style="color:#C8151B;"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:.84rem;font-weight:500;"><?= htmlspecialchars($d['titulo']) ?></div>
        <div style="font-size:.72rem;color:var(--txt3);"><?= ucfirst($d['tipo']) ?> · <?= date('d/m/Y',strtotime($d['created_at'])) ?></div>
      </div>
      <?php if($d['archivo']): ?>
      <a href="/uploads/<?= htmlspecialchars($d['archivo']) ?>" download class="ba ba-r ba-sm"><i class="fas fa-download"></i></a>
      <?php endif; ?>
    </div>
    <?php endforeach; endif; ?>
  </div>

</div>

<?php require_once SRC_PATH.'/views/portal/layout-bottom.php'; ?>
