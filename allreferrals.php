<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Print Referral';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<link href="css/printreferral_styles.css" rel="stylesheet">';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

$sid = mysqli_real_escape_string($dbc, $_GET['id']);
$query = "SELECT firstname, lastname FROM student_list WHERE student_id = $sid";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4 d-print-none">
	<div class="container">
		<h1>
			Students
		</h1>
	    <p class="lead">
	      	Manage students and interventions
	    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
			<h3>
				<?php echo $row['firstname'] . ' ' . $row['lastname']; ?> Referral List
			</h3>
			<p>
				<a class="btn btn-secondary d-print-none" href="referrals.php">Referrals</a>
				<a class="btn btn-secondary d-print-none" href="allreferrals_unassigned.php?id=<?php echo $sid; ?>">Unassigned Referrals for Student</a>
			</p>
		</div>
	</div>
	<?php
	$query = "SELECT * FROM referrals WHERE student_id = $sid ORDER BY time";
	$result = mysqli_query($dbc, $query);
	while ($row = mysqli_fetch_array($result)) {
		?>
		<div class="mt-3">
			<div class="row">
				<div class="col-md-4">
					Incident Date/Time: <?php echo parseDateTime($row['time']); ?><br>
					Teacher: <?php echo $row['teacher']; ?>
				</div>
				<div class="col-md-8">
					Behavior: <?php echo $row['behavior']; ?>
				</div>
			</div>
			<div class="row mt-1">
				<div class="col-12">
					Description of Incident: <?php echo nl2br($row['description']); ?>
				</div>
			</div>
			<?php
			if ($row['action'] !== null) { ?>
				<div class="row mt-1">
					<div class="col-4">
						Consequence: <?php echo $row['action']; ?><br>
						Administrator: <?php echo $row['admin']; ?>
					</div>
					<div class="col-4">
						Start Date: <?php echo makeDateAmerican($row['action_date']); ?><br>
						End Date: <?php echo makeDateAmerican($row['end_date']); ?>
					</div>
					<div class="col-4">
						Length: <?php echo ($row['length']); ?>
					</div>
				</div>
				<div class="row mt-1">
					<div class="col-12">
						Administrator Comments: <?php echo nl2br($row['admin_comments']); ?>
					</div>
				</div>
				<?php
			} else {
				?>
				<div class="row mt-1">
					<div class="col-12">
						UNASSIGNED
					</div>
				</div>
				<?php
			} ?>
		</div>
		<hr>
		<?php
	}
	?>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
