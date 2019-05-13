<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Meeting Sign In';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/meeting_scripts.js"></script>';

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
			District
		</h1>
    <p class="lead">
      Manage forms and reports
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Meeting Sign In
			</h1>
			<p class="lead">
				Use this page to sign into a meeting or create one.
			</p>
			<h2>
				Sign In
			</h2>
			<form method="post" action="service.php" id="signInForm" class="form-inline">
				<input type="hidden" name="action" value="meetingSignIn">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">

				<label class="sr-only" for="meeting_id">Meeting ID</label>
  			<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="meeting_id" name="meeting_id" placeholder="Meeting ID">

        <a class="btn btn-primary" href="#!" id="submitSignIn">Submit</a>
      </form>

			<!--Create a meeting-->
			<?php
			if (in_array($_SESSION['access'], array('Dept Head', 'Admin', 'Superintendent', 'Principal'))) {
				?>
				<h2 class="mt-3">
					Create Meeting
				</h2>
				<p>
					Participants will have 30 minutes to sign into your meeting after you create it. After this time it will be locked.
				</p>
				<form method="post" action="service.php" id="createMeetingForm" class="form-inline">
					<input type="hidden" name="action" value="createMeeting">
					<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">

					<label class="sr-only" for="meeting_name">Meeting Name</label>
	  			<input type="text" class="form-control mb-3 mr-sm-3 mb-sm-0" id="meeting_name" name="meeting_name" placeholder="Meeting Name" maxlength="30">

					<label class="mr-sm-2" for="meeting_type">Meeting Type</label>
				  <select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="meeting_type" id="meeting_type">
						<option disabled selected></option>
						<option value="Department Heads">Department Heads</option>
						<option value="Leadership Team">Leadership Team</option>
						<option value="PLC">PLC</option>
						<option value="Professional Development">Professional Development</option>
				  </select>

	        <a class="btn btn-primary" href="#!" id="submitCreate">Submit</a>
				</form>
				<?php
			}
			?>
			<div id="alert" class="alert mt-3" role="alert">

			</div>
			<div id="participantDiv" style="display: none;" class="mt-3">
				<input type="hidden" id="set_meeting_id">
				<h2>
					Participants
				</h2>
				<p>
					This list will be updated every 10 seconds until 30 minutes after the meeting was created.
				</p>
				<div id="participants">

				</div>
			</div>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('../footer2.php');
?>
