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
  Program: <?php echo $row['program']; ?>
</p>
<input type="hidden" name="form_type" value="fundraiser">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  Fundraiser Details
</h5>
<?php
$query = "SELECT * FROM forms_fundraiser WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<p>
  Fundraising Group: <?php echo $row['title']; ?><br/>
  Description: <?php echo $row['description']; ?><br/>
  Proposed Use of Profits: <?php echo $row['objective']; ?><br/>
  School: <?php echo $row['school']; ?><br/>
  Solicitation: <?php echo $row['location']; ?><br/>
  Was this done in the past? <?php echo $row['past']; ?><br/>
  Dates of Fundraiser: <?php echo makeDateAmerican($row['start']) . ' - ' . makeDateAmerican($row['end']); ?><br/>
  Do you need to purchase items? <?php echo $row['purchase']; ?><br/>
  Will you need to use school district facilities? <?php echo $row['facility']; ?>
</p>
<h5>
  Revenue and Profits
</h5>
<p>
  Estimated Upfront Costs: $<?php echo $row['cost']; ?><br/>
  Purchasing Account: <?php echo $row['purchase_code']; ?><br/>
  Estimated Total Revenue: $<?php echo $row['revenue']; ?><br/>
  Depositing Account: <?php echo $row['revenue_code']; ?><br/>
  Estimated Total Profit: $<?php echo $row['profit']; ?>
</p>