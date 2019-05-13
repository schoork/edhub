<?php

$page_title = 'Classes';
$page_access = 'All';
include('header.php');

//include other scripts needed here

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-classes.php');
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
    <p class="lead">
      Manage and submit lesson plans, weekly assessment data, and inventory
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Classes
			</h1>
			<p class="lead">
				Use this spoke of edhub to manage lesson plans and weekly assessment data.
			</p>
		</div>
	</div>
	<div class="list-group">
		<a href="adddata.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Add Weekly Data</h5>
			</div>
			<p class="mb-1">Add weekly assessment data.</p>
			<small class="text-muted">All staff can access this page.</small>
		</a>
		<a href="weeklydata.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Weekly Data</h5>
			</div>
			<p class="mb-1">View and manage weekly assessment data. View weekly data reports.</p>
			<small class="text-muted">All users can access this page.</small>
		</a>
		<a href="weeklydata_schoollevel.php" class="list-group-item list-group-item-action flex-column align-items-start">
			<div class="d-flex w-100 justify-content-between">
				<h5 class="mb-1">Disaggregated Data</h5>
			</div>
			<p class="mb-1">View weekly assessment data at the school, grade, and class level.</p>
			<small class="text-muted">All users can access this page.</small>
		</a>
	</div>
</div>

<?php

//include footer
include('footer.php');
?>
