<?php

$form_type = 'Fundraiser';

$username = $_POST['employee'];
$program = mysqli_real_escape_string($dbc, trim($_POST['program']));
$description = mysqli_real_escape_string($dbc, trim($_POST['description']));
$objective = mysqli_real_escape_string($dbc, trim($_POST['objective']));
$school = mysqli_real_escape_string($dbc, trim($_POST['school']));
$location = mysqli_real_escape_string($dbc, trim($_POST['location']));
$past = mysqli_real_escape_string($dbc, trim($_POST['past']));
$start = mysqli_real_escape_string($dbc, trim($_POST['start']));
$end = mysqli_real_escape_string($dbc, trim($_POST['end']));
$purchase = mysqli_real_escape_string($dbc, trim($_POST['purchase']));
$facility = mysqli_real_escape_string($dbc, trim($_POST['facility']));
$revenue = mysqli_real_escape_string($dbc, trim($_POST['revenue']));
$facility_school = mysqli_real_escape_string($dbc, trim($_POST['facility_school']));
$purchase_code = mysqli_real_escape_string($dbc, $_POST['purchase_code']);
$revenue_code = mysqli_real_escape_string($dbc, $_POST['revenue_code']);

$departments = array('superintendent');
$fund = substr($purchase_code, 0, 4);
if (in_array($fund, array(2211, 2311, 2511))) {
  if (!in_array('federal_programs', $departments)) {
    array_push($departments, 'federal_programs');
  }
}
else if ($fund == 2110) {
  if (!in_array('food_services', $departments)) {
    array_push($departments, 'food_services');
  }
}
else if (in_array($fund, array(2090, 2610, 2620))) {
  if (!in_array('sped', $departments)) {
    array_push($departments, 'sped');
  }
}

if ($school == 'Sanders') {
  array_push($departments, 'sanders');
}
else if ($school == 'Simmons') {
  array_push($departments, 'simmons');
}
if ($purchase == 'Yes') {
  array_push($departments, 'business');
}
if ($facility_school != '' && $facility_school != $school) {
  array_push($departments, strtolower($facility_school));
}
include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('$form_type', '$program', '$username', NOW() + INTERVAL 2 HOUR, ";
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
  //if requisition, add to requistions items
  $total = 0;
  /*
  if ($purchase == 'Yes') {
    array_push($departments, 'business');
    
    //makes another requisition form
    $query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Requisition', '$program', '$username', NOW() + INTERVAL 2 HOUR, ";
    foreach ($depts as $dept) {
      if (in_array($dept, $departments)) {
        $query .= "'Approval Needed', ";
      }
      else {
        $query .= "'N/A', ";
      }
    }
    $query .= "'Submitted')";
    mysqli_query($dbc, $query);
    $req_id = mysqli_insert_id($dbc);
    
    $itemTotal = $_POST['itemTotal'];
    for ($i = 1; $i < $itemTotal + 1; $i++) {
      $part = mysqli_real_escape_string($dbc, trim($_POST["part-$i"]));
      $description = mysqli_real_escape_string($dbc, trim($_POST["description-$i"]));
      $quantity = mysqli_real_escape_string($dbc, trim($_POST["quantity-$i"]));
      $unitCost = mysqli_real_escape_string($dbc, trim($_POST["unitCost-$i"]));
      $price = round($quantity * $unitCost, 2);
      $total = $total + $price;
      $query = "INSERT INTO forms_requisitions_items (formId, part_num, description, quantity, unit_cost, price) VALUES ($req_id, '$part', '$description', $quantity, $unitCost, $price)";
      if (!mysqli_query($dbc, $query)) {
        echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . $query . ')</script>';
      }
    }
    
    //Add to requisition
    $vendor = mysqli_real_escape_string($dbc, trim($_POST['vendor']));
    $address = mysqli_real_escape_string($dbc, trim($_POST['address']));
    $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
    $zipcode = mysqli_real_escape_string($dbc, trim($_POST['zipcode']));
    $program = mysqli_real_escape_string($dbc, trim($_POST['program']));
    $objective = mysqli_real_escape_string($dbc, trim($_POST['objective']));
    
    $query = "INSERT INTO forms_requisition (formId, vendor, address, city, zipcode, objective, total) VALUES ($req_id, '$vendor', '$address', '$city', $zipcode, '$objective', $total)";
    if (!mysqli_query($dbc, $query)) {
      echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.2)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . $query . ')</script>';
    }
  }
  
  if ($facility == 'Yes') {
    
    if (sizeof($_POST['facilities']) > 0) {
      $facilities = implode(", ", $_POST['facilities']);
    }
    else {
      $facilities = $_POST['facilities'];
    }
    $facilities .= ', ' . mysqli_real_escape_string($dbc, trim($_POST['other_facility']));
    $number_people = mysqli_real_escape_string($dbc, trim($_POST['number_people']));
    $security = mysqli_real_escape_string($dbc, trim($_POST['security']));
    $custodians = mysqli_real_escape_string($dbc, trim($_POST['custodians']));
    
    $query = "INSERT INTO forms_facilities (form_id, school, facilities, number_people, security, custodians) VALUES ($formId, '$facility_school', '$facilities', $number_people, '$security', '$custodians')";
    if (!mysqli_query($dbc, $query)) {
      echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.3)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . $query . ')</script>';
      echo $query;
    }
  }
  */
  
  //Add to forms_fundraiser
  $cost = mysqli_real_escape_string($dbc, $_POST['cost']);
  $revenue = mysqli_real_escape_string($dbc, $_POST['revenue']);
  $profit = $revenue - $cost;
  $query = "INSERT INTO forms_fundraiser (form_id, description, objective, school, location, past, start, end, purchase, facility, cost, revenue, profit, purchase_code, revenue_code) VALUES ($formId, '$description', '$objective', '$school', '$location', '$past', '$start', '$end', '$purchase', '$facility', $total, $revenue, $profit, '$purchase_code', '$revenue_code')";
  if (!mysqli_query($dbc, $query)) {
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.4)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . $query . ')</script>';
  }
  
  //form created, log form creation
  $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW())";
  if (mysqli_query($dbc, $query)) {
    //Form logged sucessfully, will send a confirmation and alert email
    $message = "$user_name has just submitted a Requisition Form." . ' You can view the details of this form, and approve or deny it, on the <a href="www.sblwilliams.com/hollandale/forms.php">Forms List page</a>.';
    $subject = "Requisition submitted by $user_name";
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
    $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$username'";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $user_name = $row['firstname'] . ' ' . $row['lastname'];
    array_push($to_array, array($user_name, $username . '@hollandalesd.org'));

    //require('mail.php');

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
  echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . mysqli_error($dbc) . ')</script>';
}
?>