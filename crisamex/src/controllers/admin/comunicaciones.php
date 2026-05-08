<?php
$adminTitle = 'Centro de Comunicaciones';
$adminPage  = 'comunicaciones';

$cliente_id = isset($_GET['c']) ? (int)$_GET['c'] : 0;

/* ── ENVIAR MENSAJE ── */
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['accion'])){

  if($_POST['accion']==='enviar_msg'){
    $msg = trim($_POST['mensaje'] ?? '');
    $cid = (int)($_POST['cliente_id'] ?? 0);
    if($msg && $cid){
      Database::query(
        "INSERT INTO portal_mensajes (cliente_id,asunto,mensaje,de,leido_cliente) VALUES (?,?,?,'admin',0)",
        [$cid, 'Mensaje de CRISAMEX', $msg]
      );
      // Notificación al cliente
      Database::query(
        "INSERT INTO portal_notificaciones (cliente_id,titulo,mensaje,tipo) VALUES (?,?,?,'info')",
        [$cid,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.']
      );
      header('Location: /admin/comunicaciones?c='.$cid.'&ok=msg'); exit;
    }
  }

  if($_POST['accion']==='enviar_doc'){
    $cid   = (int)($_POST['cliente_id'] ?? 0);
    $titulo= trim($_POST['titulo'] ?? '');
    $desc  = trim($_POST['descripcion'] ?? '');
    $tipo  = $_POST['tipo'] ?? 'otro';
    $archivo = '';
    if(!empty($_FILES['archivo']['name']) && $_FILES['archivo']['error']===0){
      $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
      $allowed = ['pdf','doc','docx','xls','xlsx','jpg','jpeg','png','zip'];
      if(in_array($ext,$allowed)){
        $fn = 'doc_'.$cid.'_'.time().'.'.$ext;
        move_uploaded_file($_FILES['archivo']['tmp_name'], ROOT_PATH.'/public/uploads/'.$fn);
        $archivo = $fn;
      }
    }
    if($titulo && $cid){
      Database::query(
        "INSERT INTO portal_documentos (cliente_id,titulo,descripcion,archivo,tipo,subido_por) VALUES (?,?,?,?,?,'admin')",
        [$cid,$titulo,$desc,$archivo,$tipo]
      );
      Database::query(
        "INSERT INTO portal_notificaciones (cliente_id,titulo,mensaje,tipo) VALUES (?,?,?,'success')",
        [$cid,'Nuevo documento disponible','CRISAMEX ha subido un nuevo documento para ti: '.$titulo]
      );
      header('Location: /admin/comunicaciones?c='.$cid.'&ok=doc'); exit;
    }
  }

  if($_POST['accion']==='marcar_leido'){
    $cid = (int)($_POST['cliente_id'] ?? 0);
    Database::query("UPDATE portal_mensajes SET leido_admin=1 WHERE cliente_id=? AND de='cliente'",[$cid]);
    echo json_encode(['ok'=>true]); exit;
  }
}

/* ── DATOS ── */
// Lista de clientes con último mensaje y no leídos
$clientes = Database::fetchAll("
  SELECT c.id, c.nombre, c.apellidos, c.empresa, c.email, c.avatar,
         c.licencia_status, c.ultimo_acceso,
         p.nombre as plan_nombre,
         (SELECT COUNT(*) FROM portal_mensajes pm WHERE pm.cliente_id=c.id AND pm.de='cliente' AND pm.leido_admin=0) as sin_leer,
         (SELECT pm2.mensaje FROM portal_mensajes pm2 WHERE pm2.cliente_id=c.id ORDER BY pm2.created_at DESC LIMIT 1) as ultimo_msg,
         (SELECT pm3.created_at FROM portal_mensajes pm3 WHERE pm3.cliente_id=c.id ORDER BY pm3.created_at DESC LIMIT 1) as ultimo_msg_fecha,
         (SELECT pm4.de FROM portal_mensajes pm4 WHERE pm4.cliente_id=c.id ORDER BY pm4.created_at DESC LIMIT 1) as ultimo_msg_de
  FROM clientes c
  LEFT JOIN planes p ON c.plan_id=p.id
  WHERE c.activo=1
  ORDER BY sin_leer DESC, ultimo_msg_fecha DESC, c.created_at DESC
");

// Datos del cliente activo
$cliente_activo = null;
$mensajes       = [];
$documentos     = [];

if($cliente_id){
  $cliente_activo = Database::fetchOne(
    "SELECT c.*,p.nombre as plan_nombre,p.precio_mensual FROM clientes c LEFT JOIN planes p ON c.plan_id=p.id WHERE c.id=?",
    [$cliente_id]
  );
  if($cliente_activo){
    $mensajes = Database::fetchAll(
      "SELECT * FROM portal_mensajes WHERE cliente_id=? ORDER BY created_at ASC",
      [$cliente_id]
    );
    $documentos = Database::fetchAll(
      "SELECT * FROM portal_documentos WHERE cliente_id=? ORDER BY created_at DESC LIMIT 10",
      [$cliente_id]
    );
    // Marcar mensajes del cliente como leídos
    Database::query("UPDATE portal_mensajes SET leido_admin=1 WHERE cliente_id=? AND de='cliente'",[$cliente_id]);
  }
}

// Total sin leer global
$total_sin_leer = Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE de='cliente' AND leido_admin=0")['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Comunicaciones — CRISAMEX Admin</title>
<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;overflow:hidden}
body{font-family:'DM Sans',sans-serif;background:#f0f2f5;color:#1a1a1a;-webkit-font-smoothing:antialiased;display:flex;flex-direction:column}

/* ══ TOP BAR ══ */
.topbar{background:#1a1a1a;height:54px;display:flex;align-items:center;padding:0 20px;gap:16px;flex-shrink:0;border-bottom:3px solid #C8151B}
.tb-logo-wrap{background:#fff;padding:5px 10px;border-radius:4px;display:flex;align-items:center}
.tb-logo-wrap img{height:32px;width:auto;display:block}
.tb-title{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:2px;color:#fff;margin-left:4px}
.tb-badge{background:#C8151B;color:#fff;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:12px;font-family:'Bebas Neue',sans-serif;letter-spacing:1px}
.tb-right{margin-left:auto;display:flex;align-items:center;gap:12px}
.tb-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:4px;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .2s;border:none;cursor:pointer;font-family:'DM Sans',sans-serif}
.tb-btn-ghost{background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.12)}
.tb-btn-ghost:hover{background:rgba(255,255,255,.15);color:#fff}
.tb-btn-red{background:#C8151B;color:#fff}
.tb-btn-red:hover{background:#9a1015}
.tb-user{display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.6);font-size:.8rem}
.tb-avatar{width:32px;height:32px;background:#C8151B;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.82rem;color:#fff}

/* ══ LAYOUT 3 COLUMNAS ══ */
.layout{display:grid;grid-template-columns:280px 1fr 320px;flex:1;overflow:hidden;height:calc(100vh - 54px)}

/* ══ COLUMNA 1 — LISTA CLIENTES ══ */
.col-clients{background:#fff;border-right:1px solid #e8e8e8;display:flex;flex-direction:column;overflow:hidden}
.cc-head{padding:16px;border-bottom:1px solid #e8e8e8;flex-shrink:0}
.cc-head h2{font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:1.5px;color:#1a1a1a;margin-bottom:10px;display:flex;align-items:center;gap:8px}
.cc-head h2 .cnt{background:#C8151B;color:#fff;font-size:.68rem;padding:1px 7px;border-radius:10px}
.search-box{position:relative}
.search-box i{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#bbb;font-size:.82rem}
.search-inp{width:100%;padding:8px 10px 8px 32px;border:1.5px solid #e8e8e8;border-radius:6px;font-family:'DM Sans',sans-serif;font-size:.84rem;color:#1a1a1a;outline:none;background:#f8f8f8}
.search-inp:focus{border-color:#C8151B;background:#fff}
.cc-list{overflow-y:auto;flex:1}

.client-row{display:flex;align-items:center;gap:12px;padding:14px 16px;cursor:pointer;border-bottom:1px solid #f5f5f5;transition:background .15s;text-decoration:none;color:inherit;position:relative}
.client-row:hover{background:#fef2f2}
.client-row.active{background:#fef2f2;border-left:3px solid #C8151B}
.client-row.active .cr-name{color:#C8151B}
.cr-avatar{width:42px;height:42px;border-radius:50%;background:#C8151B;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;color:#fff;flex-shrink:0;font-family:'Bebas Neue',sans-serif;letter-spacing:1px;position:relative}
.cr-online{position:absolute;bottom:1px;right:1px;width:10px;height:10px;background:#22c55e;border-radius:50%;border:2px solid #fff}
.cr-info{flex:1;min-width:0}
.cr-name{font-size:.88rem;font-weight:600;color:#1a1a1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cr-empresa{font-size:.74rem;color:#888;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cr-preview{font-size:.74rem;color:#999;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px}
.cr-meta{display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0}
.cr-time{font-size:.68rem;color:#bbb}
.cr-badge{background:#C8151B;color:#fff;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:12px;min-width:20px;text-align:center}
.cr-plan{font-size:.64rem;font-weight:600;padding:1px 6px;border-radius:3px;background:#fef2f2;color:#C8151B;border:1px solid #fecaca}

/* Estado sin cliente seleccionado */
.no-client{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:#ccc;text-align:center;padding:40px}
.no-client i{font-size:4rem;margin-bottom:16px;color:#e8e8e8}
.no-client h3{font-family:'Bebas Neue',sans-serif;font-size:1.5rem;letter-spacing:2px;color:#ccc;margin-bottom:8px}
.no-client p{font-size:.85rem;color:#bbb;font-weight:300}

/* ══ COLUMNA 2 — CHAT ══ */
.col-chat{display:flex;flex-direction:column;overflow:hidden;background:#f0f2f5}

/* Cabecera chat */
.chat-head{background:#fff;border-bottom:1px solid #e8e8e8;padding:12px 20px;display:flex;align-items:center;gap:14px;flex-shrink:0;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.ch-avatar{width:44px;height:44px;border-radius:50%;background:#C8151B;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:#fff;flex-shrink:0;font-family:'Bebas Neue',sans-serif}
.ch-name{font-size:.95rem;font-weight:700;color:#1a1a1a}
.ch-sub{font-size:.76rem;color:#888}
.ch-status{display:inline-flex;align-items:center;gap:4px;font-size:.7rem;font-weight:600}
.ch-status.activa{color:#16a34a}
.ch-status.trial{color:#d97706}
.ch-status.vencida{color:#C8151B}
.ch-status i{font-size:.5rem}
.ch-actions{margin-left:auto;display:flex;gap:8px}
.ch-btn{width:34px;height:34px;border-radius:6px;background:#f5f5f5;border:1px solid #e8e8e8;display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#666;cursor:pointer;transition:all .2s;text-decoration:none}
.ch-btn:hover{background:#fef2f2;color:#C8151B;border-color:#fecaca}

/* Mensajes */
.chat-msgs{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:12px;scroll-behavior:smooth}
.chat-msgs::-webkit-scrollbar{width:4px}
.chat-msgs::-webkit-scrollbar-track{background:transparent}
.chat-msgs::-webkit-scrollbar-thumb{background:#ddd;border-radius:2px}

/* Fecha separador */
.msg-date-sep{text-align:center;font-size:.7rem;color:#bbb;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin:8px 0}
.msg-date-sep span{background:#e8e8e8;padding:3px 12px;border-radius:12px}

/* Burbuja de mensaje */
.msg-wrap{display:flex;align-items:flex-end;gap:8px;max-width:75%}
.msg-wrap.mine{align-self:flex-end;flex-direction:row-reverse}
.msg-wrap.theirs{align-self:flex-start}
.msg-av{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;font-family:'Bebas Neue',sans-serif}
.msg-av.admin-av{background:#C8151B;color:#fff}
.msg-av.client-av{background:#e8e8e8;color:#555}
.msg-bubble{padding:10px 14px;border-radius:12px;font-size:.88rem;line-height:1.55;position:relative;word-break:break-word}
.msg-wrap.mine .msg-bubble{background:#C8151B;color:#fff;border-radius:12px 12px 2px 12px}
.msg-wrap.theirs .msg-bubble{background:#fff;color:#1a1a1a;border-radius:12px 12px 12px 2px;box-shadow:0 1px 3px rgba(0,0,0,.08)}
.msg-time{font-size:.65rem;opacity:.65;margin-top:5px;white-space:nowrap}
.msg-wrap.mine .msg-time{text-align:right;color:rgba(255,255,255,.7)}
.msg-wrap.theirs .msg-time{color:#bbb}
.msg-asunto{font-size:.7rem;font-weight:700;opacity:.7;margin-bottom:3px;text-transform:uppercase;letter-spacing:.5px}

/* Sin mensajes */
.chat-empty{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#ccc;text-align:center;padding:40px}
.chat-empty i{font-size:3.5rem;margin-bottom:14px;color:#e0e0e0}
.chat-empty p{font-size:.88rem;color:#bbb;font-weight:300}

/* Input de mensaje */
.chat-input-area{background:#fff;border-top:1px solid #e8e8e8;padding:14px 16px;flex-shrink:0}
.chat-input-row{display:flex;align-items:flex-end;gap:10px}
.chat-textarea{flex:1;padding:11px 14px;border:1.5px solid #e8e8e8;border-radius:20px;font-family:'DM Sans',sans-serif;font-size:.9rem;color:#1a1a1a;outline:none;resize:none;max-height:120px;min-height:44px;transition:border-color .2s;line-height:1.45;background:#f8f8f8}
.chat-textarea:focus{border-color:#C8151B;background:#fff}
.chat-textarea::placeholder{color:#bbb}
.chat-send{width:44px;height:44px;border-radius:50%;background:#C8151B;border:none;color:#fff;font-size:1rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.chat-send:hover{background:#9a1015;transform:scale(1.05)}
.chat-send:active{transform:scale(.95)}
.chat-hint{font-size:.72rem;color:#bbb;text-align:center;margin-top:8px}

/* ══ COLUMNA 3 — INFO CLIENTE ══ */
.col-info{background:#fff;border-left:1px solid #e8e8e8;display:flex;flex-direction:column;overflow:hidden}
.ci-head{padding:16px;border-bottom:1px solid #e8e8e8;background:#f8f8f8;flex-shrink:0}
.ci-head h3{font-family:'Bebas Neue',sans-serif;font-size:1rem;letter-spacing:1.5px;color:#1a1a1a}

/* Tabs */
.ci-tabs{display:flex;border-bottom:1px solid #e8e8e8;flex-shrink:0}
.ci-tab{flex:1;padding:12px;text-align:center;font-size:.78rem;font-weight:600;color:#888;cursor:pointer;transition:all .2s;border-bottom:2px solid transparent;background:none;border-top:none;border-left:none;border-right:none;font-family:'DM Sans',sans-serif}
.ci-tab:hover{color:#C8151B;background:#fef2f2}
.ci-tab.active{color:#C8151B;border-bottom-color:#C8151B;background:#fff}

.ci-body{overflow-y:auto;flex:1;padding:16px}

/* Info del cliente */
.info-block{margin-bottom:20px}
.info-block-title{font-size:.68rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#C8151B;margin-bottom:10px;display:flex;align-items:center;gap:6px}
.info-row{display:flex;align-items:flex-start;gap:10px;padding:8px 0;border-bottom:1px solid #f5f5f5;font-size:.83rem}
.info-row:last-child{border:none}
.info-row i{color:#C8151B;width:16px;flex-shrink:0;margin-top:2px;font-size:.8rem}
.info-row .lbl{color:#888;font-size:.74rem;display:block;margin-bottom:1px}
.info-row .val{color:#1a1a1a;font-weight:500;word-break:break-word}

/* Badge estado */
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:12px;font-size:.72rem;font-weight:700}
.s-activa{background:#dcfce7;color:#15803d;border:1px solid #bbf7d0}
.s-trial{background:#fef3c7;color:#92400e;border:1px solid #fde68a}
.s-vencida{background:#fef2f2;color:#C8151B;border:1px solid #fecaca}
.s-suspendida{background:#f3f4f6;color:#374151;border:1px solid #e5e7eb}

/* Avatar grande */
.client-profile{text-align:center;padding:20px 16px;border-bottom:1px solid #f0f0f0;margin-bottom:0}
.cp-avatar{width:72px;height:72px;border-radius:50%;background:#C8151B;display:flex;align-items:center;justify-content:center;font-family:'Bebas Neue',sans-serif;font-size:2rem;color:#fff;margin:0 auto 12px;letter-spacing:2px}
.cp-name{font-size:1rem;font-weight:700;color:#1a1a1a}
.cp-empresa{font-size:.8rem;color:#888;margin-top:2px}

/* Formulario docs */
.doc-form{background:#f8f8f8;border:1.5px solid #e8e8e8;border-radius:8px;padding:16px;margin-bottom:16px}
.doc-form h4{font-family:'Bebas Neue',sans-serif;font-size:.95rem;letter-spacing:1.5px;color:#1a1a1a;margin-bottom:12px;display:flex;align-items:center;gap:7px}
.df{margin-bottom:12px}
.df label{display:block;font-size:.7rem;font-weight:700;color:#666;margin-bottom:5px;text-transform:uppercase;letter-spacing:.8px}
.df input,.df select,.df textarea{width:100%;padding:8px 12px;background:#fff;border:1.5px solid #e8e8e8;border-radius:5px;font-family:'DM Sans',sans-serif;font-size:.84rem;color:#1a1a1a;outline:none;transition:border-color .2s}
.df input:focus,.df select:focus,.df textarea:focus{border-color:#C8151B}
.df textarea{min-height:60px;resize:vertical}
.df-file{width:100%;padding:8px 12px;background:#fff;border:1.5px dashed #e8e8e8;border-radius:5px;font-size:.82rem;color:#888;cursor:pointer}
.df-file:focus{border-color:#C8151B;outline:none}
.btn-send-doc{width:100%;padding:10px;background:#C8151B;color:#fff;border:none;border-radius:5px;font-family:'Bebas Neue',sans-serif;font-size:.9rem;letter-spacing:1.5px;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-send-doc:hover{background:#9a1015}

/* Lista docs */
.doc-item{display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f5f5f5}
.doc-item:last-child{border:none}
.doc-ico{width:36px;height:36px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:#C8151B;flex-shrink:0}
.doc-name{font-size:.82rem;font-weight:500;color:#1a1a1a;display:block}
.doc-meta{font-size:.7rem;color:#bbb}
.doc-dl{color:#C8151B;font-size:.75rem;text-decoration:none;padding:3px 8px;border:1px solid #fecaca;border-radius:4px;transition:all .2s}
.doc-dl:hover{background:#C8151B;color:#fff}

/* Notif de éxito */
.flash-ok{background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:10px 14px;border-radius:6px;font-size:.82rem;display:flex;align-items:center;gap:8px;margin-bottom:16px}

/* Sin datos */
.empty-state{text-align:center;padding:30px 16px;color:#ccc}
.empty-state i{font-size:2.5rem;display:block;margin-bottom:10px;color:#e8e8e8}
.empty-state p{font-size:.82rem;font-weight:300}

/* AUTO REFRESH indicator */
.live-dot{width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}
</style>
</head>
<body>

<!-- ══ TOP BAR ══ -->
<div class="topbar">
  <div class="tb-logo-wrap">
    <img src="/images/logo-crisamex.jpg" alt="CRISAMEX">
  </div>
  <span class="tb-title">COMUNICACIONES</span>
  <?php if($total_sin_leer > 0): ?>
  <span class="tb-badge"><?= $total_sin_leer ?> SIN LEER</span>
  <?php endif; ?>
  <span style="display:inline-flex;align-items:center;gap:5px;font-size:.72rem;color:#666;margin-left:8px">
    <span class="live-dot"></span><span style="color:#888">En vivo</span>
  </span>
  <div class="tb-right">
    <a href="/admin" class="tb-btn tb-btn-ghost"><i class="fas fa-arrow-left"></i> Panel Admin</a>
    <a href="/admin/logout" class="tb-btn tb-btn-ghost"><i class="fas fa-sign-out-alt"></i></a>
    <div class="tb-user">
      <div class="tb-avatar"><?= strtoupper(substr($_SESSION['admin_nombre']??'A',0,1)) ?></div>
      <span><?= htmlspecialchars($_SESSION['admin_nombre']??'Admin') ?></span>
    </div>
  </div>
</div>

<!-- ══ LAYOUT 3 COLS ══ -->
<div class="layout">

  <!-- ── COL 1: LISTA CLIENTES ── -->
  <aside class="col-clients">
    <div class="cc-head">
      <h2>
        <i class="fas fa-users" style="color:#C8151B"></i>
        Clientes
        <?php if($total_sin_leer > 0): ?>
        <span class="cnt"><?= $total_sin_leer ?></span>
        <?php endif; ?>
      </h2>
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-inp" id="search-inp" placeholder="Buscar cliente o empresa...">
      </div>
    </div>
    <div class="cc-list" id="client-list">
      <?php if(empty($clientes)): ?>
      <div class="empty-state" style="padding:40px 16px">
        <i class="fas fa-users"></i>
        <p>No hay clientes registrados aún</p>
      </div>
      <?php else: foreach($clientes as $cl):
        $ini = strtoupper(substr($cl['nombre'],0,1));
        $isActive = ($cl['id'] == $cliente_id);
        $previewMsg = $cl['ultimo_msg'] ? mb_substr($cl['ultimo_msg'],0,45).'…' : 'Sin mensajes';
        $previewPre = $cl['ultimo_msg_de']==='admin' ? '→ ' : '';
        $fecha = '';
        if($cl['ultimo_msg_fecha']){
          $d = new DateTime($cl['ultimo_msg_fecha']);
          $hoy = new DateTime();
          $fecha = $d->format('d/m') == $hoy->format('d/m') ? $d->format('H:i') : $d->format('d/m');
        }
      ?>
      <a href="/admin/comunicaciones?c=<?= $cl['id'] ?>" class="client-row <?= $isActive?'active':'' ?>" data-name="<?= htmlspecialchars(strtolower($cl['nombre'].' '.$cl['empresa'])) ?>">
        <div class="cr-avatar">
          <?= $ini ?>
          <?php if($cl['ultimo_acceso'] && (time()-strtotime($cl['ultimo_acceso']))<3600): ?>
          <div class="cr-online"></div>
          <?php endif; ?>
        </div>
        <div class="cr-info">
          <div class="cr-name"><?= htmlspecialchars($cl['nombre'].' '.($cl['apellidos']??'')) ?></div>
          <div class="cr-empresa"><?= htmlspecialchars($cl['empresa']) ?></div>
          <div class="cr-preview"><?= $previewPre.htmlspecialchars($previewMsg) ?></div>
        </div>
        <div class="cr-meta">
          <span class="cr-time"><?= $fecha ?></span>
          <?php if($cl['sin_leer']>0): ?><span class="cr-badge"><?= $cl['sin_leer'] ?></span><?php endif; ?>
          <?php if($cl['plan_nombre']): ?><span class="cr-plan"><?= htmlspecialchars($cl['plan_nombre']) ?></span><?php endif; ?>
        </div>
      </a>
      <?php endforeach; endif; ?>
    </div>
  </aside>

  <!-- ── COL 2: CHAT ── -->
  <section class="col-chat">
    <?php if(!$cliente_activo): ?>
      <div class="no-client">
        <i class="fas fa-comments"></i>
        <h3>Selecciona un cliente</h3>
        <p>Elige un cliente de la lista para ver la conversación y enviarle mensajes o documentos.</p>
      </div>
    <?php else: ?>

    <!-- Cabecera del chat -->
    <div class="chat-head">
      <div class="ch-avatar"><?= strtoupper(substr($cliente_activo['nombre'],0,1)) ?></div>
      <div>
        <div class="ch-name"><?= htmlspecialchars($cliente_activo['nombre'].' '.($cliente_activo['apellidos']??'')) ?></div>
        <div class="ch-sub">
          <?= htmlspecialchars($cliente_activo['empresa']) ?> &nbsp;·&nbsp;
          <span class="ch-status <?= $cliente_activo['licencia_status'] ?>">
            <i class="fas fa-circle"></i>
            <?= ucfirst($cliente_activo['licencia_status']) ?>
          </span>
        </div>
      </div>
      <div class="ch-actions">
        <a href="mailto:<?= htmlspecialchars($cliente_activo['email']) ?>" class="ch-btn" title="Enviar email">
          <i class="fas fa-envelope"></i>
        </a>
        <a href="tel:<?= htmlspecialchars($cliente_activo['telefono']??'') ?>" class="ch-btn" title="Llamar">
          <i class="fas fa-phone-alt"></i>
        </a>
        <button class="ch-btn" onclick="document.getElementById('tab-docs').click()" title="Enviar documento">
          <i class="fas fa-paperclip"></i>
        </button>
      </div>
    </div>

    <!-- Mensajes -->
    <div class="chat-msgs" id="chat-msgs">
      <?php if(empty($mensajes)): ?>
      <div class="chat-empty">
        <i class="fas fa-comment-dots"></i>
        <p>Sin mensajes aún.<br>Sé el primero en escribir.</p>
      </div>
      <?php else:
        $lastDate = '';
        foreach($mensajes as $m):
          $mDate = date('d/m/Y', strtotime($m['created_at']));
          $mTime = date('H:i',   strtotime($m['created_at']));
          $isAdmin = ($m['de'] === 'admin');
          if($mDate !== $lastDate):
            $lastDate = $mDate;
            $labelDate = $mDate === date('d/m/Y') ? 'Hoy' : ($mDate === date('d/m/Y',strtotime('-1 day')) ? 'Ayer' : $mDate);
      ?>
        <div class="msg-date-sep"><span><?= $labelDate ?></span></div>
      <?php endif; ?>

        <div class="msg-wrap <?= $isAdmin ? 'mine' : 'theirs' ?>">
          <div class="msg-av <?= $isAdmin ? 'admin-av' : 'client-av' ?>">
            <?= $isAdmin ? 'CR' : strtoupper(substr($cliente_activo['nombre'],0,1)) ?>
          </div>
          <div>
            <div class="msg-bubble">
              <?= nl2br(htmlspecialchars($m['mensaje'])) ?>
            </div>
            <div class="msg-time"><?= $mTime ?> <?= $isAdmin ? '· CRISAMEX' : '' ?></div>
          </div>
        </div>

      <?php endforeach; endif; ?>
    </div>

    <!-- Input enviar mensaje -->
    <div class="chat-input-area">
      <?php if(isset($_GET['ok'])): ?>
      <div class="flash-ok"><i class="fas fa-check-circle"></i><?= $_GET['ok']==='msg' ? 'Mensaje enviado correctamente.' : 'Documento enviado al cliente.' ?></div>
      <?php endif; ?>
      <form method="POST" id="msg-form">
        <input type="hidden" name="accion" value="enviar_msg">
        <input type="hidden" name="cliente_id" value="<?= $cliente_id ?>">
        <div class="chat-input-row">
          <textarea name="mensaje" id="msg-textarea" class="chat-textarea" placeholder="Escribe un mensaje..." rows="1" required></textarea>
          <button type="submit" class="chat-send" title="Enviar">
            <i class="fas fa-paper-plane"></i>
          </button>
        </div>
      </form>
      <div class="chat-hint">Enter + Shift para nueva línea · Enter para enviar</div>
    </div>

    <?php endif; ?>
  </section>

  <!-- ── COL 3: INFO CLIENTE ── -->
  <aside class="col-info">
    <?php if(!$cliente_activo): ?>
      <div class="empty-state" style="padding:60px 16px">
        <i class="fas fa-user-circle"></i>
        <p>Selecciona un cliente para ver su información</p>
      </div>
    <?php else: ?>

    <div class="client-profile">
      <div class="cp-avatar"><?= strtoupper(substr($cliente_activo['nombre'],0,1)) ?></div>
      <div class="cp-name"><?= htmlspecialchars($cliente_activo['nombre'].' '.($cliente_activo['apellidos']??'')) ?></div>
      <div class="cp-empresa"><?= htmlspecialchars($cliente_activo['empresa']) ?></div>
      <br>
      <span class="status-pill s-<?= $cliente_activo['licencia_status'] ?>">
        <i class="fas fa-circle" style="font-size:.5rem"></i>
        <?= ucfirst($cliente_activo['licencia_status']) ?>
      </span>
    </div>

    <!-- TABS -->
    <div class="ci-tabs">
      <button class="ci-tab active" id="tab-info" onclick="showTab('info')"><i class="fas fa-user"></i> Info</button>
      <button class="ci-tab" id="tab-docs" onclick="showTab('docs')"><i class="fas fa-folder"></i> Documentos</button>
    </div>

    <!-- TAB INFO -->
    <div class="ci-body" id="body-info">
      <div class="info-block">
        <div class="info-block-title"><i class="fas fa-address-card"></i> Contacto</div>
        <div class="info-row"><i class="fas fa-envelope"></i><div><span class="lbl">Email</span><a href="mailto:<?= htmlspecialchars($cliente_activo['email']) ?>" class="val" style="color:#C8151B"><?= htmlspecialchars($cliente_activo['email']) ?></a></div></div>
        <div class="info-row"><i class="fas fa-phone-alt"></i><div><span class="lbl">Teléfono</span><span class="val"><?= htmlspecialchars($cliente_activo['telefono']??'—') ?></span></div></div>
        <div class="info-row"><i class="fas fa-briefcase"></i><div><span class="lbl">Cargo</span><span class="val"><?= htmlspecialchars($cliente_activo['cargo']??'—') ?></span></div></div>
      </div>
      <div class="info-block">
        <div class="info-block-title"><i class="fas fa-star"></i> Plan y Licencia</div>
        <div class="info-row"><i class="fas fa-id-card"></i><div><span class="lbl">Plan activo</span><span class="val"><?= htmlspecialchars($cliente_activo['plan_nombre']??'Sin plan') ?></span></div></div>
        <?php if($cliente_activo['precio_mensual']): ?>
        <div class="info-row"><i class="fas fa-tag"></i><div><span class="lbl">Precio</span><span class="val">$<?= number_format($cliente_activo['precio_mensual'],0,'.',',') ?>/mes</span></div></div>
        <?php endif; ?>
        <div class="info-row"><i class="fas fa-calendar-alt"></i><div><span class="lbl">Inicio</span><span class="val"><?= $cliente_activo['licencia_inicio'] ? date('d/m/Y',strtotime($cliente_activo['licencia_inicio'])) : '—' ?></span></div></div>
        <div class="info-row"><i class="fas fa-calendar-times"></i><div><span class="lbl">Vencimiento</span><span class="val"><?= $cliente_activo['licencia_fin'] ? date('d/m/Y',strtotime($cliente_activo['licencia_fin'])) : '—' ?></span></div></div>
      </div>
      <div class="info-block">
        <div class="info-block-title"><i class="fas fa-clock"></i> Actividad</div>
        <div class="info-row"><i class="fas fa-sign-in-alt"></i><div><span class="lbl">Último acceso</span><span class="val"><?= $cliente_activo['ultimo_acceso'] ? date('d/m/Y H:i',strtotime($cliente_activo['ultimo_acceso'])) : '—' ?></span></div></div>
        <div class="info-row"><i class="fas fa-user-plus"></i><div><span class="lbl">Registrado</span><span class="val"><?= date('d/m/Y',strtotime($cliente_activo['created_at'])) ?></span></div></div>
        <?php
          $totalMsgs = Database::fetchOne("SELECT COUNT(*) as c FROM portal_mensajes WHERE cliente_id=?",[$cliente_id])['c']??0;
          $totalDocs = Database::fetchOne("SELECT COUNT(*) as c FROM portal_documentos WHERE cliente_id=?",[$cliente_id])['c']??0;
        ?>
        <div class="info-row"><i class="fas fa-comments"></i><div><span class="lbl">Total mensajes</span><span class="val"><?= $totalMsgs ?></span></div></div>
        <div class="info-row"><i class="fas fa-folder"></i><div><span class="lbl">Documentos</span><span class="val"><?= $totalDocs ?></span></div></div>
      </div>
      <!-- Acciones rápidas -->
      <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px">
        <a href="mailto:<?= htmlspecialchars($cliente_activo['email']) ?>" style="display:flex;align-items:center;gap:8px;padding:9px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;font-size:.82rem;font-weight:600;color:#C8151B;text-decoration:none;transition:all .2s" onmouseover="this.style.background='#C8151B';this.style.color='#fff'" onmouseout="this.style.background='#fef2f2';this.style.color='#C8151B'">
          <i class="fas fa-envelope"></i> Enviar email directo
        </a>
      </div>
    </div>

    <!-- TAB DOCUMENTOS -->
    <div class="ci-body" id="body-docs" style="display:none">
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="accion" value="enviar_doc">
        <input type="hidden" name="cliente_id" value="<?= $cliente_id ?>">
        <div class="doc-form">
          <h4><i class="fas fa-upload" style="color:#C8151B"></i> Enviar Documento</h4>
          <div class="df"><label>Título *</label><input type="text" name="titulo" placeholder="Ej: Reporte mensual mayo" required></div>
          <div class="df"><label>Tipo</label>
            <select name="tipo">
              <option value="reporte">Reporte</option>
              <option value="certificado">Certificado</option>
              <option value="factura">Factura</option>
              <option value="contrato">Contrato</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="df"><label>Descripción</label><textarea name="descripcion" placeholder="Descripción breve..."></textarea></div>
          <div class="df"><label>Archivo (PDF, DOC, XLS, IMG)</label><input type="file" name="archivo" class="df-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip"></div>
          <button type="submit" class="btn-send-doc"><i class="fas fa-cloud-upload-alt"></i> ENVIAR AL CLIENTE</button>
        </div>
      </form>

      <!-- Documentos enviados -->
      <div class="info-block-title" style="margin-bottom:10px"><i class="fas fa-folder-open"></i> Enviados (<?= count($documentos) ?>)</div>
      <?php if(empty($documentos)): ?>
      <div class="empty-state"><i class="fas fa-folder-open"></i><p>Sin documentos enviados</p></div>
      <?php else:
        $tipo_icons=['reporte'=>'fa-chart-bar','certificado'=>'fa-certificate','factura'=>'fa-file-invoice','contrato'=>'fa-file-contract','otro'=>'fa-file'];
        foreach($documentos as $doc): $ico=$tipo_icons[$doc['tipo']]??'fa-file'; ?>
      <div class="doc-item">
        <div class="doc-ico"><i class="fas <?= $ico ?>"></i></div>
        <div style="flex:1;min-width:0">
          <span class="doc-name"><?= htmlspecialchars($doc['titulo']) ?></span>
          <span class="doc-meta"><?= ucfirst($doc['tipo']) ?> · <?= date('d/m/Y',strtotime($doc['created_at'])) ?></span>
        </div>
        <?php if($doc['archivo']): ?>
        <a href="/uploads/<?= htmlspecialchars($doc['archivo']) ?>" target="_blank" class="doc-dl"><i class="fas fa-download"></i></a>
        <?php endif; ?>
      </div>
      <?php endforeach; endif; ?>
    </div>

    <?php endif; ?>
  </aside>

</div>

<script>
/* ── BÚSQUEDA ── */
document.getElementById('search-inp').addEventListener('input', function(){
  var q = this.value.toLowerCase();
  document.querySelectorAll('#client-list .client-row').forEach(function(row){
    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
  });
});

/* ── TABS ── */
function showTab(tab){
  document.getElementById('body-info').style.display = tab==='info' ? '' : 'none';
  document.getElementById('body-docs').style.display = tab==='docs' ? '' : 'none';
  document.getElementById('tab-info').classList.toggle('active', tab==='info');
  document.getElementById('tab-docs').classList.toggle('active', tab==='docs');
}

/* ── TEXTAREA AUTO-RESIZE + ENTER para enviar ── */
var ta = document.getElementById('msg-textarea');
if(ta){
  ta.addEventListener('keydown', function(e){
    if(e.key === 'Enter' && !e.shiftKey){
      e.preventDefault();
      if(this.value.trim()) document.getElementById('msg-form').submit();
    }
  });
  ta.addEventListener('input', function(){
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
  });
}

/* ── SCROLL al fondo del chat ── */
var chatMsgs = document.getElementById('chat-msgs');
if(chatMsgs) chatMsgs.scrollTop = chatMsgs.scrollHeight;

/* ── AUTO REFRESH cada 8 segundos si hay cliente activo ── */
<?php if($cliente_id): ?>
setTimeout(function refresh(){
  fetch(window.location.href, {headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(){
      // Solo recargar si hay mensajes nuevos (simplificado)
      setTimeout(refresh, 8000);
    }).catch(function(){ setTimeout(refresh, 8000); });
}, 8000);
<?php endif; ?>
</script>
</body>
</html>
