<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Form';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/addforms_scripts.js"></script>';
echo '<script src="js/forms_scripts.js"></script>';

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

      <?php
			if (!empty($form_type)) {
				?>
				<form method="post" action="addform.php" enctype="multipart/form-data">
					<input type="hidden" name="user_name" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
					<input type="hidden" name="user_location" value="<?php echo $_SESSION['school']; ?>">
					<?php
					if ($_SESSION['departments'] != '') {
						?>
						<div class="form-group row">
							<label for="employee" class="col-sm-3 col-form-label">Employee</label>
							<div class="col-sm-9">
								<select id="employee" class="form-control" name="employee">
									<?php
									$school = $_SESSION['school'];
									$query = "SELECT username, firstname, lastname FROM staff_list WHERE status = 1 ORDER BY lastname, firstname";
									$result = mysqli_query($dbc, $query);
									while ($row = mysqli_fetch_array($result)) {
										echo '<option value="' . $row['username'] . '"';
										if ($row['username'] == $_SESSION['username']) {
											echo ' selected';
										}
										echo '>' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
									}
									?>
								</select>
								<small class="muted-text text-success required">Required</small>
							</div>
						</div>
						<?php
					}
					else {
						echo '<input type="hidden" name="employee" value="' . $_SESSION['username'] . '">';
					}
					include('forms/' . $form_type . '.php');
					?>
					<div id="alert" class="alert" role="alert">

					</div>
					<button class="btn btn-primary" id="btnSubmit" disabled>Submit</button>
					<a class="btn btn-danger" href="forms.php">Cancel</a>
				</form>
				<?php
			}
			else if (!empty($_POST)) {
				$form_type = $_POST['form_type'];
				include('forms/posts/' . $form_type . '.php');
			}
			else {
                ?>
			    <div class="alert alert-danger">Please submit all new forms in <a href="https://hollandale.schoork.com">Schoork</a>. Thanks!</div>	
                <?php
                /*
                ?>
                
				<div class="alert alert-info">
					Requsitions that are submitted by close of business (COB) on Mondays and Wednesdays are processed the next day (Tuesdays and Thursdays, respectively) by COB. If your requisition has not been fully approved in that timeline, please contact the appropriate department heads and business office.
				</div>
				<div class="form-group">
					<label for="type_select">Type of Form</label>
					<select id="type_select" class="form-control">
						<option disabled selected></option>
						<option value="activity">Activity Request</option>
						<option value="busrequest">Bus Request</option>
						<option value="deposit">Deposit</option>
						<option value="fundraiser">Fundraiser</option>
						<option value="inventory">Inventory Management</option>
						<?php
						if (strpos($_SESSION['departments'], 'federal_programs')) {
							echo '<option value="journal">Journal Entry</option>';
						}
						?>
						<option value="outoftown">Out of Town Travel</option>
						<option value="requisition">Requisition</option>
						<option value="reimbursement">Travel Reimbursement</option>
						<option value="timeoff">Time Off Request</option>
						<?php
						if ($_SESSION['username'] == 'swilliams') {
							echo '<option value="timesheet">Hourly Timesheet & Overtime Request</option>';
						}
						?>
					</select>
				</div>
				<?php
				echo $_SESSION['departments'];
                */
			}
			?>

    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('../footer2.php');
?>
