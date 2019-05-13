<?php

$page_title = 'Edit Behavior Tracker';
$page_access = 'Principal Dept Head Admin Superintendent Designee';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

$student_id = mysqli_real_escape_string($dbc, $_GET['id']);
$query = "SELECT * FROM rti_checklist_written LEFT JOIN student_list USING (student_id) WHERE student_id = $student_id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Students
		</h1>
        <p class="lead">
            Manage students and interventions
        </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
            <h1>
                Edit Student Tracker - <?php echo $row['firstname'] . ' ' . $row['lastname']; ?>
            </h1>
            <form method="post" action="service.php" id="memoForm">
                <input type="hidden" name="action" value="editTracker">
                <input type="hidden" name="student" value="<?php echo $student_id; ?>">
                <h3>Target Behaviors</h3>
                <p>
                    Target behaviors are the behaviors that we are trying to change. For instance, "Walking out of class without permission."
                </p>
                <?php
                for ($i = 1; $i < 4; $i++) {
                    ?>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="target<?php echo $i; ?>">Target Behavior <?php echo $i; ?></label>
                            <input type="text" class="form-control" name="target<?php echo $i; ?>" id="target<?php echo $i; ?>" maxlength="100" value="<?php echo $row["target$i"]; ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <h3>Interventions</h3>
                <p>
                    Interventions are things that we will do as teachers and staff when the student behaves in one of the ways below. Interventions should be used to help curb the behavior and teach a better one. Interventions should be used before the normal consequences for these behaviors.
                </p>
                <?php
                for ($i = 1; $i < 6; $i++) {
                    ?>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="intervention<?php echo $i; ?>">Intervention <?php echo $i; ?></label>
                            <input type="text" class="form-control" name="intervention<?php echo $i; ?>" id="intervention<?php echo $i; ?>" maxlength="100" value="<?php echo $row["intervention$i"]; ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <h3>Replacement Behaviors</h3>
                <p>
                    Replacement behaviors are the behaviors we would like to see from the student instead of the behaviors listed above. For instance, "Ask and wait for permission to leave the classroom," or "Remains in the classroom."
                </p>
                <?php
                for ($i = 1; $i < 4; $i++) {
                    ?>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="replace<?php echo $i; ?>">Replacement Behavior <?php echo $i; ?></label>
                            <input type="text" class="form-control" name="replace<?php echo $i; ?>" id="replace<?php echo $i; ?>" maxlength="100" value="<?php echo $row["replace$i"]; ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
				<h3>Teachers</h3>
				<p>
					You can assign up to four teachers per student. Teachers do not have to be assigned in any specific order.
				</p>
				<?php
				for ($i = 1; $i < 5; $i++) {
                    ?>
					<div class="form-row">
                        <div class="form-group col-12">
                            <label for="teacher<?php echo $i; ?>">Teacher <?php echo $i; ?></label>
							<select name="teacher<?php echo $i; ?>" id="teacher<?php echo $i; ?>" class="custom-select">
								<option value="Unassigned"></option>
								<?php
								$query = "SELECT firstname, lastname, username FROM staff_list ORDER BY lastname, firstname";
								$data = mysqli_query($dbc, $query);
								while ($line = mysqli_fetch_array($data)) {
									echo '<option value="' . $line['username'] .'"';
									if ($row["teacher$i"] == $line['username']) {
										echo ' selected';
									}
									echo '>' . $line['lastname'] . ', ' . $line['firstname'] . '</option>';
								}
								?>
							</select>
                        </div>
                    </div>
					<?php
				}
				?>
                <div id="alert" class="alert" role="alert">

                </div>
                <a class="btn btn-primary" href="#!" id="btnSubmitForm">Submit</a>
                <a class="btn btn-danger" href="studenttrackers.php">Cancel</a>
            </form>

        </div>
    </div>
</div>

<?php

//include footer
include('footer.php');
?>
