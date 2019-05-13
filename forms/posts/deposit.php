<?php

$user_name = $_POST['user_name'];
$username = $_POST['employee'];


$school = mysqli_real_escape_string($dbc, trim($_POST['school']));
$code = mysqli_real_escape_string($dbc, trim($_POST["code$school"]));
$amount = mysqli_real_escape_string($dbc, trim($_POST['amount']));
$account = mysqli_real_escape_string($dbc, trim($_POST['account']));
$description = mysqli_real_escape_string($dbc, trim($_POST['description']));
$revenue_code = mysqli_real_escape_string($dbc, $_POST['revenue_code']);
$receipt = mysqli_real_escape_string($dbc, $_POST['receipt']);

$departments = array('payroll', 'superintendent');

if ($school == '1151') {
  array_push($departments, 'sanders');
  $school = 'Sanders';
}
else if ($school == '1153') {
  array_push($departments, 'simmons');
  $school = 'Simmons';
}
else {
  $school = 'District Office';
}
include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Deposit', '$school', '$username', NOW() + INTERVAL 2 HOUR, ";
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
  //Add items to item List
  $query = "INSERT INTO forms_deposits (formId, revenue_code, amount, school, account, description, receipt) VALUES ($formId, '$revenue_code', $amount, '$school', '$account', '$description', '$receipt')";
  if (mysqli_query($dbc, $query)) {
    $query = "INSERT INTO budget_activities (purchase_code, vendor, date, description, amount, form_id, status) VALUES ('$revenue_code', 'Deposit', CURDATE(), '$description', $amount, $formId, 'Proposed')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));

    //form created, log form creation
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW() + INTERVAL 2 HOUR)";
    if (mysqli_query($dbc, $query)) {
      //Form logged sucessfully, will send a confirmation and alert email
      $message = "$user_name has just submitted a Deposit Form." . ' You can view the details of this form, and approve or deny it, on the <a href="www.sblwilliams.com/hollandale/forms.php">Forms List page</a>.';
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
