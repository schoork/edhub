<?php
$type = 'Bus Request';
$program = mysqli_real_escape_string($dbc, trim($_POST['title']));
$username = mysqli_real_escape_string($dbc, trim($_POST['employee']));

$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
$school = $_POST['school'];
$destination = mysqli_real_escape_string($dbc, trim($_POST['location']));
$number = mysqli_real_escape_string($dbc, trim($_POST['number']));
$faculty = mysqli_real_escape_string($dbc, trim($_POST['faculty']));
$safety = mysqli_real_escape_string($dbc, trim($_POST['safety']));
$travel_start = mysqli_real_escape_string($dbc, trim($_POST['travel_start']));
$travel_end = mysqli_real_escape_string($dbc, trim($_POST['travel_end']));
$length = mysqli_real_escape_string($dbc, trim($_POST['length']));
$pay_group = mysqli_real_escape_string($dbc, trim($_POST['pay_group']));
$bus_num = mysqli_real_escape_string($dbc, trim($_POST['bus_num']));
$miles = mysqli_real_escape_string($dbc, trim($_POST['miles']));
$miles_cost = round($bus_num * $miles, 2);
$driver_num = mysqli_real_escape_string($dbc, trim($_POST['driver_num']));
$drivers = mysqli_real_escape_string($dbc, trim($_POST['drivers']));
$hours = mysqli_real_escape_string($dbc, $_POST['hours']);
$length = $hours . ' hours';
$driver_cost = round($driver_num * $hours * 11, 2);
$total = $miles_cost + $driver_cost;
$purchase_code = mysqli_real_escape_string($dbc, $_POST['purchase_code']);

$departments = array('transportation');
$fund = substr($purchase_code, 0, 4);
$func = substr($purchase_code, 9, 3);
$location = substr($purchase_code, 22, 3);
$dept_code = substr($purchase_code, 9, 12);
if (in_array($fund, array('2211', '2311', '2511'))) {
  if (!in_array('federal_programs', $departments)) {
    array_push($departments, 'federal_programs');
    $query = "UPDATE forms SET federal_programs = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}
if ($fund == '2110') {
  if (!in_array('food_services', $departments)) {
    array_push($departments, 'food_services');
    $query = "UPDATE forms SET food_services = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}
if (in_array($fund, array('1130','2574','2090', '2610', '2620'))) {
  if (!in_array('sped', $departments)) {
    array_push($departments, 'sped');
    $query = "UPDATE forms SET sped = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}
if ($location == '008') {
  if (!in_array('sanders', $departments)) {
    array_push($departments, 'sanders');
    $query = "UPDATE forms SET sanders = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}
if ($location == '012') {
  if (!in_array('simmons', $departments)) {
    array_push($departments, 'simmons');
    $query = "UPDATE forms SET simmons = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}
if (in_array($dept_code, array('1910-000-610', '1910-000-810', '1910-000-580', '1910-000-610', '1910-000-510'))) {
  if (!in_array('athletics', $departments)) {
    array_push($departments, 'athletics');
    $query = "UPDATE forms SET athletics = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}
if ($dept_code == '2740-000-631' || $func == '2720') {
  if (!in_array('transportation', $departments)) {
    array_push($departments, 'transportation');
    $query = "UPDATE forms SET transportation = 'Approval Needed' WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
}

include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Bus Request', '$program', '$username', NOW() + INTERVAL 2 HOUR, ";
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
  $query = "INSERT INTO forms_busrequest (formId, title, location, number, faculty, safety, travel_start, travel_end, length, pay_group, bus_num, miles, miles_cost, driver_num, drivers, hours, driver_cost, total, purchase_code) VALUES ($formId, '$title', '$destination', $number, '$faculty', '$safety', '$travel_start', '$travel_end', '$length', '$pay_group', $bus_num, $miles, $miles_cost, $driver_num, '$drivers', $hours, $driver_cost, $total, '$purchase_code')";
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
      $message = "$user_name has just submitted a Bus Request." . ' You can view the details of this request, and approve or deny it, <a href="www.sblwilliams.com/hollandale/forms.php">here</a>.<br/>www.sblwilliams.com/hollandale/forms.php';
      $subject = "Bus Request submitted by $user_name";
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
