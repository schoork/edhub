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

$referral_id = mysqli_real_escape_string($dbc, $_GET['id']);
$query = "SELECT * FROM referrals LEFT JOIN student_list USING (student_id) WHERE rowid = $referral_id";
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
				Hollandale School District Disciplinary Referral
			</h3>
		</div>
	</div>
	<div class="row bg-light mt-3">
		<div class="col">
			<p>
				Student: <?php echo $row['firstname'] . ' ' . $row['lastname']; ?> <a class="btn btn-secondary btn-sm d-print-none" href="allreferrals.php?id=<?php echo $row['student_id']; ?>">All Referrals</a><br>
				Grade Level: <?php echo $row['grade']; ?><br>
				Incident Date/Time: <?php echo parseDateTime($row['time']); ?>
			</p>
		</div>
		<div class="col">
			<p>
				Teacher: <?php echo $row['teacher']; ?><br>
				School: <?php echo $row['school']; ?><br>
				Behavior: <?php echo $row['behavior']; ?>
			</p>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<p>
				Description of Incident: <?php echo nl2br($row['description']); ?>
			</p>
		</div>
	</div>
	<div class="row bg-light mt-3">
		<div class="col">
			<p>
				Consequence: <?php echo $row['action']; ?><br>
				Start Date: <?php echo makeDateAmerican($row['action_date']); ?><br>
				Length: <?php echo $row['length']; ?>
			</p>
		</div>
		<div class="col">
			<p>
				Administrator: <?php echo $row['admin']; ?><br>
				End Date: <?php echo makeDateAmerican($row['end_date']); ?>
			</p>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<p>
				Administrator Comments: <?php echo nl2br($row['admin_comments']); ?>
			</p>
		</div>
	</div>
	<div class="row bg-light mt-3">
		<div class="col">
			<table>
				<tr>
					<td>Admin Signature</td>
					<td class="sig_line" width="75%"></td>
				</tr>
			</table>
		</div>
	</div>
	<?php
	if ($row['action'] == 'OSS pending hearing' || $row['action'] == 'OSS') {
		?>
		<div class="row mt-3">
			<div class="col">
				<p>
					<em>
						<?php echo $row['firstname'] . ' ' . $row['lastname'] . ' has been suspended from school.';
						if ($row['action'] != 'OSS') {
							$date = strtotime($row['action_date']);
							$date = strtotime("+7 day", $date);
							?>
							The school administratration is recommending that your child be remanded to alternative school for this offense. Your child will be suspended for <?php echo $row['length']; ?> days, pending a disciplinary hearing. This hearing is meant to serve as due process for your child. During the hearing, the principal, child, and parent will be able to present evidence to the hearing committee. Based on this evidence, the hearing committee will decide whether to approve the recommendation of the school or to change this recommendation.<br>
							<br>
							If you would like to have a hearing you must contact the district office within seven calendar days (by <?php echo date('M d, Y', $date); ?>). If you choose not to request a hearing, your child will be eligible to attend alternative school on the school date after <?php echo makeDateAmerican($row['end_date']); ?> for the length of time indicated by school administrators.
							<?php
						}
						else {
							?>
							You may bring your child back to school on the school date after <?php echo makeDateAmerican($row['end_date']); ?>. In order for your child to attend classes, you will have to attend a Mandatory Parent Conference with the school principal or desginee on that day.
							<?php
						}
						?>
					</em>
				</p>
			</div>
		</div>
		<?php
	}
	if ($_SESSION['access'] == 'Principal' || $_SESSION['access'] == 'Admin' || $_SESSION['access'] == 'Superintendent') {
		?>
		<div class="row mt-5 d-print-none">
			<div class="col">
				<a class="btn btn-secondary" href="viewreferral.php?id=<?php echo $referral_id; ?>">Edit</a>
			</div>
		</div>
		<?php
	}
	?>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
