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
  Program: <?php echo $row['program']; ?>
</p>
<input type="hidden" name="form_type" value="busrequest">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Trip Details
</h5>
<?php
$query = "SELECT * FROM forms_busrequest WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<p>
  Purpose/Name of Trip: <?php echo $row['title']; ?><br/>
  Destination: <?php echo $row['location']; ?><br/>
  Number of Students: <?php echo $row['number']; ?><br/>
  Faculty Member(s): <?php echo $row['faculty']; ?><br/>
  Safety Council Member(s): <?php echo $row['safety']; ?><br/>
  Leaving: <?php echo parseDatetime($row['travel_start']); ?><br/>
  Returning: <?php echo parseDatetime($row['travel_end']); ?><br/>
  Length of Trip: <?php echo $row['length']; ?>
</p>
<h5>
  Trip Expenses
</h5>
<p>
  Payment Provided by: <?php echo $row['pay_group']; ?><br/>
  Purchasing Code for Payment: <?php echo $row['purchase_code']; ?><br/>
  Number of Buses: <?php echo $row['bus_num']; ?><br/>
  Number of Miles: <?php echo $row['miles']; ?><br/>
  Number of Drivers: <?php echo $row['driver_num']; ?><br/>
  Drivers Provided by Program: <?php echo $row['drivers']; ?><br/>
  Number of Hours: <?php echo $row['hours']; ?><br/>
  Mileage Cost: $<?php echo $row['miles_cost']; ?><br/>
  Drivers Cost: $<?php echo $row['driver_cost']; ?><br/>
  Total Cost: $<?php echo $row['total']; ?><br/>
</p>
<div class="form-group">
  <label for="drivers_assigned">Drivers Assigned</label>
  <input type="text" class="form-control" name="drivers_assigned" id="drivers_assigned" value="<?php echo $row['drivers_assigned']; ?>">
</div>
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
  ?>
</p>