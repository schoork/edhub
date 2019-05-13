<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/hollandale/connectvars.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/hollandale/appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, trim($_GET['formId']));
$query = "SELECT * FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<p>
  Requested by: <?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br/>
  Submitted: <?php echo parseDatetime($row['submit_datetime']); ?><br/>
  Reason for Reimbursements: <?php echo $row['program']; ?>
</p>
<?php
$query = "SELECT * FROM forms_reimbursement WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<input type="hidden" name="form_type" value="reimbursement">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Instate Totals
</h5>
<p>
  Per Diem: $<?php echo $row['instate_perdiem']; ?><br/>
  Taxable Meals: $<?php echo $row['instate_mealstax']; ?><br/>
  Non-Taxable Meals: $<?php echo $row['instate_mealsnotax']; ?><br/>
  Lodging: $<?php echo $row['instate_lodging']; ?><br/>
  Private Travel: $<?php echo $row['instate_travelprivate']; ?><br/>
  Public Travel: $<?php echo $row['instate_travelpublic']; ?><br/>
  Other: $<?php echo $row['instate_other']; ?><br/>
  <strong>Subtotal: $<?php echo $row['instate_total']; ?></strong>
</p>
<h5>
  Out of State Totals
</h5>
<p>
  Per Diem: $<?php echo $row['outstate_perdiem']; ?><br/>
  Taxable Meals: $<?php echo $row['outstate_mealstax']; ?><br/>
  Non-Taxable Meals: $<?php echo $row['outstate_mealsnotax']; ?><br/>
  Lodging: $<?php echo $row['outstate_lodging']; ?><br/>
  Private Travel: $<?php echo $row['outstate_travelprivate']; ?><br/>
  Public Travel: $<?php echo $row['outstate_travelpublic']; ?><br/>
  Other: $<?php echo $row['outstate_other']; ?><br/>
  <strong>Subtotal: $<?php echo $row['outstate_total']; ?></strong>
</p>
<h5>
  Total
</h5>
<p>
  <strong>Total: $<?php echo $row['total']; ?></strong><br/>
  Travel Request Form ID: <?php echo $row['travel_form']; ?><br/>
  <?php
  $travel_form = $row['travel_form'];
  $query = "SELECT expected_cost, status, purchase_code FROM forms_outoftown AS town LEFT JOIN forms AS f ON (town.formId = f.form_id) WHERE formId = $travel_form";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    echo 'Travel Form Status: ' . $row['status'] . '<br/>';
    echo 'Travel Purchase Code: ' . $row['purchase_code'] . '<br/>';
    echo 'Expected Cost: $' . $row['expected_cost'] . '<br/>';
  }
  else {
    echo '<strong><em>This form ID does not match a submitted Out of Town Travel Request form.</em></strong><br/>';
  }
  ?>
  Purchasing Code: <br/>
  <?php
  $purchase_code = $row['purchase_code'];
  if ($purchase_code == '------') {
    $purchase_code = '';
  }
  ?>
  <div class="form-group row">
    <div class="col">
      <input type="text" class="form-control purchase_code" name="purchase_code" maxlength="30" value="<?php echo $purchase_code; ?>">
    </div>
  </div>
</p>
<h5>
  Supporting Documents
</h5>
<p>
  <?php
  $query = "SELECT docname FROM forms_reimbursements_docsf WHERE formId = $formId";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      echo '<a href="documents/' . $row['docname'] . '" target="_blank" download>' . $row['docname'] . '</a><br/>';
    }
  }
  $query = "SELECT docname FROM forms_docs WHERE form_id = $formId";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      echo '<a href="' . $row['docname'] . '" target="_blank" download>' . $row['docname'] . '</a><br/>';
    }
  }
  ?>
</p>
<?php
mysqli_close($dbc);
?>
