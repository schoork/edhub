<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'View Referral';
$page_access = 'Principal Admin Superintendent Dept Head';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<script src="js/viewreferral_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

$referral_id = mysqli_real_escape_string($dbc, $_GET['id']);
$query = "SELECT student_id, firstname, lastname, rowid, teacher, time, behavior, description, action, action_date, end_date, length, admin_comments FROM referrals LEFT JOIN student_list USING (student_id) WHERE rowid = $referral_id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

$actions = array(
	'No action needed',
	'Warning',
	'Loss of Privileges',
	'Detention',
	'Parent Contact',
	'Confiscation of Item/Device',
	'Fine',
	'Mandatory Parent Conference',
	'Corporal Punishment',
	'ISS',
	'OSS',
	'OSS pending hearing'
);

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
				View Referral
			</h1>
			<p class="lead">
				Use this page to complete a referral.
			</p>
			<p>
				<a class="btn btn-secondary" href="referrals.php">Referrals</a>
			</p>
			<form method="post" action="service.php">
				<input type="hidden" name="action" value="completeReferral">
				<input type="hidden" name="referral_id" value="<?php echo $referral_id; ?>">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
				<input type="hidden" name="staff" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
				<p>
					Student: <?php echo $row['firstname'] . ' ' . $row['lastname']; ?> <a class="btn btn-secondary btn-sm d-print-none" href="allreferrals.php?id=<?php echo $row['student_id']; ?>">All Referrals</a><br>
					Teacher: <?php echo $row['teacher']; ?><br>
					Date/Time: <?php echo parseDateTime($row['time']); ?><br>
					Behavior: <?php echo $row['behavior']; ?><br>
					Description: <?php echo nl2br($row['description']); ?>
				</p>
				<div class="form-group row">
		        	<label for="admin_action" class="col-sm-3 col-form-label">Action</label>
		          	<div class="col-sm-9">
		            	<select class="form-control" id="admin_action" name="admin_action">
							<option disabled selected></option>
		              		<?php
							foreach ($actions as $act) {
								echo '<option value="' . $act . '"';
								if ($act == $row['action']) {
									echo ' selected';
								}
								echo '>' . $act . '</option>';
							}
		              		?>
		            	</select>
		          	</div>
		        </div>
				<div class="form-group row">
					<label for="start_date" class="col-sm-3 col-form-label">Start Date</label>
					<div class="col-sm-9">
						<input type="date" name="start_date" class="form-control" id="start_date" value="<?php echo $row['action_date']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="end_date" class="col-sm-3 col-form-label">End Date</label>
					<div class="col-sm-9">
						<input type="date" name="end_date" class="form-control" id="end_date" value="<?php echo $row['end_date']; ?>">
						<small class="muted-text">If left blank, defaults to start date. This should be the final date of the action (not the date the student returns).</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="length" class="col-sm-3 col-form-label">Length</label>
					<div class="col-sm-9">
						<input type="number" name="length" class="form-control" id="length" value="<?php echo $row['length']; ?>">
						<small class="muted-text">If left blank, defaults to 1.</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="notes" class="col-sm-3 col-form-label">Notes</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="notes" rows="5" name="notes" maxlength="500"><?php echo $row['admin_comments']; ?></textarea>
					</div>
				</div>
				<div id="alert" class="alert" role="alert">

				</div>
				<a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
				<a class="btn btn-danger" href="referrals.php">Cancel</a>
			</form>
		</div>
	</div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
