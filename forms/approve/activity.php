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
  Activity: <?php echo $row['program']; ?>
</p>
<input type="hidden" name="form_type" value="activity">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Activity Details
</h5>
<?php
$query = "SELECT * FROM forms_activity WHERE formId = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<p>
  Purpose: <?php echo $row['purpose']; ?><br/>
  Location: <?php echo $row['location']; ?><br/>
  School: <?php echo $row['school']; ?><br/>
  Date: <?php echo makeDateAmerican($row['date']); ?><br/>
  Times: <?php echo parseTime($row['start']) . ' - ' . parseTime($row['end']); ?><br/>
  Method of Travel: <?php echo $row['method']; ?><br/>
  Purchasing Code for Payment: <?php echo $row['purchase_code']; ?>
</p>