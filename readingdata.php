<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Reading Data';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';


//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Students
		</h1>
		<p class="lead">
	      	Manage students and interventions
	    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
			<h1>
				Reading Data
			</h1>
			<p class="lead">
				In the table below you will see students who have not turned in their reading work with a parent signature today.
			</p>
            <p>
                <a class="btn btn-secondary" href="readingtracker.php">Update Reading Tracker</a>
                <?php
                $date = date('Y-m-d', strtotime('today'));

                $query = "SELECT student_id, count(*) AS count, max(date) AS max_date FROM readingtracker GROUP BY student_id";
                $result = mysqli_query($dbc, $query);
                if (mysqli_num_rows($result) > 0) {
                    $students = array();
					array_push($students, array('6625676666', 'faculty'));
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['max_date'] == $date && ($row['count'] % 3) == 2) {
                            array_push($students, array($row['student_id'], 'student'));
                        }
                    }
                    if (sizeOf($students) > 0) {
                        $file = fopen('call_list.csv', 'w');
						$array = array('referencecode', 'contacttype');
						fputcsv($file, $array);
                        foreach ($students as $line) {
                            fputcsv($file, $line);
                        }
                        fclose($file);
                        echo '<a class="btn btn-secondary" href="call_list.csv" download>Call File for ' . makeDateAmerican($date) . '</a>';
                    }
                }
                 ?>

            </p>
			<?php
            $query = "SELECT reading.student_id, firstname, lastname, grade, count(*) AS count, max(date) as date FROM readingtracker AS reading LEFT JOIN student_list USING (student_id) GROUP BY reading.student_id, lastname, firstname ORDER BY reading.date, lastname, firstname";
			$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Student</th>
							<th>Grade</th>
							<th>Number of Times</th>
							<th>Consequence</th>
							<th>Date of Last Infraction</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . $row['grade'] . '</td>';
							echo '<td>' . $row['count'] . '</td>';
							echo '<td>';
							if (($row['count'] % 3) == 0) {
								echo 'Mandatory Parent Conference';
							} else if (($row['count'] % 3) == 1) {
								echo 'Student Conference';
							} else {
								echo 'Automated Call';
							}
							echo '</td>';
							echo '<td>' . makeDateAmerican($row['date']) . '</date>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else {
				echo '<p>No referrals have been submitted as of yet.</p>';
			}
			?>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {

	$('table').dataTable({
		"order": [[3, 'dsc']],
		"pageLength": 50
	});
});
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
