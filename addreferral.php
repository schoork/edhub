<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Referral';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';

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
				Add Referral
			</h1>
			<p class="lead">
				Use this page to report a behavior incident.
			</p>
			<p>
				<a class="btn btn-primary" href="referrals.php">Referrals</a>
			</p>
            <div class="alert alert-danger">Edhub services are moving to Schoork. Please use <a href="https://hollandale.schoork.com">Schoork</a> to submit referrals.
		</div>
	</div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
