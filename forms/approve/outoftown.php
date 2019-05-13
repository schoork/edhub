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
  Submitted: <?php echo $row['submit_datetime']; ?><br/>
  Name of Meeting: <?php echo $row['program']; ?>
</p>
<input type="hidden" name="form_type" value="outoftown">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Trip Details
</h5>
<?php
$query = "SELECT * FROM forms_outoftown WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$purchase_code = explode("-", $row['purchase_code']);
?>
<p>
  Purpose of Trip: <?php echo $row['purpose']; ?><br/>
  Destination: <?php echo $row['location']; ?><br/>
  Dates of Meeting: <?php echo makeDateAmerican($row['start']) . ' - ' . makeDateAmerican($row['end']); ?><br/>
  Departure Date: <?php echo makeDateAmerican($row['travel_start']); ?><br/>
  Returning Date: <?php echo makeDateAmerican($row['travel_start']); ?><br/>
  Method of Travel: <?php echo $row['method']; ?><br/>
  Expected Cost: $<?php echo $row['expected_cost']; ?><br/>
  Purchasing Code: <br/>
  <?php
  $total = $row['expected_cost'];
  $purchase_code = $row['purchase_code'];
  if ($purchase_code == '------') {
    $purchase_code = '';
  }
  ?>
  <div class="form-group row">
    <div class="col">
      <input type="text" class="form-control purchase_code" name="purchase_code" value="<?php echo $purchase_code; ?>" maxlength="30">
    </div>
  </div>
</p>
<h5>
  Checks Requested
</h5>
<?php
$query = "SELECT * FROM forms_outoftown_checks WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
  $i = 1;
  while ($row = mysqli_fetch_array($result)) {
    echo '<p>';
    echo 'Check #' . $i . '<br/>';
    echo 'Payable To: ' . $row['name'] . '<br/>';
    echo 'Address:<br/>';
    echo $row['address'] . '<br/>';
    echo $row['city'] . '<br/>';
    echo $row['zipcode'] . '<br/>';
    echo 'Reason: ' . $row['reason'] . '<br/>';
    echo 'Amount: $' . $row['amount'] . '<br/>';
    echo 'Purchasing Code: ' . $row['purchase_code'];
    echo '</p>';
    $total += $row['amount'];
    $i++;
  }
}
else {
  echo '<p>No checks have been requested.</p>';
}
?>
<p>
  <strong>Total Trip Cost: $<?php echo number_format($total, 2); ?></strong>
</p>
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
