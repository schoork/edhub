<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Weekly Data';
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

$courses = array(
	'3rd Grade ELA' => 16.3,
	'3rd Grade Math' => 12.1,
	'4th Grade ELA' => 16.3,
	'4th Grade Math' => 12.1,
	'5th Grade ELA' => 28.9,
	'5th Grade Math' => 26.2,
	'5th Grade Science' => 72.2,
	'6th Grade ELA' => 39.7,
	'6th Grade Math' => 23.5,
	'7th Grade ELA' => 26.7,
	'7th Grade Math' => 31.4,
	'8th Grade ELA' => 12.4,
	'8th Grade Math' => 39.3,
	'8th Grade Science' => 55.6,
	'Algebra I' => 59.3,
	'Biology I' => 55.6,
	'English II' => 39.2,
	'US History' => 42.8
);
 
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
				Weekly Data by Course
			</h1>
			<p class="lead">
				Use this page to view and print weekly data reports.
			</p>
			<p>
				<a class="btn btn-secondary" href="weeklydata_students.php">Data By Student</a>
				<a class="btn btn-secondary" href="weeklydata_core.php">CORE Analysis</a>
				<a class="btn btn-secondary" href="upload_data.php">Upload Weekly Data</a>
			</p>
			<p>
				<em>Note: While the scores listed for pre-test and post-test are the average of all students who took each test, growth is calculated by taking the average of growth of the students who took both the pre-test and post-test.</em>
			</p>
			<table class="table table-sm table-striped dataTbl text-center">
				<thead>
					<tr>
						<th>Course</th>
						<th>Week of</th>
						<th>Pre-Test</th>
						<th>Post-Test</th>
						<th>Growth</th>
						<th>Proficiency</th>
						<th>Target</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query = "SELECT course, week, avg(pre) AS pre, avg(post) AS post, avg(growth) AS growth, sum(CASE WHEN post >= 85 THEN 1 ELSE 0 END) AS prof, sum(CASE WHEN post >= 0 THEN 1 ELSE 0 END) As total FROM data_scores GROUP BY course, week";
					$result = mysqli_query($dbc, $query);
					while ($row = mysqli_fetch_array($result)) {
						?>
						<tr>
							<td><?php echo $row['course']; ?></td>
							<td><?php echo makeDateAmerican($row['week']); ?></td>
							<td><?php echo round($row['pre'], 1); ?>%</td>
							<td><?php echo round($row['post'], 1); ?>%</td>
							<?php
							if ($row['growth'] < 0) {
								echo '<td class="bg-danger text-white">' . round($row['growth'], 1) . '</td>';
							}
							else if ($row['growth'] < 10) {
								echo '<td class="bg-warning">' . round($row['growth'], 1) . '</td>';
							}
							else {
								echo '<td class="bg-success text-white">' . round($row['growth'], 1) . '</td>';
							}
							$target = $courses[$row['course']];
							$prof = round($row['prof'] / $row['total'] * 100, 1);
 							if ($prof < $target - 10) {
 								echo '<td class="bg-danger text-white">' . $prof . '%</td>';
 							}
 							else if ($prof < $target) {
 								echo '<td class="bg-warning">' . $prof . '%</td>';
 							}
 							else {
 								echo '<td class="bg-success text-white">' . $prof . '%</td>';
 							}
 							 ?>
							<td><?php echo $target; ?>%</td>
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
	$('table').DataTable(
		{order: [[1, 'dsc'], [0, 'asc']]}
	);
});
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
