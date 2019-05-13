<?php

$page_title = 'District';
$page_access = 'All';
include('header.php');

//include other scripts needed here

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
				District
			</h1>
			<p class="lead">
				Use this spoke of edhub to add, view, and manage forms, inventory, and reports.
			</p>
		</div>
	</div>
	<div class="list-group">
		<a href="forms.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Forms</h5>
			</div>
			<p class="mb-1">Submit and manage forms.</p>
			<small class="text-muted">All staff can access this page.</small>
		</a>
		<a href="weeklydeptreport.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Department Reports</h5>
			</div>
			<p class="mb-1">Add and view weekly department reports</p>
			<small class="text-muted">Only Principals and Department Heads can access this page.</small>
		</a>
		<a href="financials.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Reports</h5>
			</div>
			<p class="mb-1">View expenditure and forms reports by category.</p>
			<small class="text-muted">Only Principals and Department Heads can access this page.</small>
		</a>
		<a href="inventory.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Inventory</h5>
			</div>
			<p class="mb-1">Submit and manage inventory, monthly.</p>
			<small class="text-muted">All staff can access this page.</small>
		</a>
		<a href="meeting.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Meeting Sign In</h5>
			</div>
			<p class="mb-1">Sign into a meeting or create one.</p>
			<small class="text-muted">All staff can access this page.</small>
		</a>
	</div>
</div>

<?php

//include footer
include('footer.php');
?>
