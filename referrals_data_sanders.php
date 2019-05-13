<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Referrals';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';

echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js"></script>';

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
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
            <h1>Sanders Referral Data</h1>
            <a class="btn btn-secondary" href="referrals.php">Referrals</a>
            <canvas id="behaviorChart" width="400" height="200"></canvas>
            <canvas id="actionChart" width="400" height="200"></canvas>
            <canvas id="studentChart" width="400" height="300"></canvas>
            <p>
				<small class="muted-text">
					<?php
					$behaviors = array(
						'(1-1) Tardies',
						'(1-2) Running and/or making excessive noise in hallways/cafeteria',
						'(1-3) In unauthorized area without a pass (halls, etc.)',
						'(1-4) Dress code violation',
						'(1-5) Loitering in the halls, common areas, etc.',
						'(1-6) Behavior that disrupts instruction',
						'(1-7) Failure to do homework/assignments',
						'(1-8) Violation of Lab Safety',
						'(1-9) Other disruptive behaviors as deemed by teacher',
						'(2-1) Initiating or participating in any unacceptable physical contact, including, but not limited to, inappropriate physical displays of affection',
						'(2-2) Exhaustion of classroom consequences',
						'(2-3) Student Harassment',
						'(2-4) Sale of Snacks/Candy',
						'(2-5) Skipping class or school',
						'(2-6) Insubordination (refusal to comply with rules, instructions)',
						'(2-7) Defiance, disrespect (disrespect or rudeness to staff or students, failure to serve detention)',
						'(2-8) Exhibition of any hostile actions ([physical, verbal, written)',
						'(2-9) Possession of any cell phone or electronic equipment without prior approval of the administration',
						'(2-10) Clothing, apparel, or accessories that signify membership or affiliation with any gang or social club associated with criminal activity',
						'(2-11) Using profane, obscene, indecent, immoral, or offense language and/or gestures, possession of obscene materials',
						'(2-12) Excessive tardiness',
						'(2-13) Misuse of technology',
						'(2-14) Other behaviors as deemed by the administrator',
						'(3-1) Fighting',
						'(3-2) Gambling',
						'(3-3) Theft of personal or school property',
						'(3-4) Acts which threaten the safety and/or well-being of students and/or staff',
						'(3-5) Use of intimidation, coercion, force, or extortion (includes bullying)',
						'(3-6) Vandalism/destruction of personal and/or school property',
						'(3-7) Sexual harassment/misconduct',
						'(3-8) Threats (verbal or written) towards a student',
						'(3-9) Gang-Related Activity',
						'(3-10) Verbal Altercation',
						'(3-11) Refusal to turn in cell phone',
						'(3-12) Possession of stolen property',
						'(3-13) Other behaviors as deemed by the administrator',
						'(4-1) Possession, use, sale, or under the influence of tobacco, alcohol, illegal drugs, narcotics, controlled substance(s) or paraphernalia',
						'(4-2) Assault on a student',
						'(4-3) Possession and/or use of a weapon',
						'(4-4) Physical, written, or verbal threat or assault on a staff member',
						'(4-5) Other behaviors as deemed by the administrator'
					);
					foreach ($behaviors as $behave) {
						echo $behave . '<br>';
					}

					 ?>
			 </small>
		 	</p>
        </div>
    </div>
</div>
<?php
$query = "SELECT behavior, count(*) AS count FROM referrals LEFT JOIN student_list USING (student_id) WHERE grade <= 6 GROUP BY behavior ORDER BY behavior";
$result1 = mysqli_query($dbc, $query);
$query = "SELECT action, count(*) AS count FROM referrals LEFT JOIN student_list USING (student_id) WHERE grade <= 6 GROUP BY action ORDER BY action";
$result2 = mysqli_query($dbc, $query);
$query = "SELECT lastname, firstname, count(*) AS count FROM referrals AS ref LEFT JOIN student_list USING (student_id) WHERE grade <= 6 GROUP BY ref.student_id ORDER BY count";
$result3 = mysqli_query($dbc, $query);
?>
<script>
    $(document).ready(function() {
        var labels = [];
        var data = [];
        <?php
        while ($row = mysqli_fetch_array($result1)) {
            ?>
            labels[labels.length] = '<?php echo $row['behavior']; ?>';
            data[data.length] = <?php echo $row['count']; ?>;
            <?php
        }
        ?>
        for (var i=0; i<labels.length; i++) {
            labels[i] = labels[i].substring(0, labels[i].indexOf(')') + 1);
        }
        var ctx = document.getElementById("behaviorChart").getContext("2d");
        var data = {
            labels: labels,
            datasets: [{
                label: 'Behaviors',
                backgroundColor: "#007bff",
                data: data
            }]
        };
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                title: {
                    display: true,
                    text: 'Sanders - Referrals by Behavior',
                    fontSize: 16
                }
            }
        });
        var labels = [];
        var data = [];
        <?php
        while ($row = mysqli_fetch_array($result2)) {
			if ($row['action'] == null) {
				?>
				labels[labels.length] = 'Unassigned';
				<?php
			}
			else {
				?>
				labels[labels.length] = '<?php echo $row['action']; ?>';
				<?php
			}
            ?>
            data[data.length] = <?php echo $row['count']; ?>;
            <?php
        }
        ?>
        var ctx = document.getElementById("actionChart").getContext("2d");
        var data = {
            labels: labels,
            datasets: [{
                label: 'Actions',
                backgroundColor: "#007bff",
                data: data
            }]
        };
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                title: {
                    display: true,
                    text: 'Sanders - Referrals by Action',
                    fontSize: 16
                }
            }
        });

		var labels = [];
        var data = [];
        <?php
        while ($row = mysqli_fetch_array($result3)) {
			if ($row['count'] > 1) {
				?>
				labels[labels.length] = "<?php echo mysqli_real_escape_string($dbc, $row['firstname'] . ' ' . $row['lastname']); ?>";
	            data[data.length] = <?php echo $row['count']; ?>;
	            <?php
			}
        }
        ?>
        var ctx = document.getElementById("studentChart").getContext("2d");
        var data = {
            labels: labels,
            datasets: [{
                label: 'Students',
				backgroundColor: "#007bff",
                data: data
            }]
        };
        var myBarChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: data,
            options: {
                title: {
                    display: true,
                    text: 'Sanders - Referrals by Student',
                    fontSize: 16
                }
            }
        });
    });

</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
