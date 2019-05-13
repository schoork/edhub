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
    $query = "SELECT students.student_id, lastname, firstname, teacher1, teacher2, teacher3, teacher4 FROM rti_checklist_written AS checklist LEFT JOIN student_list AS students ON (checklist.student_id = students.student_id) ORDER BY teacher, lastname, firstname";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        if ($row['firstname'] !== null) {
            $student_id = $row['student_id'];
            $student_name = $row['firstname'] . ' ' . $row['lastname'];
            for ($i = 1; $i < 5; $i++) {
                $teacher = $row["teacher$i"];
                if ($teacher != 'Unassigned') {
                    $query = "SELECT day1 FROM rti_teacher_tracker WHERE student_id = $student_id AND teacher = $teacher AND behavior = 1 AND date = '$date'";
                    echo $query . '<br>';
                }
            }
        }
    }
    /*
    $teacher = '';
    $student = 0;
    $i = 1;
    $body1 = "The following teachers did not complete trackers today. A reminder email has been sent to them.<br>";
    //echo $query;

        if ($row['firstname']) {
            if ($row['teacher'] != $teacher) {
                if ($mail) {
                    //this is not the first teacher, need to send an email
                    $mail->Body = $body;
                    echo '<br>' . $body . '<br><br>';
                    //$mail->send();
                }
                $teacher = $row['teacher'];
                $body = '';
                echo '<br><br>Teacher: ' . $row['teacher'];
            }
            echo '<br>Student: ' . $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['student_id'] . ')';
            $student_id = $row['student_id'];
            // if students array is not empty
            $query = "SELECT day$d AS value, firstname, lastname, school FROM rti_teacher_tracker AS rti LEFT JOIN staff_list AS sl ON (rti.teacher = sl.username) WHERE student_id = $student_id AND date = '$date' AND teacher = '$teacher' AND behavior = 1";
            $data = mysqli_query($dbc, $query);
            //echo '<br>' . $query . '<br>';
            $complete = 0;
            if (mysqli_fetch_array($data) > 0) {
                $line = mysqli_fetch_array($data);
                if ($line['value'] >= 0) {
                    $complete = 1;
                }
            }
            echo ' ' . $complete;
            if ($complete === 0) {
                if ($body == '') {
                    // this is the first student for the teacher
                    $body = 'You have not yet completed behavior trackers for the following students for today. <a href="www.sblwilliams.com/hollandale/updatetracker.php">Please do so</a> at your earliest convenience. Thanks!' . "<br/>" . $row['firstname'] . ' ' . $row['lastname'] . ' - ' . $row['student_id'];;
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
                    $query = "SELECT firstname, lastname, school FROM staff_list WHERE username = '$teacher'";
                    $data = mysqli_query($dbc, $query);
                    $line = mysqli_fetch_array($data);
                    $name = $line['firstname'] . ' ' . $line['lastname'];
                    $school = $line['school'];
                    echo '<br>Name: ' . $name;
                    $mail->setFrom('web-service@sblwilliams.com', 'edhub');
                    $mail->addAddress($teacher . '@hollandalesd.org', $name);
                    $mail->Subject = 'Behavior Tracker Reminder - ' . date('n/j/y');
                    $body1 .= $name . ' (' . $school . ')<br>';
                } else {
                    $body .= "<br>" . $row['firstname'] . ' ' . $row['lastname'] . ' - ' . $row['student_id'];
                }
            }
        }
    }
    //Send email to admins for summary.
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
    $mail->setFrom('web-service@sblwilliams.com', 'Tracker Summary');
    $mail->addAddress('swilliams@hollandalesd.org', 'Sam Williams');
    $mail->addAddress('cjohnson@hollandalesd.org', 'Cortez Johnson');
    $mail->addAddress('wtackett@hollandalesd.org', 'Wade Tackett');
    $mail->addAddress('ksmith@hollandalesd.org', 'Kanesha Smith');
    $mail->addAddress('tcausey@hollandalesd.org', 'Terdell Causey');
    $mail->Subject = 'Behavior Tracker Summary - ' . date('n/j/y');
    $mail->Body = $body1;
    echo 'Email: ' . $body1;
    //$mail->send();
    */
} //end $r check for no Dates

mysqli_close($dbc);


?>
