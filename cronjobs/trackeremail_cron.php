<?php

$noDates = array('2018-03-30', '2018-04-02');

/*
//adds Winter Break to noDates
for ($i = 17; $i < 32; $i++) {
  array_push($noDates, "2016-12-$i");
}
for ($i = 1; $i < 3; $i++) {
  array_push($noDates, "2017-01-0$i");
}
//adds Spring Break to noDates
for ($i = 13; $i < 18; $i++) {
  array_push($noDates, "2017-03-$i");
}
*/

include('/home/samwilpersonal/sblwilliams.com/hollandale/connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.phpmailer.php';
require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.smtp.php';

$r = date('Y-m-d');
if (!in_array($r, $noDates)) {
    $d = date("w");
    if ($d == 0) {
        $date = date('Y-m-d', strtotime('today'));
    }
    else {
        $date = date('Y-m-d', strtotime('previous monday'));
    }
    $query = "SELECT firstname, lastname, username, school FROM staff_list ORDER BY lastname, firstname";
    $result = mysqli_query($dbc, $query);
    $admin_body = '';
    $admin_body_teacher_list = '';
    while ($row = mysqli_fetch_array($result)) {
        $teacher_name = $row['firstname'] . ' ' . $row['lastname'];
        $school = $row['school'];
        $username = $row['username'];
        $body = '';
        $query = "SELECT firstname, lastname, student_id FROM rti_checklist_written LEFT JOIN student_list USING (student_id) WHERE teacher1 = '$username' OR teacher2 = '$username' OR teacher3 = '$username' OR teacher4 = '$username' ORDER BY lastname, firstname";
        $student_results = mysqli_query($dbc, $query);
        if (mysqli_num_rows($student_results)) {
            $admin_body_teacher_list .= '<br><br>' . $teacher_name . ' (' . $school . '):';
            while ($student_row = mysqli_fetch_array($student_results)) {
                $student_id = $student_row['student_id'];
                $student_name = $student_row['firstname'] . ' ' . $student_row['lastname'];
                $query = "SELECT day$d AS value FROM rti_teacher_tracker WHERE student_id = $student_id AND teacher = '$username' AND behavior = 1 AND date = '$date'";
                $tracker_result = mysqli_query($dbc, $query);
                $complete = 0;
                if (mysqli_num_rows($tracker_result) > 0) {
                    $value = mysqli_fetch_array($tracker_result)['value'];
                    if ($value !== null) {
                        $complete = 1;
                    }
                }
                if ($complete == 0) {
                    if ($body == '') {
                        $body = 'You have not yet completed behavior trackers for the following students for today. <a href="www.sblwilliams.com/hollandale/updatetracker.php">Please do so</a> at your earliest convenience. Thanks!';
                    }
                    $body .= '<br>' . $student_name;
                    $admin_body_teacher_list .= '<br>' . $student_name;
                }
            }
        }
        if ($body != '') {
            if ($admin_body == '') {
                $admin_body = 'The following is a list of teachers and the students who they have not submitted behavior tracker information for today. The teachers have been reminded via email. If a teacher has no students listed below his/her name, then he/she submitted all required data today.';
            }
            // Sends email to teacher
            $mail = new PHPMailer;
            $mail->IsHTML(true);
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.sblwilliams.com';                 // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
            $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
            $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
            $mail->Username = 'web-service@sblwilliams.com';                 // SMTP username
            $mail->Password = '&j390CKIInPU';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;
            $mail->Subject = 'Behavior Tracker Reminder - ' . date('n/j/y');
            $mail->setFrom('web-service@sblwilliams.com', 'edhub');
            $mail->addAddress('swilliams@hollandalesd.org', 'Sam Williams');
            $mail->addAddress($username . '@hollandalesd.org', $teacher_name);
            $mail->Body = $body;
            $mail->send();
        }
    }
    if ($admin_body != '') {
        $admin_body .= $admin_body_teacher_list;
        echo $admin_body;
        // Sends email to administrators
        $mail = new PHPMailer;
        $mail->IsHTML(true);
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.sblwilliams.com';                 // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
        $mail->Username = 'web-service@sblwilliams.com';                 // SMTP username
        $mail->Password = '&j390CKIInPU';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->setFrom('web-service@sblwilliams.com', 'edhub');
        $mail->addAddress('swilliams@hollandalesd.org', 'Sam Williams');
        $mail->addAddress('cjohnson@hollandalesd.org', 'Cortez Johnson');
        $mail->addAddress('wtackett@hollandalesd.org', 'Wade Tackett');
        $mail->addAddress('ksmith@hollandalesd.org', 'Kanesha Smith');
        $mail->addAddress('tcausey@hollandalesd.org', 'Terdell Causey');
        $mail->Subject = 'Behavior Tracker Summary - ' . date('n/j/y');
        $mail->Body = $admin_body;
        $mail->send();
    }
}

mysqli_close($dbc);


?>
