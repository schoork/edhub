<?php

$page_title = 'General Ledger';
$page_access = 'Admin Superintendent Dept Head Principal';
include('header.php');

//include other scripts needed here
?>
<link href="css/budget_styles.css" rel="stylesheet"/>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

<?php

//end header
echo '</head>';

//start body
echo '<body>';

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$funds = array();
$query = "SELECT /* first four characters of purchase_code */ FROM budget GROUP BY /* first four characters of purchase_code */ ORDER BY /* first four characters of purchase_code */";
$result = mysqli_query($dbc, $query);
while ($row = mysqli_fetch_array($result)) {
	array_push($funds, $row['fund']);
}

?>

<!--Allows to select only certain funds-->
<div class="d-print-none">
	<div class="form-group row">
		<div class="col-sm-9">
			<select class="form-control grade-select" id="fund" multiple>
				<?php
				foreach ($funds as $fund) {
					echo '<option value="' . $fund . '">' . $fund . '</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<a href="#!" id="updateLedger" class="btn btn-primary">Update Ledger</a>
		</div>
	</div>
</div>
<div class="row d-print-none">
	<div class="col 12">
		<h1>
			General Ledger Report
		</h1>
	</div>
</div>
<div class="row d-print-none">
	<div class="col-12">
		<p>
			<a class="btn btn-primary" href="budget.php">Back to Budget</a>
		</p>
	</div>
</div>
<?php
foreach ($funds as $fund) {
	?>
	<div class="row">
		<div class="col-12">
			<h4>
				General Ledger Report
			</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			Fund: <?php echo $fund; ?><br>
			Effective Date: <?php echo date('M/d/Y'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<table class="table table-sm">
				<?php
				$query = "SELECT purchase_code, initial FROM budget WHERE purchase_code LIKE '$fund-%' ORDER BY purchase_code";
				$result = mysqli_query($dbc, $query);
				while ($row = mysqli_fetch_array($result)) {
					?>
					<tr class="linetitlerow">
						<th>Budget Line: <?php echo $row['purchase_code']; ?></th>
						<th>Initial Balance: <?php echo $row['initial']; ?></th>
						<th colspan="2"></th>
						<th>YTD Spent</th>
						<th>YTD Encumbered</th>
						<?php
						$balance = $row['initial'];
						$query = "SELECT vendor, date, po, description, amount, status FROM budget_activities ORDER BY date ASC";
						$data = mysqli_query($dbc, $query);
						if (mysqli_num_rows($data) > 0) {
							while ($line = mysqli_fetch_array($data)) {
								echo '<tr>';
								echo '<td>' . makeDateAmerican($line['date']) . '</td>';
								echo '<td>' . $line['vendor'] . '</td>';
								echo '<td>' . $line['description'] . '</td>';
								echo '<td>' . $line['po'] . '</td>';
								if ($line['status'] == 'spent') {
									echo '<td>' . number_format($line['amount'], 2) . '</td><td></td>';
								}
								else {
									echo '<td></td><td>' . number_format($line['amount'], 2) . '</td>';
								}
								echo '</tr>';
								$balance = $balance - $row['amount'];
							}
						}
						?>
					</tr>
					<tr>
						<th colspan="4"></th>
						<th>YTD Balance</th>
						<th>$<?php number_format($balance, 2); ?></th>
					</tr>
					<?php
				}
				?>
			</table>
		</div>
	</div>
	<?php
}

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
