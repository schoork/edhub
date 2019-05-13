<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Deposits Report';
$page_access = 'Dept Head Admin Principal Superintendent';
include('header.php');

//include other scripts needed here
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';

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
			District Forms
		</h1>
    <p class="lead">
      Add, edit, and manage forms
    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Deposits
      </h1>
      <input type="hidden" id="form_type" value="deposit">
      <p class="lead">
        In the table below you will see all approved deposits.
      </p>
      <p>
        Click on a form to view and/or print it.
      </p>
		<?php

		$query = "SELECT * FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) LEFT JOIN forms_deposits AS dep ON (f.form_id = dep.formId) WHERE f.status NOT LIKE 'Denied%' AND type = 'Deposit' ORDER BY submit_datetime DESC";
		$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-hover clickable dataTbl" id="tblApprove">
					<thead>
						<tr>
							<th></th>
							<th>Form ID</th>
							<th>Employee</th>
							<th>School / Account</th>
							<th>Description</th>
							<th>Revenue Code</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td><a class="btn btn-secondary" href="forms/prints/deposit.php?formId=' . $row['form_id'] .'" target="_blank">View</a></td>';
							echo '<td>' . $row['form_id'] . '</td>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . $row['school'] . ' / ' . $row['account'] . '</td>';
							echo '<td>' . $row['description'] . '</td>';
							echo '<td>' . $row['revenue_code'] . '</td>';
							echo '<td>$' . $row['amount'] . '</td></tr>';
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else {
				echo '<p>No forms of this type have been approved as of yet.</p>';
			}
					?>


    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
