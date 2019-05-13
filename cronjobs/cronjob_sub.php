<?php

require '/home/samwilpersonal/sblwilliams.com/hollandale/appfunctions.php';
require '/home/samwilpersonal/sblwilliams.com/hollandale/connectvars.php';
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$date = date('Y-m-d');
$date = date('Y-m-d', strtotime($date. ' + 1 days'));

$query = "SELECT count(*) AS count FROM forms_timeoff WHERE start <= '$date' AND end >= '$date'";
$result = mysqli_query($dbc, $query);
$count = mysqli_fetch_array($result)['count'];
$query = "SELECT count(*) AS count FROM sub_request WHERE date = '$date' AND approved = 1";
$result = mysqli_query($dbc, $query);
$total = $count - mysqli_fetch_array($result)['count'];

if ($total > 0) {
  $query = "SELECT name, number, email, code FROM sub_list";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    $code = $row['code'];
    $body = 'Hello! HSD needs subs tomorrow. Click the link to apply. Your code is ' . $code . '. https://goo.gl/XqMLP8';
    $people = array('+1' . $row['number'] => $row['name']);
    include '/home/samwilpersonal/sblwilliams.com/twilio/sendnotifications.php';
    //send email
    if ($row['email'] != '') {
      $subject = 'Subs Needed on ' . makeDateAmerican($date);
      $message = $body;
      $to_array = array();
      array_push($to_array, array($name, $row['email']));
      print_r($to_array);
      require_once '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/PHPMailerAutoload.php';
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
      $mail->Send() or die($mail->ErrorInfo);
    }
  }
}

echo 'success';

mysqli_close($dbc);

?>
