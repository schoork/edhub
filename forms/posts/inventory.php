<?php

$type = 'Inventory';
$user_name = mysqli_real_escape_string($dbc, trim($_POST['user_name']));
$username = mysqli_real_escape_string($dbc, trim($_POST['employee']));
$invent_action = mysqli_real_escape_string($dbc, trim($_POST['invent_action']));
$program = 'Asset Management - ' . $invent_action;

$departments = array('asset', 'technology');
$location = mysqli_real_escape_string($dbc, trim($_POST['location']));
if ($location == 'Simmons') {
  array_push($departments, 'simmons');
}
else if ($location == 'Sanders') {
  array_push($departments, 'sanders');
}
else {
  array_push($departments, 'superintendent');
}

$fileNum = mysqli_real_escape_string($dbc, $_POST['fileNum']);
include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('$type', '$program', '$username', NOW() + INTERVAL 2 HOUR, ";
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
  //Add documents
  for ($i = 1; $i < $fileNum + 1; $i++) {
    $file_tmp = $_FILES["file-$i"]['tmp_name'];
    $file_name = $_FILES["file-$i"]['name'];
    if (!empty($file_tmp)) {
      $query = "INSERT INTO forms_docs (form_id, docname) VALUES ($formId, '$file_name')";
      mysqli_query($dbc, $query);
      if (!move_uploaded_file($file_tmp,"documents/".$file_name)) {
        echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem uploading your files. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p>';
      }
      else {
        $query = "INSERT INTO forms_reimbursements_docs (formId, docname) VALUES ($formId, '$target_file')";
        mysqli_query($dbc, $query);
      }
    }
    $target_dir = "documents/";
    $target_file = $target_dir . basename($_FILES["file-$i"]["form-$formId-file-$i"]);

  }

  //Add to detailed forms list
  if ($invent_action == 'Disposal') {
    $reason = mysqli_real_escape_string($dbc, trim($_POST['reason']));
    $room = mysqli_real_escape_string($dbc, trim($_POST['disposal_room']));
    $query = "INSERT INTO forms_inventory (form_id, invent_action, old_room, explanation) VALUES ($formId, '$invent_action', '$room', '$reason')";
  }
  else if ($invent_action == 'Donation') {
    $date = mysqli_real_escape_string($dbc, trim($_POST['donation_date']));
    $donation_from = mysqli_real_escape_string($dbc, trim($_POST['donation_from']));
    $total_cost = mysqli_real_escape_string($dbc, trim($_POST['total_cost']));
    $new_room = mysqli_real_escape_string($dbc, trim($_POST['donation_room']));
    $query = "INSERT INTO forms_inventory (form_id, invent_action, donation_date, donation_from, total_cost, new_room) VALUES ($formId, '$invent_action', '$date', '$donation_from', $total_cost, '$new_room')";
  }
  else if ($invent_action == 'Transfer') {
    $room = mysqli_real_escape_string($dbc, trim($_POST['old_room']));
    $new_room = mysqli_real_escape_string($dbc, trim($_POST['new_room']));
    //update rooms with supervisors
    $sanders_buildings = array(600, 700, 900, 1000, 1300);
    $simmons_buildings = array(100, 500, 800, 1100, 1400, 1500, 1600);
    $sped_buildings = array(300, 400);
    $tech_buildings = array(200);
    $food_buildings = array(1200);
    $supt_buildings = array(1700, 1800);
    $building = floor($new_room / 100) * 100;
    if (in_array($building, $sanders_buildings)) {
      if (!in_array('sanders', $departments)) {
        array_push($departments, 'sanders');
        $query = "UPDATE forms SET sanders = 'Approval Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query);
      }
    }
    else if (in_array($building, $simmons_buildings)) {
      if (!in_array('simmons', $departments)) {
        array_push($departments, 'simmons');
        $query = "UPDATE forms SET simmons = 'Approval Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query);
      }
    }
    else if (in_array($building, $sped_buildings)) {
      if (!in_array('sped', $departments)) {
        array_push($departments, 'sped');
        $query = "UPDATE forms SET sped = 'Approval Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query);
      }
    }
    else if (in_array($building, $tech_buildings)) {
      if (!in_array('technology', $departments)) {
        array_push($departments, 'technology');
        $query = "UPDATE forms SET technology = 'Approval Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query);
      }
    }
    else if (in_array($building, $food_buildings)) {
      if (!in_array('food_services', $departments)) {
        array_push($departments, 'food_services');
        $query = "UPDATE forms SET food_services = 'Approval Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query);
      }
    }
    else if (in_array($building, $supt_buildings)) {
      if (!in_array('superintendent', $departments)) {
        array_push($departments, 'superintendent');
        $query = "UPDATE forms SET superintendent = 'Approval Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query);
      }
    }
    $query = "INSERT INTO forms_inventory (form_id, invent_action, old_room, new_room) VALUES ($formId, '$invent_action', '$room', $new_room)";
  }
  else if ($invent_action == 'Lost/Stolen') {
    $report_number = mysqli_real_escape_string($dbc, trim($_POST['report_number']));
    $explain_loss = mysqli_real_escape_string($dbc, trim($_POST['explain_loss']));
    $query = "INSERT INTO forms_inventory (form_id, invent_action, report, explanation) VALUES ($formId, '$invent_action', '$report_number', '$explain_loss')";
  }

  if (mysqli_query($dbc, $query)) {

    //add items
    $itemTotal = mysqli_real_escape_string($dbc, $_POST['itemTotal']);
    for ($i = 1; $i < $itemTotal + 1; $i++) {
      $tag = mysqli_real_escape_string($dbc, $_POST["tag-$i"]);
      $description = mysqli_real_escape_string($dbc, $_POST["description-$i"]);
      $query = "INSERT INTO forms_inventory_items (form_id, tag, description) VALUES ($formId, $tag, '$description')";
      mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    }

    //form created, log form creation
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW())";
    if (mysqli_query($dbc, $query)) {
      //Form logged sucessfully, will send a confirmation and alert email
      $message = "$user_name has just submitted an Inventory Management Form." . ' You can view the details of this form, and approve or deny it, on the <a href="www.sblwilliams.com/hollandale/forms.php">Forms List page</a>.';
      $subject = "Inventory Management submitted by $user_name";
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
