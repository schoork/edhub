<?php
require_once '../PHPMailer-master/PHPMailerAutoload.php';
$mail = new PHPMailer;

//$mail->SMTPDebug = 2;                               // Enable verbose debug output

$mail->IsHTML(true);
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.sblwilliams.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
$mail->Username = 'web-service@sblwilliams.com';                 // SMTP username
$mail->Password = '&j390CKIInPU';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('web-service@sblwilliams.com', 'HSD edhub');
foreach ($to_array as $to) {
  $mail->addAddress($to[1], $to[0]);
}
$mail->addReplyTo($to_array[0][1], $to_array[0][0]);

$mail->Subject = $subject;
$mail->Body    = $message;
?>
