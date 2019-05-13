<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Observation Data';
$page_access = 'Superintendent Principal Dept Head Admin';
include('header.php');

//include other scripts needed here
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

$standards = array(
    'dom1' => array(
        array(
            'name' => 'Differentiation',
            'code' => 'diff'
        ),
        array(
            'name' => 'High Level Thinking',
            'code' => 'think'
        ),
        array(
            'name' => 'Scaffolding',
            'code' => 'scaff'
        )
    ),
    'dom2' => array(
        array(
            'name' => 'Formative Assessment',
            'code' => 'form'
        ),
        array(
            'name' => 'Feedback',
            'code' => 'feed'
        ),
        array(
            'name' => 'Apply Feedback',
            'code' => 'apply'
        ),
        array(
            'name' => 'Cross-Curricular',
            'code' => 'disc'
        ),
        array(
            'name' => 'Real-World Applications',
            'code' => 'world'
        ),
        array(
            'name' => 'Probing Questions',
            'code' => 'probe'
        )
    ),
    'dom3' => array(
        array(
            'name' => 'Redirect Misbehavior',
            'code' => 'red'
        ),
        array(
            'name' => 'Active Participation',
            'code' => 'part'
        ),
        array(
            'name' => 'Meaningful Work',
            'code' => 'mean'
        ),
        array(
            'name' => 'Transitions and Procedures',
            'code' => 'proc'
        ),
        array(
            'name' => 'Positive Interactions - Teacher/Student',
            'code' => 'pos'
        ),
        array(
            'name' => 'Positive Interactions - Student/Student',
            'code' => 'stud'
        )
    )
);
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Teachers
		</h1>
	    <p class="lead">
	    	Manage and observe teachers
	    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
			<h1>
				Observation Data
			</h1>
			<p class="lead">
				In the table below you will see all completed observations.
			</p>
			<p>
				Click on an observation to view it.
			</p>
			<p>
				<a class="btn btn-primary" href="addobservation.php">Add Observation</a>
                <a class="btn btn-success" href="observations.php">Observations</a>
			</p>
			<form class="form-inline">
				<label class="mr-sm-2" for="inlineFormCustomSelectPref">Observer</label>
				<select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="observer">
					<option selected>Observer...</option>
					<?php
					$query = "SELECT firstname, lastname, observer FROM observations AS obs LEFT JOIN staff_list AS sl ON (obs.observer = sl.username) GROUP BY observer ORDER BY lastname, firstname";
					$result = mysqli_query($dbc, $query);
					while ($row = mysqli_fetch_array($result)) {
						echo '<option value="' . $row['observer'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
					}
					?>
				</select>

				<label class="mr-sm-2" for="inlineFormCustomSelectPref">Sub-Standard</label>
				<select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="standard">
					<option selected>Sub-Standard...</option>
					<?php
					for ($i = 1; $i < 4; $i++) {
						foreach ($standards["dom$i"] as $stand) {
							$code = $stand['code'];
							echo '<option value="' . $stand['code'] . '">' . $stand['name'] . '</option>';
						}
					}
					?>
				</select>

				<label class="mr-sm-2" for="inlineFormCustomSelectPref">Level</label>
				<select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="standard">
					<option selected>Level...</option>
					<?php
					for ($i = 1; $i < 4; $i++) {
						echo '<option value="' . $i . '">Level ' . $i . '</option>';
					}
					?>
				</select>
			</form>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        //click on an observation to view it
        $("table tbody tr").click(function() {
            var id = $(this).prop("id").substr(4);
            window.location.href = 'viewobservation.php?obs=' + id;
        });
    });
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
