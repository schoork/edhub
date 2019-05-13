<?php

$page_title = 'View Student Registration';
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

$student_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
$query = "SELECT * FROM student_registration WHERE student_id = $student_id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
 
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
				<?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']; ?>
			</h1>
      <p>
        <a class="btn btn-primary" href="registered_students.php">Back to Registrations Page</a>
      </p>
			<p>
				<div class="dropdown">
				  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    Print
				  </button>
				  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item" href="registrationprint_allforms.php?id=<?php echo $student_id; ?>">All Forms</a>
						<a class="dropdown-item" href="registrationprint_proofs.php?id=<?php echo $student_id; ?>">Proofs of Residency</a>
				    <a class="dropdown-item" href="registrationprint_studenthealth.php?id=<?php echo $student_id; ?>">Student Health Form</a>
						<a class="dropdown-item" href="registrationprint_mckinneyvento.php?id=<?php echo $student_id; ?>">McKinney-Vento Form</a>
						<a class="dropdown-item" href="registrationprint_consent.php?id=<?php echo $student_id; ?>">Consent Forms</a>
						<a class="dropdown-item" href="registrationprint_dha.php?id=<?php echo $student_id; ?>">DHA Forms</a>
				  </div>
				</div>
			</p>
			<p>
				Grade: <?php echo $row['grade']; ?><br>
				<?php
				$today = date("Y-m-d");
				$diff = date_diff(date_create($row['birthday']), date_create($today));
				?>
				Birthdate: <?php echo makeDateAmerican($row['birthday']) . ' (' . $diff->format('%y') . ')'; ?><br>
				Gender: <?php echo $row['gender']; ?><br>
				Current Address: <?php echo $row['address_curr']; ?><br>
				City, ST: <?php echo $row['city_curr']; ?><br>
				Zipcode: <?php echo $row['zipcode_curr']; ?>
			</p>
			<h6>
				Parent(s)
			</h6>
			<table>
				<?php
				$query = "SELECT parent_rel, parent_name, parent_phone1, parent_phone2, parent_email FROM registration_parents WHERE student_id = $student_id";
				$result = mysqli_query($dbc, $query);
				while ($line = mysqli_fetch_array($result)) {
					echo '<tr>';
					echo '<td>' . $line['parent_name'] . ' (' . $line['parent_rel'] . ')</td>';
					echo '<td>' . $line['parent_phone1'] . '</td>';
					echo '<td>' . $line['parent_phone2'] . '</td>';
					echo '<td>' . $line['parent_email'] . '</td>';
					echo '</tr>';
				}
				?>
			</table>
			<h6 class="mt-3">
				Emergency Contact(s)
			</h6>
			<table>
				<?php
				$query = "SELECT contact_name, contact_phone1, contact_phone2 FROM registration_contacts WHERE student_id = $student_id";
				$result = mysqli_query($dbc, $query);
				while ($line = mysqli_fetch_array($result)) {
					echo '<tr>';
					echo '<td>' . $line['contact_name'] . '</td>';
					echo '<td>' . $line['contact_phone1'] . '</td>';
					echo '<td>' . $line['contact_phone2'] . '</td>';
					echo '</tr>';
				}
				?>
			</table>
    </div>
  </div>
</div>


<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
