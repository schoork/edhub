<?php
$page_title = 'Time Off Request Form';
$page_access = 'All';
include('../../header.php');

require_once('../../connectvars.php');
require_once('../../appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, $_GET['formId']);

$query = "SELECT * FROM forms LEFT JOIN staff_list ON (forms.employee = staff_list.username) WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$employee = $row['firstname'] . ' ' . $row['lastname'];
$location = $row['location'];
$submit_date = makeDateAmerican($row['submit_datetime']);
$payroll = $row['payroll'];
$school = $row['program'];
switch ($school) {
  case 'Sanders':
    $supervisor = $row['sanders'];
    break;
  case 'Simmons':
    $supervisor = $row['simmons'];
    break;
  default:
    $supervisor = $row['superintendent'];
}

$query = "SELECT * FROM forms_timeoff WHERE formId = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

//include other scripts needed here
echo '<link rel="stylesheet" href="forms_print.css">';

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <?php
  if ($_SESSION['access'] == 'Admin') {
    ?>
    <div class="row hidden-print">
     <p>
       <a class="btn btn-success" href="../../approveform.php?formId=<?php echo $formId;?>&type=BusRequest">Edit Form</a>
     </p>
    </div>
    <?php
  }
  ?>
  <!-- Write HTML just like a web page -->
  <div class="row">
    <div class="col" style="text-align: center;">
      <span id="districtName">Hollandale School District</span><br/>
      101 W. Washington St. PO Box 128<br/>
      Hollandale, MS 38748<br/>
      Phone: (662) 827-2276<br/>
      Fax: (662) 827-5261
    </div>
  </div>
  <div class="row mt-3">
    <h3>
      Time Off Request and Substitute Pay Sheet
    </h3>
  </div>
  <div class="row">
    <table style="width: 100%;" id="infoTable" class="mb-3 mt-3">
      <tr>
        <td class="entry"><?php echo $employee; ?></td>
        <td class="entry"><?php echo $school; ?></td>
        <td class="entry"><?php echo $submit_date; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>School/Office</td>
        <td>Submitted Date</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['number'] . ' / ' . $row['length']; ?></td>
        <td class="entry"><?php echo makeDateAmerican($row['start']) . ' - ' . makeDateAmerican($row['end']); ?></td>
        <td class="entry"><?php echo $row['type']; ?></td>
      </tr>
      <tr class="label">
        <td>Number of Days Requested / Length</td>
        <td>Dates of Leave</td>
        <td>Type of Leave</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['relationship']; ?></td>
        <td class="entry" colspan="2"><?php echo $row['description']; ?></td>
      </tr>
      <tr class="label">
        <td>Relationship to Employee</td>
        <td colspan="2">Description of Leave</td>
      </tr>
    </table>
  </div>
  <div class="row">
    <h4>
      Substitute Information
    </h4>
  </div>
  <div class="row">
    <table style="width: 50%;" class="mb-3 mt-3 infoTable">
      <tr>
        <td class="entry"><?php echo $row['sub_assigned']; ?></td>
        <td class="entry"></td>
      </tr>
      <tr class="label">
        <td>Substitute</td>
        <td>Date Worked</td>
      </tr>
      <tr style="height:2em">
        <td class="entry"></td>
        <td class="entry"></td>
      </tr>
      <tr class="label">
        <td>Time In</td>
        <td>Time Out</td>
      </tr>
    </table>
    <p>
      <em>This form should be printed and turned into the District Office each day that the above employee is out.</em>
    </p>
  </div>

  <table class="mt-3" style="width: 100%;" id="signedTable">
    <tr class="signedRow">
      <td class="signed" width="33%"><?php echo $employee; ?></td>
      <td class="signed" width="33%"><?php echo $supervisor; ?></td>
      <td class="signed" width="33%"><?php echo $payroll; ?></td>
    </tr>
    <tr class="label">
      <td>Employee</td>
      <td>Principal / Supervisor</td>
      <td>Payroll Director</td>
    </tr>
    <tr class="signedRow">
      <td class="signed" width="33%"></td>
      <td width="33%"></td>
      <td class="signed" width="33%"></td>
    </tr>
    <tr class="label">
      <td>Substitute</td>
      <td></td>
      <td>Principal / Secretary</td>
    </tr>
  </table>
  <p>
    <strong>Form History (#<?php echo $formId; ?>):</strong><br/>
    <em>
      <?php
      $query = "SELECT action, firstname, lastname, date_time FROM forms_log LEFT JOIN staff_list ON (forms_log.user = staff_list.username) WHERE formId = $formId";
      $result = mysqli_query($dbc, $query);
      while ($row = mysqli_fetch_array($result)) {
        echo $row['action'] . ' by ' . $row['firstname'] . ' ' . $row['lastname'] . ' on ' . parseDatetime($row['date_time']). '<br/>';
      }
      ?>
    </em>
  </p>
</body>
<script>
  $(document).ready(function() {
    window.print();
  });
</script>
<?php

mysqli_close($dbc);

?>
</html>
