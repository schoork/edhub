<?php

$page_title = 'Students';
$page_access = 'All';
include('header.php');

//include other scripts needed here

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
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Students
			</h1>
			<p class="lead">
				Use this spoke of edhub to manage students' information and referrals.
			</p>
		</div>
	</div>
	<div class="list-group">
		<a href="referrals.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Referrals</h5>
			</div>
			<p class="mb-1">View, add, and complete referrals.</p>
			<small class="text-muted">All staff can view this page.</small>
		</a>
		<a href="meetings.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Meetings</h5>
			</div>
			<p class="mb-1">Schedule and RSVP to IEP and other student-based meetings.</p>
			<small class="text-muted">All staff can view this page.</small>
		</a>
		<a href="studenttrackers.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Behavior Trackers</h5>
			</div>
			<p class="mb-1">Track behaviors for students on behavior plans.</p>
			<small class="text-muted">All staff can view this page.</small>
		</a>
		<a href="readingdata.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Reading Data</h5>
			</div>
			<p class="mb-1">Track reading initiative compliance.</p>
			<small class="text-muted">All staff can view this page.</small>
		</a>
		<a href="addsafety.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Safety Incidents</h5>
			</div>
			<p class="mb-1">Add safety incidents.</p>
			<small class="text-muted">All staff can view this page.</small>
		</a>
		<a href="hearings.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Disciplinary Hearings</h5>
			</div>
			<p class="mb-1">Request a disciplinary hearing.</p>
			<small class="text-muted">Only specific staff can view this page.</small>
		</a>
	</div>
</div>

<?php

//include footer
include('footer.php');
?>
