<?php

$page_title = 'Registered Students';
$page_access = 'Superintendent Admin Dept Head Designee Principal';
include('header.php');

//include other scripts needed here
echo '<script src="js/registeredstudents_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Students
		</h1>
    <p class="lead">
      Manage students
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Registered Students
			</h1>
			<p class="lead">
				Use this page to manage registrations and print registration documents.
			</p>
      <p>
        Click on a student to print documents and approve or deny the registration.
      </p>
      <p>
        <a class="btn btn-primary" href="!#" id="viewHidden">View/Hide Approved Registrations</a>
      </p>
      <p>
				<div class="dropdown">
				  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    Print for All
				  </button>
				  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item" href="registrationprint_allforms.php?id=ALL">All Forms</a>
						<a class="dropdown-item" href="registrationprint_enrollment.php?id=ALL">Enrollment Form</a>
						<a class="dropdown-item" href="registrationprint_proofs.php?id=ALL">Proofs of Residency</a>
				    <a class="dropdown-item" href="registrationprint_studenthealth.php?id=ALL">Student Health Form</a>
						<a class="dropdown-item" href="registrationprint_mckinneyvento.php?id=ALL">McKinney-Vento Form</a>
						<a class="dropdown-item" href="registrationprint_consent.php?id=ALL">Consent Forms</a>
						<a class="dropdown-item" href="registrationprint_dha.php?id=ALL">DHA Forms</a>
				  </div>
				</div>
			</p>
      <table class="table table-hover dataTbl">
        <thead>
          <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Grade Level</th>
            <th>IEP</th>
            <th>Expelled</th>
            <th>McKinney-Vento</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT firstname, lastname, student_id, grade, iep, expelled, residence, reside_with, reside_stable, status FROM student_registration WHERE status <> 'Denied' ORDER BY student_id";
          $result = mysqli_query($dbc, $query);
          while ($row = mysqli_fetch_array($result)) {
            echo '<tr id="student-' . $row['student_id'] . '"';
            if ($row['status'] == 'Approved') {
              echo ' style="display: none;" class="hidden_row"';
            }
            echo '>';
            echo '<td>' . $row['student_id'] . '</td>';
            echo '<td>' . $row['lastname'] . ', ' . $row['firstname'] . '</td>';
            echo '<td>' . $row['grade'] . '</td>';
            echo '<td>';
            if ($row['iep'] == 'Yes') {
              echo 'IEP';
            }
            echo '</td>';
            echo '<td>';
            if ($row['expelled'] == 'Yes') {
              echo 'Possible';
            }
            echo '</td>';
            echo '<td>' . $row['mckinney_vento'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
