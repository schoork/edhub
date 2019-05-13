<?php

$page_title = 'Intervention Dashboard';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
echo '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
echo '<script src="js/intervention_scripts.js"></script>';
echo '<link rel="stylesheet" href="css/intervention_styles.css">';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

?>

<?php

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
<?php
if (isset($_GET['id'])) {
	$student_id = $_GET['id'];
	echo '<input type="hidden" id="student_id" value="' . $student_id . '">';
	$query = "SELECT firstname, lastname, grade, flags, refs, iss, oss, beh_plan, iss_days, oss_days, ela_pl, math_pl FROM student_interventions WHERE student_id = $student_id";
	$result = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($result);
	?>
	<div class="container mt-3 mb-3">
		<div class="row">
			<div class="col 12">
				<h1>
					Intervention Dashboard - <?php echo $row['firstname'] . ' ' . $row['lastname'] . ' (Gr ' . $row['grade'] . ')'; ?>
				</h1>
				<p class="text-danger">
					MTSS Flags: <?php echo $row['flags']; ?>
				</p>
				<div class="row">
					<div class="col-sm-6">
						<h3>
							Absences
						</h3>
						<div class="ct-chart ct-golden-section" id="chart-att"></div>
					</div>
					<div class="col-sm-6">
						<h3>
							Behavior
						</h3>
						<p>
							Number of Referrals: <?php echo $row['refs']; ?><br>
							Number of ISS: <?php echo $row['iss'] . ' ('. $row['iss_days'] . ' days)'; ?><br>
							Number of Suspensions: <?php echo $row['oss'] . ' ('. $row['oss_days'] . ' days)'; ?><br>
							Behavior Plan: <?php echo '<a href="' . $row['beh_plan'] . '" download>' . substr($row['beh_plan'], 6) . '</a>'; ?>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<h3>
							ELA Scores
						</h3>
						<div class="ct-chart ct-golden-section" id="chart-ela"></div>
					</div>
					<div class="col-sm-6">
						<h3>
							Math Scores
						</h3>
						<div class="ct-chart ct-golden-section" id="chart-math"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<h3>
							State Tests
						</h3>
						<div class="ct-chart ct-golden-section" id="chart-state"></div>
						<p class="text-center">
							<span class="text-primary">Math</span>
							<span class="text-success">ELA</span>
						</p>
					</div>
					<div class="col-sm-6">
						<table class="table table-sm table-striped">
							<thead>
								<tr>
									<th>Numeric</th>
									<th>2014 PL</th>
									<th>2015 - 2017 PL</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>8</td>
									<td>Advanced</td>
									<td>Advanced</td>
								</tr>
								<tr>
									<td>7</td>
									<td>Proficient</td>
									<td>Proficient</td>
								</tr>
								<tr>
									<td>6</td>
									<td></td>
									<td>High Pass</td>
								</tr>
								<tr>
									<td>5</td>
									<td>Pass</td>
									<td>Low Pass</td>
								</tr>
								<tr>
									<td>4</td>
									<td></td>
									<td>High Basic</td>
								</tr>
								<tr>
									<td>3</td>
									<td>Basic</td>
									<td>Low Basic</td>
								</tr>
								<tr>
									<td>2</td>
									<td></td>
									<td>High Minimal</td>
								</tr>
								<tr>
									<td>1</td>
									<td>Minimal</td>
									<td>Low Minimal</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<h3>
							Interventions
						</h3>
						<table class="table table-sm table-striped">
							<thead>
								<tr>
									<th>Date</th>
									<th>Staff Member</th>
									<th>Intervention</th>
									<th>Notes</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$query = "SELECT date, intervention, notes, firstname, lastname FROM interventions LEFT JOIN staff_list USING (username) WHERE student_id = $student_id ORDER BY date DESC";
								$result = mysqli_query($dbc, $query);
								if (mysqli_num_rows($result) > 0) {
									while ($line = mysqli_fetch_array($result)) {
										echo '<tr>';
										echo '<td>' . makeDateAmerican($line['date']) . '</td>';
										echo '<td>' . $line['firstname'] . ' ' . $line['lastname'] . '</td>';
										echo '<td>' . $line['intervention'] . '</td>';
										echo '<td>' . $line['notes'] . '</td>';
										echo '</tr>';
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<p>
					<a class="btn btn-primary" href="edit_mtss.php?id=<?php echo $student_id; ?>">Edit/Add Data</a>
					<a class="btn btn-success" href="add_intervention.php">Add Intervention</a>
				</p>
			</div>
		</div>
	</div>
	<?php
}
else {
	?>
	<div class="container mt-3 mb-3">
		<div class="row">
			<div class="col 12">
				<h1>
					Intervention Dashboard
				</h1>
				<p>
					Select a student to see his/her dashboard.
				</p>
				<div class="form-group">
					<label for="course_search">Select a student</label>
					<select id="course_search" class="form-control">
						<option disabled selected></option>
						<?php
						$query = "SELECT student_id, firstname, lastname FROM student_interventions ORDER BY lastname, firstname";
						$result = mysqli_query($dbc, $query);
						while ($row = mysqli_fetch_array($result)) {
							echo '<option value="' . $row['student_id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<?php
}


mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
