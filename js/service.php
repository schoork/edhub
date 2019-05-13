<?php

require_once('connectvars.php');
require_once('appfunctions.php');
include('forms/departments.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if ($_GET['action'] == 'getData-Week') {
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $query = "SELECT test_type, avg(score) AS average, week FROM data_students WHERE course <> '' GROUP BY week, test_type ORDER BY week ASC, test_type";
  $result = mysqli_query($dbc, $query);
  $scores = array();
  $weeks = array();
  while ($row = mysqli_fetch_array($result)) {
    array_push($scores, array('week' => makeDateAmerican($row['week']), 'test_type' => $row['test_type'], 'score' => $row['average']));
    if(!in_array(makeDateAmerican($row['week']), $weeks, true)){
      array_push($weeks, makeDateAmerican($row['week']));
    }
  }
  echo json_encode(array('scores' => $scores, 'weeks' => $weeks, 'query' => $query));
}

if ($_GET['action'] == 'getData-Student') {
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $period = mysqli_real_escape_string($dbc, trim($_GET['period']));
  $student = mysqli_real_escape_string($dbc, trim($_GET['student']));
  $student = str_replace("%20", " ", $student);
  $teacher = mysqli_real_escape_string($dbc, trim($_GET['teacher']));
  $teacher = str_replace("%20", " ", $teacher);
  $query = "SELECT test_type, score, week FROM data_students WHERE name = '$student' AND period = $period AND teacher = '$teacher' ORDER BY week, test_type";
  $result = mysqli_query($dbc, $query);
  $scores = array();
  $dates = array();
  while ($row = mysqli_fetch_array($result)) {
    array_push($scores, array('date' => makeDateAmerican($row['week']), 'test_type' => $row['test_type'], 'score' => $row['score']));
    if(!in_array(makeDateAmerican($row['week']), $dates, true)){
      array_push($dates, makeDateAmerican($row['week']));
    }
  }
  echo json_encode(array('scores' => $scores, 'dates' => $dates, 'query' => $query));
}

if ($_GET['action'] == 'getData-Period') {
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $period = mysqli_real_escape_string($dbc, trim($_GET['period']));
  $teacher = mysqli_real_escape_string($dbc, trim($_GET['teacher']));
  $teacher = str_replace("%20", " ", $teacher);
  $query = "SELECT course FROM data_enrollment WHERE teacher = '$teacher' AND period = $period LIMIT 1";
  $result = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($result);
  $course = $row['course'];
  $query = "SELECT name, test_type, score FROM data_students WHERE teacher = '$teacher' AND week = '$week' AND course = '$course' AND period = $period ORDER BY name, test_type";
  $result = mysqli_query($dbc, $query);
  $scores = array();
  $students = array();
  while ($row = mysqli_fetch_array($result)) {
    array_push($scores, array('student' => $row['name'], 'test_type' => $row['test_type'], 'score' => $row['score']));
    if(!in_array($row['name'], $students, true)){
      array_push($students, $row['name']);
    }
  }
  echo json_encode(array('scores' => $scores, 'students' => $students));
}

if ($_GET['action'] == 'getData-Teacher') {
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $teacher = mysqli_real_escape_string($dbc, trim($_GET['teacher']));
  $teacher = str_replace("%20", " ", $teacher);
  $query = "SELECT course, test_type, AVG(score) AS average, period FROM data_students WHERE teacher = '$teacher' AND week = '$week' GROUP BY period, course, test_type";
  $result = mysqli_query($dbc, $query);
  $scores = array();
  $periods = array();
  $test_ids = '';
  while ($row = mysqli_fetch_array($result)) {
    if ($row['average'] !== null) {
      $test_ids .= $row['test_id'] . ',';
      array_push($scores, array('period' => $row['period'] . ' (' . $row['course'] . ')', 'test_type' => $row['test_type'], 'average' => $row['average']));
      if(!in_array($row['period'] . ' (' . $row['course'] . ')', $periods, true)){
        array_push($periods, $row['period'] . ' (' . $row['course'] . ')');
      }
    }
  }
  $test_ids = substr($test_ids, 0, strlen($test_ids) - 1);
  $students = array('a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'f' => 0);
  $query = "SELECT score FROM data_students WHERE teacher = '$teacher' AND week = '$week'";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    if ($row['score'] < 64.5) {
      $students['f'] = $students['f'] + 1;
    }
    else if ($row['score'] < 74.5) {
      $students['d'] = $students['d'] + 1;
    }
    else if ($row['score'] < 84.5) {
      $students['c'] = $students['c'] + 1;
    }
    else if ($row['score'] < 94.5) {
      $students['b'] = $students['b'] + 1;
    }
    else {
      $students['a'] = $students['a'] + 1;
    }
  }
  echo json_encode(array('scores' => $scores, 'periods' => $periods, 'test_ids' => $test_ids, 'students' => $students));
}

if ($_GET['action'] == 'getData-Subject') {
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $subject = mysqli_real_escape_string($dbc, trim($_GET['subject']));
  $query = "SELECT teacher, test_type, AVG(score) AS average FROM data_students WHERE ";
  if ($subject == 'Math') {
    $query .= "(course LIKE '%Math%' OR course LIKE '%Algebra%')";
  }
  else if ($subject == 'ELA') {
    $query .= "(course LIKE '%Reading%' OR course LIKE '%English%')";
  }
  else if ($subject == 'Science') {
    $query .= "course LIKE '%Biology%'";
  }
  $query .= " AND teacher <> '' AND week = '$week' GROUP BY teacher, test_type ORDER BY teacher, test_type";
  $result = mysqli_query($dbc, $query);
  $scores = array();
  $teachers = array();
  while ($row = mysqli_fetch_array($result)) {
    if ($row['average'] !== null && $row['test_type'] !== null && $row['teacher'] != '') {
      array_push($scores, array('teacher' => $row['teacher'], 'test_type' => $row['test_type'], 'average' => $row['average']));
      if(!in_array($row['teacher'], $teachers, true)){
        array_push($teachers, $row['teacher']);
      }
    }
  }
  echo json_encode(array('scores' => $scores, 'teachers' => $teachers, 'query' => $query));
}

if ($_GET['action'] == 'getCourses') {
  $teacher = mysqli_real_escape_string($dbc, trim($_GET['teacher']));
  $teacher = str_replace("%20", " ", $teacher);
  $query = "SELECT course FROM data_enrollment WHERE teacher = '$teacher' GROUP BY course ORDER BY course";
  $courses = array();
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    array_push($courses, array('course' => $row['course']));
  }
  echo json_encode(array('courses' => $courses));
}

if ($_POST['action'] == 'meetingSignIn') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $meeting_id = mysqli_real_escape_string($dbc, trim($_POST['meeting_id']));
  $query = "SELECT meeting_name FROM meetings WHERE meeting_id = $meeting_id AND close_time > NOW()";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $meeting_name = $row['meeting_name'];
    $query = "INSERT INTO meetings_participants (username, meeting_id) VALUES ('$username', $meeting_id)";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query, 'msg' => 'Could not join meeting.')));
    echo json_encode(array('status' => 'success', 'msg' => 'You have joined the meeting: ' . $meeting_name));
  }
  else {
    echo json_encode(array('status' => 'fail', 'msg' => 'This meeting has closed or does not exist.', 'query' => $query));
  }
}

if ($_GET['action'] == 'getMeetingParticipants') {
  $meeting_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
  $query = "SELECT firstname, lastname FROM meetings_participants LEFT JOIN staff_list USING (username) WHERE meeting_id = $meeting_id ORDER BY lastname, firstname";
  $result = mysqli_query($dbc, $query);
  $participants = array();
  while ($row = mysqli_fetch_array($result)) {
    array_push($participants, array('name' => $row['firstname'] . ' ' . $row['lastname']));
  }
  echo json_encode(array('participants' => $participants));
}

if ($_POST['action'] == 'createMeeting') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $meeting_name = mysqli_real_escape_string($dbc, trim($_POST['meeting_name']));
  $meeting_type = mysqli_real_escape_string($dbc, trim($_POST['meeting_type']));
  $query = "INSERT INTO meetings (username, meeting_name, meeting_type, date, close_time)";
  $query .= " VALUES ('$username', '$meeting_name', '$meeting_type', CURDATE(), NOW() + INTERVAL 30 MINUTE)";
  if (mysqli_query($dbc, $query)) {
    $meeting_id = mysqli_insert_id($dbc);
    $query = "INSERT INTO meetings_participants (username, meeting_id) VALUES ('$username', $meeting_id)";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query, 'msg' => 'Could not create meeting.')));
    echo json_encode(array('status' => 'success', 'msg' => 'Your meeting has been created. The meeting ID is ' . $meeting_id . '. Participants will be added to the list below as they sign in.', 'meeting_id' => $meeting_id));
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query, 'msg' => 'Could not create meeting.'));
  }
}

if ($_POST['action'] == 'addSafety') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $school = mysqli_real_escape_string($dbc, trim($_POST['school']));
  $date_time = mysqli_real_escape_string($dbc, trim($_POST['date_time']));
  $location = mysqli_real_escape_string($dbc, trim($_POST['location']));
  $staff_present = mysqli_real_escape_string($dbc, trim($_POST['staff_present']));
  $staff_name = mysqli_real_escape_string($dbc, trim($_POST['staff_name']));
  $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
  $injuries = mysqli_real_escape_string($dbc, trim($_POST['injuries']));
  $actions_taken = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['actions_taken'])));
  $actions_taken_desc = mysqli_real_escape_string($dbc, trim($_POST['actions_taken_desc']));
  $contacts = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['contacts'])));
  $query = "INSERT INTO safety_incidents (username, school, date_time, location, staff_present, staff_name, description, injuries, actions_taken, actions_taken_desc, contacts) ";
  $query .= "VALUES ('$username', '$school', '$date_time', '$location', '$staff_present', '$staff_name', '$description', '$injuries', '$actions_taken', '$actions_taken_desc', '$contacts')";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  $incident_id = mysqli_insert_id($dbc);
  $studentTotal = mysqli_real_escape_string($dbc, trim($_POST['studentTotal']));
  $students = array();
  for ($i = 1; $i < $studentTotal + 1; $i++) {
    $student_id = mysqli_real_escape_string($dbc, trim($_POST["student_id-$i"]));
    array_push($students, $student_id);
    $query = "INSERT INTO safety_incidents_students (incident_id, student_id) VALUES ($incident_id, $student_id)";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  }

  $to_array = array();
  $subject = 'Safety Incident';
  $message = "A safety incident has just been reported.<br><br>";
  $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$username'";
  $result = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($result);
  $name = $row['firstname'] . ' ' . $row['lastname'];
  array_push($to_array, array($name, $username . '@hollandalesd.org'));
  $message .= "Submitted by: $name<br>";
  $message .= "Student(s): ";
  $query = "SELECT firstname, lastname FROM student_interventions WHERE student_id IN (" . implode(', ', $students) . ")";
  $result = mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  while ($row = mysqli_fetch_array($result)) {
    $message .= $row['firstname'] . ' ' . $row['lastname'] . ', ';
  }
  $message = substr($message, 0, strlen($message) - 2);
  $message .= "<br>Date/Time: " . parseDatetime($date_time) . "<br>";
  $message .= "School: $school<br>";
  $message .= "Location: $location<br>";
  $message .= "Staff Present: $staff_name<br>";
  $message .= "Description: $description<br>";
  $message .= "Injuries: $injuries<br>";
  $message .= "Immediate Actions Taken: $actions_taken<br>";
  $message .= "Description of Actions Taken: $actions_taken_desc<br>";
  $message .= "Persons Contacted: $contacts";

  array_push($to_array, array('Mamie Warren', 'mwarren@hollandalesd.org'));
  array_push($to_array, array('Mario Willis', 'mwillis2@hollandalesd.org'));
  array_push($to_array, array('Sam Williams', 'swilliams@hollandalesd.org'));
  if ($school == 'Sanders') {
    array_push($to_array, array('Jorgell Jones', 'jjones@hollandalesd.org'));
    array_push($to_array, array('Yvonne Venson', 'yvenson@hollandalesd.org'));
  }
  else if ($school == 'Simmons') {
    array_push($to_array, array('Shiquita Brown', 'sbrown2@hollandalesd.org'));
    array_push($to_array, array('Astria Lloyd', 'abrown@hollandalesd.org'));
  }
  include('mail.php');
  if ($mail->Send()) {
    echo json_encode(array('status' => 'success'));
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
  }
}

if ($_GET['action'] == 'getStudentList') {
  $students = array();
  $query = "SELECT firstname, lastname, grade, student_id FROM student_interventions ORDER BY lastname, firstname";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    array_push($students, array('firstname' => $row['firstname'], 'lastname' => $row['lastname'], 'grade' => $row['grade'], 'student_id' => $row['student_id']));
  }
  echo json_encode(array('students' => $students));
}

if ($_POST['action'] == 'addIntervention') {
  $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
  $student_id = mysqli_real_escape_string($dbc, trim($_POST['student_id']));
  $intervention = mysqli_real_escape_string($dbc, trim($_POST['intervention']));
  $notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
  $query = "INSERT INTO interventions (username, student_id, date, intervention, notes) VALUES ('$username', $student_id, CURDATE(), '$intervention', '$notes')";
  if (mysqli_query($dbc, $query)) {
    echo json_encode(array('status' => 'success'));
  }
  else {
    echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
  }
}

if ($_GET['action'] == 'getStudentInterventions') {
  $student_id = mysqli_real_escape_string($dbc, $_GET['id']);
  $attendance = array();
  $query = "SELECT month, absences FROM interventions_attendance WHERE student_id = $student_id ORDER BY field(month, 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May')";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($attendance, array('date' => $row['month'], 'absences' => $row['absences']));
    }
  }
  $ela = array();
  $query = "SELECT date, score FROM interventions_scores WHERE type = 'ela' AND student_id = $student_id ORDER BY date";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($ela, array('date' => makeDateAmerican($row['date']), 'score' => $row['score']));
    }
  }
  $math = array();
  $query = "SELECT date, score FROM interventions_scores WHERE type = 'math' AND student_id = $student_id ORDER BY date";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($math, array('date' => makeDateAmerican($row['date']), 'score' => $row['score']));
    }
  }
  $state_tests = array();
  $query = "SELECT math_2017, math_2016, math_2015, math_2014, ela_2017, ela_2016, ela_2015, ela_2014 FROM interventions_statetests WHERE student_id = $student_id";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $state_tests = array(array('math_2017' => $row['math_2017'], 'math_2016' => $row['math_2016'], 'math_2015' => $row['math_2015'], 'math_2014' => $row['math_2014'], 'ela_2017' => $row['ela_2017'], 'ela_2016' => $row['ela_2016'], 'ela_2015' => $row['ela_2015'], 'ela_2014' => $row['ela_2014']));
  }
  echo json_encode(array('attendance' => $attendance, 'ela' => $ela, 'math' => $math, 'state_tests' => $state_tests));
}


if ($_POST['action'] == 'registerStudent') {
  $firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
  $middlename = mysqli_real_escape_string($dbc, trim($_POST['middlename']));
  $lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
  $ssn = mysqli_real_escape_string($dbc, trim($_POST['ssn']));
  $birthday = mysqli_real_escape_string($dbc, trim($_POST['birthday']));
  $grade = mysqli_real_escape_string($dbc, trim($_POST['grade']));
  $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
  $address_curr = mysqli_real_escape_string($dbc, trim($_POST['address_curr']));
  $city_curr = mysqli_real_escape_string($dbc, trim($_POST['city_curr'])) . ', MS';
  $zipcode_curr = mysqli_real_escape_string($dbc, trim($_POST['zipcode_curr']));
  if ($_POST['mailDiff'] == 'Yes') {
    $address_mail = mysqli_real_escape_string($dbc, trim($_POST['address_mail']));
    $city_mail = mysqli_real_escape_string($dbc, trim($_POST['city_mail'])) . ', MS';
    $zipcode_mail = mysqli_real_escape_string($dbc, trim($_POST['zipcode_mail']));
  }
  else {
    $address_mail = $address_curr;
    $city_mail = $city_curr;
    $zipcode_mail = $zipcode_curr;
  }
  //Residency
  $residence = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['residence'])));
  $reside_with = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['reside_with'])));
  $reside_stable = mysqli_real_escape_string($dbc, trim($_POST['reside_stable']));

  //income
  $house_size = mysqli_real_escape_string($dbc, trim($_POST['house_size']));
  $house_income = mysqli_real_escape_string($dbc, trim($_POST['house_income']));

  //school_specific
  $home_lang = mysqli_real_escape_string($dbc, trim($_POST['home_lang']));
  if ($home_lang == 'Yes') {
    $home_lang = mysqli_real_escape_string($dbc, trim($_POST['home_lang_list']));
  }
  $expelled = mysqli_real_escape_string($dbc, trim($_POST['expelled']));
  $iep = mysqli_real_escape_string($dbc, trim($_POST['iep']));
  $prev_school = mysqli_real_escape_string($dbc, trim($_POST['prev_school']));
  $corporal = 'No';
  if (mysqli_real_escape_string($dbc, trim($_POST['consent-corporal'])) == 'Yes') {
    $corporal = 'Yes';
  }
  //dha
  $consent_dha = 'No';
  if (mysqli_real_escape_string($dbc, trim($_POST['consent-dha'])) == 'Yes') {
    $consent_dha = 'Yes';
  }

  //query
  $query = "INSERT INTO student_registration (firstname, lastname, middlename, ssn, birthday, grade, gender, address_curr, city_curr, zipcode_curr, address_mail, city_mail, zipcode_mail, residence, reside_with, reside_stable, house_size, house_income, home_lang, expelled, iep, prev_school, corporal, consent_dha) VALUES ('$firstname', '$middlename', '$lastname', '$ssn', '$birthday', '$grade', '$gender', '$address_curr', '$city_curr', $zipcode_curr, '$address_mail', '$city_mail', $zipcode_mail, '$residence', '$reside_with', '$reside_stable', $house_size, '$house_income',";
  $query .= "'$home_lang', '$expelled', '$iep', '$prev_school', '$corporal', '$consent_dha')";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  $student_id = mysqli_insert_id($dbc);

  //parents/guardians
  $parentTotal = mysqli_real_escape_string($dbc, trim($_POST['parentTotal']));
  for ($i = 1; $i < $parentTotal + 1; $i++) {
    $parent_rel = mysqli_real_escape_string($dbc, trim($_POST["parent_rel-$parentTotal"]));
    $parent_name = mysqli_real_escape_string($dbc, trim($_POST["parent_name-$parentTotal"]));
    $parent_phone1 = mysqli_real_escape_string($dbc, trim($_POST["parent_phone1-$parentTotal"]));
    $parent_phone2 = mysqli_real_escape_string($dbc, trim($_POST["parent_phone2-$parentTotal"]));;
    $parent_email = mysqli_real_escape_string($dbc, trim($_POST["parent_email-$parentTotal"]));;
    $query = "INSERT INTO registration_parents (student_id, parent_rel, parent_name, parent_phone1, parent_phone2, parent_email) VALUES ($student_id, '$parent_rel', '$parent_name', '$parent_phone1', '$parent_phone2', '$parent_email')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  }

  //emergency
  $contactTotal = mysqli_real_escape_string($dbc, trim($_POST['contactTotal']));
  for ($i = 1; $i < $contactTotal + 1; $i++) {
    $contact_name = mysqli_real_escape_string($dbc, trim($_POST["contact_name-$contactTotal"]));
    $contact_phone1 = mysqli_real_escape_string($dbc, trim($_POST["contact_phone1-$contactTotal"]));
    $contact_phone2 = mysqli_real_escape_string($dbc, trim($_POST["contact_phone2-$contactTotal"]));
    $query = "INSERT INTO registration_contacts (student_id, contact_name, contact_phone1, contact_phone2) VALUES ($student_id, '$contact_name', '$contact_phone1', '$contact_phone2')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  }

  //student-health
  $insurance = mysqli_real_escape_string($dbc, trim($_POST['insurance']));
  $insure_number = mysqli_real_escape_string($dbc, trim($_POST['insure_number']));
  $allergies = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['allergies'])));
  $allergy_notes = mysqli_real_escape_string($dbc, trim($_POST['allergy_notes']));
  if (is_array($_POST['medical_history'])) {
    $medical_history = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['medical_history'])));
  }
  else {
    $medical_history = mysqli_real_escape_string($dbc, trim($_POST['medical_history']));
  }
  $medical_history_notes = mysqli_real_escape_string($dbc, trim($_POST['medical_history_notes']));
  $asthma_triggers = mysqli_real_escape_string($dbc, trim($_POST['asthma_triggers']));
  $asthma_medications = mysqli_real_escape_string($dbc, trim($_POST['asthma_medications']));
  $special_needs = mysqli_real_escape_string($dbc, trim($_POST['special_needs']));
  $doctor = mysqli_real_escape_string($dbc, trim($_POST['doctor']));
  $doctor_number = mysqli_real_escape_string($dbc, trim($_POST['doctor_number']));
  $hospital = mysqli_real_escape_string($dbc, trim($_POST['hospital']));
  $er = mysqli_real_escape_string($dbc, trim($_POST['er']));
  $dentist = mysqli_real_escape_string($dbc, trim($_POST['dentist']));
  $dentist_number = mysqli_real_escape_string($dbc, trim($_POST['dentist_number']));
  $daily_meds = mysqli_real_escape_string($dbc, trim($_POST['daily_meds']));
  $daily_medication = mysqli_real_escape_string($dbc, trim($_POST['daily_medication']));
  $dhc_restrictions = mysqli_real_escape_string($dbc, trim($_POST['dhc_restrictions']));
  $consent_dhc_privacy = mysqli_real_escape_string($dbc, trim($_POST['consent-dhc-privacy']));
  $consent_dhc_screenings = mysqli_real_escape_string($dbc, trim($_POST['consent-dhc-screenings']));
  $query = "INSERT INTO registration_health (student_id, insurance, insure_number, allergies, allergy_notes, medical_history, medical_history_notes, asthma_triggers, asthma_medications, special_needs, doctor, doctor_number, hospital, er, dentist, dentist_number, daily_meds, daily_medication, dhc_restrictions, consent_dhc_privacy, consent_dhc_screenings) VALUES ($student_id, '$insurance', '$insure_number', '$allergies', '$allergy_notes', '$medical_history', '$medical_history_notes', '$asthma_triggers', '$asthma_medications', '$special_needs', '$doctor', '$doctor_number', '$hospital', '$er', '$dentist', '$dentist_number', '$daily_meds', '$daily_medication', '$dhc_restrictions', '$consent_dhc_privacy', '$consent_dhc_screenings')";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));

  //Kindergrten info
  if ($grade == 'K') {
    $agefour = mysqli_real_escape_string($dbc, trim($_POST['agefour']));
    $agefour_text = mysqli_real_escape_string($dbc, trim($_POST['agefour_text']));
    $mother_educ = mysqli_real_escape_string($dbc, trim($_POST['mother_educ']));
    $bedtime = mysqli_real_escape_string($dbc, trim($_POST['bedtime']));
    $sick = mysqli_real_escape_string($dbc, trim($_POST['sick']));
    $pic_book = mysqli_real_escape_string($dbc, trim($_POST['pic_book']));
    $talk_book = mysqli_real_escape_string($dbc, trim($_POST['talk_book']));
    $alphabet = mysqli_real_escape_string($dbc, trim($_POST['alphabet']));
    $nursery = mysqli_real_escape_string($dbc, trim($_POST['nursery']));
    $stories = mysqli_real_escape_string($dbc, trim($_POST['stories']));
    $library = mysqli_real_escape_string($dbc, trim($_POST['library']));
    $read = mysqli_real_escape_string($dbc, trim($_POST['read']));
    $books_self = mysqli_real_escape_string($dbc, trim($_POST['books_self']));
    $fun = mysqli_real_escape_string($dbc, trim($_POST['fun']));
    $picbooks_have = mysqli_real_escape_string($dbc, trim($_POST['picbooks_have']));
    $like_read = mysqli_real_escape_string($dbc, trim($_POST['like_read']));
    $comfort = mysqli_real_escape_string($dbc, trim($_POST['comfort']));
    if (is_array($_POST['medical_history'])) {
      $before_k = mysqli_real_escape_string($dbc, trim(implode(", ", $_POST['dhc_restrictions'])));
    }
    else {
      $before_k = mysqli_real_escape_string($dbc, trim($_POST['dhc_restrictions']));
    }
    $dolly = mysqli_real_escape_string($dbc, trim($_POST['dolly']));
    $internet = mysqli_real_escape_string($dbc, trim($_POST['internet']));
    $query = "INSERT INTO registration_kindergarten (student_id, agefour, agefour_text, mother_educ, bedtime, sick, pic_book, talk_book, alphabet, nursery, stories, library, ask_read, books_self, fun, picbooks_have, like_read, comfort, before_k, dolly, internet) VALUES ($student_id, '$agefour', '$agefour_text', '$mother_educ', '$bedtime', '$sick', '$pic_book', '$talk_book', '$alphabet', '$nursery', '$stories', '$library', '$read', '$books_self', ";
    $query .= "'$fun', '$picbooks_have', '$like_read', '$comfort', '$before_k', '$dolly', '$internet')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  }

  //if iep is yes, email sped director
  $subject = 'Student with IEP Registered';
  $message = "A student with an IEP has just registered.<br><br>Name: $firstname $lastname<br>Grade: $grade<br>Date of Birth: $birthday<br>Previous School: $prev_school<br>Registration ID: $student_id";
  $to_array = array();
  array_push($to_array, array('Kanesha Smith', 'swilliams@hollandalesd.org'));
  include('mail.php');
  $mail->Send() or die(json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo)));






  echo json_encode(array('status' => 'success'));
}


if ($_GET['action'] == 'getLineBalance') {
  $line = mysqli_real_escape_string($dbc, trim($_GET['line']));
  $query1 = "SELECT initial FROM budget WHERE line_id = $line";
  $result = mysqli_query($dbc, $query1) or die(json_encode(array('message' => mysqli_error($dbc))));
  $data = mysqli_fetch_array($result);
  $balance = $data['initial'];
  //Reqs
  $query = "SELECT sum(price) AS total FROM forms_requisitions_items AS fri LEFT JOIN forms AS f ON (fri.formId = f.form_id) LEFT JOIN forms_requisition AS fr ON (fri.formId = fr.formId) WHERE purchase_code = '$purchase_code' AND status NOT LIKE 'Denied%' GROUP BY form_id ORDER BY form_id";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $balance = $balance - $row['total'];
    }
  }
  //Reimbursements
  $query = "SELECT sum(total) AS total FROM forms_reimbursement AS fr LEFT JOIN forms AS f ON (fr.formId = f.form_id) WHERE purchase_code = '$purchase_code' AND status NOT LIKE 'Denied%' ORDER BY form_id";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $balance = $balance - $row['total'];
    }
  }
  //Bus Requests
  $query = "SELECT sum(total) AS total FROM forms_busrequest AS fr LEFT JOIN forms AS f ON (fr.formId = f.form_id) WHERE purchase_code = '$purchase_code' AND status NOT LIKE 'Denied%'";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $balance = $balance - $row['total'];
    }
  }
  //Out of Town Travel
  $query = "SELECT sum(expected_cost) AS total FROM forms_outoftown AS fr LEFT JOIN forms AS f ON (fr.formId = f.form_id) LEFT JOIN forms_reimbursement AS rei ON (fr.formId = rei.travel_form) WHERE fr.purchase_code = '$purchase_code' AND status NOT LIKE 'Denied%' AND travel_form IS NULL";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $balance = $balance - $row['total'];
    }
  }
  echo json_encode(array('balance' => number_format($balance, 2)));
}

if ($_GET['action'] == 'getLineForms') {
  $line = mysqli_real_escape_string($dbc, trim($_GET['line']));
  $query = "SELECT purchase_code FROM budget WHERE line_id = $line";
  $result = mysqli_query($dbc, $query) or die(json_encode(array('message' => mysqli_error($dbc))));
  $purchase_code = mysqli_fetch_array($result)['purchase_code'];
  $query = "SELECT vendor, form_id, description, date, amount FROM budget_activities WHERE purchase_code = '$purchase_code'";
  $result = mysqli_query($dbc, $query);
  $codes = array();
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($codes, array('vendor' => $row['vendor'], 'form_id' => $row['form_id'], 'description' => $row['description'], 'date' => makeDateAmerican($row['date']), 'amount' => number_format($row['amount'], 2)));
    }
  }
  echo json_encode(array('codes' => $codes, 'purchase_code' => $purchase_code));
}

if ($_GET['action'] == 'getClassRoster') {
  $id = mysqli_real_escape_string($dbc, trim($_GET['id']));
  $type = mysqli_real_escape_string($dbc, trim($_GET['type']));
  $date = mysqli_real_escape_string($dbc, trim($_GET['date']));
  $query = "SELECT name, student_id, c.course FROM school_enrollment AS se LEFT JOIN courses AS c ON (se.course = c.course_id) WHERE course_id = $id AND status = 1 ORDER BY name";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    $students = array();
    while ($row = mysqli_fetch_array($result)) {
      $student_id = $row['student_id'];
      $name = $row['name'];
      $array = array('name' => $name, 'student_id' => $student_id, 'course' => $row['course'], 'club' => $row['club']);
      //grab info from check in/out table
      /*
      $query = "SELECT time, status FROM check_in_out WHERE student_id = $student_id AND date = '$date'";
      $data = mysqli_query($dbc, $query);
      $array['checkin'] = 'N/A';
      $array['checkout'] = 'N/A';
      if (mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_array($data)) {
          if ($row['status'] == 1) {
            $array['checkin'] = $row['time'];
          }
          else if ($row['status'] == 2) {
            $array['checkout'] = $row['time'];
          }
        }
      }
      */

      //grab info from attendance table for that class
      //for ($i=1; $i<6; $i++) {
        $query = "SELECT status FROM attendance WHERE id = $id AND type = '$type' AND student_id = $student_id AND date = '$date' LIMIT 1";
        $data = mysqli_query($dbc, $query);
        if (mysqli_num_rows($data) > 0) {
          $array['period'] = mysqli_fetch_array($data)['status'];
        }
      //}
      array_push($students, $array);
    }
    echo json_encode(array('students' => $students));
  }
  else {
    echo json_encode(array('status' => 'no students', 'query' => $query));
  }
}

if ($_POST['action'] == 'takeAttendance') {
  $id = $_POST['id'];
  $type = $_POST['type'];
  $date = $_POST['date'];
  $query = "SELECT name, student_id FROM school_enrollment AS se LEFT JOIN courses AS c ON (se.course = c.course_id) WHERE course_id = $id ORDER BY name";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $student_id = $row['student_id'];
      $status = $_POST["period-" . $student_id];
      //check to see if attendance has already been submitted for this student
      $query = "SELECT row_id FROM attendance WHERE student_id = '$student_id' AND date = '$date' AND id = $id AND type = '$type' LIMIT 1";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) > 0) {
        //attendance has already been submitted
        $att_row = mysqli_fetch_array($data);
        $att_id = $att_row['row_id'];
        $query = "UPDATE attendance SET status=$status WHERE row_id = $att_id";
        mysqli_query($dbc, $query);
      }
      else {
        $query = "INSERT INTO attendance (student_id, id, type, date, status) VALUES ($student_id, $id, '$type', '$date', $status)";
        mysqli_query($dbc, $query);
      }
    }
  }
  echo json_encode(array('status' => 'success', 'query' => $query));
}

if ($_POST['action'] == 'addSub') {
  $name = mysqli_real_escape_string($dbc, $_POST['name']);
  $number = mysqli_real_escape_string($dbc, $_POST['number']);
  $email = mysqli_real_escape_string($dbc, $_POST['email']);
  $query = "SELECT code FROM sub_list";
  $result = mysqli_query($dbc, $query);
  $codes = array();
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($codes, $row['code']);
    }
  }
  $subcode = generateSubCode($codes);
  $query = "INSERT INTO sub_list (name, number, email, code, status) VALUES ('$name', $number, '$email', '$subcode', 1)";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query, 'message' => mysqli_error($dbc))));
  echo json_encode(array('status' => 'success'));
}

function generateSubCode($codes) {
  $length = 6;
  $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  if (in_array($randomString, $codes)) {
    generateSubCode($codes);
  }
  else {
    return $randomString;
  }
}

if ($_GET['action'] == 'getClassLevelChartData') {
  $label = mysqli_real_escape_string($dbc, trim($_GET['label']));
  $label = str_replace("_", " ", $label);
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $label_array = explode("-", $label);
  $grade = $label_array[0];
  $course = $label_array[1];
  $classes = array();
  $query = "SELECT class, teacher, minimal, basic, pass, pro, adv, row_id FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE grade = '$grade' AND course = '$course' AND week = '$week' ORDER BY row_id";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $averages = array();
      $class = mysqli_real_escape_string($dbc, $row['class']);
      //Get line chart averages
      $query = "SELECT week, average FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE class = '$class'";
      $data = mysqli_query($dbc, $query);
      while ($line = mysqli_fetch_array($data)) {
        array_push($averages, array('date' => $line['week'], 'average' => $line['average']));
      }
      array_push($classes, array('row_id' => $row['row_id'], 'class' => $row['class'], 'teacher' => $row['teacher'], 'minimal' => $row['minimal'], 'basic' => $row['basic'], 'pass' => $row['pass'], 'pro' => $row['pro'], 'adv' => $row['adv'], 'averages' => $averages));
    }
  }
  echo json_encode(array('classes' => $classes, 'query' => $query, 'label' => $label));
}

if ($_GET['action'] == 'getGradeLevelChartData') {
  $week = mysqli_real_escape_string($dbc, trim($_GET['week']));
  $ela = array();
  $math = array();
  $other = array();
  $query = "SELECT grade, course, avg(average) AS average, (sum(pro) + sum(adv)) / (sum(minimal) + sum(basic) + sum(pass) + sum(pro) + sum(adv)) * 100 AS prof FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE week = '$week' AND course IN ('ELA', 'Eng') GROUP BY grade ORDER BY grade ASC";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      if ($row['grade'] == 'High School') {
        $grade = 'Eng II';
      }
      else {
        $grade = $row['grade'];
      }
      array_push($ela, array('grade' => $grade, 'average' => $row['average'], 'prof' => $row['prof'], 'label' => $row['grade'] . '-' . $row['course']));
    }
  }
  $query = "SELECT grade, course, avg(average) AS average, (sum(pro) + sum(adv)) / (sum(minimal) + sum(basic) + sum(pass) + sum(pro) + sum(adv)) * 100 AS prof FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE week = '$week' AND course IN ('Math', 'Alg') GROUP BY grade ORDER BY grade ASC";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      if ($row['grade'] == 'High School') {
        $grade = 'Alg I';
      }
      else {
        $grade = $row['grade'];
      }
      array_push($math, array('grade' => $grade, 'average' => $row['average'], 'prof' => $row['prof'], 'label' => $row['grade'] . '-' . $row['course']));
    }
  }
  $query = "SELECT grade, course, avg(average) AS average, (sum(pro) + sum(adv)) / (sum(minimal) + sum(basic) + sum(pass) + sum(pro) + sum(adv)) * 100 AS prof FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE week = '$week' AND course NOT IN ('Math', 'Alg', 'ELA', 'Eng') GROUP BY grade ORDER BY grade ASC";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      if ($row['grade'] == 'High School') {
        $grade = $row['course'];
      }
      else {
        $grade = $row['grade'] . ' ' . $row['course'];
      }
      array_push($other, array('grade' => $grade, 'average' => $row['average'], 'prof' => $row['prof'], 'label' => $row['grade'] . '-' . $row['course']));
    }
  }
  echo json_encode(array('ela' => $ela, 'math' => $math));
}

if ($_GET['action'] == 'getTeacherAttendanceChart') {
  //get weeks
  $totals = array();
  $query = "SELECT sum(number) AS number, month(start) AS month, program FROM forms_timeoff AS time LEFT JOIN forms AS f ON (time.formId = f.form_id) WHERE f.status = 'Approved' AND month(start) <> 0 AND program <> 'District Office' AND employee NOT LIKE 'dummy%' GROUP BY program, month(start)";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($totals, array('month' => $row['month'], 'number' => $row['number'], 'school' => $row['program']));
    }
  }
  $types = array();
  $query = "SELECT sum(number) AS number, time.type FROM forms_timeoff AS time LEFT JOIN forms AS f ON (time.formId = f.form_id) WHERE f.status = 'Approved' AND month(start) <> 0 AND program <> 'District Office' AND employee NOT LIKE 'dummy%' GROUP BY time.type";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($types, array('number' => $row['number'], 'type' => $row['type']));
    }
  }
  echo json_encode(array('totals' => $totals, 'types' => $types));
}

if ($_GET['action'] == 'getSchoolLevelChartData') {
  //get Sanders
  $schools = array();
  $query = "SELECT week, avg(average) AS average, (sum(pro) + sum(adv)) / (sum(minimal) + sum(basic) + sum(pass) + sum(pro) + sum(adv)) * 100 AS prof FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE grade < 7 GROUP BY week ORDER BY week ASC";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($schools, array('school' => 'Sanders', 'week' => $row['week'], 'average' => $row['average'], 'prof' => $row['prof']));
    }
  }
  $query = "SELECT week, avg(average) AS average, (sum(pro) + sum(adv)) / (sum(minimal) + sum(basic) + sum(pass) + sum(pro) + sum(adv)) * 100 AS prof FROM data_classes AS dc LEFT JOIN data_tests AS dt USING (test_id) WHERE grade > 6 OR grade = 'High School' GROUP BY week ORDER BY week ASC";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      array_push($schools, array('school' => 'Simmons', 'week' => $row['week'], 'average' => $row['average'], 'prof' => $row['prof']));
    }
  }
  echo json_encode(array('schools' => $schools));
}

if ($_POST['action'] == 'applySubs') {
  $date = mysqli_real_escape_string($dbc, $_POST['date']);
  $sub_id = mysqli_real_escape_string($dbc, $_POST['sub_id']);
  $teachers = $_POST['teachers'];
  foreach ($teachers as $teacher) {
    $query = "INSERT INTO sub_request (date, sub_id, employee, approved) VALUES ('$date', $sub_id, '$teacher', 0)";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query, 'message' => mysqli_error($dbc))));
  }
  echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'approveSubs') {
  $date = mysqli_real_escape_string($dbc, $_POST['date']);
  $query = "SELECT username, firstname, lastname, school, start, end FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE start <= '$date' AND end >= '$date' AND sub <> 'No' ORDER BY school, lastname, firstname";
  $result = mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
  while ($row = mysqli_fetch_array($result)) {
    $username = $row['username'];
    $sub = mysqli_real_escape_string($dbc, $_POST['select-' . $username]);
    if ($sub != -1) {
      //make sure this sub has not already been assigned
      $query = "SELECT approved, sub_id FROM sub_request WHERE row_id = $sub";
      $data = mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
      $line = mysqli_fetch_array($data);
      if ($line['approved'] != 1) {
        //sub was not already assigned
        $sub_id = $line['sub_id'];
        //make sure no other subs are assigned
        $query = "UPDATE sub_request SET approved = 0 WHERE date = '$date' AND employee = '$username'";
        mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
        //assign this sub
        $query = "UPDATE sub_request SET approved = 1 WHERE row_id = $sub";
        mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
        //notify the sub
        $query = "SELECT number, email FROM sub_list WHERE sub_id = $sub_id";
        $data = mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
        $line = mysqli_fetch_array($data);
        $number = $line['number'];
        $email = $line['email'];
        $body = 'You have been approved to sub for ' . $row['firstname'] . ' ' . $row['lastname'] . ' at ' . $row['school'] . ' on ' . makeDateAmerican($date) . '. Please report to the main office.';
        $people = array("+1$number" => $name);
        include($_SERVER["DOCUMENT_ROOT"] . '/twilio/sendnotifications.php');
      }
    }
    else {
      $query = "UPDATE sub_request SET approved = 0 WHERE date = '$date' AND employee = '$username'";
      mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'query' => $query)));
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
    $query = "DELETE FROM budget_activities WHERE form_id = $formId";
    mysqli_query($dbc, $query);
  }
  else {
    if ($approve_deny == 'override') {
      $over_depts = array('business', 'payroll', 'sanders', 'simmons', 'technology', 'transportation', 'athletics', 'student_health', 'accounts_payable');
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
      $formType = mysqli_real_escape_string($dbc, trim($_POST['form_type']));
      if ($formType == 'requisition') {
        $query = "UPDATE forms SET status = 'PO Needed' WHERE form_id = $formId";
        mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => $mysqli_error($dbc))));
      }
      else {
        if ($formType == 'deposit') {
          $query = "UPDATE budget_activities SET status = 'Deposited' WHERE form_id = $formId";
        }
        else {
          $query = "UPDATE budget_activities SET status = 'Encumbered' WHERE form_id = $formId";
        }
        mysqli_query($dbc, $query);

        $query = "UPDATE forms SET status = 'Approved' WHERE form_id = $formId";
        mysqli_query($dbc, $query);

        if ($formType == 'timeoff') {
          $formType .= 'request';
        }
        else if ($formType == 'outoftown') {
          $formType .= 'travel';
        }
        $message .= '<br/><br/><strong>In addition, your form has been fully approved and can now be viewed and printed <a href="www.sblwilliams.com/hollandale/forms/prints/' . $formType . '.php?formId=' . $formId . '">here</a>. This has been sent to the appropriate staff member for filing. You can always view a copy of this form on the <a href="www.sblwilliams.com/hollandale/forms.php">Forms List page</a>.<strong>';
      }
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
    $query = "UPDATE budget_activities SET status = 'Encumbered', po = '$po' WHERE form_id = $formId";
    mysqli_query($dbc, $query);

    $query = "UPDATE forms SET status = 'Approved' WHERE form_id = $formId";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc))));
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
