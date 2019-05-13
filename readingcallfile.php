<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$date = date('Y-m-d', strtotime('today'));
echo $date . '<br>';

$query = "SELECT student_id, count(*) AS count, max(date) AS max_date FROM readingtracker GROUP BY student_id";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
    $students = array();
    while ($row = mysqli_fetch_array($result)) {
        if ($row['max_date'] == $date) {
            array_push($students, array($row['student_id'], '7611', 'student'));
        }
    }
    if (sizeOf($students) > 0) {
        $file = fopen('call_list.csv', 'w');
        foreach ($students as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
        echo '<a href="call_list.csv" download>Call List File</a>';
    }
} else {
    echo 'No students meet the criteria.';
}

mysqli_close($dbc);

?>
