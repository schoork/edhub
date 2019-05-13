<?php

$page_title = 'View Behavior Tracker Data';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js"></script>';

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
                View Behavior Tracker Data - <?php echo $row['firstname'] . ' ' . $row['lastname']; ?>
            </h1>
            <p>
                <a class="btn btn-secondary" href="studenttrackers.php">All Student Trackers</a>
                <a class="btn btn-secondary" href="updatetracker.php">Update Trackers</a>
                <a class="btn btn-secondary" href="edit_tracker.php?id=<?php echo $student_id; ?>">Edit this Tracker</a>
            </p>
            <h5>Replacement Behaviors</h5>
            <ol>
                <?php
                for ($i = 1; $i < 4; $i++) {
                    if ($row["replace$i"] != '') {
                        echo '<li>' . $row["replace$i"] . '</li>';
                    }
                }
                ?>
            </ol>
            <p>
                <canvas id="myChart" width="400" height="200"></canvas>
            </p>
            <div class="row mb-3">
                <div class="col-md-6">
                    0 = Behavior not demonstrated (0%)<br/>
                    1 = Behavior poorly demonstrated (1%-25%)<br>
                    2 = Behavior somewhat demonstrated (26%-50%)
                </div>
                <div class="col-md-6">
                    3 = Behavior adequately demonstrated (51%-75%)<br>
                    4 = Behavior largely demonstrated (76%-100%)
                </div>
            </div>
            <?php
            $query = "SELECT firstname, lastname, date, notes FROM rti_teacher_tracker_notes AS rti LEFT JOIN staff_list AS sl ON (rti.teacher = sl.username) WHERE student_id = $student_id ORDER BY date DESC";
            $data = mysqli_query($dbc, $query);
            if (mysqli_num_rows($data) > 0) {
                ?>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Week of</th>
                            <th>Teacher</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($line = mysqli_fetch_array($data)) {
                            ?>
                            <tr>
                                <td><?php echo makeDateAmerican($line['date']);?></td>
                                <td><?php echo $line['firstname'] . ' ' . $line['lastname']; ?></td>
                                <td><?php echo $line['notes']; ?></td>
                            </tr>
                            <?php
                        }

                        ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
            <h5>Target Behaviors</h5>
            <ol>
                <?php
                for ($i = 1; $i < 4; $i++) {
                    if ($row["target$i"] != '') {
                        echo '<li>' . $row["target$i"] . '</li>';
                    }
                }
                ?>
            </ol>
            <h5>Interventions</h5>

            <ol>
                <?php
                for ($i = 1; $i < 6; $i++) {
                    if ($row["intervention$i"] != '') {
                        echo '<li>' . $row["intervention$i"] . '</li>';
                    }
                }
                ?>
            </ol>
        </div>
    </div>
</div>

<script>
    var labels = [];
    var data1 = [];
    var data2 = [];
    var data3 = [];
    <?php
    $teacher_dates = array();
    $query = "SELECT date, behavior, avg(CASE WHEN day1 < 100 THEN day1 ELSE NULL END) as day1, avg(CASE WHEN day2 < 100 THEN day2 ELSE NULL END) as day2, avg(CASE WHEN day3 < 100 THEN day3 ELSE NULL END) as day3, avg(CASE WHEN day4 < 100 THEN day4 ELSE NULL END) as day4, avg(CASE WHEN day5 < 100 THEN day5 ELSE NULL END) as day5 FROM rti_teacher_tracker WHERE student_id = $student_id and behavior <> '' GROUP BY behavior, date ORDER BY date ASC";
    $result = mysqli_query($dbc, $query);
    $teacher_inputs = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $sum = 0;
            $denom = 0;
            for ($i = 1; $i < 6; $i++) {
                if ($row["day$i"] !== null) {
                    $sum += $row["day$i"];
                    $denom++;
                }
            }
            if ($denom !== 0) {
                $avg = round($sum / $denom, 1);
                $mon = date('m/d', strtotime($row['date']));
                $fri = date('m/d', strtotime('+4 day', strtotime($mon)));
                $date = $mon . ' - ' . $fri;
                array_push($teacher_inputs, array('date' => $date, 'behavior' => $row['behavior'], 'avg' => $avg));
                if (!in_array(array('date' => $date), $teacher_dates)) {
                    array_push($teacher_dates, array('date' => $date));
                }
            }
        }
        foreach ($teacher_dates as $date) {
            ?>
            labels[labels.length] = '<?php echo $date['date']; ?>';
            <?php
        }
        foreach ($teacher_inputs as $input) {
            ?>
            var i = labels.indexOf('<?php echo $input['date']; ?>');
            switch (<?php echo $input['behavior']; ?>) {
                case 1:
                    data1[i] = <?php echo $input['avg']; ?>;
                    break;
                case 2:
                    data2[i] = <?php echo $input['avg']; ?>;
                    break;
                case 3:
                    data3[i] = <?php echo $input['avg']; ?>;
                    break;
            }
            <?php
        }
    }
    ?>
    var ctx = document.getElementById("myChart").getContext("2d");
    var data = {
        labels: labels,
        datasets: [
            {
                label: "Replacement Behavior 1",
                borderColor: "#fd7e14",
                backgroundColor: 'rgba(0, 0, 0, 0)',
                data: data1
            },
            {
                label: "Replacement Behavior 2",
                borderColor: "#007bff",
                backgroundColor: 'rgba(0, 0, 0, 0)',
                data: data2
            }
            ,
            {
                label: "Replacement Behavior 3",
                borderColor: "#28a745",
                backgroundColor: 'rgba(0, 0, 0, 0)',
                data: data3
            }
        ]
    };
    var myBarChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            title: {
                display: true,
                text: 'Replacement Behaviors Over Time',
                fontSize: 16
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 5
                    }
                }]
            }
        }
    });
</script>

<?php

//include footer
include('footer.php');
?>
