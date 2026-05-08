<?php
$portalTitle='Mi Perfil'; $cliente_id=$_SESSION['cliente_id'];
$ok=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nombre=trim($_POST['nombre']??''); $apellidos=trim($_POST['apellidos']??''); $telefono=trim($_POST['telefono']??''); $cargo=trim($_POST['cargo']??''); $empresa=trim($_POST['empresa']??'');
  $pass=$_POST['password']??''; $pass2=$_POST['password2']??'';
  if($pass && strlen($pass)<8){ $err='La contraseña debe tener al menos 8 caracteres.'; }
  elseif($pass && $pass!==$pass2){ $err='Las contraseñas no coinciden.'; }
  else {
    Database::query("UPDATE clientes SET nombre=?,apellidos=?,telefono=?,cargo=?,empresa=? WHERE id=?",[$nombre,$apellidos,$telefono,$cargo,$empresa,$cliente_id]);
    if($pass) Database::query("UPDATE clientes SET password_hash=? WHERE id=?",[password_hash($pass,PASSWORD_BCRYPT),$cliente_id]);
    $_SESSION['cliente_nombre']=$nombre; $_SESSION['cliente_empresa']=$empresa;
    $ok='Perfil actualizado correctamente.';
  }
}
$c=Database::fetchOne("SELECT * FROM clientes WHERE id=?",[$cliente_id]);
require_once SRC_PATH.'/views/portal/layout-top.php';
?>
<?php if($ok): ?><div class="aa aa-ok" style="margin-bottom:18px;"><i class="fas fa-check"></i> <?=$ok?></div><?php endif; ?>
<?php if($err): ?><div class="aa aa-err" style="margin-bottom:18px;"><i class="fas fa-times"></i> <?=$err?></div><?php endif; ?>
<div class="ac" style="max-width:700px;">
  <div class="ac-head"><h2><i class="fas fa-user-cog"></i> Mis Datos</h2></div>
  <div class="ac-body"><form method="POST">
    <div class="afr2"><div class="afg"><label>Nombre *</label><input type="text" name="nombre" required value="<?=htmlspecialchars($c['nombre'])?>"></div><div class="afg"><label>Apellidos</label><input type="text" name="apellidos" value="<?=htmlspecialchars($c['apellidos']??'')?>"></div></div>
    <div class="afr2"><div class="afg"><label>Cargo</label><input type="text" name="cargo" value="<?=htmlspecialchars($c['cargo']??'')?>"></div><div class="afg"><label>Teléfono</label><input type="tel" name="telefono" value="<?=htmlspecialchars($c['telefono']??'')?>"></div></div>
    <div class="afg"><label>Empresa</label><input type="text" name="empresa" value="<?=htmlspecialchars($c['empresa'])?>"></div>
    <div class="afg"><label>Email</label><input type="email" value="<?=htmlspecialchars($c['email'])?>" disabled style="opacity:.5;cursor:not-allowed;"><small style="color:var(--txt3);font-size:.7rem;">El email no puede cambiarse. Contáctanos si necesitas actualizar.</small></div>
    <div style="margin:20px 0 16px;padding-top:16px;border-top:1px solid var(--brd);font-size:.76rem;color:var(--txt2);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Cambiar contraseña (opcional)</div>
    <div class="afr2"><div class="afg"><label>Nueva contraseña</label><input type="password" name="password" placeholder="Dejar en blanco para no cambiar"></div><div class="afg"><label>Confirmar contraseña</label><input type="password" name="password2" placeholder="Repetir contraseña"></div></div>
    <button type="submit" class="ba ba-r" style="padding:11px 28px;"><i class="fas fa-save"></i> Guardar cambios</button>
  </form></div>
</div>
<?php require_once SRC_PATH.'/views/portal/layout-bottom.php'; ?>
