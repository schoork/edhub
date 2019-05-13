<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = strtolower(mysqli_real_escape_string($dbc, trim($_GET['type'])));
$formId = mysqli_real_escape_string($dbc, trim($_GET['formId']));

$page_title = 'Form Review';
$page_access = 'Dept Head Principal Superintendent Admin';
include('header.php');

//include other scripts needed here
echo '<script src="js/formspage_scripts.js"></script>';

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
        Form Review
      </h1>
      <form method="post" action="service.php" id="formApproval">
        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
        <input type="hidden" name="action" value="formApproval">
        <input type="hidden" name="approve_deny" id="approve_deny">
        <input type="hidden" name="formId" id="formId" value="<?php echo $formId; ?>">
        <div id="specificDiv">
          <?php include('forms/approve/' . $form_type . '.php'); ?>
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
      <button type="button" class="btn btn-danger" id="btnReturn">Deny</button>
      <button type="button" class="btn btn-success" id="btnApprove">Approve</button>
      <?php if ($_SESSION['access'] == 'Superintendent') { echo '<button type="button" class="btn btn-outline-success" id="btnOveride">Supt Override</button>'; } ?>
      <a class="btn btn-secondary" data-dismiss="modal" href="forms.php">Close</a>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
