<?php
$type = 'Time Off';
$program = mysqli_real_escape_string($dbc, trim($_POST['user_location']));
$user_name = mysqli_real_escape_string($dbc, trim($_POST['user_name']));
$employee = mysqli_real_escape_string($dbc, trim($_POST['employee']));

$number = mysqli_real_escape_string($dbc, ($_POST['number']));
$start = mysqli_real_escape_string($dbc, trim($_POST['start']));
$end = mysqli_real_escape_string($dbc, trim($_POST['end']));
$type = mysqli_real_escape_string($dbc, trim($_POST['type']));
$relationship = mysqli_real_escape_string($dbc, trim($_POST['relationship']));
$description = mysqli_real_escape_string($dbc, trim($_POST['description']));
$length = mysqli_real_escape_string($dbc, trim($_POST['length']));

$principals = array('sbrown2', 'wtackett');
if (in_array($user_name, $principals)) {
    $departments = array('superintendent');
    array_push($departments, 'federal_programs');
}
else if ($_POST['user_location'] == 'Sanders') {
    $departments = array('sanders');
}
else if ($_POST['user_location'] == 'Simmons') {
    $departments = array('simmons');
}
else {
    $departments = array('superintendent');
}

$sped_teachers = array('sgray', 'twilliams3', 'sperry', 'amills', 'ctolbert', 'tnelson', 'edorsey', 'ybee', 'icollins');
if (in_array($username, $sped_teachers)) {
    array_push($departments, 'sped');
}

include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Time Off Request', '$program', '$employee', NOW() + INTERVAL 2 HOUR, ";
foreach ($depts as $dept) {
    if (in_array($dept, $departments)) {
        $query .= "'Approval Needed', ";
    }
    else {
        $query .= "'N/A', ";
    }
}
$query .= "'Submitted')";
if (!mysqli_query($dbc, $query)) {
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . mysqli_error($dbc) . ')</script>';
}
//Initial form creation successful, now create full form details
$formId = mysqli_insert_id($dbc);
$query = "INSERT INTO forms_timeoff (formId, number, length, start, end, type, relationship, description) VALUES ($formId, $number, '$length', '$start', '$end', '$type', '$relationship', '$description')";
if (!mysqli_query($dbc, $query)) {
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p>' . $query;
}
//form created, log form creation
$query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW())";
if (!mysqli_query($dbc, $query)) {
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 3)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . mysqli_error($dbc) . ')</script>';
}
//Form logged sucessfully, will send a confirmation and alert email
$message = "$user_name has just submitted a Time Off Request." . ' You can view the details of this request, and approve or deny it, <a href="www.sblwilliams.com/hollandale/forms.php">here</a>.';
$subject = "Time Off Request submitted by $user_name";
$to_array = array();
array_push($to_array, array($user_name, $employee . '@hollandalesd.org'));
$query = "SELECT firstname, lastname, username, departments FROM staff_list WHERE departments IS NOT NULL";
$result = mysqli_query($dbc, $query);
while ($row = mysqli_fetch_array($result)) {
    $depts = explode(", ", $row['departments']);
    foreach ($depts as $dept) {
        if (in_array(strtolower($dept), $departments)) {
            array_push($to_array, array($row['firstname'] . ' ' . $row['lastname'], $row['username'] . '@hollandalesd.org'));
        }
    }
}
require('mail.php');

if ($mail->send()) {
    echo '<div class="alert alert-success" role="alert"><strong>Good job!</strong> Your request was submitted successfully.' . print_r($to_array) . '</div><p><a class="btn btn-primary" href="forms.php">Forms</a></p>';
}
else {
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 4)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . $mail->ErrorInfo . ')</script>';
}
?>
