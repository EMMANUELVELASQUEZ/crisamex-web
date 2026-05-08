<?php
$portalTitle='Documentos'; $cliente_id=$_SESSION['cliente_id'];
$docs=Database::fetchAll("SELECT * FROM portal_documentos WHERE cliente_id=? ORDER BY created_at DESC",[$cliente_id]);
$tipo_icons=['reporte'=>'fa-chart-bar','certificado'=>'fa-certificate','factura'=>'fa-file-invoice','contrato'=>'fa-file-contract','otro'=>'fa-file'];
require_once SRC_PATH.'/views/portal/layout-top.php';
?>
<div class="ac">
  <div class="ac-head"><h2><i class="fas fa-folder"></i> Mis Documentos (<?=count($docs)?>)</h2></div>
  <?php if(empty($docs)): ?>
  <div style="padding:60px;text-align:center;color:var(--txt3);"><i class="fas fa-folder-open" style="font-size:3rem;display:block;margin-bottom:14px;"></i><p>Aún no tienes documentos disponibles.</p><p style="font-size:.8rem;margin-top:8px;">CRISAMEX subirá aquí tus reportes, certificados y más.</p></div>
  <?php else: ?>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;padding:20px;">
    <?php foreach($docs as $d): $ico=$tipo_icons[$d['tipo']]??'fa-file'; ?>
    <div style="background:var(--bg3);border:1px solid var(--brd);border-radius:8px;padding:20px;transition:all .25s;" onmouseover="this.style.borderColor='rgba(200,21,27,.35)'" onmouseout="this.style.borderColor='var(--brd)'">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
        <div style="width:42px;height:42px;background:rgba(200,21,27,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas <?=$ico?>" style="color:var(--red);font-size:1.1rem;"></i></div>
        <div><div style="font-size:.88rem;font-weight:600;color:white;"><?=htmlspecialchars($d['titulo'])?></div><div style="font-size:.7rem;color:var(--txt2);"><?=ucfirst($d['tipo'])?></div></div>
      </div>
      <?php if($d['descripcion']): ?><p style="font-size:.78rem;color:var(--txt2);margin-bottom:12px;font-weight:300;"><?=htmlspecialchars($d['descripcion'])?></p><?php endif; ?>
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.68rem;color:var(--txt3);"><?=date('d/m/Y',strtotime($d['created_at']))?></span>
        <?php if($d['archivo']): ?><a href="/uploads/<?=htmlspecialchars($d['archivo'])?>" download class="ba ba-r ba-sm"><i class="fas fa-download"></i> Descargar</a><?php else: ?><span style="font-size:.72rem;color:var(--txt3);">Sin archivo</span><?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php require_once SRC_PATH.'/views/portal/layout-bottom.php'; ?>
