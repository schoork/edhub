<?php

$page_title = 'General Ledger';
$page_access = 'Admin Superintendent Dept Head Principal';
include('header.php');

//include other scripts needed here
?>
<link href="css/ledger_styles.css" rel="stylesheet"/>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

<?php

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-district.php');

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$funds = array();
$query = "SELECT substring(purchase_code, 1, 4) AS fund FROM budget GROUP BY substring(purchase_code, 1, 4) ORDER BY substring(purchase_code, 1, 4)";
$result = mysqli_query($dbc, $query);
while ($row = mysqli_fetch_array($result)) {
	array_push($funds, $row['fund']);
}

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4 d-print-none">
	<div class="container">
		<h1>
			District
		</h1>
    <p class="lead">
      Manage budgets, forms, and inventory
    </p>
	</div>
</div>
<div class="container-fluid mt-3 mb-3">
	<div class="row d-print-none">
		<div class="col 12">
			<h1>
				General Ledger Report
			</h1>
		</div>
	</div>
	<!--Allows to select only certain funds-->
	<div class="d-print-none">
		<div class="row mb-3">
			<div class="col-12">
				<a href="addform.php" class="btn btn-primary">Add Journal Entry</a>
				<a class="btn btn-primary" href="budget.php">Back to Budget</a>
			</div>
		</div>
	</div>
	<?php
	foreach ($funds as $fund) {
		?>
		<div class="row page-start">
			<div class="col-12">
				<h4>
					General Ledger Report
				</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				Fund: <?php echo $fund; ?><br>
				Effective Date: <?php echo date('m/d/Y'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table class="table table-sm">
					<thead>
						<tr>
							<td colspan="2"></td>
							<th>PO</th>
							<th class="monetary">Proposed</th>
							<th class="monetary">Encumbered</th>
							<th class="monetary">Spent</th>
							<th class="monetary">Balance</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = "SELECT purchase_code, initial FROM budget WHERE purchase_code LIKE '$fund-%' ORDER BY purchase_code";
						$result = mysqli_query($dbc, $query);
						while ($row = mysqli_fetch_array($result)) {
							?>
							<tr class="linetitlerow">
								<td colspan="7"><?php echo $row['purchase_code']; ?></td>
							</tr>
							<tr>
								<td colspan="6"></td>
								<td class="monetary"><?php echo number_format($row['initial'], 2); ?></td>
							</tr>
							<?php
							$balance = $row['initial'];
							$purchase_code = $row['purchase_code'];
							$query = "SELECT form_id, vendor, date, po, description, amount, status FROM budget_activities WHERE purchase_code = '$purchase_code' ORDER BY date ASC";
							$data = mysqli_query($dbc, $query);
							if (mysqli_num_rows($data) > 0) {
								while ($line = mysqli_fetch_array($data)) {
									echo '<tr>';
									echo '<td>' . makeDateAmerican($line['date']) . '</td>';
									echo '<td>' . $line['vendor'] . ' Form ' . $line['form_id'] . '</td>';
									echo '<td>' . $line['po'] . '</td>';
									if ($line['vendor'] != 'Deposit') {
										$amount = -$line['amount'];
									}
									else {
										$amount = $line['amount'];
									}
									if ($line['status'] == 'Proposed') {
										echo '<td class="monetary">' . number_format($amount, 2) . '</td><td colspan="2"></td>';
									}
									else if ($line['status'] == 'Encumbered') {
										echo '<td></td><td class="monetary">' . number_format($amount, 2) . '</td><td></td>';
									}
									else {
										echo '<td colspan="2"></td><td class="monetary">' . number_format($amount, 2) . '</td>';
									}
									$balance = $balance + $amount;
									echo '<td class="monetary';
									if ($balance < 0) {
										echo ' text-danger';
									}
									echo '">' . number_format($balance, 2) . '</td>';
									echo '</tr>';

								}
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
echo '</div>';

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
