<?php

require '/home/samwilpersonal/sblwilliams.com/hollandale/appfunctions.php';
require '/home/samwilpersonal/sblwilliams.com/hollandale/connectvars.php';
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$today = date('m/d/Y');
$date = date('Y-m-d');

require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.phpmailer.php';
require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.smtp.php';

$query = "SELECT reading.student_id, firstname, lastname, grade, count(*) AS count, max(date) as date FROM readingtracker AS reading LEFT JOIN student_list USING (student_id) GROUP BY reading.student_id, lastname, firstname ORDER BY reading.date, lastname, firstname";
$result = mysqli_query($dbc, $query);
echo $query;
if (mysqli_num_rows($result) > 0) {
    $level1 = array();
    $level2 = array();
    $level3 = array();
    while ($row = mysqli_fetch_array($result)) {
        if ($row['date'] == $date) {
            switch ($row['count'] % 3) {
                case 1:
                    array_push($level1, array('firstname' => $row['firstname'], 'lastname' => $row['lastname'], 'grade' => $row['grade']));
                    break;
                case 2:
                    array_push($level2, array('firstname' => $row['firstname'], 'lastname' => $row['lastname'], 'grade' => $row['grade']));
                    break;
                default:
                    array_push($level3, array('firstname' => $row['firstname'], 'lastname' => $row['lastname'], 'grade' => $row['grade']));
                    break;
            }
        }
    }
    if (sizeOf($level1) > 0 || sizeOf($level2) > 0 || sizeOf($level3) > 0) {
        echo 'email';
        //Send the email
        $message = "Below are the students who did not turn in parent signatures on their daily reading assignments today.";
        if (sizeOf($level1) > 0) {
            $message .= "<br><br><strong>Student Conference:</strong><br>";
            foreach ($level1 as $student) {
                $message .= $student['firstname'] . ' ' . $student['lastname'] . ' (Grade ' . $student['grade'] . ')<br>';
            }
        }
        if (sizeOf($level2) > 0) {
            $message .= "<br><br><strong>Automated Phone Call:</strong><br>";
            foreach ($level2 as $student) {
                $message .= $student['firstname'] . ' ' . $student['lastname'] . ' (Grade ' . $student['grade'] . ')<br>';
            }
        }
        if (sizeOf($level3) > 0) {
            $message .= "<br><br><strong>Mandatory Parent Conference:</strong><br>";
            foreach ($level1 as $student) {
                $message .= $student['firstname'] . ' ' . $student['lastname'] . ' (Grade ' . $student['grade'] . ')<br>';
            }
        }
        echo 'Level 1:';
        print_r($level1);
        $subject = "Daily Reading Consequences - $today";
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
        $mail->addAddress('wtackett@hollandalesd.org', 'Wade Tackett');
        $mail->addAddress('hbrown@hollandalesd.org', 'Herman Brown');
        $mail->addAddress('sjohnson@hollandalesd.org', 'Shontelle Bridges');
        $mail->addAddress('rthomas3@hollandalesd.org', 'Raven Thomas');
        $mail->addAddress('tcausey@hollandalesd.org', 'Terdell Causey');
        $mail->addAddress('shamilton@hollandalesd.org', 'Shaffany Hamilton');

        $mail->Subject = $subject;
        $mail->Body    = $message;
        if(!$mail->Send()) {
            echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo, 'query' => $query));
        }
        echo '<br>Email sent!<br>';

    }
}



?>
