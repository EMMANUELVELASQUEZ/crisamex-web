<?php
$adminTitle='Configuración'; $adminPage='configuracion';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $campos=['empresa_nombre','empresa_razon_social','empresa_slogan','empresa_descripcion','empresa_telefono','empresa_email','empresa_direccion','empresa_facebook','empresa_anos_experiencia','meta_title_home','meta_description_home','google_analytics'];
  foreach($campos as $c){ if(isset($_POST[$c])) Database::query("UPDATE site_config SET valor=? WHERE clave=?",[trim($_POST[$c]),$c]); }
  header('Location: /admin/configuracion?ok=1'); exit;
}
$cfg=[]; foreach(Database::fetchAll("SELECT clave,valor FROM site_config") as $r) $cfg[$r['clave']]=$r['valor'];
require_once SRC_PATH.'/views/admin/layout-top.php';
?>
<?php if(isset($_GET['ok'])): ?><div class="aa aa-ok"><i class="fas fa-check"></i> Configuración guardada correctamente.</div><?php endif; ?>
<form method="POST">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <div>
      <div class="ac" style="margin-bottom:18px;">
        <div class="ac-head"><h2><i class="fas fa-building"></i> Información de la Empresa</h2></div>
        <div class="ac-body">
          <div class="afg"><label>Nombre corto</label><input type="text" name="empresa_nombre" value="<?=htmlspecialchars($cfg['empresa_nombre']??'')?>"></div>
          <div class="afg"><label>Razón Social</label><input type="text" name="empresa_razon_social" value="<?=htmlspecialchars($cfg['empresa_razon_social']??'')?>"></div>
          <div class="afg"><label>Slogan</label><input type="text" name="empresa_slogan" value="<?=htmlspecialchars($cfg['empresa_slogan']??'')?>"></div>
          <div class="afg"><label>Descripción principal</label><textarea name="empresa_descripcion"><?=htmlspecialchars($cfg['empresa_descripcion']??'')?></textarea></div>
          <div class="afg"><label>Años de experiencia</label><input type="number" name="empresa_anos_experiencia" value="<?=htmlspecialchars($cfg['empresa_anos_experiencia']??'25')?>"></div>
        </div>
      </div>
    </div>
    <div>
      <div class="ac" style="margin-bottom:18px;">
        <div class="ac-head"><h2><i class="fas fa-phone"></i> Contacto</h2></div>
        <div class="ac-body">
          <div class="afg"><label>Teléfono</label><input type="text" name="empresa_telefono" value="<?=htmlspecialchars($cfg['empresa_telefono']??'')?>"></div>
          <div class="afg"><label>Email</label><input type="email" name="empresa_email" value="<?=htmlspecialchars($cfg['empresa_email']??'')?>"></div>
          <div class="afg"><label>Dirección</label><input type="text" name="empresa_direccion" value="<?=htmlspecialchars($cfg['empresa_direccion']??'')?>"></div>
          <div class="afg"><label>Facebook URL</label><input type="url" name="empresa_facebook" value="<?=htmlspecialchars($cfg['empresa_facebook']??'')?>"></div>
        </div>
      </div>
      <div class="ac">
        <div class="ac-head"><h2><i class="fas fa-search"></i> SEO</h2></div>
        <div class="ac-body">
          <div class="afg"><label>Meta Title (home)</label><input type="text" name="meta_title_home" value="<?=htmlspecialchars($cfg['meta_title_home']??'')?>"></div>
          <div class="afg"><label>Meta Description</label><textarea name="meta_description_home" style="min-height:70px;"><?=htmlspecialchars($cfg['meta_description_home']??'')?></textarea></div>
          <div class="afg"><label>Google Analytics ID</label><input type="text" name="google_analytics" placeholder="G-XXXXXXXX" value="<?=htmlspecialchars($cfg['google_analytics']??'')?>"></div>
        </div>
      </div>
    </div>
  </div>
  <div style="text-align:right;margin-top:8px;"><button type="submit" class="ba ba-r" style="padding:11px 28px;font-size:.86rem;"><i class="fas fa-save"></i> Guardar toda la configuración</button></div>
</form>
<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
