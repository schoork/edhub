<?php

$page_title = 'Budget';
$page_access = 'Admin Superintendent Dept Head Principal';
include('header.php');

//include other scripts needed here
?>
<link href="css/budget_styles.css" rel="stylesheet"/>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="js/datatables_scripts.js"></script>
<script src="js/budget_scripts.js"></script>

<?php

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-district.php');

?>

<?php

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			District
		</h1>
    <p class="lead">
      Manage budgets, forms, and inventory
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Budget
			</h1>
			<p class="lead">
				From this page you can see your budget lines.
			</p>
      <p>
        You will only be able to see budget lines for which you have been approved to manage. The Superintendent manages these permissions.
			</p>
			<p>
				<a class="btn btn-primary" href="budget_generalledger.php" target="_blank">View General Ledger</a>
				<a class="btn btn-primary" href="budget_printchecks.php">Print Checks</a>
			</p>
      <table class="table table-sm table-hover mt-4 dataTbl" id="budgetTbl">
        <thead>
          <tr>
            <th>Budget Line</th>
            <th>Description</th>
						<th>YTD Encumbered</th>
            <th>YTD Spent</th>
						<th>Initial Budget</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT line_id, b.purchase_code, b.description, initial, sum(CASE WHEN ba.status = 'Encumbered' THEN amount ELSE 0 END) AS total_encumbered, sum(CASE WHEN ba.status = 'Spent' THEN amount ELSE 0 END) AS total_spent, sum(CASE WHEN ba.status = 'Proposed' THEN amount ELSE 0 END) AS total_proposed FROM budget AS b LEFT JOIN budget_activities AS ba USING (purchase_code) GROUP BY b.purchase_code ORDER BY b.purchase_code";
          $result = mysqli_query($dbc, $query);
          while ($row = mysqli_fetch_array($result)) {
            echo '<tr style="cursor: pointer;" id="line-' . $row['line_id'] . '">';
						echo '<td>' . $row['purchase_code'] . '</td>';
						echo '<td>' . $row['description'] . '</td>';
						echo '<td class="monetary-col">' . number_format($row['total_encumbered'], 2) . '</td>';
						echo '<td class="monetary-col">' . number_format($row['total_spent'], 2) . '</td>';
						echo '<td class="monetary-col">' . number_format($row['initial'], 2) . '</td>';
						$balance = $row['initial'] - $row['total_spent'] - $row['total_encumbered'];
						echo '<td class="monetary-col';
						if ($balance <= 0) {
							echo ' text-danger';
						}
						echo '">' . number_format($balance, 2) . '</td>';
						echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!--Ledger Modal-->
<div class="modal fade" role="dialog" id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Expense Review - <span id="purchase_code"></span></h5>
      </div>
      <div class="modal-body">
				<table class="table table-sm table-striped" id="lineTbl">
					<thead>
						<tr>
							<th>Vendor</th>
							<th>Date</th>
							<th>Form N.</th>
							<th>Description</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
