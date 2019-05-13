<?php
$page_title = 'Activity Request Form';
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
$program = $row['program'];
$submit_date = makeDateAmerican($row['submit_datetime']);
$superintendent = $row['superintendent'];


$query = "SELECT * FROM forms_activity WHERE formId = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$school = $row['program'];
switch ($school) {
  case 'Sanders':
    $principal = $row['sanders'];
    break;
  case 'Simmons':
    $principal = $row['simmons'];
    break;
}

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
      Activity Request Form
    </h3>
  </div>
  <div class="row">
    <table style="width: 100%;" id="infoTable" class="mb-3 mt-3">
      <tr>
        <td class="entry"><?php echo $employee; ?></td>
        <td class="entry"><?php echo $school; ?></td>
        <td class="entry"><?php echo $row['location']; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>School</td>
        <td>Location</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $program; ?></td>
        <td class="entry"><?php echo $row['purpose'];?></td>
        <td class="entry"><?php echo makeDateAmerican($row['submit_datetime']); ?></td>
      </tr>
      <tr class="label">
        <td>Activity</td>
        <td>Purpose</td>
        <td>Submit Date</td>
      </tr>
      <tr>
        <td class="entry"><?php echo makeDateAmerican($row['date']); ?></td>
        <td class="entry"><?php echo parseTime($row['start']); ?></td>
        <td class="entry"><?php echo parseTime($row['end']); ?></td>
      </tr>
      <tr class="label">
        <td>Activity Date</td>
        <td>Start Time</td>
        <td>End Time</td>
      </tr>
    </table>
  </div>
  <table class="mt-3" style="width: 100%;" id="signedTable">
    <tr class="signedRow">
      <td class="signed" width="33%"><?php echo $employee; ?></td>
      <td class="signed" width="33%"><?php echo $principal; ?></td>
      <td class="signed" width="33%"><?php echo $superintendent; ?></td>
    </tr>
    <tr class="label">
      <td>Employee</td>
      <td>Principal</td>
      <td>Superintendent's Secretary</td>
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
