<?php

$page_title = 'Behavior Trackers';
$page_access = 'All';
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
            <h1>Student Behavior Trackers</h1>
            <p>
                <a class="btn btn-secondary" href="add_tracker.php">Add Behavior Tracker</a>
                <a class="btn btn-secondary" href="updatetracker.php">Update Trackers</a>
            </p>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
						<th>Grade Level</th>
                        <th>View Data</th>
                        <th>Edit Tracker</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT firstname, lastname, sl.student_id, grade FROM rti_checklist_written LEFT JOIN student_list AS sl USING (student_id) ORDER BY lastname, firstname";
                    $result = mysqli_query($dbc, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['firstname'] . ' ' . $row['lastname'];?></td>
							<td><?php echo $row['grade']; ?></td>
                            <td><a class="btn btn-secondary" href="view_tracker.php?id=<?php echo $row['student_id']; ?>">View</a></td>
                            <td><a class="btn btn-secondary" href="edit_tracker.php?id=<?php echo $row['student_id']; ?>">Edit</a></td>
                        </tr>
                        <?php
                    }
                     ?>
                 </tbody>
             </table>



        </div>
    </div>
</div>

<?php

//include footer
include('footer.php');
?>
