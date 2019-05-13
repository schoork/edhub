<?php
$type = 'Out of Town Travel';
$program = mysqli_real_escape_string($dbc, trim($_POST['title']));
$user_name = mysqli_real_escape_string($dbc, trim($_POST['user_name']));
$username = mysqli_real_escape_string($dbc, trim($_POST['employee']));
$purchase_code = mysqli_real_escape_string($dbc, $_POST['purchase_code']);

$departments = array('accounts_payable', 'superintendent');
$purchase_codes = array($purchase_code);
$itemTotal = mysqli_real_escape_string($dbc, $_POST['itemTotal']);
for ($i = 1; $i < $itemTotal + 1; $i++) {
  array_push($purchase_codes, mysqli_real_escape_string($dbc, $_POST['purchase_code-' . $i]));
}
foreach ($purchase_codes as $purchase_code) {
  $fund = substr($purchase_code, 0, 4);
  $func = substr($purchase_code, 9, 3);
  $location = substr($purchase_code, 22, 3);
  $dept_code = substr($purchase_code, 9, 12);
  if (in_array($fund, array('2211', '2311', '2511', '2811', '2290'))) {
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
  if (in_array($fund, array('1130', '2574','2090', '2610', '2620'))) {
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
}

include('forms/departments.php');
$query = "INSERT INTO forms (type, program, employee, submit_datetime, " . implode(", ", $depts) . ", status) VALUES ('Out of Town Travel', '$program', '$username', NOW() + INTERVAL 2 HOUR, ";
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
  $purpose = mysqli_real_escape_string($dbc, trim($_POST['purpose']));
  $location = mysqli_real_escape_string($dbc, trim($_POST['location']));
  $start = mysqli_real_escape_string($dbc, trim($_POST['start']));
  $end = mysqli_real_escape_string($dbc, trim($_POST['end']));
  $travel_start = mysqli_real_escape_string($dbc, trim($_POST['travel_start']));
  $travel_end = mysqli_real_escape_string($dbc, trim($_POST['travel_end']));
  $method = mysqli_real_escape_string($dbc, trim($_POST['method']));
  $expected_cost = mysqli_real_escape_string($dbc, $_POST['expected_cost']);
  $outofstate = mysqli_real_escape_string($dbc, $_POST['outofstate']);

  $formId = mysqli_insert_id($dbc);
  $query = "INSERT INTO forms_outoftown (formId, purpose, location, start, end, travel_start, travel_end, method, purchase_code, expected_cost, outofstate) VALUES ($formId, '$purpose', '$location', '$start', '$end', '$travel_start', '$travel_end', '$method', '$purchase_code', '$expected_cost', '$outofstate')";

    if (mysqli_query($dbc, $query)) {
        //Add documents
        $fileNum = mysqli_real_escape_string($dbc, $_POST['fileNum']);
        for ($i = 1; $i < $fileNum + 1; $i++) {
            $fileId = mysqli_insert_id($dbc);
            $file_tmp = $_FILES["file-$i"]['tmp_name'];
            $file_name = $_FILES["file-$i"]['name'];
            $_FILES["file-$i"]['name'];
            if ($file_tmp != '') {
                $target_dir = "documents/";
                while (file_exists($target_dir . basename($file_name))) {
                    $file_name = "$formId " . $file_name;
                }
                $target_file = $target_dir . basename($file_name);
                if (!move_uploaded_file($file_tmp,"documents/".$file_name)) {
                    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem uploading your files. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p>';
                }
                else {
                    $query = "INSERT INTO forms_docs (form_id, docname) VALUES ($formId, '$target_file')";
                    mysqli_query($dbc, $query);
                }
            }
        }

        //add checks
        $budget_activities = array();
        for ($i = 1; $i < $itemTotal + 1; $i++) {
            $name = mysqli_real_escape_string($dbc, trim($_POST["name-$i"]));
            $address = mysqli_real_escape_string($dbc, trim($_POST["address-$i"]));
            $city = mysqli_real_escape_string($dbc, trim($_POST["city-$i"]));
            $zipcode = mysqli_real_escape_string($dbc, trim($_POST["zipcode-$i"]));
            $reason = mysqli_real_escape_string($dbc, trim($_POST["reason-$i"]));
            $amount = mysqli_real_escape_string($dbc, trim($_POST["amount-$i"]));
            $purchase_code = mysqli_real_escape_string($dbc, trim($_POST["purchase_code-$i"]));
            $checkdate = mysqli_real_escape_string($dbc, trim($_POST["checkdate"]));
            $budget_activities["$purchase_code"] = $budget_activities["$purchase_code"] + $amount;
            if ($reason == 'Advance Travel') {
                $query = "INSERT INTO forms_outoftown_checks (form_id, name, address, city, zipcode, reason, amount, purchase_code, checkdate) VALUES ($formId, '$name', '$address', '$city', $zipcode, '$reason', $amount, '$purchase_code', '$checkdate')";
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query, 'message' => mysqli_error($dbc))));
            }
            else {
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
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query, 'message' => mysqli_error($dbc))));
                $reqId = mysqli_insert_id($dbc);
                $date = makeDateAmerican($checkdate);
                $query = "INSERT INTO forms_requisition (formId, vendor, address, city, zipcode, objective, total) VALUES ($reqId, '$name', '$address', '$city', $zipcode, '$reason - Check Needed by $date', $amount)";
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query, 'message' => mysqli_error($dbc))));
                $query = "INSERT INTO forms_requisitions_items (formId, part_num, description, quantity, unit_cost, price, purchase_code) VALUES ($reqId, '', '$reason - See Travel Form #$formId', 1, $amount, $amount, '$purchase_code')";
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query, 'message' => mysqli_error($dbc))));
            }
        }

        foreach ($budget_activities as $key => $value) {
            $query = "INSERT INTO budget_activities (purchase_code, vendor, date, description, amount, form_id, status) VALUES ('$key', '$vendor', CURDATE(), '$objective', $value, $formId, 'Proposed')";
            mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
        }

        //form created, log form creation
        $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Submitted', '$username', NOW())";
        if (mysqli_query($dbc, $query)) {
            //Form logged sucessfully, will send a confirmation and alert email
            $message = "$user_name has just submitted an Out of Town Travel Request." . ' You can view the details of this request, and approve or deny it, <a href="www.sblwilliams.com/hollandale/forms.php">here</a>.';
            $subject = "Out of Town Travel Request submitted by $user_name";
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
    echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem submitting your request. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p><script>console.log(' . mysqli_error($dbc) . '); console.log(' . $query . ');</script>';
}
?>
