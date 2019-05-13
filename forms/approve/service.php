<?php

require_once('connectvars.php');
require_once('appfunctions.php');
include('forms/departments.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($_POST['action'] == 'approveSubs') {
  $query = "SELECT username, firstname, lastname, school, start, end FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE start <= CURDATE() AND end >= CURDATE() AND sub <> 'No' ORDER BY school, lastname, firstname";
  $result = mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
  while ($row = mysqli_fetch_array($result)) {
    $username = $row['username'];
    $sub = mysqli_real_escape_string($dbc, $_POST['select-' . $username]);
    if ($sub != '') {
      $query = "SELECT number, email FROM sub_list WHERE sub_id = $sub";
      $data = mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
      $line = mysqli_fetch_array($data);
      $number = $line['number'];
      $email = $line['email'];
      $body = 'You have been approved to sub for ' . $row['firstname'] . ' ' . $row['lastname'] . ' at ' . $row['school'] . 'today. Please report to the main office.';
      $people = array("+1" . $number, $name);
      include($_SERVER["DOCUMENT_ROOT"] . 'twilio/sendnotifications.php');
    }
  }
  echo json_encode(array('status' => 'success'));
}

if ($_GET['action'] == 'getClassNames') {
  $query = "SELECT class FROM data_classes GROUP BY class ORDER BY class";
  $result = mysqli_query($dbc, $query);
  $classes = array();
  while ($row = mysqli_fetch_array($result)) {
    array_push($classes, $row['class']);
  }
  echo json_encode(array('classes' => $classes));
}

if ($_GET['action'] == 'getWeeklyChartData') {
  $test_id = mysqli_real_escape_string($dbc, ($_GET['id']));
  //get pie chart numbers
  $query = "SELECT row_id, class, minimal, basic, pass, pro, adv FROM data_classes WHERE test_id = $test_id";
  $result = mysqli_query($dbc, $query);
  $classes = array();
  while ($row = mysqli_fetch_array($result)) {
    $averages = array();
    $class = mysqli_real_escape_string($dbc, $row['class']);
    //Get line chart averages
    $query = "SELECT week, average FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE class = '$class'";
    $data = mysqli_query($dbc, $query);
    while ($line = mysqli_fetch_array($data)) {
      array_push($averages, array('date' => $line['week'], 'average' => $line['average']));
    }
    array_push($classes, array('class_id' => $row['row_id'], 'class' => $row['class'], 'minimal' => $row['minimal'], 'basic' => $row['basic'], 'pass' => $row['pass'], 'pro' => $row['pro'], 'adv' => $row['adv'], 'averages' => $averages));
  }
  echo json_encode(array('classes' => $classes, 'query' => $query));
}

if ($_POST['action'] == 'editWeeklyData') {
  $test_id = mysqli_real_escape_string($dbc, $_POST['test_id']);
  $teacher = mysqli_real_escape_string($dbc, trim($_POST['name']));
  $week = mysqli_real_escape_string($dbc, $_POST['week']);
  $grade = mysqli_real_escape_string($dbc, ($_POST['grade']));
  $course = mysqli_real_escape_string($dbc, ($_POST['course']));
  $test_name = mysqli_real_escape_string($dbc, ($_POST['test']));
  $standards = mysqli_real_escape_string($dbc, trim($_POST["standards"]));
  if ($course == 'Other') {
    $course = mysqli_real_escape_string($dbc, trim($_POST["other"]));
  }
  $query = "UPDATE data_tests SET teacher = '$teacher', grade = '$grade', course = '$course', test_name = '$test_name', week = '$week', standards = '$standards' WHERE test_id = $test_id";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
  $query = "SELECT row_id FROM data_classes WHERE test_id = $test_id";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    $i = $row['row_id'];
    $class = mysqli_real_escape_string($dbc, trim($_POST["class-$i"]));
    $average = mysqli_real_escape_string($dbc, ($_POST["average-$i"]));
    $minimal = mysqli_real_escape_string($dbc, ($_POST["minimal-$i"]));
    $basic = mysqli_real_escape_string($dbc, ($_POST["basic-$i"]));
    $pass = mysqli_real_escape_string($dbc, ($_POST["pass-$i"]));
    $pro = mysqli_real_escape_string($dbc, ($_POST["pro-$i"]));
    $adv = mysqli_real_escape_string($dbc, ($_POST["adv-$i"]));
    $practice = mysqli_real_escape_string($dbc, ($_POST["practice-$i"]));
    $victories = mysqli_real_escape_string($dbc, ($_POST["victories-$i"]));
    $better = mysqli_real_escape_string($dbc, ($_POST["better-$i"]));
    $query = "UPDATE data_classes SET class = '$class', average = $average, minimal = $minimal, basic = $basic, pass = $pass, pro = $pro, adv = $adv, practice = '$practice', victories = '$victories', better = '$better' WHERE row_id = $i";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
  }
  echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'addWeeklyData') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $classTotal = mysqli_real_escape_string($dbc, $_POST['classTotal']);
  $teacher = mysqli_real_escape_string($dbc, trim($_POST['name']));
  $week = mysqli_real_escape_string($dbc, $_POST['week']);
  $grade = mysqli_real_escape_string($dbc, ($_POST['grade']));
  $course = mysqli_real_escape_string($dbc, ($_POST['course']));
  $test_name = mysqli_real_escape_string($dbc, ($_POST['test']));
  $standards = mysqli_real_escape_string($dbc, trim($_POST["standards"]));
  if ($course == 'Other') {
    $course = mysqli_real_escape_string($dbc, trim($_POST["other"]));
  }
  $query = "INSERT INTO data_tests (username, teacher, grade, course, test_name, week, standards) VALUES ('$username', '$teacher', '$grade', '$course', '$test_name', '$week', '$standards')";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
  $test_id = mysqli_insert_id($dbc);
  for ($i = 1; $i < $classTotal + 1; $i++) {
    $class = mysqli_real_escape_string($dbc, trim($_POST["class-$i"]));
    $average = mysqli_real_escape_string($dbc, ($_POST["average-$i"]));
    $minimal = mysqli_real_escape_string($dbc, ($_POST["minimal-$i"]));
    $basic = mysqli_real_escape_string($dbc, ($_POST["basic-$i"]));
    $pass = mysqli_real_escape_string($dbc, ($_POST["pass-$i"]));
    $pro = mysqli_real_escape_string($dbc, ($_POST["pro-$i"]));
    $adv = mysqli_real_escape_string($dbc, ($_POST["adv-$i"]));
    $practice = mysqli_real_escape_string($dbc, ($_POST["practice-$i"]));
    $victories = mysqli_real_escape_string($dbc, ($_POST["victories-$i"]));
    $better = mysqli_real_escape_string($dbc, ($_POST["better-$i"]));
    $query = "INSERT INTO data_classes (test_id, class, average, minimal, basic, pass, pro, adv, practice, victories, better) VALUES ($test_id, '$class', $average, $minimal, $basic, $pass, $pro, $adv, '$practice', '$victories', '$better')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
  }
  echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'deleteIScan') {
  $scan_id = mysqli_real_escape_string($dbc, $_POST['scan_id']);
  $query = "DELETE FROM inventory_scan WHERE scan_id = $scan_id";
  if (mysqli_query($dbc, $query)) {
    echo json_encode(array('status' => 'success'));
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
  }
}

if ($_POST['action'] == 'addRequest') {
  $name = $_POST['name'];
  $grade = $_POST['grade'];
  $english = $_POST['english'];
  $math = $_POST['math'];
  $science = $_POST['science'];
  $history = $_POST['history'];
  $electives = $_POST['electives'];

  $query = "INSERT INTO course_requests (name, grade, req1, req2, req3, req4, req5, req6, req7, eng, math, sci, hist, status) VALUES ('$name', $grade, ";
  $eng_stat = 0;
  $math_stat = 0;
  $sci_stat = 0;
  $hist_stat = 0;
  if ($english != null) {
    $eng_stat = 1;
    $query .= "'$english', ";
  }
  if ($math != null) {
    $math_stat = 1;
    $query .= "'$math', ";
  }
  if ($science != null) {
    $sci_stat = 1;
    $query .= "'$science', ";
  }
  if ($history != null) {
    $hist_stat = 1;
    $query .= "'$history', ";
  }
  foreach ($electives as $elec) {
    $query .= "'$elec', ";
  }
  $query .= "$eng_stat, $math_stat, $sci_stat, $hist_stat, 1)";
  mysqli_query($dbc, $query);

  echo json_encode(array('status' => 'success'));
}

if ($_GET['action'] == 'getFormInfo') {
  $formId = mysqli_real_escape_string($dbc, $_GET['formId']);
  $query = "SELECT type, employee, submit_datetime FROM forms WHERE form_id = $formId";
  $result = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($result);
  $type = $row['type'];
  $employee = $row['employee'];
  $location = $row['location'];
  $submit_datetime = $row['submit_datetime'];
  $formInfo = array();
  switch ($type) {
    case 'Bus Request':
      $query = "SELECT * FROM forms_busrequest WHERE formId = $formId";
      $result = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($result);
      array_push($formInfo, array('type' => $type, 'title' => $row['title'], 'location' => $row['location'], 'number' => $row['number'], 'title' => $row['title'], 'faculty' => $row['faculty'], 'safety' => $row['safety'], 'leaving' => $row['travel_start'], 'returning' => $row['travel_end'], 'length' => $row['length'], 'hours' => $row['hours'], 'payGroup' => $row['pay_group'], 'busNum' => $row['bus_num'], 'miles' => $row['miles'], 'driverNum' => $row['driver_num'], 'milesCost' => $row['miles_cost'], 'driverCost' => $row['driver_cost'], 'total' => $row['total'], 'employee' => $employee, 'emp_location' => $location, 'submit_datetime' => $submit_datetime));
      echo json_encode(array('status' => 'success', 'formInfo' => $formInfo));
      break;
    case 'Requisition':
      $query = "SELECT * FROM forms_requisition WHERE formId= $formId";
      $result = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($result);
      array_push($formInfo, array('type' => $type, 'employee' => $employee, 'emp_location' => $location, 'submit_datetime' => $submit_datetime, 'vendor' => $row['vendor'], 'address' => $row['address'], 'city' => $row['city'], 'zipcode' => $row['zipcode'], 'objective' => $row['objective'], 'total' => $row['total']));
      $query = "SELECT * FROM forms_requisitions_items WHERE formId = $formId";
      $result = mysqli_query($dbc, $query);
      $items = array();
      while ($row = mysqli_fetch_array($result)) {
        array_push($items, array('part_num' => $row['part_num'], 'description' => $row['description'], 'quantity' => $row['quantity'], 'unit_cost' => $row['unit_cost'], 'price' => $price));
      }
      echo json_encode(array('status' => 'success', 'formInfo' => $formInfo, 'items' => $items));
      break;
  }

}

if ($_POST['action'] == 'formApproval') {
  $approve_deny = $_POST['approve_deny'];
  $formId = $_POST['formId'];
  $username = mysqli_real_escape_string($dbc, $_POST['username']);
  $to_array = array();
  $query = "SELECT firstname, lastname, sl.username FROM staff_list AS sl LEFT JOIN forms ON (sl.username = forms.employee) WHERE form_id = $formId";
  $result = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($result);
  array_push($to_array, array($row['firstname'] . ' ' . $row['lastname'], $username . '@hollandalesd.org'));
  $emp_name = $row['firstname'] . ' ' . $row['lastname'];
  $query = "SELECT firstname, lastname, departments FROM staff_list WHERE username = '$username'";
  $result = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($result);
  $user_name = $row['firstname'] . ' ' . $row['lastname'];
  $departments = explode(", ", $row['departments']);

  if ($approve_deny == 'deny') {
    $query = "SELECT * FROM forms WHERE form_id = $formId";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $dept_array = array();
    foreach ($depts as $dept) {
      if ($row["$dept"] != 'N/A') {
        array_push($dept_array, $dept);
      }
    }
    $reason = mysqli_real_escape_string($dbc, trim($_POST['denyReason']));
    $query1 = "UPDATE forms SET status = 'Denied', reason = '$reason' WHERE form_id = $formId";
    mysqli_query($dbc, $query1);
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Denied', '$username', NOW() + INTERVAL 2 HOUR)";
    mysqli_query($dbc, $query);
    $subject = 'Form Denied';

    $message = "$emp_name,<br/>Your form (#$formId) has been denied by $user for the reason(s) listed below. You can access this form and resubmit it on the " . ' <a href="www.sblwilliams.com/hollandale/forms.php">Forms List Page</a><br/<br/>Reason(s): ' . $reason;
    $query = "SELECT firstname, lastname, username FROM staff_list WHERE ";
    foreach ($dept_array as $dept) {
      $query.= "departments LIKE '%$dept%' OR ";
    }
    $query = substr($query, 0, strlen($query) - 4);
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
      array_push($to_array, array($row['firstname'] . ' ' . $row['lastname'], $row['username'] . '@hollandalesd.org'));
    }
  }
  else {
    if ($approve_deny == 'override') {
      $over_depts = array('business', 'payroll', 'sanders', 'simmons', 'technology', 'transportation', 'athletics', 'student_health');
      $query = "SELECT * FROM forms WHERE form_id = $formId";
      $result = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($result);
      $override = array();
      $query = "UPDATE forms SET";
      foreach ($over_depts as $dept) {
        if ($row[$dept] == 'Approval Needed') {
          $query .= " $dept = 'Superintendent Override',";
        }
      }
      $query = substr($query, 0, strlen($query) - 1);
      $query .= " WHERE form_id = $formId";
      if (!mysqli_query($dbc, $query)) {
        echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query, 'departments' => $departments));
      }
      $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Supt. Override Approval', '$username', NOW() + INTERVAL 2 HOUR)";
    }
    else {
      $query = "UPDATE forms SET";
      foreach ($departments as $dept) {
        $query .= " $dept = '$user_name',";
      }
      $query = substr($query, 0, strlen($query) - 1);
      $query .= " WHERE form_id = $formId";
      if (!mysqli_query($dbc, $query)) {
        echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query, 'departments' => $departments));
      }
      $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Approved', '$username', NOW() + INTERVAL 2 HOUR)";
    }
    mysqli_query($dbc, $query);
    $subject = 'Form Approved';
    $message = "$emp_name,<br/>Your form (#$formId) has been approved by $user_name.";
    array_push($to_array, $user_name, $username . '@hollandalesd.org');
    //Add form_specific updates
    if ($_POST['form_type'] == 'requisition') {
      $query = "SELECT itemId FROM forms_requisitions_items WHERE formId = $formId";
      $result = mysqli_query($dbc, $query);
      while ($row = mysqli_fetch_array($result)) {
        $i = $row['itemId'];
        $purchase_code = mysqli_real_escape_string($dbc, $_POST["purchase_code-$i"]);
        $query = "UPDATE forms_requisitions_items SET purchase_code = '$purchase_code' WHERE itemId = $i";
        mysqli_query($dbc, $query);
      }
    }
    else if ($_POST['form_type'] == 'reimbursement') {
      $purchase_code = mysqli_real_escape_string($dbc, $_POST["purchase_code"]);
      $query = "UPDATE forms_reimbursement SET purchase_code = '$purchase_code' WHERE formId = $formId";
      mysqli_query($dbc, $query);
    }
    else if ($_POST['form_type'] == 'busrequest') {
      if ($_POST['drivers_assigned'] != '') {
        $query = "UPDATE forms_busrequest SET drivers_assigned = '" . mysqli_real_escape_string($dbc, trim($_POST['drivers_assigned'])) . "' WHERE formId = $formId";
      mysqli_query($dbc, $query);
      }
    }
    else if ($_POST['form_type'] == 'outoftown') {
      $purchase_code = mysqli_real_escape_string($dbc, $_POST["purchase_code"]);
      $query1 = "UPDATE forms_outoftown SET purchase_code = '$purchase_code' WHERE formId = $formId";
      mysqli_query($dbc, $query1);
    }
    //determine if all approvals have been acquired
    $approval = 1;
    $query = "SELECT * FROM forms WHERE form_id = $formId";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    foreach ($depts as $dept) {
      if ($row[$dept] == 'Approval Needed') {
        $approval = 0;
      }
    }
    if ($approval > 0) {
      $query = "UPDATE forms SET status = 'Approved' WHERE form_id = $formId";
      mysqli_query($dbc, $query);
      $formType = $_POST['form_type'];
      if ($formType == 'timeoff') {
        $formType .= 'request';
      }
      else if ($formType == 'outoftown') {
        $formType .= 'travel';
      }
      $message .= '<br/><br/><strong>In addition, your form has been fully approved and can now be viewed and printed <a href="www.sblwilliams.com/hollandale/forms/prints/' . $formType . '.php?formId=' . $formId . '">here</a>. This has been sent to the appropriate staff member for filing. You can always view a copy of this form on the <a href="www.sblwilliams.com/hollandale/forms.php">Forms List page</a>.<strong>';
    }
  }

  include('mail.php');
    echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'locationChange') {
  $username = $_POST['username'];
  $school = $_POST['location'];
  $access = $_POST['access'];
  if ($school != 'Unknown') {
    $query = "UPDATE staff_list SET school = '$school' WHERE username = '$username'";
    if (mysqli_query($dbc, $query)) {
      if ($access != 'Teacher') {
        $name = $_POST['name'];
        $subject = $access . ' Access Level Requested';
        $message = "$name has requested the $access Access Level for the Hollandale edhub. You can change $name's access level at any time on the " . '<a href="www.sblwilliams.com/hollandale/teacherslist.php">Teachers List page</a>.';
        $to_array = array();
        array_push($to_array, array($name, $username . '@hollandalesd.org'));
        array_push($to_array, array('Samuel Williams', 'swilliams@hollandalesd.org'));
        include('mail.php');
        if ($mail->Send()) {
          echo json_encode(array('status' => 'success'));
        }
        else {
          echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
        }
      }
      else {
        echo json_encode(array('status' => 'success'));
      }
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
    }
  }
}

if ($_POST['action'] == 'teacherChange') {
  $staff_id = $_POST['staff_id'];
  $school = $_POST['location'];
  $access = $_POST['access'];

  $departments = $_POST['departments'];
  if (sizeof($departments) > 0) {
    $departments = implode(", ", $_POST['departments']);
  }
  $query = "UPDATE staff_list SET school = '$school', access = '$access', departments = '$departments' WHERE staff_id = $staff_id";
  if (mysqli_query($dbc, $query)) {
    $query = "SELECT username, firstname, lastname FROM staff_list WHERE staff_id = $staff_id";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $username = $row['username'];
    $name = $row['firstname'] . ' ' . $row['lastname'];
    $subject = 'edhub Information Changed';
    $user = $_POST['user'];
    $email = $_POST['username'] . '@hollandalesd.org';
    $message = "$name, <br/><br/>$user has changed your information for the Hollandale edhub to the following.<br/><br/>Location: $school<br/>Access Level: $access<br/>Department(s): $departments<br/><br/>If you think this is in error, contact $user at " . '<a href="mailto:' . $email . '" target="_blank">' . $email . '</a>.';
    $to_array = array();
    array_push($to_array, array($name, $username . '@hollandalesd.org'));
    include('mail.php');
    if ($mail->Send()) {
      echo json_encode(array('status' => 'success'));
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
  }
}

if ($_POST['action'] == 'addMemo') {
  $sender = mysqli_real_escape_string($dbc, trim($_POST['sender']));
  $recipients = $_POST['recipients'];
  $glows = sizeof($_POST['glows']);
  $grows = sizeof($_POST['grows']);
  $message = mysqli_real_escape_string($dbc, trim($_POST['message']));
  $sql_errors = 0;
  if (sizeof($recipients) > 1) {
    foreach ($recipients as $recip) {
      $query1 = "INSERT INTO acct_memos (date, sender, recipient, glows, grows, message) VALUES (CURDATE(), '$sender', '$recip', $glows, $grows, '$message')";
      if (!mysqli_query($dbc, $query1)) {
        $sql_errors++;
      }
    }
  }
  else {
    $query1 = "INSERT INTO acct_memos (date, sender, recipient, glows, grows, message) VALUES (CURDATE(), '$sender', '$recipients', $glows, $grows, '$message')";
    if (!mysqli_query($dbc, $query1)) {
      $sql_errors++;
    }
  }
  if ($sql_errors === 0) {
    $message = nl2br($_POST['message']);
    $errors = 0;
    $subject = 'Accountability Memo';
    if (sizeof($recipients) > 1) {
      foreach ($recipients as $recip) {
        $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$recip'";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);
        $name = $row['firstname'] . ' ' . $row['lastname'];
        $to_array = array();
        array_push($to_array, array($admin, $sender . '@hollandalesd.org'));
        array_push($to_array, array($name, $recip . '@hollandalesd.org'));
        $message = $name . ", <br/>" . $message;
        include('mail.php');
        if (!$mail->Send()) {
          $errors++;
        }
      }
    }
    else {
      $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$recipients'";
      $result = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($result);
      $name = $row['firstname'] . ' ' . $row['lastname'];
      $to_array = array();
      array_push($to_array, array($admin, $sender . '@hollandalesd.org'));
      array_push($to_array, array($name, $recipients . '@hollandalesd.org'));
      $message = $name . ", <br/>" . $message;
      include('mail.php');
      if (!$mail->Send()) {
        $errors++;
      }
    }
    if ($errors > 0) {
      echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
    else {
      echo json_encode(array('status' => 'success'));
    }
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
  }
}


if ($_GET['action'] == 'getMemo') {
  $memoId = mysqli_real_escape_string($dbc, trim($_GET['memoId']));
  $query = "SELECT message FROM acct_memos WHERE memoId = $memoId";
  $result = mysqli_query($dbc, $query);
  $message = mysqli_fetch_array($result)['message'];
  echo json_encode(array('message' => $message));
}


if ($_GET['action'] == 'getInventoryItems') {
  $location = mysqli_real_escape_string($dbc, trim($_GET['location']));
  $room = mysqli_real_escape_string($dbc, trim($_GET['room']));

  $query = "SELECT * FROM inventory WHERE FIND_IN_SET(room, '$room') <> 0 ORDER BY location, room";
  $result = mysqli_query($dbc, $query);
  $items = array();
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($items, array('room' => $row['room'], 'title' => $row['title'], 'itemId' => $row['itemId'], 'class' => $row['class'], 'fund' => $row['fund'], 'date_acquired' => makeDateAmerican($row['date_acquired']), 'serial' => $row['serial'], 'cost' => $row['cost'], 'condition' => $row['item_condition']));
    }
  }
  echo json_encode(array('status' => 'success', 'items' => $items, 'query' => $query));
}


if ($_POST['action'] == 'verifyInventory') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$username'";
  $result = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($result);
  $name = $row['firstname'] . ' ' . $row['lastname'];
  $room = mysqli_real_escape_string($dbc, trim($_POST['room']));

  $query = "SELECT itemId FROM inventory WHERE room = '$room'";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    $itemId = $row['itemId'];
    $status = $_POST["item-$itemId"];
    $description = mysqli_real_escape_string($dbc, trim($_POST["desc-$itemId"]));
    $query = "INSERT INTO inventory_updates (itemId, date, username, status, description) VALUES ($itemId, CURDATE(), '$username', '$status', '$description')";
    mysqli_query($dbc, $query);
  }
  echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'deleteForm') {
  $formId = mysqli_real_escape_string($dbc, trim($_POST['formId']));
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));

  $query = "DELETE FROM forms WHERE form_id = $formId";
  if (mysqli_query($dbc, $query)) {
    //Log deletion of form
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Deleted', '$username', NOW() + INTERVAL 2 HOUR)";
    if (mysqli_query($dbc, $query)) {
      echo json_encode(array('status' => 'success'));
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => $mysqli_error($dbc)));
    }
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => $mysqli_error($dbc)));
  }
}

if ($_POST['action'] == 'addDeptReport') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $upcoming = mysqli_real_escape_string($dbc, trim($_POST['upcomingNum']));
  $web = mysqli_real_escape_string($dbc, trim($_POST['webNum']));
  $department = mysqli_real_escape_string($dbc, trim($_POST['department']));
  $information = mysqli_real_escape_string($dbc, trim($_POST['information']));

  $query = "INSERT INTO dept_report (date, department, username, information) VALUES (CURDATE(), '$department', '$username', '$information')";
  if (mysqli_query($dbc, $query)) {
    //add upcoming events
    $report_id = mysqli_insert_id($dbc);
    $errors = 0;
    for ($i = 1; $i < $upcoming + 1; $i++) {
      $type = mysqli_real_escape_string($dbc, trim($_POST["type-$i"]));
      $title = mysqli_real_escape_string($dbc, trim($_POST["title-$i"]));
      $start = mysqli_real_escape_string($dbc, trim($_POST["start-$i"]));
      $end = mysqli_real_escape_string($dbc, trim($_POST["end-$i"]));
      $time = mysqli_real_escape_string($dbc, $_POST["time-$i"]);
      $location = mysqli_real_escape_string($dbc, trim($_POST["location-$i"]));
      if ($title != '') {
        $query1 = "INSERT INTO dept_report_upcoming (report_id, type, title, start, end, time, location) VALUES ($report_id, '$type', '$title', '$start', '$end', '$time', '$location')";
        if (!mysqli_query($dbc, $query1)) {
          $errors++;
        }
      }
    }
    if ($errors == 0) {
      //add web links
      $error = 0;
      for ($i = 1; $i < $web + 1; $i++) {
        $title = mysqli_real_escape_string($dbc, trim($_POST["webtitle-$i"]));
        $url = mysqli_real_escape_string($dbc, trim($_POST["url-$i"]));
        $description = mysqli_real_escape_string($dbc, trim($_POST["description-$i"]));
        if ($title != '') {
          $query = "INSERT INTO dept_report_web (report_id, title, url, description) VALUES ($report_id, '$title', '$url', '$description')";
          if (!mysqli_query($dbc, $query)) {
            $errors++;
          }
        }
      }
      if ($errors == 0) {
        //add feedback
        foreach ($depts as $dept) {
          $friendly = mysqli_real_escape_string($dbc, trim($_POST["friendly-$dept"]));
          $response = mysqli_real_escape_string($dbc, trim($_POST["response-$dept"]));
          $support = mysqli_real_escape_string($dbc, trim($_POST["support-$dept"]));
          if ($friendly !== null) {
            $query = "INSERT INTO dept_report_feedback (date, department, friendly, response, support) VALUES (CURDATE(), '$dept', '$friendly', '$response', '$support')";
            if (!mysqli_query($dbc, $query)) {
              $errors++;
            }
          }
        }
        if ($errors == 0) {
          echo json_encode(array('status' => 'success', 'query' => $query1));
        }
        else {
          echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
        }
      }
      else {
        echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
      }
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
    }
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
  }

}

if ($_GET['action'] == 'getDeptReport') {
  $dept = mysqli_real_escape_string($dbc, trim($_GET['dept']));
  $query = "SELECT type, title, start, end, location FROM dept_report_upcoming LEFT JOIN dept_report USING (report_id) WHERE end >= CURDATE() AND department = '$dept' ORDER BY start";
  $result = mysqli_query($dbc, $query);
  $events = array();
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($events, array('title' => $row['title'], 'type' => $row['type'], 'start' => makeDateAmerican($row['start']), 'end' => makeDateAmerican($row['end']), 'location' => $row['location']));
    }
  }
  $links = array();
  $query = "SELECT title, description FROM dept_report_web LEFT JOIN dept_report USING (report_id) WHERE date >= CURDATE() - INTERVAL 2 WEEK AND department = '$dept'";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($links, array('title' => $row['title'], 'description' => $row['description']));
    }
  }
  $query = "SELECT information FROM dept_report WHERE date >= CURDATE() - INTERVAL 10 DAY AND department = '$dept'";
  $result = mysqli_query($dbc, $query);
  $info = '';
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $info .= $row['information'];
    }
  }
  echo json_encode(array('events' => $events, 'links' => $links, 'info' => $info));
}

if ($_POST['action'] == 'addPO') {
  $formId = mysqli_real_escape_string($dbc, trim($_POST['formId']));
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $req = mysqli_real_escape_string($dbc, $_POST['req']);
  $po = mysqli_real_escape_string($dbc, $_POST['po']);

  $query = "UPDATE forms_requisition SET req = '$req', po = '$po' WHERE formId = $formId";
  if (mysqli_query($dbc, $query)) {
    //Log on form
    $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Assigned PO Number', '$username', NOW() + INTERVAL 2 HOUR)";
    if (mysqli_query($dbc, $query)) {
      echo json_encode(array('status' => 'success'));
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
    }
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
  }
}

mysqli_close($dbc);

?>
