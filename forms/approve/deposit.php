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
</p>
<input type="hidden" name="form_type" value="deposit">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Deposit Details
</h5>
<?php
$query = "SELECT * FROM forms_deposits WHERE formId = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<p>
  School: <?php echo $row['school']; ?><br/>
  Revenue Source: <?php echo $row['account']; ?><br/>
  Description: <?php echo $row['description']; ?><br/>
  Amount Deposited: $<?php echo $row['amount']; ?><br/>
  Revenue Code: <?php echo $row['revenue_code']; ?><br/>
  Receipt Number: <?php echo $row['receipt']; ?>
</p>
<div class="alert alert-info" role="alert">
  When you approve this deposit, you are stating that you have checked it for accuracy and matched it against the actual amount of money deposited.
</div>
