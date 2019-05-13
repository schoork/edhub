<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Substitute';
$page_access = 'Admin Dept Head Superintendent';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';

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
			Teachers
		</h1>
		<p class="lead">
			Manage teachers, complete observations and forms, and view overall trends.
		</p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Add Substitute
      </h1>
      <p class="lead">
        Use this page to a substitute to the sub list.
      </p>
      <form method="post" action="service.php">
        <input type="hidden" name="action" value="addSub">
        <div class="form-group row">
          <label for="name" class="col-sm-3 col-form-label">Name</label>
          <div class="col-sm-9">
            <input type="text" name="name" class="form-control" id="name">
            <small class="muted-text required text-success">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="number" class="col-sm-3 col-form-label">Phone Number</label>
          <div class="col-sm-9">
            <input type="number" name="number" class="form-control" id="number" maxlength="10" placeholder="1234567890">
            <small class="muted-text required text-success">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="number" class="col-sm-3 col-form-label">Email</label>
          <div class="col-sm-9">
            <input type="email" name="email" class="form-control" id="email">
          </div>
        </div>
        <div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" href="#!" id="btnSubmitForm">Submit</a>
        <a class="btn btn-danger" href="weeklydata.php">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
