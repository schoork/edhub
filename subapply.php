<?php

$page_title = 'Substitute Application';
$page_access = 'All';

if (isset($_POST['code'])) {
	$code = $_POST['code'];
	setcookie('subcode', $code, time() + (86400), "/"); // 86400 = 1 day
}

include('header_nosignin.php');

//include other scripts needed here
echo '<script src="js/subapply_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-logo.php');

?>

<?php

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_GET['date'])) {
	$date = mysqli_real_escape_string($dbc, $_GET['date']);
}
else {
	$hour = date('G');
	if ($hour > 10) {
		$date = date('Y-m-d');
		$date = date('Y-m-d', strtotime($date. ' + 1 days'));
	}
	else {
		$date = date('Y-m-d');
	}
}

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Substitutes
		</h1>
		<p class="lead">
			Apply for open positions
		</p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<?php
			if (isset($_POST['code']) || isset($_COOKIE['subcode'])) {
				if (isset($_POST['code'])) {
					$code = mysqli_real_escape_string($dbc, trim($_POST['code']));
				}
				else {
					$code = $_COOKIE['subcode'];
				}
				$query = "SELECT name, sub_id FROM sub_list WHERE code = '$code'";
				$result = mysqli_query($dbc, $query);
				if (mysqli_num_rows($result) > 0) {
					$sub = mysqli_fetch_array($result);
					$sub_id = $sub['sub_id'];
					$name = $sub['name'];
					?>
					<div class="alert alert-success">
						You are signed in as <strong><?php echo $name; ?></strong>. If this is not you, click the logout button below.
					</div>
					<p>
						<a class="btn btn-primary" href="<?php echo $_SERVER['PHP_SELF']; ?>">Logout</a>
					</p>
					<h1>
						Vacancy List
					</h1>
					<p class="lead">
						Select all positions for which you would like to sub for the date below. To apply for a different day, change the date.
					</p>
					<form method="post" action="service.php" id="subForm">
						<input type="hidden" name="action" value="applySubs">
						<input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>">
						<div class="form-group row">
							<label for="date" class="col-2 col-form-label">Date</label>
							<div class="col-10">
							  <input class="form-control" type="date" value="<?php echo $date; ?>" id="date" name="date">
							</div>
						</div>
						<h2>
							Sanders Elementary
						</h2>
			      <p>
			        <?php
			        $query = "SELECT staff_id, username, firstname, lastname, fto.type, school, start, end, sub, length FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE school = 'Sanders' AND start <= '$date' AND end >= '$date' AND sub = 'Yes' ORDER BY school, lastname, firstname";
			        $result = mysqli_query($dbc, $query);
							if (mysqli_num_rows($result) > 0) {
				        while ($row = mysqli_fetch_array($result)) {
									echo '<div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input" name="teachers[]" value="' . $row['username'] . '">';
									echo '<strong>' . $row['firstname'] . ' ' . $row['lastname'] . '</strong> (' . $row['length'] . ', ' . makeDateAmerican($row['start']) . ' to ' . makeDateAmerican($row['end']) . ')';
	      					echo '</label></div>';
								}
							}
							else {
								echo '<p>No subs needed.</p>';
							}
			        ?>
						</p>
						<h2>
							Simmons Jr/Sr High
						</h2>
			      <p>
			        <?php
			        $query = "SELECT staff_id, username, firstname, lastname, fto.type, school, start, end, sub, length FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE school = 'Simmons' AND start <= '$date' AND end >= '$date' AND sub = 'Yes' ORDER BY school, lastname, firstname";
			        $result = mysqli_query($dbc, $query);
							if (mysqli_num_rows($result) > 0) {
				        while ($row = mysqli_fetch_array($result)) {
									echo '<div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input" name="teachers[]" value="' . $row['username'] . '">';
									echo '<strong>' . $row['firstname'] . ' ' . $row['lastname'] . '</strong> (' . $row['length'] . ', ' . makeDateAmerican($row['start']) . ' to ' . makeDateAmerican($row['end']) . ')';
	      					echo '</label></div>';
								}
							}
							else {
								echo '<p>No subs needed.</p>';
							}
			        ?>
						</p>
						<div id="alert" class="alert" role="alert">

		        </div>
						<p>
							<a class="btn btn-primary" href="#!" id="btnApply">Apply</a>
						</p>
					</form>
					<?php
				}
				else {
					//code does not match
					?>
					<div class="alert alert-danger">
						This code does not maych any on file. Please sign in again.
					</div>
					<p>
						<a class="btn btn-primary" href="<?php echo $_SERVER['PHP_SELF']; ?>">Sign-in Page</a>
					</p>
					<?php
				}
			}
			else {
				//Code not SEt
				?>
				<h1>
					Login
				</h1>
				<p>
					Use your unique code to log into this page.
				</p>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="date" value="<?php echo $date; ?>">
					<div class="form-group row">
						<div class="col-4">
							<input class="form-control" type="text" id="code" name="code" placeholder="CODE">
						</div>
					</div>
					<p>
						<button type="submit" name="submit" class="btn btn-primary">Login</button>
					</p>
				</form>
				<?php
			} ?>
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
