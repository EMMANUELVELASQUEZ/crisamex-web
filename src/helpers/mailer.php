<?php
/**
 * CRISAMEX — Sistema de Correos
 * Soporta: Gmail SMTP, Resend API, SendGrid, o mail() nativo
 */
class Mailer {

  // ── Configuración desde variables de entorno ──────────────
  private static function config(): array {
    return [
      'driver'     => getenv('MAIL_DRIVER')     ?: 'smtp',   // smtp | resend | sendgrid | native
      'host'       => getenv('MAIL_HOST')        ?: 'smtp.gmail.com',
      'port'       => (int)(getenv('MAIL_PORT')  ?: 587),
      'user'       => getenv('MAIL_USER')        ?: '',
      'pass'       => getenv('MAIL_PASS')        ?: '',
      'from_email' => getenv('MAIL_FROM_EMAIL')  ?: 'noreply@crisamex.com',
      'from_name'  => getenv('MAIL_FROM_NAME')   ?: 'CRISAMEX',
      'resend_key' => getenv('RESEND_API_KEY')   ?: '',
      'sg_key'     => getenv('SENDGRID_API_KEY') ?: '',
    ];
  }

  // ── Enviar email ──────────────────────────────────────────
  public static function send(string $to, string $subject, string $html, string $toName = ''): bool {
    $cfg = self::config();
    try {
      switch($cfg['driver']) {
        case 'resend':   return self::sendResend($to, $toName, $subject, $html, $cfg);
        case 'sendgrid': return self::sendSendGrid($to, $toName, $subject, $html, $cfg);
        case 'smtp':     return self::sendSMTP($to, $toName, $subject, $html, $cfg);
        default:         return self::sendNative($to, $subject, $html, $cfg);
      }
    } catch(Exception $e) {
      error_log("CRISAMEX Mailer Error: " . $e->getMessage());
      return false;
    }
  }

  // ── RESEND (recomendado) ──────────────────────────────────
  private static function sendResend(string $to, string $toName, string $subject, string $html, array $cfg): bool {
    $payload = json_encode([
      'from'    => $cfg['from_name'].' <'.$cfg['from_email'].'>',
      'to'      => [$toName ? "$toName <$to>" : $to],
      'subject' => $subject,
      'html'    => $html,
    ]);
    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => $payload,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $cfg['resend_key'],
        'Content-Type: application/json',
      ],
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code === 200 || $code === 201;
  }

  // ── SENDGRID ──────────────────────────────────────────────
  private static function sendSendGrid(string $to, string $toName, string $subject, string $html, array $cfg): bool {
    $payload = json_encode([
      'personalizations' => [['to' => [['email'=>$to,'name'=>$toName]]]],
      'from'    => ['email'=>$cfg['from_email'],'name'=>$cfg['from_name']],
      'subject' => $subject,
      'content' => [['type'=>'text/html','value'=>$html]],
    ]);
    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
    curl_setopt_array($ch, [
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => $payload,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $cfg['sg_key'],
        'Content-Type: application/json',
      ],
    ]);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code === 202;
  }

  // ── SMTP (Gmail / cualquier SMTP) ─────────────────────────
  private static function sendSMTP(string $to, string $toName, string $subject, string $html, array $cfg): bool {
    // Usar PHPMailer si está disponible
    if(class_exists('PHPMailer\PHPMailer\PHPMailer')) {
      $mail = new PHPMailer\PHPMailer\PHPMailer(true);
      $mail->isSMTP();
      $mail->Host       = $cfg['host'];
      $mail->SMTPAuth   = true;
      $mail->Username   = $cfg['user'];
      $mail->Password   = $cfg['pass'];
      $mail->SMTPSecure = 'tls';
      $mail->Port       = $cfg['port'];
      $mail->CharSet    = 'UTF-8';
      $mail->setFrom($cfg['from_email'], $cfg['from_name']);
      $mail->addAddress($to, $toName);
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $html;
      return $mail->send();
    }
    // Fallback sin PHPMailer
    return self::sendNative($to, $subject, $html, $cfg);
  }

  // ── NATIVO PHP mail() ─────────────────────────────────────
  private static function sendNative(string $to, string $subject, string $html, array $cfg): bool {
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$cfg['from_name']} <{$cfg['from_email']}>\r\n";
    $headers .= "Reply-To: {$cfg['from_email']}\r\n";
    return mail($to, $subject, $html, $headers);
  }

  // ── TEMPLATES ─────────────────────────────────────────────

  /** Email de bienvenida al registrarse */
  public static function bienvenida(string $to, string $nombre, string $empresa, string $plan = 'Trial'): bool {
    $html = self::template(
      "¡Bienvenido a CRISAMEX, $nombre!",
      "Tu cuenta ha sido creada exitosamente",
      "
      <p>Hola <strong>$nombre</strong>,</p>
      <p>Tu cuenta en el <strong>Portal CRISAMEX</strong> ha sido creada exitosamente.</p>
      <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Empresa</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$empresa</td></tr>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Plan</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$plan</td></tr>
        <tr><td style='padding:8px;color:#666;'>Estado</td><td style='padding:8px;font-weight:600;color:#C8151B;'>Pendiente de activación</td></tr>
      </table>
      <p>Un asesor de CRISAMEX se pondrá en contacto contigo en las próximas 24 horas hábiles para activar tu licencia.</p>
      ",
      'Acceder al Portal',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/portal'
    );
    return self::send($to, "¡Bienvenido a CRISAMEX Portal! — $nombre", $html, $nombre);
  }

  /** Notificación de licencia activada */
  public static function licenciaActivada(string $to, string $nombre, string $plan, string $fechaFin): bool {
    $html = self::template(
      "¡Tu licencia está activa!",
      "Plan $plan activado correctamente",
      "
      <p>Hola <strong>$nombre</strong>,</p>
      <p>¡Buenas noticias! Tu licencia en CRISAMEX ha sido <strong style='color:#16a34a;'>activada</strong>.</p>
      <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Plan</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$plan</td></tr>
        <tr><td style='padding:8px;color:#666;'>Vencimiento</td><td style='padding:8px;font-weight:600;'>$fechaFin</td></tr>
      </table>
      <p>Ya tienes acceso completo a todos los servicios de tu plan. Ingresa al portal para ver tus reportes y documentos.</p>
      ",
      'Ir al Portal',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/portal'
    );
    return self::send($to, "Licencia activada — Plan $plan — CRISAMEX", $html, $nombre);
  }

  /** Nuevo mensaje de CRISAMEX al cliente */
  public static function nuevoMensajeCliente(string $to, string $nombre, string $asunto, string $preview): bool {
    $html = self::template(
      "Nuevo mensaje de CRISAMEX",
      $asunto,
      "
      <p>Hola <strong>$nombre</strong>,</p>
      <p>El equipo de <strong>CRISAMEX</strong> te ha enviado un nuevo mensaje:</p>
      <div style='background:#f8f8f8;border-left:4px solid #C8151B;padding:16px 20px;margin:20px 0;border-radius:0 6px 6px 0;font-style:italic;color:#444;'>
        $preview...
      </div>
      <p>Ingresa al portal para leer el mensaje completo y responder.</p>
      ",
      'Leer Mensaje',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/portal/mensajes'
    );
    return self::send($to, "Nuevo mensaje de CRISAMEX — $asunto", $html, $nombre);
  }

  /** Nuevo documento disponible */
  public static function nuevoDocumento(string $to, string $nombre, string $titulo, string $tipo): bool {
    $html = self::template(
      "Nuevo documento disponible",
      $titulo,
      "
      <p>Hola <strong>$nombre</strong>,</p>
      <p>CRISAMEX ha subido un nuevo documento a tu portal:</p>
      <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Documento</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$titulo</td></tr>
        <tr><td style='padding:8px;color:#666;'>Tipo</td><td style='padding:8px;font-weight:600;'>".ucfirst($tipo)."</td></tr>
      </table>
      <p>Ingresa al portal para descargarlo.</p>
      ",
      'Ver Documentos',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/portal/documentos'
    );
    return self::send($to, "Nuevo documento: $titulo — CRISAMEX", $html, $nombre);
  }

  /** Aviso al admin de nuevo mensaje de cliente */
  public static function avisoAdminNuevoMensaje(string $adminEmail, string $clienteNombre, string $empresa, string $preview): bool {
    $html = self::template(
      "Nuevo mensaje de cliente",
      "Mensaje de $clienteNombre",
      "
      <p>Un cliente ha enviado un nuevo mensaje en el portal:</p>
      <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Cliente</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$clienteNombre</td></tr>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Empresa</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$empresa</td></tr>
        <tr><td style='padding:8px;color:#666;'>Mensaje</td><td style='padding:8px;font-style:italic;'>$preview...</td></tr>
      </table>
      ",
      'Ver en el Chat',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/admin/comunicaciones'
    );
    return self::send($adminEmail, "🔔 Nuevo mensaje de $clienteNombre — CRISAMEX Portal", $html, 'Admin CRISAMEX');
  }

  /** Aviso al admin de nuevo registro */
  public static function avisoAdminNuevoRegistro(string $adminEmail, string $nombre, string $empresa, string $email, string $plan): bool {
    $html = self::template(
      "Nuevo cliente registrado",
      "$nombre se registró en el portal",
      "
      <p>Un nuevo cliente se ha registrado en el portal de CRISAMEX:</p>
      <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Nombre</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$nombre</td></tr>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Empresa</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$empresa</td></tr>
        <tr><td style='padding:8px;color:#666;border-bottom:1px solid #eee;'>Email</td><td style='padding:8px;font-weight:600;border-bottom:1px solid #eee;'>$email</td></tr>
        <tr><td style='padding:8px;color:#666;'>Plan solicitado</td><td style='padding:8px;font-weight:600;color:#C8151B;'>$plan</td></tr>
      </table>
      <p>Activa su licencia desde el panel de administración.</p>
      ",
      'Ver Cliente',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/admin/clientes'
    );
    return self::send($adminEmail, "🆕 Nuevo cliente: $nombre — $empresa", $html, 'Admin CRISAMEX');
  }

  /** Formulario de contacto recibido */
  public static function confirmacionContacto(string $to, string $nombre, string $servicio = ''): bool {
    $html = self::template(
      "Recibimos tu mensaje",
      "Gracias por contactar a CRISAMEX",
      "
      <p>Hola <strong>$nombre</strong>,</p>
      <p>Hemos recibido tu mensaje correctamente. Un especialista de CRISAMEX se pondrá en contacto contigo en las próximas <strong>24 horas hábiles</strong>.</p>
      ".($servicio ? "<p><strong>Servicio de interés:</strong> $servicio</p>" : "")."
      <p>Si necesitas atención inmediata, puedes llamarnos:</p>
      <p style='font-size:1.2rem;font-weight:700;color:#C8151B;'>📞 01 55 5650 8420</p>
      ",
      'Ver nuestros servicios',
      (getenv('APP_URL') ?: 'http://localhost:8090') . '/servicios'
    );
    return self::send($to, "Recibimos tu mensaje — CRISAMEX", $html, $nombre);
  }

  // ── TEMPLATE HTML BASE ────────────────────────────────────
  private static function template(string $titulo, string $subtitulo, string $cuerpo, string $btnText = '', string $btnUrl = ''): string {
    $btn = $btnText && $btnUrl
      ? "<div style='text-align:center;margin:30px 0;'><a href='$btnUrl' style='display:inline-block;background:#C8151B;color:#ffffff;padding:14px 32px;text-decoration:none;font-weight:700;font-size:14px;letter-spacing:1px;border-radius:4px;'>$btnText</a></div>"
      : '';

    return "<!DOCTYPE html>
<html lang='es'>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'><title>$titulo</title></head>
<body style='margin:0;padding:0;background:#f5f5f5;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f5f5f5;padding:30px 0;'>
  <tr><td align='center'>
    <table width='600' cellpadding='0' cellspacing='0' style='max-width:600px;width:100%;'>

      <!-- HEADER -->
      <tr><td style='background:#1a1a1a;padding:24px 32px;border-radius:8px 8px 0 0;border-top:4px solid #C8151B;text-align:center;'>
        <img src='" . (getenv('APP_URL') ?: 'http://localhost:8090') . "/images/logo-crisamex.jpg'
             alt='CRISAMEX' style='height:48px;width:auto;background:#fff;padding:8px 12px;border-radius:4px;'>
      </td></tr>

      <!-- TÍTULO -->
      <tr><td style='background:#C8151B;padding:20px 32px;text-align:center;'>
        <h1 style='margin:0;color:#ffffff;font-size:20px;letter-spacing:1px;'>$titulo</h1>
        <p style='margin:6px 0 0;color:rgba(255,255,255,.8);font-size:14px;'>$subtitulo</p>
      </td></tr>

      <!-- CUERPO -->
      <tr><td style='background:#ffffff;padding:32px;border-left:1px solid #e8e8e8;border-right:1px solid #e8e8e8;font-size:15px;line-height:1.7;color:#333;'>
        $cuerpo
        $btn
      </td></tr>

      <!-- FOOTER -->
      <tr><td style='background:#f8f8f8;padding:20px 32px;border:1px solid #e8e8e8;border-top:none;border-radius:0 0 8px 8px;text-align:center;font-size:12px;color:#999;'>
        <p style='margin:0 0 8px;'><strong style='color:#C8151B;'>CRISAMEX</strong> — Control de Radiaciones e Ingeniería S.A. de C.V.</p>
        <p style='margin:0 0 4px;'>📞 01 55 5650 8420 &nbsp;·&nbsp; ✉️ contacto@crisamex.com</p>
        <p style='margin:0;'>Ciudad de México, México</p>
        <p style='margin:10px 0 0;font-size:11px;color:#bbb;'>Este es un correo automático, por favor no respondas directamente.</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>";
  }
}
