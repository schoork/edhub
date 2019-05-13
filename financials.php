<?php

$page_title = 'District Reports';
$page_access = 'Principal Dept Head Admin Superintendent Designee';
include('header.php');

//include other scripts needed here


require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
			Teachers
		</h1>
    <p class="lead">
      Manage teachers and forms
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
            <h1>
                District Reports
            </h1>
            <p class="lead">
                Use this page to view reports for expenditures, forms submitted, and more.
            </p>
            <div class="list-group">
        		<a href="expenditures.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">District Expenditures</h5>
        			</div>
        			<p class="mb-1">District expenditures organized by Fund and Purchasing Code.</p>
        		</a>
        		<a href="expenditures_sanders.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Sanders Expenditures</h5>
        			</div>
        			<p class="mb-1">Sanders's expenditures organized by Fund and Purchasing Code.</p>
        		</a>
        		<a href="expenditures_simmons.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Simmons Expenditures</h5>
        			</div>
        			<p class="mb-1">Simmons's expenditures organized by Fund and Purchasing Code.</p>
        		</a>
        		<a href="expenditures_dha.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">DHA Expenditures</h5>
        			</div>
        			<p class="mb-1">DHA expenditures organized by Fund and Purchasing Code.</p>
        		</a>
        	</div>
        	<br/>
        	<div class="list-group">
						<a href="forms_act.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Activity Requests</h5>
        			</div>
        			<p class="mb-1">View all approved activity requests.</p>
        		</a>
        		<a href="forms_bus.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Bus Requests</h5>
        			</div>
        			<p class="mb-1">View all approved bus requests.</p>
        		</a>
						<a href="forms_dep.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Deposits</h5>
        			</div>
        			<p class="mb-1">View all approved deposits.</p>
        		</a>
						<a href="forms_fund.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Fundraisers</h5>
        			</div>
        			<p class="mb-1">View all requested fundraisers.</p>
        		</a>
        		<a href="forms_out.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Out of Town Travel Requests</h5>
        			</div>
        			<p class="mb-1">View all approved out of town travel requests.</p>
        		</a>
        		<a href="forms_reimb.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Reimbursements</h5>
        			</div>
        			<p class="mb-1">View all approved reimbursements.</p>
        		</a>
        		<a href="forms_reqs.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Requisitions</h5>
        			</div>
        			<p class="mb-1">View all requistions which have been approved and assigned a PO number.</p>
        		</a>
        		<a href="forms_timeoff.php" class="list-group-item list-group-item-action flex-column align-items-start">
        			<div class="d-flex w-100 justify-content-between">
        				<h5 class="mb-1">Time Off Requests</h5>
        			</div>
        			<p class="mb-1">View all approved time off requests.</p>
        		</a>
        	</div>

        </div>
    </div>
</div>

<?php

//include footer
include('footer.php');
?>
