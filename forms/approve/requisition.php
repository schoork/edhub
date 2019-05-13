<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/hollandale/connectvars.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/hollandale/appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, trim($_GET['formId']));
$query = "SELECT * FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$program = $row['program'];
$reason = $row['reason'];
?>
<p>
  Requested by: <?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br/>
  Submitted: <?php echo parseDatetime($row['submit_datetime']); ?>
</p>
<?php
$query = "SELECT * FROM forms_requisition WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$total = $row['total'];
?>
<input type="hidden" name="form_type" value="requisition">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Vendor Details
</h5>
<p>
  <?php echo $row['vendor']; ?><br/>
  <?php echo $row['address']; ?><br/>
  <?php echo $row['city'] . ' ' . $row['zipcode']; ?><br/>
</p>
<h5>
  Requistion Details
</h5>
<p>
  School/Program: <?php echo $program; ?><br/>
  Objective: <?php echo $row['objective']; ?><br/>
</p>
<?php
$query = "SELECT * FROM forms_requisitions_items WHERE formId = $formId";
$result = mysqli_query($dbc, $query);
$balances = array();
while ($row = mysqli_fetch_array($result)) {
  $i = $row['itemId'];
  ?>
  <p>
    <strong>Item #<?php echo $i; ?></strong><br/>
    Part Number: <?php echo $row['part_num']; ?><br/>
    Description: <?php echo $row['description']; ?><br/>
    Quantity: <?php echo $row['quantity']; ?><br/>
    Unit Cost: $<?php echo number_format($row['unit_cost'], 2); ?><br/>
    <?php $price = round($row['quantity'] * $row['unit_cost'], 2); ?>
    Estimated Price: $<?php echo number_format($price, 2); ?><br/>
    Purchasing Code: <br/>
    <?php
    $purchase_code = $row['purchase_code'];
    if ($purchase_code == '------') {
      $purchase_code = '';
    }
    $balances[$purchase_code] += $price;
    ?>
    <div class="form-group row">
      <div class="col">
        <input type="text" class="form-control purchase_code" name="purchase_code-<?php echo $i; ?>" value="<?php echo $purchase_code; ?>" maxlength="30">
      </div>
    </div>
  </p>
  <hr>
  <?php
}
?>
<p>
  Total Estimated Cost: $<?php echo number_format($total, 2); ?>
</p>
<table class="table table-small table-striped">
  <tr>
    <th>Purchasing Code</th>
    <th>Already Spent</th>
    <th>Spending in This Req.</th>
  </tr>
  <?php
  foreach ($balances as $key => $value) {
    $query = "SELECT sum(amount) AS amount FROM budget_activities WHERE purchase_code = '$key' AND status IN ('Encumbered', 'Spent')";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    echo '<tr><td>' . $key . '</td><td>$' . number_format($row['amount'], 2) . '</td><td>$' . number_format($value, 2) . '</td></tr>';
  }
  ?>
</table>
<div class="alert alert-info" role="alert">
  All items must have a purchase code. Any item that does not have a purchase code will not appear on the approved requisition.
</div>
<h5>
  Supporting Documents
</h5>
<p>
  <?php
  $query = "SELECT docname FROM forms_docs WHERE form_id = $formId";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      echo '<a href="' . $row['docname'] . '"  target="_blank">' . substr($row['docname'], 10) . '</a><br/>';
    }
  }
  ?>
</p>
<h5>
  Form History
</h5>
<p>
  <?php
  $query = "SELECT action, firstname, lastname, date_time FROM forms_log LEFT JOIN staff_list ON (forms_log.user = staff_list.username) WHERE formId = $formId ORDER BY logId ASC";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    echo $row['action'] . ' by ' . $row['firstname'] . ' ' . $row['lastname'] . ' on ' . parseDatetime($row['date_time']). '<br/>';
  }
  if ($reason != '') {
    echo '<br>Denial Reason: ' . $reason;
  }
  ?>
</p>
