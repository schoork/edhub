<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Weekly Data (CORE)';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-classes.php');

$courses = array(
	array('name' => '3rd Grade Math', 'target' => 12.1, 'subject' => 'math'),
	array('name' => '4th Grade ELA', 'target' => 16.3, 'subject' => 'ela'),
	array('name' => '4th Grade Math', 'target' => 12.1, 'subject' => 'math'),
	array('name' => '5th Grade ELA', 'target' => 28.9, 'subject' => 'ela'),
	array('name' => '5th Grade Math', 'target' => 26.2, 'subject' => 'math'),
	array('name' => '5th Grade Science', 'target' => 72.2, 'subject' => 'sci'),
	array('name' => '6th Grade ELA', 'target' => 39.7, 'subject' => 'ela'),
	array('name' => '6th Grade Math', 'target' => 23.5, 'subject' => 'math')
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
				Weekly Data (CORE Analysis)
			</h1>
			<p class="lead">
				Use this page to view and print weekly data reports.
			</p>
			<p>
				<a class="btn btn-secondary" href="weeklydata_courses.php">Data By Course</a>
				<a class="btn btn-secondary" href="weeklydata_students.php">Data By Student</a>
				<a class="btn btn-secondary" href="upload_data.php">Upload Weekly Data</a>
			</p>
			<p>
				<em>Note: Students are classified as either belonging to a CORE teacher or a SCHOOL teacher. Thus, School aggregates listed below only include students not assigned to CORE teachers.</em>
			</p>
			<table class="table table-sm table-striped dataTbl text-center">
				<thead>
					<tr>
						<th>Course</th>
						<th>School Growth</th>
						<th>CORE Growth</th>
						<th>School Proficiency</th>
						<th>CORE Proficiency</th>
						<th>Overall Proficiency</th>
						<th>Target</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($courses as $course) {
						$growth = array('core_sum' => 0, 'core_total' => 0, 'school_sum' => 0, 'school_total' => 0);
						$prof = array('core_sum' => 0, 'core_total' => 0, 'school_sum' => 0, 'school_total' => 0, 'overall_sum' => 0, 'overall_total' => 0);
						$name = $course['name'];
						$subject = $course['subject'];
						$target = $course['target'];
						$query = "SELECT growth, post, core_math, core_ela, core_sci FROM data_scores LEFT JOIN data_enrollment USING (eadms_id) WHERE course = '$name'";
						$result = mysqli_query($dbc, $query);
						if (mysqli_num_rows($result) > 0) {
							while ($row = mysqli_fetch_array($result)) {
								if ($row['growth'] !== null) {
									if ($row["core_$subject"] == 1) {
										$growth['core_sum'] = $growth['core_sum'] + $row['growth'];
										$growth['core_total'] = $growth['core_total'] + 1;
									}
									else {
										$growth['school_sum'] = $growth['school_sum'] + $row['growth'];
										$growth['school_total'] = $growth['school_total'] + 1;
									}
								}
								if ($row['post'] !== null) {
									if ($row["core_$subject"] == 1) {
										if ($row['post'] >= 85) {
											$prof['core_sum'] = $prof['core_sum'] + $row['post'];
											$prof['overall_sum'] = $prof['overall_sum'] + $row['post'];
										}
										$prof['core_total'] = $prof['core_total'] + 1;
									}
									else {
										if ($row['post'] >= 85) {
											$prof['school_sum'] = $prof['school_sum'] + $row['post'];
											$prof['overall_sum'] = $prof['overall_sum'] + $row['post'];
										}
										$prof['school_total'] = $prof['school_total'] + 1;
									}
									$prof['overall_total'] = $prof['overall_total'] + 1;
								}
							}
							if ($growth['school_total'] > 0) {
								$school_growth = round($growth['school_sum'] / $growth['school_total'], 1);
							}
							if ($growth['core_total'] > 0) {
								$core_growth = round($growth['core_sum'] / $growth['core_total'], 1);
							}
							if ($prof['school_total'] > 0) {
								$school_prof = round($prof['school_sum'] / $prof['school_total'], 1);
							}
							if ($prof['core_total'] > 0) {
								$core_prof = round($prof['core_sum'] / $prof['core_total'], 1);
							}
							if ($prof['overall_total'] > 0) {
								$overall_prof = round($prof['overall_sum'] / $prof['overall_total'], 1);
							}
							?>
							<tr>
								<td><?php echo $name; ?></td>
								<?php
								if ($school_growth < 0) {
									echo '<td class="bg-danger text-white">' . $school_growth . '</td>';
								}
								else if ($school_growth < 10) {
									echo '<td class="bg-warning">' . $school_growth . '</td>';
								}
								else {
									echo '<td class="bg-success text-white">' . $school_growth . '</td>';
								}
								if ($core_growth < 0) {
									echo '<td class="bg-danger text-white">' . $core_growth . '</td>';
								}
								else if ($core_growth < 10) {
									echo '<td class="bg-warning">' . $core_growth . '</td>';
								}
								else {
									echo '<td class="bg-success text-white">' . $core_growth . '</td>';
								}
								?>
								<td><?php echo $school_prof; ?>%</td>
								<td><?php echo $core_prof; ?>%</td>
								<?php
								if ($overall_prof < $target - 10) {
									echo '<td class="bg-danger text-white">' . $overall_prof . '%</td>';
								}
								else if ($overall_prof < $target) {
									echo '<td class="bg-warning">' . $overall_prof . '%</td>';
								}
								else if ($overall_prof >= $target) {
									echo '<td class="bg-success text-white">' . $overall_prof . '%</td>';
								}
								else {
									echo '<td>' . $overall_prof . '%</td>';
								}
								?>
								<td><?php echo $target; ?>%</td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
