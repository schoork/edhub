<?php
$type = 'Budget Revision';
$username = mysqli_real_escape_string($dbc, trim($_POST['employee_budget']));

$from = mysqli_real_escape_string($dbc, trim($_POST['from_line']));
$to = mysqli_real_escape_string($dbc, trim($_POST['to_line']));
$amount = mysqli_real_escape_string($dbc, trim($_POST['amount']));

$departments = array('federal_programs');

include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Budget Revision', 'N/A', '$username', NOW() + INTERVAL 2 HOUR, ";
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
  $query = "INSERT INTO forms_budgetrevision (form_id, from_line, to_line, amount) VALUES ($formId, '$from', '$to', $amount)";
  if (mysqli_query($dbc, $query)) {
    //form created, log form creation
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW())";
    if (mysqli_query($dbc, $query)) {
      $to_array = array();
      //Form logged sucessfully, will send a confirmation and alert email
      $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$username'";
      $result = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($result);
      $user_name = $row['firstname'] . ' ' . $row['lastname'];
      array_push($to_array, array($user_name, $username . '@hollandalesd.org'));
      $message = "$user_name has just submitted a Budget Revision Request." . ' You can view the details of this request, and approve or deny it, <a href="www.sblwilliams.com/hollandale/forms.php">here</a>.<br/>www.sblwilliams.com/hollandale/forms.php';
      $subject = "Budget Revision Request submitted by $user_name";
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
