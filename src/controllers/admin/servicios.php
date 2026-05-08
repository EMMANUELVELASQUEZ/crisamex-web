<?php
$adminTitle='Servicios'; $adminPage='servicios';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)($_POST['id']??0); $titulo=trim($_POST['titulo']??''); $dc=trim($_POST['descripcion_corta']??''); $dl=trim($_POST['descripcion_larga']??''); $icono=trim($_POST['icono']??'fas fa-atom'); $orden=(int)($_POST['orden']??0); $activo=isset($_POST['activo'])?1:0;
  $slug=strtolower(preg_replace('/[^a-z0-9]+/i','-',$titulo));
  if($id>0) Database::query("UPDATE servicios SET titulo=?,descripcion_corta=?,descripcion_larga=?,icono=?,orden=?,activo=?,slug=? WHERE id=?",[$titulo,$dc,$dl,$icono,$orden,$activo,$slug,$id]);
  else Database::query("INSERT INTO servicios(titulo,descripcion_corta,descripcion_larga,icono,orden,activo,slug) VALUES(?,?,?,?,?,?,?)",[$titulo,$dc,$dl,$icono,$orden,$activo,$slug]);
  header('Location: /admin/servicios?ok=1'); exit;
}
if(isset($_GET['del'])) { Database::query("DELETE FROM servicios WHERE id=?",[(int)$_GET['del']]); header('Location: /admin/servicios'); exit; }
if(isset($_GET['toggle'])) { $s=Database::fetchOne("SELECT activo FROM servicios WHERE id=?",[(int)$_GET['toggle']]); if($s) Database::query("UPDATE servicios SET activo=? WHERE id=?",[(int)!$s['activo'],(int)$_GET['toggle']]); header('Location: /admin/servicios'); exit; }
$edit=isset($_GET['editar'])?Database::fetchOne("SELECT * FROM servicios WHERE id=?",[(int)$_GET['editar']]):null;
$lista=Database::fetchAll("SELECT * FROM servicios ORDER BY orden");
require_once SRC_PATH.'/views/admin/layout-top.php';
?>
<?php if(isset($_GET['ok'])): ?><div class="aa aa-ok"><i class="fas fa-check"></i> Servicio guardado correctamente.</div><?php endif; ?>
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px;align-items:start;">
  <div class="ac">
    <div class="ac-head"><h2><i class="fas fa-list"></i> Servicios (<?=count($lista)?>)</h2></div>
    <div style="overflow-x:auto;"><table class="at">
      <thead><tr><th>#</th><th>Título</th><th>Ícono</th><th>Activo</th><th>Acciones</th></tr></thead>
      <tbody>
        <?php foreach($lista as $s): ?>
        <tr>
          <td style="color:var(--txt2)"><?=$s['orden']?></td>
          <td><strong style="color:#fff"><?=htmlspecialchars($s['titulo'])?></strong></td>
          <td><code style="color:var(--red);font-size:.72rem;"><?=htmlspecialchars($s['icono'])?></code></td>
          <td><a href="/admin/servicios?toggle=<?=$s['id']?>"><span class="badge <?=$s['activo']?'bg':'br'?>"><?=$s['activo']?'Sí':'No'?></span></a></td>
          <td style="display:flex;gap:5px;">
            <a href="/admin/servicios?editar=<?=$s['id']?>" class="ba ba-g ba-sm"><i class="fas fa-edit"></i></a>
            <a href="/admin/servicios?del=<?=$s['id']?>" class="ba ba-d ba-sm" data-confirm="¿Eliminar este servicio?"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
  </div>
  <div class="ac">
    <div class="ac-head"><h2><i class="fas fa-<?=$edit?'edit':'plus'?>"></i> <?=$edit?'Editar':'Nuevo'?> Servicio</h2><?php if($edit):?><a href="/admin/servicios" class="ba ba-g ba-sm">Cancelar</a><?php endif;?></div>
    <div class="ac-body"><form method="POST">
      <input type="hidden" name="id" value="<?=$edit['id']??0?>">
      <div class="afg"><label>Título *</label><input type="text" name="titulo" required value="<?=htmlspecialchars($edit['titulo']??'')?>"></div>
      <div class="afg"><label>Ícono (FontAwesome)</label><input type="text" name="icono" value="<?=htmlspecialchars($edit['icono']??'fas fa-atom')?>" placeholder="fas fa-atom"><small style="color:var(--txt2);font-size:.68rem;">ej: fas fa-shield-alt, fas fa-atom</small></div>
      <div class="afg"><label>Descripción corta</label><textarea name="descripcion_corta"><?=htmlspecialchars($edit['descripcion_corta']??'')?></textarea></div>
      <div class="afg"><label>Descripción completa</label><textarea name="descripcion_larga" style="min-height:100px;"><?=htmlspecialchars($edit['descripcion_larga']??'')?></textarea></div>
      <div class="afr2">
        <div class="afg"><label>Orden</label><input type="number" name="orden" value="<?=$edit['orden']??0?>" min="0"></div>
        <div class="afg" style="display:flex;align-items:center;padding-top:22px;"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="activo" <?=(!isset($edit)||$edit['activo'])?'checked':''?>> Activo en sitio</label></div>
      </div>
      <button type="submit" class="ba ba-r" style="width:100%;justify-content:center;padding:10px;"><i class="fas fa-save"></i> Guardar servicio</button>
    </form></div>
  </div>
</div>
<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
