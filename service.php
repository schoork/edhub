<?php

require_once('connectvars.php');
require_once('appfunctions.php');
include('forms/departments.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($_POST['action'] == 'updateReadingTracker') {
    $students = $_POST['students'];
    $username = $_POST['username'];
    foreach ($students as $student_id) {
        $query = "SELECT * FROM readingtracker WHERE student_id = $student_id AND date = CURDATE()";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO readingtracker (student_id, username, date) VALUES ($student_id, '$username', CURDATE())";
            mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
        }
    }
    echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'meetingCancel') {
    $meeting_id = $_POST['meeting_id'];
    $user = $_POST['user'];
    $username = $_POST['username'];
    $query = "UPDATE meetings_iep SET status = 0 WHERE meeting_id = $meeting_id";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    $query = "SELECT firstname, lastname, sl.username FROM meetings_iep_staff LEFT JOIN staff_list AS sl USING (username) WHERE meeting_id = $meeting_id";
    $result = mysqli_query($dbc, $query);
    $to_array = array();
    array_push($to_array, array($user, $username . '@hollandalesd.org'));
    while ($row = mysqli_fetch_array($result)) {
        array_push($to_array, array($row['firstname'] . ' ' . $row['lastname'], $row['username'] . '@hollandalesd.org'));
    }
    $subject = 'Meeting Cancelled';
    $query = "SELECT firstname, lastname, date, time, meeting_type FROM meetings_iep LEFT JOIN student_list USING (student_id) WHERE meeting_id = $meeting_id";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $student = $row['firstname'] . ' ' . $row['lastname'];
    $date = makeDateAmerican($row['date']);
    $time = new DateTime($row['time']);
    $display = $time->format('g:i A');
    $message = "$user has cancelled the meeting for $student. This meeting was originally scheduled for $date at $display.";
    include('mail.php');
    if ($mail->Send()) {
        echo json_encode(array('status' => 'success'));
    }
    else {
        echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
}

if ($_POST['action'] == 'meetingResponse') {
    $response = $_POST['response'];
    $meeting_id = $_POST['meeting_id'];
    $username = $_POST['username'];
    $query = "UPDATE meetings_iep_staff SET response = '$response' WHERE meeting_id = $meeting_id AND username = '$username'";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'scheduleMeeting') {
    $student_id = $_POST['student_id'];
    $date = mysqli_real_escape_string($dbc, $_POST['date']);
    $time = mysqli_real_escape_string($dbc, $_POST['time']);
    $notes = mysqli_real_escape_string($dbc, $_POST['notes']);
    $type = $_POST['type'];
    $username = $_POST['username'];
    $staff = $_POST['staff'];
    $query = "INSERT INTO meetings_iep (date, time, notes, student_id, username, status, meeting_type) VALUES ('$date', '$time', '$notes', $student_id, '$username', 1, '$type')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    $meeting_id = mysqli_insert_id($dbc);
    $to_array = array();
    $user = $_POST['user'];
    array_push($to_array, array($user, $username . '@hollandalesd.org'));
    foreach ($staff as $member) {
        $query = "INSERT INTO meetings_iep_staff (meeting_id, username, response) VALUES ($meeting_id, '$member', 'Not Responded')";
        mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    }
    $query = "SELECT firstname, lastname, username FROM staff_list WHERE username IN ('" . implode("', '", $staff) . "')";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        array_push($to_array, array($row['firstname'] . ' ' . $row['lastname'], $row['username'] . '@hollandalesd.org'));
    }
    $query = "SELECT firstname, lastname FROM student_list WHERE student_id = $student_id";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $subject = $type . ' - ' . $row['firstname'] . ' ' . $row['lastname'];
    $message = "An IEP Meeting has been scheduled for " . $row['firstname'] . " " . $row['lastname'] . ". " . $user . " has requested your presence at this meeting. Please contact this person directly if you cannot attend.<br><br>";
    $message .= "Date: " . makeDateAmerican($date) . "<br>";
    $message .= "Time: " . $time . "<br>";
    $message .= "Notes: "  . $_POST['notes'];
    $message .= '<br><br>You may RSVP for the meeting <a href="www.sblwilliams.com/hollandale/meetingdetail.php?id=' . $meeting_id . '">here</a>.';
    include('mail.php');
    if ($mail->Send()) {
        echo json_encode(array('status' => 'success'));
    }
    else {
        echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
}


if ($_POST['action'] == 'addSafety') {
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $school = mysqli_real_escape_string($dbc, trim($_POST['school']));
    $studentTotal = mysqli_real_escape_string($dbc, trim($_POST['studentTotal']));
    $location = mysqli_real_escape_string($dbc, trim($_POST['location']));
    $staff_present = mysqli_real_escape_string($dbc, trim($_POST['staff_present']));
    $staff_name = mysqli_real_escape_string($dbc, trim($_POST['staff_name']));
    $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
    $injuries = mysqli_real_escape_string($dbc, trim($_POST['injuries']));
    $actions = $_POST['actions_taken'];
    $actions_taken = '';
    foreach ($actions as $action) {
        $actions_taken .= $action . ', ';
    }
    $actions_taken = substr($actions_taken, 0, strlen($actions_taken) - 2);
    $actions_desc = mysqli_real_escape_string($dbc, trim($_POST['actions_taken_desc']));
    $contacts = $_POST['contacts'];
    $contact_list = '';
    foreach ($contacts as $contact) {
        $contact_list .= $contact . ', ';
    }
    $contact_list = substr($contact_list, 0, strlen($contact_list) - 2);
    $query = "INSERT INTO safety_incidents (username, school, date_time, location, staff_present, staff_name, description, injuries, actions_taken, actions_taken_desc, contacts) ";
    $query .= "VALUES ('$username', '$school', NOW(), '$location', '$staff_present', '$staff_name', '$description', '$injuries', '$actions_taken', '$actions_desc', '$contact_list')";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    $incident_id = mysqli_insert_id($dbc);
    $student_list = '';
    for ($i = 1; $i < $studentTotal + 1; $i++) {
        $student_id = $_POST["student_id-$i"];
        $query = "INSERT INTO safety_incidents_students (student_id, incident_id) VALUES ($student_id, $incident_id)";
        mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
        $query = "SELECT firstname, lastname FROM student_list WHERE student_id = $student_id";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);
        $student_list .= $row['firstname'] . ' ' . $row['lastname'] . ', ';
    }
    $student_list = substr($student_list, 0, strlen($student_list) - 2);
    //Sends email
    $to_array = array();
    $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$username'";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $teacher_name = $row['firstname'] . ' ' . $row['lastname'];
    array_push($to_array, array($teacher_name, $username . '@hollandalesd.org'));
    array_push($to_array, array('The Mamie Warren', 'mwarren@hollandalesd.org'));
    array_push($to_array, array('Mario Willis', 'mwillis2@hollandalesd.org'));
    array_push($to_array, array('Sam Williams', 'swilliams@hollandalesd.org'));
    if ($school == 'Sanders') {
        array_push($to_array, array('Wade Tackett', 'wtackett@hollandalesd.org'));
        array_push($to_array, array('Yvonne Venson', 'yvenson@hollandalesd.org'));
    }
    else {
        array_push($to_array, array('Shiquita Brown', 'sbrown2@hollandalesd.org'));
        array_push($to_array, array('Astria Lloyd', 'abrown@hollandalesd.org'));
    }
    $subject = 'Safety Incident';
    $message = "$teacher_name has just submitted a safety incident. You can see more information regarding the incident in edhub.<br><br>";
    $message .= "Students(s): $student_list<br>";
    $now = new DateTime();
    $message .= "Date/Time Submitted: " . $now->format("n/j/y h:m a") . '<br>';
    $message .= "School: $school<br>";
    $message .= "Location: $location<br>";
    $message .= "Staff Present: $staff_name<br><br>";
    $message .= "Description: " . $_POST['description'] . '<br><br>';
    $message .= "Injuries: " . $_POST['injuries'] . '<br><br>';
    $message .= "Immediate Actions Taken: " . $actions_taken . '<br>';
    $message .= "Description of Actions Taken: " . $_POST['actions_taken_desc'] . '<br><br>';
    $message .= "Person(s) Contacted: " . $contact_list;
    include('mail.php');
    if ($mail->Send()) {
        echo json_encode(array('status' => 'success'));
    }
    else {
        echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
}

if ($_POST['action'] == 'addTrackerData') {
    $date = $_POST['week'];
    $teacher = $_POST['teacher'];
    $query = "SELECT sl.student_id FROM rti_checklist_written AS rcw LEFT JOIN student_list AS sl USING (student_id) WHERE (teacher1 = '$teacher' OR teacher2 = '$teacher' OR teacher3 = '$teacher' OR teacher4 = '$teacher') AND replace1 IS NOT NULL GROUP BY sl.student_id";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        $student_id = $row['student_id'];
        // i is replace behavior, j is day
        for ($i = 1; $i < 7; $i++) {
            $inputs = array();
            for ($j = 1; $j < 6; $j++) {
                if ($_POST["input-$student_id-$j-$i"] != '') {
                    array_push($inputs, array("day$j", $_POST["input-$student_id-$j-$i"]));
                }
            }
            if (sizeOf($inputs) > 0) {
                $query = "SELECT rowid FROM rti_teacher_tracker WHERE student_id = $student_id AND teacher = '$teacher' AND date = '$date' AND behavior = $i";
                $result = mysqli_query($dbc, $query);
                if (mysqli_num_rows($result) > 0) {
                    //update query
                    $rowid = mysqli_fetch_array($result)['rowid'];
                    $query = "UPDATE rti_teacher_tracker SET ";
                    foreach ($inputs as $input) {
                        $query .= $input[0] . ' = ' . $input[1] . ', ';
                    }
                    $query = substr($query, 0, strlen($query) - 2);
                    $query .= " WHERE rowid = $rowid";
                    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
                }
                else {
                    //insert query
                    $query2 = "INSERT INTO rti_teacher_tracker (teacher, date, behavior, student_id";
                    $values = " VALUES('$teacher', '$date', $i, $student_id";
                    foreach ($inputs as $input) {
                        $query2 .= ", $input[0]";
                        $values .= ", $input[1]";
                    }
                    $query2 .= ")$values)";
                    mysqli_query($dbc, $query2) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query2)));
                }
            }
        }
        if ($_POST["notes-$student_id"] != '') {
            $notes = mysqli_real_escape_string($dbc, trim($_POST["notes-$student_id"]));
            $query = "SELECT rowid FROM rti_teacher_tracker_notes WHERE student_id = $student_id AND teacher = '$teacher' AND date = '$date'";
            $result = mysqli_query($dbc, $query);
            if (mysqli_num_rows($result) > 0) {
                //update query
                $query = "UPDATE rti_teacher_tracker_notes SET notes = '$notes' WHERE rowid = $rowid";
            }
            else {
                $query = "INSERT INTO rti_teacher_tracker_notes (student_id, teacher, date, notes) VALUES ($student_id, '$teacher', '$date', '$notes')";
            }
            mysqli_query($dbc, $query);
        }
    }
    echo json_encode(array('status' => 'success'));
}

if ($_POST['action'] == 'editTracker') {
    $student_id = $_POST['student'];
    $target1 = mysqli_real_escape_string($dbc, trim($_POST['target1']));
    $target2 = mysqli_real_escape_string($dbc, trim($_POST['target2']));
    $target3 = mysqli_real_escape_string($dbc, trim($_POST['target3']));
    $intervention1 = mysqli_real_escape_string($dbc, trim($_POST['intervention1']));
    $intervention2 = mysqli_real_escape_string($dbc, trim($_POST['intervention2']));
    $intervention3 = mysqli_real_escape_string($dbc, trim($_POST['intervention3']));
    $intervention4 = mysqli_real_escape_string($dbc, trim($_POST['intervention4']));
    $intervention5 = mysqli_real_escape_string($dbc, trim($_POST['intervention5']));
    $replace1 = mysqli_real_escape_string($dbc, trim($_POST['replace1']));
    $replace2 = mysqli_real_escape_string($dbc, trim($_POST['replace2']));
    $replace3 = mysqli_real_escape_string($dbc, trim($_POST['replace3']));
    $teacher1 = mysqli_real_escape_string($dbc, trim($_POST['teacher1']));
    $teacher2 = mysqli_real_escape_string($dbc, trim($_POST['teacher2']));
    $teacher3 = mysqli_real_escape_string($dbc, trim($_POST['teacher3']));
    $teacher4 = mysqli_real_escape_string($dbc, trim($_POST['teacher4']));
    $query = "UPDATE rti_checklist_written SET target1 = '$target1', target2 = '$target2', target3 = '$target3', intervention1 = '$intervention1', intervention2='$intervention2', intervention3='$intervention3', intervention4='$intervention4', intervention5='$intervention5', ";
    $query .= "replace1='$replace1', replace2='$replace2', replace3='$replace3', teacher1='$teacher1', teacher2='$teacher2', teacher3='$teacher3', teacher4='$teacher4' WHERE student_id=$student_id";
    if (mysqli_query($dbc, $query)) {
        echo json_encode(array('status' => 'success'));
    }
    else {
        echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
    }
}

if ($_POST['action'] == 'addTracker') {
    $student_id = $_POST['student'];
    $target1 = mysqli_real_escape_string($dbc, trim($_POST['target1']));
    $target2 = mysqli_real_escape_string($dbc, trim($_POST['target2']));
    $target3 = mysqli_real_escape_string($dbc, trim($_POST['target3']));
    $intervention1 = mysqli_real_escape_string($dbc, trim($_POST['intervention1']));
    $intervention2 = mysqli_real_escape_string($dbc, trim($_POST['intervention2']));
    $intervention3 = mysqli_real_escape_string($dbc, trim($_POST['intervention3']));
    $intervention4 = mysqli_real_escape_string($dbc, trim($_POST['intervention4']));
    $intervention5 = mysqli_real_escape_string($dbc, trim($_POST['intervention5']));
    $replace1 = mysqli_real_escape_string($dbc, trim($_POST['replace1']));
    $replace2 = mysqli_real_escape_string($dbc, trim($_POST['replace2']));
    $replace3 = mysqli_real_escape_string($dbc, trim($_POST['replace3']));
    $teacher1 = mysqli_real_escape_string($dbc, trim($_POST['teacher1']));
    $teacher2 = mysqli_real_escape_string($dbc, trim($_POST['teacher2']));
    $teacher3 = mysqli_real_escape_string($dbc, trim($_POST['teacher3']));
    $teacher4 = mysqli_real_escape_string($dbc, trim($_POST['teacher4']));
    $query = "INSERT INTO rti_checklist_written (student_id, created_date, target1, target2, target3, intervention1, intervention2, intervention3, intervention4, intervention5, replace1, replace2, replace3, teacher1, teacher2, teacher3, teacher4)";
    $query .= " VALUES ($student_id, CURDATE(), '$target1', '$target2', '$target3', '$intervention1', '$intervention2', '$intervention3', '$intervention4', '$intervention5', '$replace1', '$replace2', '$replace3', '$teacher1', '$teacher2', '$teacher3', '$teacher4')";
    if (mysqli_query($dbc, $query)) {
        echo json_encode(array('status' => 'success'));
    }
    else {
        echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query));
    }
}


if ($_GET['action'] == 'getInputData') {
    $teacher = $_GET['teacher'];
    $monday = $_GET['monday'];
    $query = "SELECT behavior, student_id, day1, day2, day3, day4, day5 FROM rti_teacher_tracker WHERE teacher = '$teacher' AND date = '$monday'";
    $result = mysqli_query($dbc, $query);
    $inputs = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            for ($i = 1; $i < 6; $i++) {
                if ($row["day$i"] !== null) {
                    $student_id = $row['student_id'];
                    $behavior = $row['behavior'];
                    array_push($inputs, array('input' => "$student_id-$i-$behavior", 'value' => $row["day$i"]));
                }
            }
        }
    }
    $query = "SELECT notes, student_id FROM rti_teacher_tracker_notes WHERE teacher = '$teacher' AND date = '$monday'";
    $result = mysqli_query($dbc, $query);
    $notes = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            array_push($notes, array('student_id' => $row['student_id'], 'notes' => $row['notes']));
        }
    }
    echo json_encode(array('inputs' => $inputs, 'notes' => $notes));
}


if ($_POST['action'] == 'addObservation') {
    $teacher = mysqli_real_escape_string($dbc, trim($_POST['teacher']));
    $observer = mysqli_real_escape_string($dbc, trim($_POST['observer']));
    $period = mysqli_real_escape_string($dbc, trim($_POST['period']));
    $course = mysqli_real_escape_string($dbc, trim($_POST['course']));
    $posted = $_POST['posted'];
    if ($posted != '' && $posted !== null) {
        $posted = implode(", ", $posted);
    }
    $domain = mysqli_real_escape_string($dbc, trim($_POST['domain']));
    $start = $_POST['start'];
    $query = "INSERT INTO observations (teacher, observer, date, start, end, period, course, posted";
    $values = " VALUES ('$teacher', '$observer', CURDATE(), '$start', NOW() + INTERVAL 2 HOUR, '$period', '$course', '$posted'";
    if ($domain == 1 || $domain == 'All') {
        $questions = array('differentiation', 'thinking', 'scaffold');
        $fields = array('diff', 'think', 'scaff');
        $i = 0;
        $num = 0;
        foreach ($questions as $question) {
            $value = mysqli_real_escape_string($dbc, trim($_POST[$question]));
            $evid = mysqli_real_escape_string($dbc, trim($_POST["evid-$question"]));
            $query .= ", " . $fields[$i] . ", " . $fields[$i] . "_evid";
            $values .= ", $value, '$evid'";
            $num += $value;
            $i++;
        }
        $dom1 = round($num/3,1);
        $query .= ", dom1";
        $values .= ", $dom1";
    }
    if ($domain == 2 || $domain == 'All') {
        $questions = array('formative', 'feedback', 'apply', 'disciplines', 'realworld', 'probing');
        $fields = array('form', 'feed', 'apply', 'disc', 'world', 'probe');
        $i = 0;
        $num = 0;
        foreach ($questions as $question) {
            $value = mysqli_real_escape_string($dbc, trim($_POST[$question]));
            $evid = mysqli_real_escape_string($dbc, trim($_POST["evid-$question"]));
            $query .= ", " . $fields[$i] . ", " . $fields[$i] . "_evid";
            $values .= ", $value, '$evid'";
            $num += $value;
            $i++;
        }
        $dom2 = round($num/6,1);
        $query .= ", dom2";
        $values .= ", $dom2";
    }
    if ($domain == 3 || $domain == 'All') {
        $questions = array('redirect', 'participants', 'meaningful', 'procedures', 'pos_rel_tchr', 'pos_rel_studs');
        $fields = array('red', 'part', 'mean', 'proc', 'pos', 'stud');
        $i = 0;
        $num = 0;
        foreach ($questions as $question) {
            $value = mysqli_real_escape_string($dbc, trim($_POST[$question]));
            $evid = mysqli_real_escape_string($dbc, trim($_POST["evid-$question"]));
            $query .= ", " . $fields[$i] . ", " . $fields[$i] . "_evid";
            $values .= ", $value, '$evid'";
            $num += $value;
            $i++;
        }
        $dom3 = round($num/6,1);
        $query .= ", dom3";
        $values .= ", $dom3";
    }
    $glows = mysqli_real_escape_string($dbc, trim($_POST['glows']));
    $grows = mysqli_real_escape_string($dbc, trim($_POST['grows']));
    if ($domain == 1) {
        $overall = $dom1;
    }
    else if ($domain == 2) {
        $overall = $dom2;
    }
    else if ($domain == 3) {
        $overall = $dom3;
    }
    else {
        $overall = round(($dom1 + $dom2 + $dom3)/3, 1);
    }
    $query .= ", glows, grows, overall)" . $values . ",'$glows', '$grows', $overall)";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    // Sends the email
    $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$teacher'";
    $results = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($results);
    $teacher_name = $row['firstname'] . ' ' . $row['lastname'];
    $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$observer'";
    $results = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($results);
    $obs_name = $row['firstname'] . ' ' . $row['lastname'];
    $to_array = array();
    array_push($to_array, array($teacher_name, $teacher . '@hollandalesd.org'));
    array_push($to_array, array($obs_name, $observer . '@hollandalesd.org'));
    $subject = 'Observation Feedback';
    $message = "$teacher_name,<br><br>";
    $message .= $obs_name . ' has just completed an observation of your lesson. The notes/scores from that observation are listed below. Note that evidence is not required for Sub-Standards in which you scored a Level 1. For a full listing of all questions and possible responses go to the <a href="www.sblwilliams.com/hollandale/addobservation.php">new observations page</a>.<br><br>';
    $message .= "Period: $period<br>";
    $message .= "Class/Course: $course<br>";
    $message .= "Whiteboard Protocol Posted:<ol>";
    $posted = $_POST['posted'];
    if ($posted != '' && $posted !== null) {
        foreach ($posted as $post) {
            $message .= "<li>$post</li>";
        }
    }
    $message .= "</ol><br>";
    $message .= "Focused Domain(s): $domain<br><br>";
    if ($domain == 1 || $domain == 'All') {
        $message .= "<h3>Domain I - Lesson Design</h3>";
        $message .= '<table style="border: 1px solid black; border-collapse: collapse;">';
        $message .= "<tr><th>Sub-Standard</th><th>Level</th><th>Evidence</th></tr>";
        $keys = array('differentiation', 'thinking', 'scaffold');
        $labels = array('Differentiation', 'High Level Thinking', 'Scaffolding');
        $i = 0;
        foreach ($keys as $key) {
            $value = mysqli_real_escape_string($dbc, trim($_POST[$key]));
            $evid = mysqli_real_escape_string($dbc, trim($_POST["evid-$key"]));
            $message .= "<tr><td>" . $labels[$i] . "</td><td>$value</td><td>$evid</td></tr>";
            $i++;
        }
        $message .= "</table>";
        $message .= "<br>Domain Score: $dom1";
    }
    if ($domain == 2 || $domain == 'All') {
        $message .= "<h5>Domain II - Student Learning</h5>";
        $message .= '<table style="border: 1px solid black; border-collapse: collapse;">';
        $message .= "<tr><th>Sub-Standard</th><th>Level</th><th>Evidence</th></tr>";
        $keys = array('formative', 'feedback', 'apply', 'disciplines', 'realworld', 'probing');
        $labels = array('Formative Assessment', 'Feedback', 'Apply Feedback', 'Cross-Curricular', 'Real World Applications', 'Probing Questions');
        $i = 0;
        foreach ($keys as $key) {
            $value = mysqli_real_escape_string($dbc, trim($_POST[$key]));
            $evid = mysqli_real_escape_string($dbc, trim($_POST["evid-$key"]));
            $message .= "<tr><td>" . $labels[$i] . "</td><td>$value</td><td>$evid</td></tr>";
            $i++;
        }
        $message .= "</table>";
        $message .= "<br>Domain Score: $dom2";
    }
    if ($domain == 3 || $domain == 'All') {
        $message .= "<h5>Domain III - Culture and Learning Environment</h5>";
        $message .= '<table style="border: 1px solid black; border-collapse: collapse;">';
        $message .= "<tr><th>Sub-Standard</th><th>Level</th><th>Evidence</th></tr>";
        $keys = array('redirect', 'participants', 'meaningful', 'procedures', 'pos_rel_tchr', 'pos_rel_studs');
        $labels = array('Redirect Misbehavior', 'Active Participation', 'Meaningful Work', 'Procedures', 'Teacher-Student Interactions', 'Student-Student Interactions');
        $i = 0;
        foreach ($keys as $key) {
            $value = mysqli_real_escape_string($dbc, trim($_POST[$key]));
            $evid = mysqli_real_escape_string($dbc, trim($_POST["evid-$key"]));
            $message .= "<tr><td>" . $labels[$i] . "</td><td>$value</td><td>$evid</td></tr>";
            $i++;
        }
        $message .= "</table>";
        $message .= "<br>Domain Score: $dom3";
    }
    $message .= "<br>Overall Score: $overall<br>";
    $message .= "Glows from the Lesson: $glows<br>";
    $message .= "Grows from the Lesson: $grows<br>";
    include('mail.php');
    if ($mail->Send()) {
      echo json_encode(array('status' => 'success'));
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
}

if ($_POST['action'] == 'completeReferral') {
    $referral_id = mysqli_real_escape_string($dbc, trim($_POST['referral_id']));
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $staff = mysqli_real_escape_string($dbc, trim($_POST['staff']));
    $action = mysqli_real_escape_string($dbc, trim($_POST['admin_action']));
    $start = mysqli_real_escape_string($dbc, trim($_POST['start_date']));
    $end = mysqli_real_escape_string($dbc, trim($_POST['end_date']));
    $length = mysqli_real_escape_string($dbc, trim($_POST['length']));
    if ($length == '') {
        $length = 0;
    }
    $notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
    $query = "UPDATE referrals SET admin_comments='$notes', action='$action', action_date='$start', end_date='$end', length=$length, admin='$staff', status=2 WHERE rowid = $referral_id";
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
    // Gets some referral details for the email
    $query = "SELECT firstname, lastname, behavior, description, teacher, username FROM referrals LEFT JOIN student_list USING (student_id) WHERE referrals.rowid = $referral_id";
    $results = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($results);
    $subject = 'Completed Behavior Referral';
    $message = "A referral has just been completed.<br><br>";
    $message .= "Completed by: $staff<br>";
    $message .= "Student: " . $row['firstname'] . ' ' . $row['lastname'] . "<br>";
    $message .= "Behavior: " . $row['behavior'] . "<br>";
    $message .= "Description: " . $row['description'] . '<br><br>';
    $message .= "Action: $action<br>";
    $message .= "Start Date: $start<br>";
    $message .= "End Date: $end<br>";
    $message .= "Length: $length<br>";
    $message .= "Admin Notes: " . nl2br($_POST['notes']) . "<br>";
    $to_array = array();
    array_push($to_array, array($staff, $username . '@hollandalesd.org'));
    array_push($to_array, array($row['teacher'], $row['username'] . '@hollandalesd.org'));
    include('mail.php');
    if ($mail->Send()) {
      echo json_encode(array('status' => 'success'));
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
}

if ($_POST['action'] == 'addReferral') {
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $staff = mysqli_real_escape_string($dbc, trim($_POST['staff']));
    $student = mysqli_real_escape_string($dbc, trim($_POST['student_id']));
    $behavior = mysqli_real_escape_string($dbc, trim($_POST['behavior']));
    $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
    if (!isset($_POST['admin_action'])) {
        $query = "INSERT INTO referrals (student_id, teacher, username, time, behavior, description) VALUES ($student, '$staff', '$username', NOW() + INTERVAL 2 HOUR, '$behavior', '$description')";
    }
    else {
        $action = mysqli_real_escape_string($dbc, trim($_POST['admin_action']));
        $start = mysqli_real_escape_string($dbc, trim($_POST['start_date']));
        $end = mysqli_real_escape_string($dbc, trim($_POST['end_date']));
        $length = mysqli_real_escape_string($dbc, trim($_POST['length']));
        $notes = mysqli_real_escape_string($dbc, trim($_POST['notes']));
        $query = "INSERT INTO referrals (student_id, teacher, username, time, behavior, description, action, action_date, end_date, length, admin, admin_comments, status)";
        $query .= " VALUES ($student, '$staff', '$username', NOW(), '$behavior', '$description', '$action', '$start', '$end', $length, '$staff', '$notes', 2)";
    }
    mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc))));
    //Gets the student's grade
    $query = "SELECT grade, lastname, firstname FROM student_list WHERE student_id = $student";
    $result = mysqli_query($dbc, $query);
    $student = mysqli_fetch_array($result);
    $grade = $student['grade'];
    $name = $student['firstname'] . ' ' . $student['lastname'];
    $subject = 'Behavior Referral - ' . $name;
    $message = "A referral has been reported.<br><br>";
    $message .= "Submitted by: $staff<br>";
    $message .= "Student: $name<br>";
    $message .= "Behavior: $behavior<br>";
    $message .= "Description: " . nl2br($_POST['description']);
    $to_array = array();
    array_push($to_array, array($staff, $username . '@hollandalesd.org'));
    if ($grade < 7) {
        array_push($to_array, array('Wade Tackett', 'wtackett@hollandalesd.org'));
        array_push($to_array, array('Herman Brown', 'hbrown@hollandalesd.org'));
    }
    else {
        array_push($to_array, array('Shiquita Brown', 'sbrown2@hollandalesd.org'));
        array_push($to_array, array('Cortez Johnson', 'cjohnson@hollandalesd.org'));
    }
    $extreme = array(
        '(3-1) Fighting',
    	'(3-3) Theft of personal or school property',
    	'(3-4) Acts which threaten the safety and/or well-being of students and/or staff',
    	'(3-5) Use of intimidation, coercion, force, or extortion (includes bullying)',
    	'(3-6) Vandalism/destruction of personal and/or school property',
    	'(3-7) Sexual harassment/misconduct',
    	'(3-8) Threats (verbal or written) towards a student',
    	'(3-9) Gang-Related Activity',
    	'(3-10) Verbal Altercation',
    	'(3-11) Refusal to turn in cell phone',
    	'(3-12) Possession of stolen property',
    	'(3-13) Other behaviors as deemed by the administrator',
    	'(4-1) Possession, use, sale, or under the influence of tobacco, alcohol, illegal drugs, narcotics, controlled substance(s) or paraphernalia',
    	'(4-2) Assault on a student',
    	'(4-3) Possession and/or use of a weapon',
    	'(4-4) Physical, written, or verbal threat or assault on a staff member',
    	'(4-5) Other behaviors as deemed by the administrator'
    );
    if (in_array($behavior, $extreme)) {
        array_push($to_array, array('Samuel Williams', 'swilliams@hollandalesd.org'));
    }
    include('mail.php');
    if ($mail->Send()) {
      echo json_encode(array('status' => 'success'));
    }
    else {
      echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
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
        if (!mysqli_query($dbc, $query1)) {
            echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
        }
        $query = "INSERT INTO forms_log (formId, action, user, date_time) VALUES ($formId, 'Denied', '$username', NOW() + INTERVAL 2 HOUR)";
        if (!mysqli_query($dbc, $query1)) {
            echo json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc)));
        }
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
        include('mail.php');
        if ($mail->Send()) {
            echo json_encode(array('status' => 'success'));
        }
        else {
            echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
        }
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
        include('mail.php');
        if ($mail->Send()) {
            echo json_encode(array('status' => 'success'));
        }
        else {
            echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
        }
    }
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
    foreach ($recipients as $recip) {
        $query1 = "INSERT INTO acct_memos (date, sender, recipient, glows, grows, message) VALUES (CURDATE(), '$sender', '$recip', $glows, $grows, '$message')";
        mysqli_query($dbc, $query1) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc))));
    }
    $body = nl2br($_POST['message']);
    $subject = 'Accountability Memo';
    foreach ($recipients as $recip) {
        $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$recip'";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);
        $name = $row['firstname'] . ' ' . $row['lastname'];
        $to_array = array();
        array_push($to_array, array($admin, $sender . '@hollandalesd.org'));
        array_push($to_array, array($name, $recip . '@hollandalesd.org'));
        if ($sender == 'sbrown2' || $sender == 'cjohnson' || $sender == 'wtackett') {
            array_push($to_array, array('Sam Williams', 'swilliams@hollandalesd.org'));
        }
        $message = $name . ", <br/>" . $body;
        include('mail.php');
        $mail->Send() or die(json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo)));
    }
    echo json_encode(array('status' => 'success'));
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
