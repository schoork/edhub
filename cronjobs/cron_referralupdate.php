<?php

$noDates = array('2018-03-30', '2018-04-02');

$schools = array('Sanders', 'Simmons');

include('/home/samwilpersonal/sblwilliams.com/hollandale/connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.phpmailer.php';
require '/home/samwilpersonal/sblwilliams.com/PHPMailer-master/class.smtp.php';

$r = date('Y-m-d');
$d = date("w");
if (!in_array($r, $noDates) && $d > 0 && $d < 6) {
    foreach ($schools as $school) {
        $query = "SELECT count(*) AS count FROM referrals LEFT JOIN student_list USING (student_id) WHERE school = '$school' AND DATE_FORMAT(time, '%Y-%m-%d') = '$r' AND action IS NULL";
        $result = mysqli_query($dbc, $query);
        $count = mysqli_fetch_array($result)['count'];
        echo $query . '<br/>';
        echo $count . '<br/>';
        if ($count > 0) {
            $mail = new PHPMailer;
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
            $mail->setFrom('web-service@sblwilliams.com', 'Referral Service');
            switch ($school) {
            case 'Sanders':
            $mail->addAddress('wtackett@hollandalesd.org', 'Wade Tackett');
            $mail->addAddress('hbrown@hollandalesd.org', 'Herman Brown');
            break;
            case 'Simmons':
            $mail->addAddress('cjohnson@hollandalesd.org', 'Cortez Johnson');
            break;
            }
            $mail->Subject = 'Referrals Update';
            $body = "There are currently $count referrals from today that have not yet been addressed. To view these referrals, log on to the referral system at www.sblwilliams.com/hollandale/referrals.php";
            $mail->Body = $body;
            $mail->send();
            echo 'Email sent to ' . $school . '<br/>';
        }
    }
} //end $r check for no Dates

mysqli_close($dbc);


?>
