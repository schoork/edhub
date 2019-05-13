<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Form Review';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/formspage_scripts.js"></script>';

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
        Forms
      </h1>
      <p class="lead">
        In the table below you will see all forms that are associated with you.
      </p>
      <p>
        Click on a form to view, deny, and/or approve it.
      </p>
			<p>
				<a class="btn btn-primary" href="addform.php">Add Form</a>
			</p>
			<?php

			if ($_SESSION['departments'] !== '') {
				$departments = explode(", ", $_SESSION['departments']);
				?>
				<h2>
					Forms for Approval
				</h2>
				<?php

				if ($_SESSION['access'] == 'Admin') {
					$query = "SELECT type, program, employee, lastname, firstname, submit_datetime, sanders, simmons, federal_programs, student_health, athletics, sped, superintendent, food_services, business, payroll, technology, transportation, f.status, access, form_id, po, vendor FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) LEFT JOIN forms_requisition AS fr ON (f.form_id = fr.formId) ORDER BY submit_datetime DESC";
				}
				else {
					$query = "SELECT * FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) LEFT JOIN forms_requisition AS fr ON (f.form_id = fr.formId) WHERE f.status AND (";
					foreach ($departments as $dept) {
						$query .= strtolower(str_replace(" ", "_", $dept)) . " <> 'N/A' OR ";
					}
					$query = substr($query, 0, strlen($query) - 4) . ') ORDER BY submit_datetime DESC';
				}
				$result = mysqli_query($dbc, $query);
				if (mysqli_num_rows($result) > 0) {
					?>
					<p>
						<a class="btn btn-outline-primary" id="viewApproved" href="#!">View/Hide Approved Forms</a>
					</p>
					<table class="table table-hover clickable" id="tblApprove">
						<thead>
							<tr>
								<th>Form ID</th>
								<th>Form Type</th>
								<th>School/Program</th>
								<th>Employee</th>
								<th>Date Submitted</th>
								<th>Time Submitted</th>
								<th>Approval Needed</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include('forms/departments.php');
							$name = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
							while ($row = mysqli_fetch_array($result)) {
								echo '<tr style="cursor: pointer;';
								foreach ($depts as $dept) {
									if ($row[$dept] == $name || $row['status'] == 'Denied' || substr($row['status'], 0, 6) == 'Denied') {
										echo ' display: none;" class="hidden';
										break;
									}
								}
								echo '" id="form-' . $row['form_id'] . '"><td>' . $row['form_id'] . '</td><td>' . $row['type'] . '</td><td>' . $row['program'];
								if ($row['type'] == 'Requisition') {
									echo ' [' . $row['vendor'] . ']';
								}
								echo '</td><td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td><td>' . date_format(date_create($row['submit_datetime']), 'n/j/Y') . '</td><td>' . date_format(date_create($row['submit_datetime']), 'g:i A') . '</td><td>';
								$approval = '';
								if (substr($row['status'], 0, 6) == 'Denied' || $row['status'] == 'Denied') {
									echo 'Denied';
								}
								else {
									foreach ($depts as $dept) {
										if ($row[$dept] == 'Approval Needed') {
											$dept_title = explode("_", $dept);
											$approval .= ucwords(implode(" ", $dept_title)) . '<br/>';
										}
									}
									if ($approval == '' && $row['po'] == '' && $row['type'] == 'Requisition') {
										echo 'PO Needed';
									}
									else {
										echo $approval;
									}
								}
								echo '</td></tr>';
							}
							?>
						</tbody>
					</table>
					<?php
				}
				else {
					echo '<p>You currently have no forms needing your approval.</p>';
				}
			}
			?>
			<h2>
				Forms You Submitted
			</h2>
			<p>
				<a class="btn btn-outline-primary" id="viewDenied" href="#!">View/Hide Denied Forms</a>
			</p>
			<div class="alert alert-info">
				Requsitions that are submitted by close of business (COB) on Mondays and Wednesdays are processed the next day (Tuesdays and Thursdays, respectively) by COB. If your requisition has not been fully approved in that timeline, please contact the appropriate department heads and business office.
			</div>
			<?php
			$user_name = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
			$username = $_SESSION['username'];
			$query = "SELECT * FROM forms As f LEFT JOIN forms_requisition AS fr ON (f.form_id = fr.formId) WHERE employee = '$username' ORDER BY form_id DESC";
			$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-hover" id="tblSubmit">
					<thead>
						<tr>
							<th>Form ID</th>
							<th>Form Type</th>
							<th>School/Program</th>
							<th>Date Submitted</th>
							<th>Time Submitted</th>
							<th>Approval Needed</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr style="cursor: pointer;';
							if (strpos($row['status'], 'Denied') !== false) {
								echo ' display: none;" class="denied"';
							}
							else {
								echo '"';
							}
							echo 'id="form-' . $row['form_id'] . '"><td>' . $row['form_id'] . '</td><td>' . $row['type'] . '</td><td>' . $row['program'];
							if ($row['type'] == 'Requisition') {
								echo ' [' . $row['vendor'] . ']';
							}
							echo '</td><td>' . date_format(date_create($row['submit_datetime']), 'n/j/Y') . '</td><td>' . date_format(date_create($row['submit_datetime']), 'g:i A') . '</td><td>';
							if (strstr($row['status'], 'Denied')) {
								echo $row['status'];
							}
							else if (strstr($row['status'], 'Approved')) {
								if ($row['po'] == '' && $row['type'] == 'Requisition') {
									echo 'PO Needed';
								}
								else {
									echo $row['status'];
								}
							}
							else {
								include_once('forms/departments.php');
								foreach ($depts as $dept) {
									if ($row[$dept] == 'Approval Needed') {
										$dept_title = explode("_", $dept);
										echo ucwords(implode(" ", $dept_title)) . '<br/>';
									}
								}
							}
							echo '</td></tr>';
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else {
				echo '<p>You have not submitted any forms.';
			}
			?>


    </div>
  </div>
</div>

<!--Form Modal-->
<div class="modal fade bd-example-modal-lg" role="dialog" id="formModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Review</h5>
      </div>
      <div class="modal-body">
        <div id="formApprovalDiv">
          <!--will be filled by JQuery and AJAX on selection of the form-->
        </div>
        <form method="post" action="service.php" id="formApproval">
					<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
          <input type="hidden" name="action" value="formApproval">
					<input type="hidden" name="approve_deny" id="approve_deny">
          <input type="hidden" name="formId" id="formId">
					<div id="specificDiv">

					</div>
					<?php
					if ($_SESSION['departments'] != '') {
						?>
						<div class="form-group">
							<label for="denyReason">Reason for Denial</label>
							<textarea class="form-control" name="denyReason" id="denyReason" rows="3"></textarea>
							<small class="muted-text">Use this box only if you are denying the form. This reason will be emailed to the employee.</small>
						</div>
						<p>
							Denying this form will send it back to the employee listed above. If you deny this form, it is best to include a reason for denial. This will be send to the employee.
						</p>
						<p>
							Approving this form will send it to the next supervisor/department head for approval.
						</p>
						<?php
					}
					?>
        </form>
				<div class="alert" role="alert" id="modal-alert">

				</div>
      </div>
      <div class="modal-footer">
        <?php
				if ($_SESSION['departments'] != '') {
					?>
					<button type="button" class="btn btn-danger" id="btnReturn">Deny</button>
        	<button type="button" class="btn btn-success" id="btnApprove">Approve</button>
					<?php if ($_SESSION['access'] == 'Superintendent') { echo '<button type="button" class="btn btn-outline-success" id="btnOveride">Supt Override</button>'; } ?>
					<?php
				}
				?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--Denied Modal-->
<div class="modal fade bd-example-modal-lg" role="dialog" id="deniedModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Review</h5>
      </div>
      <div class="modal-body">
				<form method="post" action="service.php" id="deleteForm">
					<input type="hidden" name="formId" id="delFormId">
					<input type="hidden" name="username" value="<?php echo $username; ?>">
					<input type="hidden" name="action" value="deleteForm">
					<div id="deniedDiv">

					</div>
					<div class="alert alert-danger" role="alert">
						If this form was denied, you will need to submit another form.
					</div>
				</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--Req and PO number-->
<div class="modal fade bd-example-modal-lg" role="dialog" id="poModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Review</h5>
      </div>
      <div class="modal-body">
				<div id="req_div">

				</div>
				<form method="post" action="service.php" id="poForm">
					<input type="hidden" name="formId" id="poFormId">
					<input type="hidden" name="username" value="<?php echo $username; ?>">
					<input type="hidden" name="action" value="addPO">
					<div id="req_form_div">

					</div>
				</form>
      </div>
      <div class="modal-footer">
				<button type="button" class="btn btn-success" id="btnSubmitPO">Submit</button>
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
