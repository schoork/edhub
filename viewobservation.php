<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'View Observation';
$page_access = 'All';
include('header.php');

//include other scripts needed here

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

$domains = array('Domain I - Lesson Design', 'Domain II - Student Learning', 'Domain III - Culture and Learning Environment');
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
				View Observation
			</h1>
			<p>
                <a class="btn btn-primary" href="observations.php">Observations</a>
			</p>
			<?php
            $obs_id = mysqli_real_escape_string($dbc, $_GET['obs']);
            $query = "SELECT * FROM observations AS obs LEFT JOIN staff_list AS sl ON (obs.observer = sl.username) WHERE row_id = $obs_id";
            $result = mysqli_query($dbc, $query);
            $row = mysqli_fetch_array($result);
            $teacher = $row['teacher'];
            $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$teacher'";
            $result = mysqli_query($dbc, $query);
            $data = mysqli_fetch_array($result);
            $teacher = $data['firstname'] . ' ' . $data['lastname'];
			?>
		</div>
	</div>
    <div class="row bg-light mt-3">
		<div class="col-sm-4">
			<p>
                Teacher: <?php echo $teacher; ?><br>
                Overall Score: <?php echo $row['overall']; ?><br>
                Domain I Score: <?php echo $row['dom1']; ?><br>
                Domain II Score: <?php echo $row['dom2']; ?><br>
                Domain III Score: <?php echo $row['dom3']; ?>
            </p>
        </div>
        <div class="col-sm-8">
            <p>
                Observer: <?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br>
                Date: <?php echo makeDateAmerican($row['date']); ?><br>
                Period: <?php echo $row['period']; ?><br>
                Course: <?php echo $row['course']; ?><br>
                Whiteboard Protocol (posted): <?php echo $row['posted']; ?>
            </p>
        </div>
    </div>
    <?php
    for ($i = 1; $i < 4; $i++) {
        if ($row["dom$i"] !== null) {
            ?>
            <div class="row mt-3">
                <div class="col">
                    <h3>
                        <?php echo $domains[$i - 1]; ?>
                    </h3>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Sub-Standard</th>
                                <th>Level</th>
                                <th>Evidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($standards["dom$i"] as $stand) {
                                $code = $stand['code'];
                                echo '<tr><td>' . $stand['name'] . '</td><td>' . $row[$code] . '</td><td>' . $row[$code . "_evid"] . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <div class="row">
		<div class="col-12">
            <p>
                <strong>Lesson Glow(s):</strong> <?php echo $row['glows']; ?>
            </p>
            <p>
                <strong>Lesson Grow(s):</strong> <?php echo $row['grows']; ?>
            </p>
        </div>
    </div>

</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
