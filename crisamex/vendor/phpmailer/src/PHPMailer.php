<?php
namespace PHPMailer\PHPMailer;

class PHPMailer {
  public $Host='smtp.gmail.com';
  public $Port=587;
  public $Username='';
  public $Password='';
  public $SMTPAuth=true;
  public $SMTPSecure='tls';
  public $CharSet='UTF-8';
  public $From='';
  public $FromName='';
  public $Subject='';
  public $Body='';
  public $isHTML=false;
  private $to=[];
  private $useSmtp=false;
  private $exceptions=false;
  private $sock=null;

  public function __construct($exceptions=false){ $this->exceptions=$exceptions; }
  public function isSMTP(){ $this->useSmtp=true; }
  public function isHTML($v=true){ $this->isHTML=$v; }
  public function setFrom($email,$name=''){ $this->From=$email; $this->FromName=$name; return true; }
  public function addAddress($email,$name=''){ $this->to[]=[$email,$name]; return true; }

  public function send(): bool {
    try {
      $sock = fsockopen('tcp://'.$this->Host, $this->Port, $errno, $errstr, 15);
      if(!$sock) throw new Exception("No conecta: $errstr");

      $r = function() use ($sock) {
        $x=''; while($l=fgets($sock,515)){ $x.=$l; if(strlen($l)>=4&&$l[3]===' ') break; } return $x;
      };
      $w = function($c) use ($sock,$r) { fwrite($sock,$c."\r\n"); return $r(); };

      $r();
      $w('EHLO crisamex.local');
      $w('STARTTLS');
      stream_socket_enable_crypto($sock,true,STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
      $w('EHLO crisamex.local');
      $w('AUTH LOGIN');
      $w(base64_encode($this->Username));
      $resp = $w(base64_encode($this->Password));
      if(strpos($resp,'235')===false) throw new Exception("Auth error: $resp");

      $w("MAIL FROM:<{$this->From}>");
      foreach($this->to as [$te,$tn]) $w("RCPT TO:<$te>");
      $w('DATA');

      $toStr = implode(', ', array_map(fn($t)=>($t[1]?"\"$t[1]\" ":"")."<$t[0]>", $this->to));
      $ct = $this->isHTML ? 'text/html' : 'text/plain';
      $msg  = "From: \"{$this->FromName}\" <{$this->From}>\r\n";
      $msg .= "To: $toStr\r\n";
      $msg .= "Subject: =?UTF-8?B?".base64_encode($this->Subject)."?=\r\n";
      $msg .= "MIME-Version: 1.0\r\nContent-Type: $ct; charset=UTF-8\r\n";
      $msg .= "Content-Transfer-Encoding: base64\r\n\r\n";
      $msg .= chunk_split(base64_encode($this->Body));
      $resp = $w($msg."\r\n.");
      $w('QUIT');
      fclose($sock);
      return strpos($resp,'250')!==false;
    } catch(Exception $e) {
      error_log('PHPMailer: '.$e->getMessage());
      if($this->exceptions) throw $e;
      return false;
    }
  }
}

class Exception extends \Exception {}
