<?php
spl_autoload_register(function($class){
  $map = [
    'PHPMailer\\PHPMailer\\PHPMailer'  => __DIR__.'/phpmailer/src/PHPMailer.php',
    'PHPMailer\\PHPMailer\\Exception'  => __DIR__.'/phpmailer/src/PHPMailer.php',
  ];
  if(isset($map[$class])) require_once $map[$class];
});
