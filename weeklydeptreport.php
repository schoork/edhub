<?php

$page_title = 'Weekly Department Report';
$page_access = 'Principal Dept Head Admin Superintendent';
include('header.php');

//include other scripts needed here
echo '<script src="js/deptreport_scripts.js"></script>';

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-district.php');
  
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Teachers
		</h1>
    <p class="lead">
      Manage teachers and forms
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
      <h1>
        Weekly Department Report
      </h1>
      <p class="lead">
        Use this page to view submitted department reports
      </p>
			<p>
				<a href="deptreport.php" class="btn-outline-primary btn">Submit Dept Report</a>
			</p>
      <?php
      include('forms/departments.php');
      sort($depts);
      foreach ($depts as $dept) {
        echo '<h2>' . ucwords(str_replace("_", " ", $dept)) . '</h2>';
        $query = "SELECT date, firstname, lastname, information FROM dept_report LEFT JOIN staff_list USING (username) WHERE department = '$dept' ORDER BY date DESC LIMIT 1";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_array($result);
          echo '<p><em>Report submitted by ' . $row['firstname'] . ' ' . $row['lastname'] . ' on ' . makeDateAmerican($row['date']) . '</em></p>';
          $info = $row['information'];
          //upcoming
          echo '<h5>Upcoming Events and Activities</h5>';
          $query = "SELECT type, title, start, end, time, location FROM dept_report_upcoming AS dru LEFT JOIN dept_report AS dr USING (report_id) WHERE department = '$dept' AND end >= CURDATE() ORDER BY start";
          $result = mysqli_query($dbc, $query);
          if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-striped"><thead><tr><th>Type</th><th>Name or Title</th><th>Start Date</th><th>End Date</th><th>Time(s)</th><th>Location</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td>' . $row['type'] . '</td><td>' . $row['title'] . '</td><td>' . makeDateAmerican($row['start']) . '</td><td>' . makeDateAmerican($row['end']) . '</td><td>' . $row['time'] . '</td><td>' . $row['location'] . '</td></tr>';
            }
            echo '</tbody></table>';
          }
          else {
            echo '<p>No upcoming events or activities.</p>';
          }
          //web
          echo '<h5>Resources and Web Links</h5>';
          $query = "SELECT title, url, description FROM dept_report_web LEFT JOIN dept_report AS dr USING (report_id) WHERE department = '$dept' AND date >= CURDATE() - INTERVAL 4 WEEK";
          $result = mysqli_query($dbc, $query);
          if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-striped"><thead><tr><th>Name or Title</th><th>Description</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_array($result)) {
              echo '<tr><td><a href="' . $row['url'] . '" target="_blank">' . $row['title'] . '</td><td>' . $row['description'] . '</td></tr>';
            }
            echo '</tbody></table>';
          }
          else {
            echo '<p>No current resources or web links.</p>';
          }
          //Other info
          echo '<h5>Other Information</h5>';
					$query = "SELECT information FROM dept_report WHERE department = '$dept' ORDER BY date DESC LIMIT 1";
          $result = mysqli_query($dbc, $query);
          if (mysqli_num_rows($result) > 0) {
						echo '<p>';
            while ($row = mysqli_fetch_array($result)) {
							if ($row['information'] != '') {
              	echo nl2br($row['information']) . '<br/>';
							}
            }
            echo '</p>';
          }
        }
        else {
          echo '<div class="alert alert-danger">No report submitted</div>';
        }
        echo '<hr/>';
      }
      ?>
      
    </div>
  </div>
</div>

<?php

//include footer
include('footer.php');
?>

