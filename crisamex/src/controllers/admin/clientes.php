<?php
$adminTitle = 'Clientes';
$adminPage  = 'clientes';

// Acciones
if(isset($_GET['toggle'])){
  $c = Database::fetchOne("SELECT activo FROM clientes WHERE id=?", [(int)$_GET['toggle']]);
  if($c) Database::query("UPDATE clientes SET activo=? WHERE id=?", [(int)!$c['activo'], (int)$_GET['toggle']]);
  header('Location: /admin/clientes?ok=toggle'); exit;
}
if(isset($_GET['del'])){
  Database::query("DELETE FROM clientes WHERE id=?", [(int)$_GET['del']]);
  header('Location: /admin/clientes?ok=del'); exit;
}

// Cambiar plan/licencia
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['accion']) && $_POST['accion']==='update_cliente'){
  $id      = (int)$_POST['id'];
  $plan_id = $_POST['plan_id'] ? (int)$_POST['plan_id'] : null;
  $status  = $_POST['licencia_status'];
  $fin     = $_POST['licencia_fin'] ?: null;
  $inicio  = $_POST['licencia_inicio'] ?: null;
  Database::query(
    "UPDATE clientes SET plan_id=?,licencia_status=?,licencia_inicio=?,licencia_fin=? WHERE id=?",
    [$plan_id, $status, $inicio, $fin, $id]
  );
  // Notificar al cliente
  if($status === 'activa'){
    Database::query("INSERT INTO portal_notificaciones(cliente_id,titulo,mensaje,tipo) VALUES(?,?,?,'success')",
      [$id,'¡Licencia activada!','Tu licencia ha sido activada por el equipo de CRISAMEX. Ya tienes acceso completo a tu plan.']);
  }
  header('Location: /admin/clientes?ok=update'); exit;
}

$clientes = Database::fetchAll("
  SELECT c.*, p.nombre as plan_nombre, p.precio_mensual
  FROM clientes c
  LEFT JOIN planes p ON c.plan_id=p.id
  ORDER BY c.created_at DESC
");
$planes   = Database::fetchAll("SELECT id, nombre FROM planes WHERE activo=1 ORDER BY orden");

$t  = count($clientes);
$ac = count(array_filter($clientes, fn($c)=>$c['licencia_status']==='activa'));
$tr = count(array_filter($clientes, fn($c)=>$c['licencia_status']==='trial'));
$in = $t - $ac - $tr;

// Cliente seleccionado para editar
$editId = isset($_GET['editar']) ? (int)$_GET['editar'] : 0;

require_once SRC_PATH.'/views/admin/layout-top.php';
?>

<?php if(isset($_GET['ok'])): ?>
<div class="aa aa-ok" style="margin-bottom:16px;">
  <i class="fas fa-check-circle"></i>
  <?= $_GET['ok']==='update' ? 'Cliente actualizado correctamente.' : ($_GET['ok']==='del' ? 'Cliente eliminado.' : 'Estado cambiado.') ?>
</div>
<?php endif; ?>

<!-- STATS -->
<div class="st-row" style="margin-bottom:18px;">
  <div class="st-card"><div class="st-ico b"><i class="fas fa-users"></i></div><div><div class="st-n"><?= $t ?></div><div class="st-l">Total Clientes</div></div></div>
  <div class="st-card"><div class="st-ico g"><i class="fas fa-check-circle"></i></div><div><div class="st-n"><?= $ac ?></div><div class="st-l">Licencias Activas</div></div></div>
  <div class="st-card"><div class="st-ico y"><i class="fas fa-clock"></i></div><div><div class="st-n"><?= $tr ?></div><div class="st-l">Trial / Pendiente</div></div></div>
  <div class="st-card"><div class="st-ico r"><i class="fas fa-times-circle"></i></div><div><div class="st-n"><?= $in ?></div><div class="st-l">Inactivos</div></div></div>
</div>

<div style="display:grid;grid-template-columns:1fr <?= $editId ? '340px' : '' ?>;gap:16px;align-items:start;">

  <!-- TABLA CLIENTES -->
  <div class="ac">
    <div class="ac-head">
      <h2><i class="fas fa-users-cog"></i> Todos los Clientes (<?= $t ?>)</h2>
      <a href="/admin/comunicaciones" class="ba ba-r ba-sm"><i class="fas fa-comments"></i> Chat</a>
    </div>
    <div style="overflow-x:auto;">
      <table class="at">
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Empresa</th>
            <th>Email</th>
            <th>Plan</th>
            <th>Estado</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($clientes)): ?>
          <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--txt3);">
            <i class="fas fa-users" style="font-size:2rem;display:block;margin-bottom:10px;color:#e0e0e0;"></i>
            No hay clientes registrados aún
          </td></tr>
          <?php else: foreach($clientes as $c):
            $isEditing = ($c['id'] == $editId);
          ?>
          <tr style="<?= $isEditing ? 'background:rgba(200,21,27,.04);' : '' ?>">
            <td>
              <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:32px;height:32px;background:<?= $isEditing?'#C8151B':'rgba(200,21,27,.1)' ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;color:<?= $isEditing?'#fff':'#C8151B' ?>;flex-shrink:0;">
                  <?= strtoupper(substr($c['nombre'],0,1)) ?>
                </div>
                <div>
                  <div style="font-weight:600;font-size:.85rem;"><?= htmlspecialchars($c['nombre'].' '.($c['apellidos']??'')) ?></div>
                  <?php if($c['cargo']): ?><div style="font-size:.72rem;color:var(--txt3);"><?= htmlspecialchars($c['cargo']) ?></div><?php endif; ?>
                </div>
              </div>
            </td>
            <td style="font-size:.83rem;color:var(--txt2);"><?= htmlspecialchars($c['empresa']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>" style="color:#2563eb;font-size:.82rem;"><?= htmlspecialchars($c['email']) ?></a></td>
            <td>
              <?php if($c['plan_nombre']): ?>
              <span class="badge bb"><?= htmlspecialchars($c['plan_nombre']) ?></span>
              <?php else: ?>
              <span style="color:var(--txt3);font-size:.8rem;">Sin plan</span>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge <?=
                $c['licencia_status']==='activa'    ? 'bg' :
               ($c['licencia_status']==='trial'     ? 'by' :
               ($c['licencia_status']==='suspendida'? 'bb' : 'br'))
              ?>">
                <?= ucfirst($c['licencia_status'] ?? '—') ?>
              </span>
            </td>
            <td style="font-size:.76rem;color:var(--txt3);white-space:nowrap;">
              <?= date('d/m/Y', strtotime($c['created_at'])) ?>
              <?php if($c['ultimo_acceso']): ?>
              <div style="font-size:.68rem;color:#bbb;"><?= date('d/m H:i', strtotime($c['ultimo_acceso'])) ?></div>
              <?php endif; ?>
            </td>
            <td style="white-space:nowrap;">
              <div style="display:flex;gap:4px;flex-wrap:wrap;">
                <a href="/admin/comunicaciones?c=<?= $c['id'] ?>" class="ba ba-r ba-sm" title="Chat"><i class="fas fa-comments"></i></a>
                <a href="/admin/clientes?editar=<?= $c['id'] ?>" class="ba ba-g ba-sm" title="Editar licencia"><i class="fas fa-edit"></i></a>
                <a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="ba ba-g ba-sm" title="Email"><i class="fas fa-envelope"></i></a>
                <a href="/admin/clientes?toggle=<?= $c['id'] ?>" class="ba ba-g ba-sm" title="Activar/Desactivar" data-confirm="¿Cambiar estado del cliente?"><i class="fas fa-power-off"></i></a>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- PANEL EDICIÓN (cuando hay cliente seleccionado) -->
  <?php if($editId):
    $ec = Database::fetchOne("SELECT c.*, p.nombre as pn FROM clientes c LEFT JOIN planes p ON c.plan_id=p.id WHERE c.id=?", [$editId]);
    if($ec):
  ?>
  <div>
    <div class="ac" style="margin:0 0 14px;">
      <div class="ac-head">
        <h2><i class="fas fa-user-edit"></i> Gestionar Licencia</h2>
        <a href="/admin/clientes" class="ba ba-g ba-sm"><i class="fas fa-times"></i></a>
      </div>
      <div class="ac-body">
        <!-- Perfil del cliente -->
        <div style="text-align:center;padding:14px 0 18px;border-bottom:1px solid var(--brd);margin-bottom:18px;">
          <div style="width:56px;height:56px;background:#C8151B;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700;color:#fff;margin:0 auto 10px;">
            <?= strtoupper(substr($ec['nombre'],0,1)) ?>
          </div>
          <div style="font-weight:700;font-size:.95rem;"><?= htmlspecialchars($ec['nombre'].' '.($ec['apellidos']??'')) ?></div>
          <div style="font-size:.8rem;color:var(--txt3);"><?= htmlspecialchars($ec['empresa']) ?></div>
          <div style="margin-top:8px;">
            <a href="mailto:<?= htmlspecialchars($ec['email']) ?>" style="color:#2563eb;font-size:.78rem;"><?= htmlspecialchars($ec['email']) ?></a>
          </div>
        </div>

        <form method="POST">
          <input type="hidden" name="accion" value="update_cliente">
          <input type="hidden" name="id" value="<?= $ec['id'] ?>">

          <div class="afg">
            <label>Plan</label>
            <select name="plan_id">
              <option value="">Sin plan</option>
              <?php foreach($planes as $pl): ?>
              <option value="<?= $pl['id'] ?>" <?= $ec['plan_id']==$pl['id']?'selected':'' ?>>
                <?= htmlspecialchars($pl['nombre']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="afg">
            <label>Estado de Licencia</label>
            <select name="licencia_status">
              <?php foreach(['trial','activa','suspendida','vencida'] as $s): ?>
              <option value="<?= $s ?>" <?= ($ec['licencia_status']??'trial')===$s?'selected':'' ?>>
                <?= ucfirst($s) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="afr2">
            <div class="afg">
              <label>Inicio</label>
              <input type="date" name="licencia_inicio" value="<?= $ec['licencia_inicio']??'' ?>">
            </div>
            <div class="afg">
              <label>Vencimiento</label>
              <input type="date" name="licencia_fin" value="<?= $ec['licencia_fin']??'' ?>">
            </div>
          </div>

          <button type="submit" class="ba ba-r" style="width:100%;justify-content:center;padding:10px;font-size:.85rem;">
            <i class="fas fa-save"></i> Guardar cambios
          </button>
        </form>

        <div style="margin-top:14px;display:flex;gap:8px;">
          <a href="/admin/comunicaciones?c=<?= $ec['id'] ?>" class="ba ba-g" style="flex:1;justify-content:center;">
            <i class="fas fa-comments"></i> Chat
          </a>
          <a href="mailto:<?= htmlspecialchars($ec['email']) ?>" class="ba ba-g" style="flex:1;justify-content:center;">
            <i class="fas fa-envelope"></i> Email
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; endif; ?>

</div>

<?php require_once SRC_PATH.'/views/admin/layout-bottom.php'; ?>
