<?php

$page_title = 'Teachers';
$page_access = 'All';
include('header.php');

//include other scripts needed here

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Teachers
		</h1>
	    <p class="lead">
	    	Manage and observe teachers
	    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Teachers
			</h1>
			<p class="lead">
				Use this spoke of edhub to manage teachers' information and submit and manage forms.
			</p>
		</div>
	</div>
	<div class="list-group">
		<a href="teacherslist.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Teachers List</h5>
			</div>
			<p class="mb-1">Add and manage teachers' information.</p>
			<small class="text-muted">All staff can access this page. Only Admin can edit information.</small>
		</a>
		<a href="acctmemos.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Accountability Memos</h5>
			</div>
			<p class="mb-1">Add and manage accountability memos for teachers.</p>
			<small class="text-muted">Only Principals, Department Heads, Admin, and the Superintendent can access this page.</small>
		</a>
		<a href="observations.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Observations</h5>
			</div>
			<p class="mb-1">Add and view classroom observations for teachers.</p>
			<small class="text-muted">All staff can access this page.</small>
		</a>
		<a href="sublist.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Sub List</h5>
			</div>
			<p class="mb-1">View and manage substitute assignments.</p>
			<small class="text-muted">Only Principals and Designees can access this page.</small>
		</a>
		<a href="teacherattendance.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Teacher Attendance</h5>
			</div>
			<p class="mb-1">View teacher attendance reports.</p>
			<small class="text-muted">All users can access this page.</small>
		</a>
	</div>
</div>

<?php

//include footer
include('footer.php');
?>
