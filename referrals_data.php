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
            <h1>Referrals Data</h1>
            <canvas id="sandersChart" width="400" height="250"></canvas>
        </div>
    </div>
</div>
<?php
$query = "SELECT behavior, count(*) AS count FROM referrals LEFT JOIN student_list USING (student_id) WHERE grade <= 6 GROUP BY behavior ORDER BY behavior";
$result1 = mysqli_query($dbc, $query);
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
        var ctx = document.getElementById("sandersChart").getContext("2d");
        var data = {
            labels: labels,
            datasets: [{
                label: 'Behaviors',
                data: data
            }]
        };
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                title: {
                    display: true,
                    text: 'Referrals by Behavior',
                    fontSize: 16
                }
            }
        });
        console.log(labels);
    });

</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
