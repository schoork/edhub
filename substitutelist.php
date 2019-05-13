<?php

$page_title = 'Substitute List';
$page_access = 'Admin Dept Head Superintendent';
include('header.php');

//include other scripts needed here
echo '<script src="js/substitute_scripts.js"></script>';

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
				Substitute List
			</h1>
			<p class="lead">
				From this page you manage subsitutes.
			</p>
			<p>
				<a class="btn btn-primary" href="addsub.php">Add Sub</a>
				<a href="#!" role="button" class="btn btn-outline-primary btnInactive">View Inactive Subs</a>
				<a href="#!" role="button" class="btn btn-outline-primary btnInactive" style="display: none">Hide Inactive Subs</a>
			</p>
      <table class="table table-hover mt-4">
        <thead>
          <tr>
            <th>Name</th>
            <th>Number</th>
            <th>Email</th>
            <th>Code</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT name, number, email, code, status FROM sub_list ORDER BY name";
          $result = mysqli_query($dbc, $query);
					echo $query;
          while ($row = mysqli_fetch_array($result)) {
            echo '<tr style="cursor: pointer;" id="teacher-' . $row['staff_id'] . '"';
						if ($row['status'] != 1) {
							echo ' style="display:none" class="hidden-row"';
						}
						echo '>';
						echo '<td>' . $row['name'] . '</td>';
						echo '<td>' . $row['number'] . '</td>';
						echo '<td>' . $row['email'] . '</td>';
						echo '<td>' . $row['code'] . '</td>';
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


//end body
echo '</body>';

//include footer
include('footer.php');
?>
