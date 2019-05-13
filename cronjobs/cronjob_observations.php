<?php

require '/home/samwilpersonal/sblwilliams.com/hollandale/appfunctions.php';
require '/home/samwilpersonal/sblwilliams.com/hollandale/connectvars.php';
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$today = date('m/d/Y');
$date = date('Y-m-d');

require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.phpmailer.php';
require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.smtp.php';

$query = "SELECT firstname, lastname FROM staff_list WHERE username = '$obs'";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

$to_array = array();
$subject = 'Daily Observation Update - ' . $today;
$message = "Below is the daily update of completed observations. Please remember that observations are a district priority. <strong>Observations and coaching support the district's goal of High Quality Teaching and Learning.</strong>";
$message .= "<br><br>If you need support in completing observations, please contact Mr. Williams.<br><br>";
$message .= '<table><tr><th>Observer</th><th>Teacher</th><th>Start Time</th><th>End Time</th><th>Domain I Average</th><th>Domain II Average</th><th>Domain III Average</th><th>Overall Average</th></tr>';

$i = 0;
$observers = array(
    array('name' => 'Wade Tackett', 'username' => 'wtackett'),
    array('name' => 'Shontelle Johnson', 'username' => 'sjohnson'),
    array('name' => 'Herman Brown', 'username' => 'hbrown'),
    array('name' => 'Raven Thomas', 'username' => 'rthomas3'),
    array('name' => 'Shiquita Brown', 'username' => 'sbrown2'),
    array('name' => 'Cortez Johnson', 'username' => 'cjohnson'),
    array('name' => 'Betty Newell', 'username' => 'bgolden'),
    array('name' => 'Alberta Raymond', 'username' => 'araymond')
);
$query = "SELECT row_id, observer, firstname, lastname, start, end, dom1, dom2, dom3, overall FROM observations As obs LEFT JOIN staff_list AS sl ON (obs.teacher = sl.username) WHERE date = '$date' ORDER BY observer";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        if ($i % 2) {
            $message .= '<tr style="background-color: #d3d3d3; text-align: center">';
        }
        else {
            $message .= '<tr style="text-align: center">';
        }
        foreach ($observers as $obs) {
            if ($obs['username'] == $row['observer']) {
                $name = $obs['name'];
            }
        }
        $message .= '<td>' . $name . '</td>';
        $message .= '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
        $message .= '<td>' . parseObsDT($row['start']) . '</td>';
        $message .= '<td>' . parseObsDT($row['end']) . '</td>';
        $message .= '<td>' . $row['dom1'] . '</td>';
        $message .= '<td>' . $row['dom2'] . '</td>';
        $message .= '<td>' . $row['dom3'] . '</td>';
        $message .= '<td><a href="https://www.sblwilliams.com/hollandale/viewobservation.php?obs=' . $row['row_id'] . '">' . $row['overall'] . '</a></td></tr>';
        $i++;
    }
}
$message .= '</table><br><br>Observations can be completed in <a href="https://www.sblwilliams.com/hollandale/addobservation.php">edhub</a>.';
echo $message;

foreach ($observers as $obs) {
    array_push($to_array, array($obs['name'], $obs['username'] . '@hollandalesd.org'));
}
array_push($to_array, array('Mario Willis', 'mwillis2@hollandalesd.org'));
array_push($to_array, array('Shaffany Hamilton', 'shamilton@hollandalesd.org'));
array_push($to_array, array('Samuel Williams', 'swilliams@hollandalesd.org'));

mysqli_close($dbc);
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
if(!$mail->Send()) {
    echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo, 'query' => $query));
}



?>
