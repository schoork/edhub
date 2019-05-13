<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Home';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/index_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-main.php');
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			edhub
		</h1>
    <p class="lead">
      Let us be at the center of what you do
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Navigating edhub
			</h1>
			<p>
				Your edhub is divided into 3 main sections (spokes): students, teachers, and classes. Click on one of these to go to that spoke of edhub. Click on the edhub logo at the top of any page to come back to this page.
			</p>
		</div>
	</div>
	<div class="list-group">
		<a href="students.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Students</h5>
			</div>
			<p class="mb-1">Manage students and submit and manage referrals.</p>
			<small class="text-muted">Some sections are only accessible to principals.</small>
		</a>
		<a href="teachers.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Teachers</h5>
			</div>
			<p class="mb-1">View and manage teacher information and access level.</p>
			<small class="text-muted">Only Admin Access Level users can edit teachers' information.</small>
		</a>
		<a href="classes.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Classes</h5>
			</div>
			<p class="mb-1">View and manage weekly assessment data.</p>
			<small class="text-muted">All sections accessible to all users.</small>
		</a>
		<a href="district.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">District</h5>
			</div>
			<p class="mb-1">Add, view, and manage forms, inventory, and district reports.</p>
			<small class="text-muted">Most sections accessible to all users.</small>
		</a>
	</div>
</div>

<input type="hidden" id="location_stored" value="<?php echo $_SESSION['school']; ?>">
<!--Unknown location modal-->
<div class="modal fade" id="formModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Personal Information</h5>
      </div>
      <div class="modal-body">
        <div id="formApprovalDiv">
          <!--will be filled by JQuery and AJAX on selection of the form-->
        </div>
        <form method="post" action="service.php" id="formLocationChange">
					<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
					<input type="hidden" name="name" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
          <input type="hidden" name="action" value="locationChange">
					<p>
						Username: <?php echo $_SESSION['username']; ?><br/>
						First Name: <?php echo $_SESSION['firstname']; ?><br/>
						Last Name: <?php echo $_SESSION['lastname']; ?>
					</p>
					<div class="form-group">
						<label for="location">Location</label>
						<select class="form-control" id="location" name="location">
							<option value="Unknown"></option>
							<option value="Sanders">Sanders</option>
							<option value="Simmons">Simmons</option>
							<option value="District Office">District Office</option>
						</select>
						<small class="muted-text">Choose your primary location</small>
					</div>
					<div class="form-group">
						<label for="access">Access</label>
						<select class="form-control" id="access" name="access">
							<option value="Teacher">Teacher</option>
							<option value="Principal">Principal</option>
							<option value="Dept Head">Department Head</option>
							<option value="Superintendent">Superintendent</option>
						</select>
						<small class="muted-text">You may request a different access level, however this must be approved by a site admin</small>
					</div>
        </form>
				<div class="alert" role="alert" id="alert">

				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnSave">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
