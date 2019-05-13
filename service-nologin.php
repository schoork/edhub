<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
  $query = "INSERT INTO student_registration (firstname, middlename, lastname, ssn, birthday, grade, gender, address_curr, city_curr, zipcode_curr, address_mail, city_mail, zipcode_mail, residence, reside_with, reside_stable, house_size, house_income, home_lang, expelled, iep, prev_school, corporal, consent_dha, status) VALUES ('$firstname', '$middlename', '$lastname', '$ssn', '$birthday', '$grade', '$gender', '$address_curr', '$city_curr', $zipcode_curr, '$address_mail', '$city_mail', $zipcode_mail, '$residence', '$reside_with', '$reside_stable', $house_size, '$house_income',";
  $query .= "'$home_lang', '$expelled', '$iep', '$prev_school', '$corporal', '$consent_dha', 'Registered')";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
  $student_id = mysqli_insert_id($dbc);

  //log
  $query = "INSERT INTO registration_logs (student_id, action, user, date) VALUES ($student_id, 'Registered', 'Parent/Guardian', CURDATE())";
  mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));

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
  if ($iep == 'Yes') {
    $subject = 'Student with IEP Registered';
    $message = "A student with an IEP has just registered.<br><br>Name: $firstname $lastname<br>Grade: $grade<br>Date of Birth: " . makeDateAmerican($birthday) . "<br>Previous School: $prev_school<br>Registration ID: $student_id";
    $to_array = array();
    array_push($to_array, array('Kanesha Smith', 'ksmith@hollandalesd.org'));
    include('mail.php');
    $mail->Send() or die(json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo)));
  }

  //if student is homeless, email homeless liaisons (counselor, federal programs)
  $homeless = 0;
  if (in_array('Hotel', $residence) || in_array('Shelter', $residence) || in_array('Temp', $residence) || in_array('Car', $residence) || in_array('Campsite', $residence) || in_array('Trans', $residence) || in_array('Stable', $residence) || in_array('Other', $residence)) {
    $subject = 'Possible McKinney-Vento Student Registered';
    $message = "A possible McKinney-Vento student has just registered.<br><br>";
    $homeless = 1;
  }
  else if (!in_array('Parent', $reside_with) && !in_array('More Guardians', $reside_with)) {
    $subject = 'Possible Unaccompanied Youth Registered';
    $message = 'A possible unaccompanied student has just registered.<br><br>';
    $homeless = 1;
  }
  else if ($reside_stable == 'No') {
    $subject = 'Possible McKinney-Vento Student Registered';
    $message = "A possible McKinney-Vento student has just registered.<br><br>";
    $homeless = 1;
  }
  if ($homeless == 1) {
    $query = "UPDATE student_registration SET mckinney_vento = 'Possible' WHERE student_id = $student_id";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    $message .= "Name: $firstname $lastname<br>Grade: $grade<br>Date of Birth: " . makeDateAmerican($birthday) . "<br>Previous School: $prev_school<br>Registration ID: $student_id<br>Current Residence: $residence<br>Resides With: $reside_with<br>Fixed, Adequate, Stable Housing? $reside_stable";
    $to_array = array();
    array_push($to_array, array('Samuel Williams', 'swilliams@hollandalesd.org'));
    if ($grade > 6) {
      array_push($to_array, array('Betty Newell', 'bgolden@hollandalesd.org'));
    }
    else {
      array_push($to_array, array('Raven Thomas', 'rthomas3@hollandalesd.org'));
    }
    include('mail.php');
    $mail->Send() or die(json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo)));
  }

  //if student has been expelled, notify Superintendent, school principal, federal programs director
  if ($expelled == 'Yes') {
    $subject = 'Possible Expelled Student Registered';
    $to_array = array();
    array_push($to_array, array('Samuel Williams', 'swilliams@hollandalesd.org'));
    array_push($to_array, array('Mario Willis', 'mwillis2@hollandalesd.org'));
    if ($grade > 6) {
      $school = 'Simmons Jr/Sr High School';
      array_push($to_array, array('Shiquita Brown', 'sbrown2@hollandalesd.org'));
    }
    else {
      $school = 'Sanders Elementary School';
      array_push($to_array, array('Jorgell Jones', 'jjones@hollandalesd.org'));
    }
    if
    $message = "A student who may have been expelled has just registered at $school.<br><br>Name: $firstname $lastname<br>Grade: $grade<br>Date of Birth: " . makeDateAmerican($birthday) . "<br>Previous School: $prev_school<br>Registration ID: $student_id";
    include('mail.php');
    $mail->Send() or die(json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo)));
  }
  echo json_encode(array('status' => 'success'));
}

mysqli_close($dbc);

?>
