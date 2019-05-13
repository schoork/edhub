<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($_POST['action'] == 'addTeacherAbsences') {
    $username = $_POST['username'];
    $i = $_POST['teacherNumber'];
    $school = $_POST['school'];
    $message = "<table><tr><th>Teacher</th><th>Reason</th><th>Length</th><th>Substitute</th></tr>";
    for ($j = 1; $j < $i + 1; $j++) {
        if (isset($_POST["teacher-$i"])) {
            $teacher = $_POST["teacher-$i"];
            $length = $_POST["length-$i"];
            $reason = $_POST["reason-$i"];
            $sub = $_POST["sub-$i"];
            $query = "SELECT row_id FROM teacherabsences WHERE staff_id = $teacher AND date = CURDATE()";
            $result = mysqli_query($dbc, $query);
            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO teacherabsences (staff_id, length, reason, sub_id, date) VALUES ($teacher, $length, '$reason', $sub, CURDATE())";
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
                $row_id = mysqli_insert_id($dbc);
            } else {
                $row_id = mysqli_fetch_array($result)['row_id'];
                $query = "UPDATE teacherabsences SET length = $length, reason = '$reason', sub_id = $sub WHERE row_id = $row_id";
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
            }
            $query = "SELECT firstname, lastname, name FROM teacherabsences AS ta LEFT JOIN staff_list AS staff ON (ta.staff_id = staff.staff_id) LEFT JOIN sub_list AS sub ON (ta.sub_id = sub.sub_id) WHERE row_id = $row_id";
            $result = mysqli_query($dbc, $query);
            $row = mysqli_fetch_array($result);
            if ($j % 2) {
                $message .= '<tr style="background-color: #d3d3d3; text-align: center">';
            }
            else {
                $message .= '<tr style="text-align: center">';
            }
            $message .= '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td><td>' . $reason . '</td><td>' . $length . '</td><td>' . $row['name'] . '</td></tr>';
        }
    }
    $message .= "</table>";
    $date = new DateTime();
    $subject = $school . " Daily Attendance Report - " . $date->format("M d");
    $to_array = array();
    array_push($to_array, array('Sam Williams', 'swilliams@hollandalesd.org'));
    include('mail.php');
    if ($mail->Send()) {
        echo json_encode(array('status' => 'success'));
    }
    else {
        echo json_encode(array('status' => 'fail', 'message' => $mail->ErrorInfo));
    }
}

mysqli_close($dbc);

?>
