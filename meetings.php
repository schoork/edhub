<?php

$page_title = 'Meetings';
$page_access = 'All';
include('header.php');

//include other scripts needed here

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

?>

<style>
.datenum {
    padding: 2px;
    border-radius: 50%;
}
.datebody {
    vertical-align: top;
}
.meeting {
    margin: 2px;
}
</style>

<?php

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$date = $_GET['date'];

$date = new DateTime($date);
$month = $date->format('F');
$month_num = $date->format('m');
$year = $date->format('Y');
$first = new DateTime($year . '-' . $month_num . '-01');
$first_day = $first->format('N');
$fd = $first_day % 7;
$total_days = $date->format('t');
$last = new DateTime($year . '-' . $month_num . '-' . $total_days);
$start = $first->format('Y-m-d');
$end = $last->format('Y-m-d');

$query = "SELECT firstname, lastname, meeting_id, time, date, school FROM meetings_iep LEFT JOIN student_list USING (student_id) WHERE date >= '$start' AND date <= '$end' AND status = 1 ORDER BY time";
$results = mysqli_query($dbc, $query);

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
				Meetings
			</h1>
			<p class="lead">
				From this page you can see all scheduled meetings.
			</p>
            <p>
                <a class="btn btn-secondary" href="addmeeting.php">Schedule Meeting</a>
            </p>
            <table style="width: 100%;" class="table-bordered">
                <tr>
                    <td colspan="7" class="text-center text-primary" style="font-size: 150%; font-weight: bold;">
                        <?php
                        $date->modify('-1 month');
                        ?>
                        <a class="text-primary" href="meetings.php?date=<?php echo $date->format('Y-m-d'); ?>"><< </a>
                        <?php echo $month;
                        $date->modify('+2 month');
                        ?>
                        <a class="text-primary" href="meetings.php?date=<?php echo $date->format('Y-m-d'); ?>"> >></a>
                    </td>
                </tr>
                <tr>
                    <td style="width:14%; font-weight: bold;" class="text-center">Sunday</td>
                    <td style="width:14%; font-weight: bold;" class="text-center">Monday</td>
                    <td style="width:14%; font-weight: bold;" class="text-center">Tuesday</td>
                    <td style="width:15%; font-weight: bold;" class="text-center">Wednesday</td>
                    <td style="width:14%; font-weight: bold;" class="text-center">Thursday</td>
                    <td style="width:14%; font-weight: bold;" class="text-center">Friday</td>
                    <td style="width:14%; font-weight: bold;" class="text-center">Saturday</td>
                </tr>
                <tr>
                    <?php
                    $week_day = 0;
                    for ($i = 0; $i < $fd; $i++) {
                        echo '<td class="bg-secondary" rowspan="2"></td>';
                        $week_day++;
                    }
                    for ($j = 1; $j + $fd < 8; $j++) {
                        echo '<td><span class="datenum bg-light">' . $j . '</span></td>';
                    }
                    echo '</tr><tr style="height: 100px;">';

                    for ($j = 1; $j + $fd < 8; $j++) {
                        echo '<td id="date-' . $j . '" class="datebody"></td>';
                    }
                    echo '</tr><tr>';
                    $week_day = 0;
                    for ($j; $j < $total_days + 1; $j++) {
                        if ($week_day == 7) {
                            $week_day = 0;
                            echo '</tr><tr style="height: 100px;">';
                            $j = $j - 7;
                            for ($i = 0; $i < 7; $i++) {
                                echo '<td id="date-' . $j . '" class="datebody"></td>';
                                $j++;
                            }
                            echo '</tr><tr>';
                        }
                        echo '<td><span class="datenum bg-light">' . $j . '</span></td>';
                        $week_day++;
                    }
                    for ($i = $week_day; $i < 7; $i++) {
                        echo '<td class="bg-secondary" rowspan="2"></td>';
                    }
                    echo '<tr style="height: 100px;">';
                    $j = $j - $week_day;
                    for ($i = 0; $i < $week_day; $i++) {
                        echo '<td id="date-' . $j . '" class="datebody"></td>';
                        $j++;
                    }
                    ?>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php
    if (mysqli_num_rows($results) > 0) {
        while ($row = mysqli_fetch_array($results)) {
            $date = new DateTime($row['date']);
            $day = $date->format('j');
            $time = new DateTime($row['time']);
            $display = $time->format('g:i A');
            $school = $row['school'];
            if ($school == 'Sanders') {
                ?>
                $("td#date-<?php echo $day; ?>").append('<div class="bg-danger text-white meeting" style="font-size: 65%"><a href="meetingdetail.php?id=<?php echo $row['meeting_id']; ?>" class="text-white"><?php echo mysqli_real_escape_string($dbc, $row['firstname'] . ' ' . $row['lastname']) . ' (' . $display . ')'; ?></a></div>');
                <?php
            }
            else {
                ?>
                $("td#date-<?php echo $day; ?>").append('<div class="bg-success text-white meeting" style="font-size: 65%"><a href="meetingdetail.php?id=<?php echo $row['meeting_id']; ?>" class="text-white"><?php echo mysqli_real_escape_string($dbc, $row['firstname'] . ' ' . $row['lastname']) . ' (' . $display . ')'; ?></a></div>');
                <?php
            }

        }
    }
    ?>
});
</script>

<?php

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
