<?php
$adminTitle='Planes'; $adminPage='planes';
$planes=Database::fetchAll("SELECT * FROM planes ORDER BY orden");
require_once SRC_PATH.'/views/admin/layout-top.php';
?>
<div class="st-row" style="grid-template-columns:repeat(3,1fr);">
  <?php foreach($planes as $p): ?>
  <div class="ac" style="margin:0;">
    <div class="ac-body" style="text-align:center;">
      <div style="width:50px;height:50px;background:rgba(200,21,27,.12);border:1px solid rgba(200,21,27,.25);border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--red);"><i class="<?=htmlspecialchars($p['icono'])?>"></i></div>
      <div style="font-weight:700;color:#fff;font-size:1rem;text-transform:uppercase;letter-spacing:1px;"><?=htmlspecialchars($p['nombre'])?></div>
      <div style="color:var(--red);font-size:1.2rem;font-weight:700;margin-top:6px;">$<?=number_format($p['precio_mensual'],0,'.',',')?><small style="color:var(--txt2);font-size:.7rem;font-weight:400;">/mes</small></div>
      <div style="font-size:.76rem;color:var(--txt2);margin-top:4px;">Anual: $<?=number_format($p['precio_anual'],0,'.',',')?></div>
      <?php $total=Database::fetchOne("SELECT COUNT(*) as c FROM clientes WHERE plan_id=?",[$p['id']])['c']??0; ?>
      <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--brd);font-size:.82rem;color:var(--txt2);"><?=$total?> clientes activos</div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div class="ac" style="margin-top:20px;">
  <div class="ac-head"><h2><i class="fas fa-list"></i> Detalle de Planes</h2></div>
  <div style="overflow-x:auto;"><table class="at">
    <thead><tr><th>Plan</th><th>Precio/mes</th><th>Precio/año</th><th>Soporte</th><th>Clientes</th><th>Estado</th></tr></thead>
    <tbody><?php foreach($planes as $p):
      $total=Database::fetchOne("SELECT COUNT(*) as c FROM clientes WHERE plan_id=?",[$p['id']])['c']??0;
    ?><tr>
      <td><strong style="color:#fff"><?=htmlspecialchars($p['nombre'])?></strong><?php if($p['destacado']): ?> <span class="badge bb">Popular</span><?php endif;?></td>
      <td style="color:var(--red)">$<?=number_format($p['precio_mensual'],0,'.',',')?></td>
      <td>$<?=number_format($p['precio_anual'],0,'.',',')?></td>
      <td style="color:var(--txt2)"><?=htmlspecialchars($p['soporte'])?></td>
      <td><span class="badge bb"><?=$total?></span></td>
      <td><span class="badge <?=$p['activo']?'bg':'br'?>"><?=$p['activo']?'Activo':'Inactivo'?></span></td>
    </tr><?php endforeach;?>
    </tbody>
  </table></div>
</div>
<div style="margin-top:14px;padding:16px;background:rgba(255,255,255,.03);border:1px solid var(--brd);border-radius:8px;">
  <p style="font-size:.8rem;color:var(--txt2);"><i class="fas fa-info-circle" style="color:var(--red);margin-right:6px;"></i>Para editar precios o features de los planes, modifica directamente la base de datos en <strong style="color:var(--txt)">phpMyAdmin</strong> (localhost:8091 → tabla <code style="color:var(--red);">planes</code>).</p>
</div>
<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
