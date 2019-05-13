<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Edit Form';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/addForms_scripts.js"></script>';
echo '<script src="js/forms_scripts.js"></script>';

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
      <?php 
      $formId = mysqli_real_escape_string($dbc, trim($_GET['formId']));
      $form_type = mysqli_real_escape_string($dbc, trim($_GET['formType']));
      include('forms/' . $form_type . '.php'); 
      
			?>
    
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('../footer2.php');
?>