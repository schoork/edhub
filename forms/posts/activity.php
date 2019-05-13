<?php

$user_name = $_POST['user_name'];
$username = $_POST['employee'];

$activity = mysqli_real_escape_string($dbc, trim($_POST['activity']));
$purpose = mysqli_real_escape_string($dbc, trim($_POST['purpose']));
$school = mysqli_real_escape_string($dbc, trim($_POST['school']));
$location = mysqli_real_escape_string($dbc, trim($_POST['location']));
$date = mysqli_real_escape_string($dbc, trim($_POST['date']));
$start = mysqli_real_escape_string($dbc, trim($_POST['start']));
$end = mysqli_real_escape_string($dbc, trim($_POST['end']));

$departments = array('superintendent');

if ($school == 'Sanders') {
  array_push($departments, 'sanders');
}
else {
  array_push($departments, 'simmons');
}
include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Activity', '$activity', '$username', NOW() + INTERVAL 2 HOUR, ";
foreach ($depts as $dept) {
  if (in_array($dept, $departments)) {
    $query .= "'Approval Needed', ";
  }
  else {
    $query .= "'N/A', ";
  }
}
$query .= "'Submitted')";
if (mysqli_query($dbc, $query)) {
  //Initial form creation successful, now create full form details
  $formId = mysqli_insert_id($dbc);
  //Add items to detailed list
  $query = "INSERT INTO forms_activity (formId, purpose, school, location, date, start, end) VALUES ($formId, '$purpose', '$school', '$location', '$date', '$start', '$end')";
  if (mysqli_query($dbc, $query)) {
    //form created, log form creation
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW() + INTERVAL 2 HOUR)";
    if (mysqli_query($dbc, $query)) {
      //Form logged sucessfully, will send a confirmation and alert email
      $message = "$user_name has just submitted an Activity Request Form." . ' You can view the details of this form, and approve or deny it, on the <a href="www.sblwilliams.com/hollandale/forms.php">Forms List page</a>.';
      $subject = "Deposit submitted by $user_name";
      $to_array = array();
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
      array_push($to_array, array($user_name, $username . '@hollandalesd.org'));
      
      require('mail.php');
      
      if ($mail->send()) {
        echo '<div class="alert alert-success" role="alert"><strong>Good job!</strong> Your request was submitted successfully.</div><p><a class="btn btn-primary" href="forms.php">Forms</a></p>';
      }
      else {
        echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 4)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . $mail->ErrorInfo . ')</script>';
      }
    }
    else {
      echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 3)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . mysqli_error($dbc) . ')</script>';
    }
  }
  else {
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p>' . $query;
  }
}
else {
  echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . mysqli_error($dbc) . ')</script>';
}
?>