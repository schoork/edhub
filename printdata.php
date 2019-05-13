<?php
$page_title = 'Weekly Data Charts';
$page_access = 'All';
include('header.php');

echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
echo '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
echo '<script src="js/plugin-legend.js"></script>';
echo '<link rel="stylesheet" href="css/plugin-legend-bootstrap.css">';

echo '<script src="js/printdata_scripts.js"></script>';
echo '<link rel="stylesheet" href="css/printdata_styles.css">';

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$week = mysqli_real_escape_string($dbc, $_GET['week']);
$query = "SELECT teacher, dt.row_id FROM data_students LEFT JOIN data_teachers AS dt USING (teacher) WHERE teacher <> '' AND week = '$week' GROUP BY teacher ORDER BY teacher";
$result = mysqli_query($dbc, $query);

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <!-- Write HTML just like a web page -->
  <div class="row d-print-none">
    <div class="alert alert-info" style="width: 100%">
      When printing, (1) make sure you print in landscape and (2) uncheck Headers and Footers from the Options section. This can be found in the Google Chrome dialog under <em>+ More Settings</em>
    </div>
  </div>
  <div class="row d-print-none">
    <a class="btn btn-primary" href="viewdata.php">Back to Weekly Data Charts</a>
    <a class="btn btn-success" href="index.php">edhub Home</a>
  </div>
  <input type="hidden" id="week" value="<?php echo $week; ?>">
  <?php
  $page = 1;
  while ($row = mysqli_fetch_array($result)) {
    if ($page > 1) {
      echo '<p style="page-break-before: always;"></p>';
    }
    ?>
    <div class="row">
      <h3>
        Weekly Data for <?php echo $row['teacher']; ?> (Week of <?php echo makeDateAmerican($week); ?>)
      </h3>
    </div>
    <div class="row mt-3">
      <div class="col">
        <div class="ct-chart ct-octave wide-stroke" id="chart-<?php echo $row['row_id']; ?>"></div>
      </div>
		</div>
		<div class="row mt-3">
      <div class="col">
        <table class="table table-sm table-bordered" id="classesTable-<?php echo $row['row_id']; ?>">
					<thead>
						<tr>
							<th>Period</th>
							<th>Course</th>
							<th>Pre-Test Average</th>
							<th>Post-Test Average</th>
							<th>Growth</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
      </div>
			<div class="col">
				<table class="table table-sm table-bordered" id="profTable-<?php echo $row['row_id']; ?>">
					<thead>
						<tr>
							<th>Period</th>
							<th>Course</th>
							<th>Advanced</th>
							<th>Proficient</th>
							<th>Pass</th>
							<th>Basic</th>
							<th>Minimal</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$teacher = mysqli_real_escape_string($dbc, $row['teacher']);
						$query = "SELECT period, course, sum(CASE WHEN score < 64.5 THEN 1 ELSE 0 END) AS f,";
						$query .= " sum(CASE WHEN score < 74.5 AND score >= 64.5 THEN 1 ELSE 0 END) AS d,";
						$query .= " sum(CASE WHEN score < 84.5 AND score >= 74.5 THEN 1 ELSE 0 END) AS c,";
						$query .= " sum(CASE WHEN score < 94.5 AND score >= 84.5 THEN 1 ELSE 0 END) AS b,";
						$query .= " sum(CASE WHEN score >= 94.5 THEN 1 ELSE 0 END) AS a,";
						$query .= " sum(CASE WHEN score >= 84.5 THEN 1 ELSE 0 END) AS pro,";
						$query .= " count(score) AS total";
						$query .= " FROM data_students WHERE week = '$week' AND teacher = '$teacher' AND test_type = 'Post-Test'";
						$query .= " GROUP BY period, course ORDER BY period, course";
						$data = mysqli_query($dbc, $query);
						$a = 0;
						$b = 0;
						$c = 0;
						$d = 0;
						$f = 0;
						$pro = 0;
						$all = 0;
						while ($line = mysqli_fetch_array($data)) {
							echo '<tr>';
							echo '<td>' . $line['period'] . '</td>';
							echo '<td>' . $line['course'] . '</td>';
							echo '<td>' . $line['a'] . '</td>';
							echo '<td>' . $line['b'] . '</td>';
							echo '<td>' . $line['c'] . '</td>';
							echo '<td>' . $line['d'] . '</td>';
							echo '<td>' . $line['f'] . '</td>';
							echo '</tr>';
							$a += $line['a'];
							$b += $line['b'];
							$c += $line['c'];
							$d += $line['d'];
							$f += $line['f'];
							$pro += $line['pro'];
							$all += $line['total'];
						}
						echo '<tr>';
						echo '<td colspan="2" rowspan="2">Overall</td>';
						echo '<td>' . $a . '</td>';
						echo '<td>' . $b . '</td>';
						echo '<td>' . $c . '</td>';
						echo '<td>' . $d . '</td>';
						echo '<td>' . $f . '</td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td colspan="2">' . round($pro/$all*100,0) . '%</td>';
						echo '<td>' . round($c/$all*100,0) . '%</td>';
						echo '<td>' . round($d/$all*100,0) . '%</td>';
						echo '<td>' . round($f/$all*100,0) . '%</td>';
						echo '</tr>';
						?>
					</tbody>
				</table>
			</div>
    </div>
    <?php
    $page++;
  }
  ?>

</body>

<?php

mysqli_close($dbc);

?>
</html>
