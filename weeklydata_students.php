<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Weekly Data By Student';
$page_access = 'All';
include('header.php');

//include other scripts needed here
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<?php

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-classes.php');

if (isset($_GET['week'])) {
	$week = mysqli_real_escape_string($dbc, $_GET['week']);
}
else {
	$week = date('Y-m-d', strtotime('last Friday'));
}
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
    <p class="lead">
      Manage and view weekly data
    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      		<h1>
				Weekly Data by Student
			</h1>
			<p class="lead">
				Use this page to view and print weekly data reports.
			</p>
			<p>
				<a class="btn btn-secondary" href="weeklydata_courses.php">Data By Course</a>
				<a class="btn btn-secondary" href="weeklydata_core.php?week=<?php echo $week; ?>">CORE Analysis</a>
				<a class="btn btn-secondary" href="upload_data.php">Upload Weekly Data</a>
			</p>
			<div class="form-group col-md-6">
				<label for="week">Week</label>
				<select class="form-control" id="week" name="week">
					<?php
					$j = 1;
					$endDate = strtotime('now');
					$startDate = '2018-02-16';
					for($i = strtotime('Friday', strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i)) {
						echo '<option value="' . date('Y-m-d', $i) . '"';
						if ($week == date('Y-m-d', $i)) {
							echo ' selected';
						}
						echo '>Week ' . $j . ' (Starting ' . date('n/j/Y', $i) . ')</option>';
						$j++;
					}
					?>
				</select>
			</div>
			<table class="table table-sm dataTbl text-center table-striped">
				<thead>
					<tr>
						<th>Student</th>
						<th>CORE</th>
						<th>Course</th>
						<th>Post-Test</th>
						<th>Performance Level</th>
						<th>Growth</th>
						<th>Growth Level</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query = "SELECT * FROM data_scores LEFT JOIN data_enrollment USING (eadms_id) WHERE post IS NOT NULL AND week = '$week'";
					$result = mysqli_query($dbc, $query);
					while ($row = mysqli_fetch_array($result)) {
						?>
						<tr>
							<td><?php echo $row['student_name']; ?></td>
							<td>
								<?php
								if ($row['core_ela'] == 1 && strpos($row['course'], 'ELA')) {
									echo 'CORE';
								}
								if ($row['core_math'] == 1 && strpos($row['course'], 'Math')) {
									echo 'CORE';
								}
								if ($row['core_sci'] == 1 && strpos($row['course'], 'Science')) {
									echo 'CORE';
								}
								?>
							</td>
							<td><?php echo $row['course']; ?></td>
							<td><?php echo round($row['post'], 1); ?></td>
							<?php
							if ($row['post'] < 65) {
								echo '<td class="bg-danger text-white">Minimal</td>';
							}
							else if ($row['post'] < 75) {
								echo '<td class="bg-warning">Basic</td>';
							}
							else if ($row['post'] < 85) {
								echo '<td class="bg-warning">Pass</td>';
							}
							else if ($row['post'] < 95) {
								echo '<td class="bg-success text-white">Proficient</td>';
							}
							else {
								echo '<td class="bg-success text-white">Advanced</td>';
							}
							 ?>
							<td><?php echo $row['growth']; ?></td>
							<?php
							if ($row['growth'] == null) {
								echo '<td></td>';
							}
							else if ($row['growth'] < 0) {
								echo '<td class="bg-danger text-white">Negative</td>';
							}
							else if ($row['growth'] < 10) {
								echo '<td class="bg-warning">Low</td>';
							}
							else {
								echo '<td class="bg-success text-white">High</td>';
							}
							 ?>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
	$('table').DataTable();

	$("#week").on("change", function() {
		var week = $(this).val();
		window.location.href = 'weeklydata_students.php?week=' + week;
	});
});

</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
