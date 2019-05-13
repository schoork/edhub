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
  School/Office: <?php echo $row['program']; ?>
</p>
<input type="hidden" name="form_type" value="timeoff">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Request Details
</h5>
<?php
$query = "SELECT * FROM forms_timeoff WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<p>
  Number of Days Requested: <?php echo $row['number']; ?><br/>
  <?php
  if ($row['length'] != '') {
    echo 'Length: ' . $row['length'];
  }
  ?>
  <br/>
  Dates Requested: <?php echo makeDateAmerican($row['start']) . ' - ' . makeDateAmerican($row['end']); ?><br/>
  Reason for Leave: <?php echo $row['type']; ?><br/>
  Relationship to Employee: <?php echo $row['relationship']; ?><br/>
  Description of Leave: <?php echo $row['description']; ?><br/>
</p>