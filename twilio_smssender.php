<?php
/**
* This section ensures that Twilio gets a response.
*/

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<Response>Your text has been sent to school secretaries.</Response>'; //Place the desired response (if any) here

/**
* This section actually sends the email.
*/

require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

/* Your email address */
$number = mysqli_real_escape_string($dbc, $_REQUEST['From']);
$number = substr($number, 2);

/* Your email address */
$query = "SELECT name FROM sub_list WHERE number = $number";
$result = mysqli_query($dbc, $query);
$name = mysqli_fetch_array($result)['name'];
$number = substr($number, 0, 3) . '-' . substr($number, 3, 3) . '-' . substr($number, 6);
$to_array = array();
array_push($to_array, array('Yvonne Venson', "swilliams@hollandalesd.org") );
//array_push($to_array, array('Yvonne Venson', "yvenson@hollandalesd.org") );
//array_push($to_array, array('Astria Brown', "abrown2@hollandalesd.org") );
$subject = "Sub Message from $name";
$message = "You have received a message from $name ($number).<br/>Body: {$_REQUEST['Body']}";

include('mail.php');
$mail->Send();

mysqli_close($dbc);

?>
