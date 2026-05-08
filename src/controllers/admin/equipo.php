<?php
$adminTitle='Equipo'; $adminPage='equipo';
$edit=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)($_POST['id']??0); $nombre=trim($_POST['nombre']??''); $puesto=trim($_POST['puesto']??''); $desc=trim($_POST['descripcion']??''); $li=trim($_POST['linkedin']??''); $orden=(int)($_POST['orden']??0); $activo=isset($_POST['activo'])?1:0;
  $foto=$_POST['foto_actual']??'';
  if(!empty($_FILES['foto']['name'])){
    $ext=strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION));
    if(in_array($ext,['jpg','jpeg','png','webp'])){ $fn='equipo_'.time().'_'.rand(100,999).'.'.$ext; if(move_uploaded_file($_FILES['foto']['tmp_name'],ROOT_PATH.'/public/uploads/'.$fn)) $foto=$fn; }
  }
  if($id>0) Database::query("UPDATE equipo SET nombre=?,puesto=?,descripcion=?,linkedin=?,orden=?,activo=?,foto=? WHERE id=?",[$nombre,$puesto,$desc,$li,$orden,$activo,$foto,$id]);
  else Database::query("INSERT INTO equipo(nombre,puesto,descripcion,linkedin,orden,activo,foto) VALUES(?,?,?,?,?,?,?)",[$nombre,$puesto,$desc,$li,$orden,$activo,$foto]);
  header('Location: /admin/equipo?ok=1'); exit;
}
if(isset($_GET['del'])) { Database::query("DELETE FROM equipo WHERE id=?",[(int)$_GET['del']]); header('Location: /admin/equipo'); exit; }
if(isset($_GET['editar'])) $edit=Database::fetchOne("SELECT * FROM equipo WHERE id=?",[(int)$_GET['editar']]);
$lista=Database::fetchAll("SELECT * FROM equipo ORDER BY orden");
require_once SRC_PATH.'/views/admin/layout-top.php';
?>
<?php if(isset($_GET['ok'])): ?><div class="aa aa-ok"><i class="fas fa-check"></i> Miembro guardado correctamente.</div><?php endif; ?>
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px;align-items:start;">
  <div class="ac">
    <div class="ac-head"><h2><i class="fas fa-users"></i> Equipo (<?=count($lista)?>)</h2></div>
    <div style="overflow-x:auto;"><table class="at">
      <thead><tr><th>Foto</th><th>Nombre</th><th>Puesto</th><th>Activo</th><th>Acciones</th></tr></thead>
      <tbody><?php foreach($lista as $m): ?>
        <tr>
          <td><?php if($m['foto']): ?><img src="/uploads/<?=htmlspecialchars($m['foto'])?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"><?php else: ?><div style="width:36px;height:36px;background:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;color:white;font-weight:700;"><?=strtoupper(substr($m['nombre'],0,1))?></div><?php endif;?></td>
          <td><strong style="color:#fff"><?=htmlspecialchars($m['nombre'])?></strong></td>
          <td style="color:var(--txt2);font-size:.8rem;"><?=htmlspecialchars($m['puesto'])?></td>
          <td><span class="badge <?=$m['activo']?'bg':'br'?>"><?=$m['activo']?'Sí':'No'?></span></td>
          <td style="display:flex;gap:5px;">
            <a href="/admin/equipo?editar=<?=$m['id']?>" class="ba ba-g ba-sm"><i class="fas fa-edit"></i></a>
            <a href="/admin/equipo?del=<?=$m['id']?>" class="ba ba-d ba-sm" data-confirm="¿Eliminar?"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?></tbody>
    </table></div>
  </div>
  <div class="ac">
    <div class="ac-head"><h2><i class="fas fa-<?=$edit?'edit':'plus'?>"></i> <?=$edit?'Editar':'Nuevo'?> Miembro</h2><?php if($edit):?><a href="/admin/equipo" class="ba ba-g ba-sm">Cancelar</a><?php endif;?></div>
    <div class="ac-body"><form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=$edit['id']??0?>">
      <input type="hidden" name="foto_actual" value="<?=htmlspecialchars($edit['foto']??'')?>">
      <div class="afg"><label>Nombre completo *</label><input type="text" name="nombre" required value="<?=htmlspecialchars($edit['nombre']??'')?>"></div>
      <div class="afg"><label>Puesto / Cargo</label><input type="text" name="puesto" value="<?=htmlspecialchars($edit['puesto']??'')?>"></div>
      <div class="afg"><label>Descripción</label><textarea name="descripcion"><?=htmlspecialchars($edit['descripcion']??'')?></textarea></div>
      <div class="afg"><label>LinkedIn URL</label><input type="url" name="linkedin" value="<?=htmlspecialchars($edit['linkedin']??'')?>"></div>
      <div class="afg"><label>Foto (JPG/PNG, máx 10MB)</label><input type="file" name="foto" accept="image/*"><?php if(!empty($edit['foto'])): ?><div style="margin-top:8px;"><img src="/uploads/<?=htmlspecialchars($edit['foto'])?>" style="height:50px;border-radius:4px;"></div><?php endif;?></div>
      <div class="afr2">
        <div class="afg"><label>Orden</label><input type="number" name="orden" value="<?=$edit['orden']??0?>" min="0"></div>
        <div class="afg" style="padding-top:22px;"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="activo" <?=(!isset($edit)||$edit['activo'])?'checked':''?>> Activo</label></div>
      </div>
      <button type="submit" class="ba ba-r" style="width:100%;justify-content:center;padding:10px;"><i class="fas fa-save"></i> Guardar</button>
    </form></div>
  </div>
</div>
<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
