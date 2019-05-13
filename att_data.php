<?php
$page_title = 'Attendance Data';
$page_access = 'Admin Superintendent Dept Head Principal';
include('header.php');

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-classes.php');

$query = "SELECT date, avg(status) / 2 AS average FROM attendance GROUP BY date ORDER BY date";
$result = mysqli_query($dbc, $query);
$dates = array();
$cols2 = array('', '', '');
while ($row = mysqli_fetch_array($result)) {
  array_push($dates, array('date' => $row['date'], 'average' => round($row['average'], 2) * 100));
}

//Get students
$query = "SELECT name, student_id, c.course, status FROM school_enrollment AS se LEFT JOIN courses AS c ON (se.course = c.course_id) ORDER BY c.course, name";
$result = mysqli_query($dbc, $query);
$students = array();
while ($row = mysqli_fetch_array($result)) {
  $student = array('name' => $row['name'], 'course' => $row['course'], 'status' => $row['status']);
  $student_id = $row['student_id'];
  $query = "SELECT date, status FROM attendance WHERE student_id = $student_id";
  $data = mysqli_query($dbc, $query);
  if (mysqli_num_rows($data) > 0) {
    while ($line = mysqli_fetch_array($data)) {
      if ($line['status'] == 0) {
        $status = 'A';
      }
      else if ($line['status'] == 2) {
        $status = 'P';
      }
      else {
        $status = 'T';
      }
      $date = $line['date'];
      $student[$date] = $status;
    }
  }
  array_push($students, $student);
}
$cols = array('Name', 'Class', 'Status');
foreach ($dates as $date) {
  array_push($cols, $date['date']);
  array_push($cols2, $date['average']);
}
$file = fopen("files/ast_attendance.csv","w");
fputcsv($file, $cols);
fputcsv($file, $cols2);
foreach ($students as $student) {
  $student_row = array($student['name'], $student['course'], $student['status']);
  foreach($dates as $date) {
    array_push($student_row, $student[$date['date']]);
  }
  fputcsv($file, $student_row);
}
fclose($file);

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
		<p class="lead">
			Manage classes and input attendance.
		</p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Attendance Data
			</h1>
      <p>
        <a class="btn btn-success" href="files/ast_attendance.csv" target="download">Data File</a>
        <a class="btn btn-primary" href="attendance.php">Take Attendance</a>
      </p>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

include('footer.php');

?>
