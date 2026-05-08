<?php
$portalTitle='Mi Licencia'; $cliente_id=$_SESSION['cliente_id'];
$cliente=Database::fetchOne("SELECT c.*,p.nombre as pn,p.precio_mensual as pm,p.precio_anual as pa,p.icono as pi,p.features as pf FROM clientes c LEFT JOIN planes p ON c.plan_id=p.id WHERE c.id=?",[$cliente_id]);
$planes=Database::fetchAll("SELECT * FROM planes WHERE activo=1 ORDER BY orden");
$features=json_decode($cliente['pf']??'[]',true)?:[];
require_once SRC_PATH.'/views/portal/layout-top.php';
?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <div class="ac">
    <div class="ac-head"><h2><i class="fas fa-id-card"></i> Plan Actual</h2></div>
    <div class="ac-body">
      <?php if($cliente['plan_id']): ?>
      <div style="padding:24px;background:rgba(200,21,27,.06);border:1px solid rgba(200,21,27,.2);border-radius:10px;text-align:center;margin-bottom:20px;">
        <div style="width:60px;height:60px;background:var(--red);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin:0 auto 12px;box-shadow:0 4px 20px rgba(200,21,27,.4);"><i class="<?=htmlspecialchars($cliente['pi']??'fas fa-star')?>"></i></div>
        <div style="font-family:'Roboto Condensed',sans-serif;font-size:1.6rem;font-weight:700;text-transform:uppercase;color:white;margin-bottom:4px;"><?=htmlspecialchars($cliente['pn'])?></div>
        <div style="font-size:1.4rem;color:var(--red);font-weight:700;">$<?=number_format($cliente['pm'],0,'.',',')?><span style="font-size:.8rem;color:var(--txt2);font-weight:400;">/mes</span></div>
        <div style="margin-top:12px;"><span class="badge <?=$cliente['licencia_status']==='activa'?'bg':($cliente['licencia_status']==='trial'?'by':'br')?>"><?=ucfirst($cliente['licencia_status'])?></span></div>
        <?php if($cliente['licencia_inicio']): ?>
        <div style="margin-top:14px;display:flex;justify-content:space-around;font-size:.76rem;color:var(--txt2);">
          <div><strong style="color:white;display:block;"><?=date('d/m/Y',strtotime($cliente['licencia_inicio']))?></strong>Inicio</div>
          <div><strong style="color:white;display:block;"><?=date('d/m/Y',strtotime($cliente['licencia_fin']))?></strong>Vencimiento</div>
        </div>
        <?php endif; ?>
      </div>
      <h4 style="font-size:.76rem;color:var(--txt2);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Incluye</h4>
      <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;">
        <?php foreach($features as $f): ?><li style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:var(--txt2);"><i class="fas fa-check-circle" style="color:var(--red);font-size:.78rem;"></i><?=htmlspecialchars($f)?></li><?php endforeach; ?>
      </ul>
      <?php else: ?>
      <div style="text-align:center;padding:30px;"><i class="fas fa-star" style="font-size:2.5rem;color:var(--txt3);display:block;margin-bottom:14px;"></i><p style="color:var(--txt3);margin-bottom:16px;">Aún no tienes un plan activo</p><a href="/planes" class="ba ba-r"><i class="fas fa-rocket"></i>Ver planes</a></div>
      <?php endif; ?>
    </div>
  </div>
  <div>
    <div class="ac">
      <div class="ac-head"><h2><i class="fas fa-arrow-up"></i> Actualizar Plan</h2></div>
      <div style="padding:0;">
        <?php foreach($planes as $p): $active=$p['id']==$cliente['plan_id']; ?>
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.04);display:flex;align-items:center;gap:12px;<?=$active?'background:rgba(200,21,27,.05);':''?>">
          <div style="width:38px;height:38px;background:<?=$active?'var(--red)':'rgba(200,21,27,.1)'?>;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.95rem;color:<?=$active?'white':'var(--red)'?>;flex-shrink:0;"><i class="<?=htmlspecialchars($p['icono'])?>"></i></div>
          <div style="flex:1;"><div style="font-size:.88rem;font-weight:600;color:white;"><?=htmlspecialchars($p['nombre'])?></div><div style="font-size:.75rem;color:var(--txt2);">$<?=number_format($p['precio_mensual'],0,'.',',')?>/mes</div></div>
          <?php if($active): ?><span class="badge bg">Activo</span>
          <?php else: ?><a href="/contacto?plan=<?=htmlspecialchars($p['nombre'])?>" class="ba ba-g ba-sm">Cambiar</a><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="ac" style="margin-top:16px;">
      <div class="ac-head"><h2><i class="fas fa-headset"></i> ¿Necesitas ayuda?</h2></div>
      <div class="ac-body">
        <p style="font-size:.84rem;color:var(--txt2);margin-bottom:16px;font-weight:300;">Para renovar, cambiar de plan o resolver dudas sobre tu licencia, contáctanos directamente.</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <a href="/portal/mensajes" class="ba ba-r" style="justify-content:center;"><i class="fas fa-comments"></i>Enviar mensaje</a>
          <a href="tel:+525556508420" class="ba ba-g" style="justify-content:center;"><i class="fas fa-phone-alt"></i>55 5650 8420</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once SRC_PATH.'/views/portal/layout-bottom.php'; ?>
