<?php

$page_title = 'Teachers List';
$page_access = 'All';
$page_type = 'Teachers';
include('header.php');

//include other scripts needed here
echo '<script src="js/teacherslist_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

?>

<?php

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Teachers List
			</h1>
			<p class="lead">
				From this page you can see all teachers and staff members.
			</p>
      <p>
        Only Admin Access Level staff are authorized to edit teacher's information. Click a teacher's name to edit that teacher's information.
      </p>
			<p>
				<a href="#!" role="button" class="btn btn-outline-primary btnInactive">
					View Inactive Teachers
				</a>
				<a href="#!" role="button" class="btn btn-outline-primary btnInactive" style="display: none">
					Hide Inactive Teachers
				</a>
			</p>
      <table class="table table-responsive table-hover mt-4">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>School</th>
            <th>Access</th>
						<th>Department(s)</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT staff_id, username, firstname, lastname, access, school, departments, status FROM staff_list ORDER BY lastname, firstname";
          $result = mysqli_query($dbc, $query);
          while ($row = mysqli_fetch_array($result)) {
            echo '<tr style="cursor: pointer;" id="teacher-' . $row['staff_id'] . '"';
						if ($row['status'] != 1) {
							echo ' style="display:none" class="hidden-row"';
						}
						echo '><td>' . $row['lastname'] . ', ' . $row['firstname'] . '</td><td><a href="mailto:' . $row['username'] . '@hollandalesd.org" target="_blank">' . $row['username'] . '@hollandalesd.org</a></td><td>' . $row['school'] . '</td><td>' . $row['access'] . '</td><td>' . ucwords(str_replace("_", " ", $row['departments'])) . '</td><td>';
            if ($row['status'] == 1) {
							echo 'Active';
						}
            else {
              echo 'Inactive';
            }
            echo '</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if ($_SESSION['access'] == 'Admin') {
  ?>
  <!--Edit Teacher Info modal-->
  <div class="modal fade" id="formModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="teacher_name"></h5>
        </div>
        <div class="modal-body">
          <form method="post" action="service.php" id="formLocationChange">
            <input type="hidden" name="staff_id" id="staff_id">
            <input type="hidden" name="action" value="teacherChange">
            <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
            <input type="hidden" name="user" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
            <div class="form-group">
              <label for="location">Location</label>
              <select class="form-control" id="location" name="location">
                <option value="Unknown"></option>
                <option value="Sanders">Sanders</option>
                <option value="Simmons">Simmons</option>
                <option value="District Office">District Office</option>
              </select>
            </div>
            <div class="form-group">
              <label for="access">Access</label>
              <select class="form-control" id="access" name="access">
                <option value="Teacher">Teacher</option>
								<option value="Designee">Designee</option>
                <option value="Principal">Principal</option>
                <option value="Dept Head">Department Head</option>
                <option value="Superintendent">Superintendent</option>
                <option value="Admin">Admin</option>
              </select>
              <small class="muted-text">The Admin Access Level should only be given to S. Williams and R. Brockman</small>
            </div>
						<div class="form-group">
              <label for="departments">Department(s)</label>
              <select multiple class="form-control" id="departments" name="departments[]">
								<option value="accounts_payable">Accounts Payable</option>
                <option value="asset">Asset Management</option>
								<option value="athletics">Athletics</option>
                <option value="business">Business</option>
                <option value="federal_programs">Federal Programs</option>
								<option value="food_services">Food Services</option>
                <option value="maintenance">Maintenance</option>
								<option value="payroll">Payroll</option>
								<option value="sanders">Sanders (Principal)</option>
								<option value="simmons">Simmons (Principal)</option>
                <option value="sped">SPED</option>
								<option value="student_health">Student Health</option>
								<option value="technology">Technology</option>
                <option value="transportation">Transportation</option>
              </select>
            </div>
          </form>
          <div class="alert" role="alert" id="alert">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btnSave">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php
}


mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
