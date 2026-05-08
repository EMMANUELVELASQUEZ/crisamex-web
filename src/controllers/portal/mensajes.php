<?php
$portalTitle = 'Mensajes';
$cid = $_SESSION['cliente_id'];

if(isset($_GET['mark'])){
  Database::query("UPDATE portal_notificaciones SET leida=1 WHERE id=? AND cliente_id=?",[(int)$_GET['mark'],$cid]);
  echo 'ok'; exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $asunto  = trim($_POST['asunto']??'Sin asunto');
  $mensaje = trim($_POST['mensaje']??'');
  if(!empty($mensaje)){
    Database::query("INSERT INTO portal_mensajes(cliente_id,asunto,mensaje,de,leido_admin) VALUES(?,?,?,'cliente',0)",[$cid,$asunto,$mensaje]);
  }
  header('Location: /portal/mensajes?ok=1'); exit;
}

Database::query("UPDATE portal_mensajes SET leido_cliente=1 WHERE cliente_id=? AND de='admin'",[$cid]);
$msgs = Database::fetchAll("SELECT * FROM portal_mensajes WHERE cliente_id=? ORDER BY created_at ASC",[$cid]);
$cliente = Database::fetchOne("SELECT nombre FROM clientes WHERE id=?",[$cid]);

require_once SRC_PATH.'/views/portal/layout-top.php';
?>

<?php if(isset($_GET['ok'])): ?>
<div class="aa aa-ok" style="margin-bottom:16px;"><i class="fas fa-check-circle"></i> Mensaje enviado. Te responderemos pronto.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:16px;height:calc(100vh - 150px);">

  <!-- CHAT -->
  <div class="ac" style="margin:0;display:flex;flex-direction:column;overflow:hidden;">
    <div class="ac-head" style="flex-shrink:0;">
      <h2><i class="fas fa-comments"></i> Conversación con CRISAMEX</h2>
      <div style="display:flex;align-items:center;gap:6px;font-size:.76rem;color:#16a34a;font-weight:600;">
        <span style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;"></span>
        En línea
      </div>
    </div>

    <!-- Mensajes -->
    <div id="chat-box" style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:10px;background:#f8f9fa;">
      <?php if(empty($msgs)): ?>
      <div style="text-align:center;padding:40px;color:var(--txt3);">
        <i class="fas fa-comment-dots" style="font-size:3rem;display:block;margin-bottom:14px;color:#e0e0e0;"></i>
        <p style="font-size:.9rem;">Sin mensajes aún. ¡Escríbenos y te ayudamos!</p>
      </div>
      <?php else:
        $lastDate = '';
        foreach($msgs as $m):
          $mDate = date('d/m/Y',strtotime($m['created_at']));
          $mTime = date('H:i',strtotime($m['created_at']));
          $mio = $m['de']==='cliente';
          if($mDate !== $lastDate):
            $lastDate = $mDate;
            $lbl = $mDate===date('d/m/Y')?'Hoy':($mDate===date('d/m/Y',strtotime('-1 day'))?'Ayer':$mDate);
      ?>
        <div style="text-align:center;font-size:.7rem;color:#bbb;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin:6px 0;">
          <span style="background:#e8e8e8;padding:3px 12px;border-radius:12px;"><?= $lbl ?></span>
        </div>
      <?php endif; ?>
        <div style="display:flex;flex-direction:column;align-items:<?= $mio?'flex-end':'flex-start' ?>;max-width:75%;<?= $mio?'align-self:flex-end':'align-self:flex-start' ?>;">
          <div style="font-size:.68rem;color:#bbb;margin-bottom:4px;"><?= $mio?'Tú':'CRISAMEX' ?> · <?= $mTime ?></div>
          <div style="padding:10px 14px;border-radius:<?= $mio?'12px 12px 2px 12px':'12px 12px 12px 2px' ?>;font-size:.88rem;line-height:1.55;word-break:break-word;<?= $mio?'background:#C8151B;color:#fff;':'background:#fff;color:#1a1a1a;box-shadow:0 1px 3px rgba(0,0,0,.08);' ?>">
            <?= nl2br(htmlspecialchars($m['mensaje'])) ?>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>

    <!-- Input -->
    <div style="padding:14px 16px;border-top:1px solid var(--brd);background:#fff;flex-shrink:0;">
      <form method="POST" style="display:flex;gap:10px;align-items:flex-end;">
        <input type="hidden" name="asunto" value="Consulta del cliente">
        <textarea name="mensaje" id="msg-ta" placeholder="Escribe tu mensaje..." required
          style="flex:1;padding:10px 14px;border:1.5px solid #e8e8e8;border-radius:20px;font-family:'DM Sans',sans-serif;font-size:.9rem;resize:none;min-height:44px;max-height:110px;outline:none;background:#f8f8f8;"
          onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();this.form.submit();}"></textarea>
        <button type="submit" style="width:44px;height:44px;border-radius:50%;background:#C8151B;border:none;color:#fff;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .2s;" onmouseover="this.style.background='#9a1015'" onmouseout="this.style.background='#C8151B'">
          <i class="fas fa-paper-plane"></i>
        </button>
      </form>
      <div style="font-size:.7rem;color:#bbb;text-align:center;margin-top:6px;">Enter para enviar · Shift+Enter para nueva línea</div>
    </div>
  </div>

  <!-- INFO LATERAL -->
  <div style="display:flex;flex-direction:column;gap:14px;">
    <div class="ac" style="margin:0;">
      <div class="ac-head"><h2><i class="fas fa-headset"></i> Soporte CRISAMEX</h2></div>
      <div class="ac-body">
        <p style="font-size:.84rem;color:var(--txt3);margin-bottom:16px;line-height:1.6;">Respondemos en menos de 24 horas hábiles. También puedes contactarnos directamente:</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <a href="tel:+525556508420" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:rgba(200,21,27,.06);border:1px solid rgba(200,21,27,.15);border-radius:6px;text-decoration:none;color:#C8151B;font-size:.84rem;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#C8151B';this.style.color='#fff'" onmouseout="this.style.background='rgba(200,21,27,.06)';this.style.color='#C8151B'">
            <i class="fas fa-phone-alt"></i> 01 55 5650 8420
          </a>
          <a href="https://wa.me/525556508420" target="_blank" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:6px;text-decoration:none;color:#16a34a;font-size:.84rem;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#25D366';this.style.color='#fff'" onmouseout="this.style.background='rgba(37,211,102,.08)';this.style.color='#16a34a'">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
          <a href="mailto:contacto@crisamex.com" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8f8f8;border:1px solid #e8e8e8;border-radius:6px;text-decoration:none;color:var(--txt2);font-size:.84rem;font-weight:600;">
            <i class="fas fa-envelope"></i> contacto@crisamex.com
          </a>
        </div>
      </div>
    </div>
    <div class="ac" style="margin:0;">
      <div class="ac-head"><h2><i class="fas fa-clock"></i> Horario</h2></div>
      <div class="ac-body" style="font-size:.83rem;color:var(--txt3);line-height:1.9;">
        <div><strong style="color:var(--txt);">Lun – Vie:</strong> 9:00 AM – 6:00 PM</div>
        <div><strong style="color:var(--txt);">Sábado:</strong> 9:00 AM – 1:00 PM</div>
        <div><strong style="color:var(--txt);">Domingo:</strong> Cerrado</div>
      </div>
    </div>
  </div>

</div>

<script>
var box = document.getElementById('chat-box');
if(box) box.scrollTop = box.scrollHeight;
</script>

<?php require_once SRC_PATH.'/views/portal/layout-bottom.php'; ?>
