<?php
$page_title = 'Bus Request Form';
$page_access = 'All';
include('../../header.php');

require_once('../../connectvars.php');
require_once('../../appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, $_GET['formId']);

$query = "SELECT * FROM forms LEFT JOIN staff_list ON (forms.employee = staff_list.username) WHERE form_id = $formId ";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$program = $row['program'];
$employee = $row['firstname'] . ' ' . $row['lastname'];
$location = $row['location'];
$submit_date = makeDateAmerican($row['submit_datetime']);
$transportation = $row['transportation'];

$query = "SELECT * FROM forms_busrequest WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$total = $row['total'];

//include other scripts needed here
echo '<link rel="stylesheet" href="forms_print.css">';

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <!-- Write HTML just like a web page -->
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

  <div class="row">
    <div class="col" style="text-align: center;">
      <span id="districtName">Hollandale School District</span><br/>
      101 W. Washington St. PO Box 128<br/>
      Hollandale, MS 38748<br/>
      Phone: (662) 827-2276<br/>
      Fax: (662) 827-5261
    </div>
  </div>
  <div class="row">
    <h3>
      Bus Request
    </h3>
  </div>
  <div class="row">
    <table class="mb-3 mt-3 table table-bordered table-sm">
      <tr>
        <th>Employee:</th>
        <td><?php echo $employee; ?></td>
        <th>Purpose of Trip:</th>
        <td colspan="3"><?php echo $program; ?></td>
      </tr>
      <tr>
        <th>Supervising Faculty:</th>
        <td><?php echo $row['faculty']; ?></td>
        <th>Safety Team Member(s):</th>
        <td><?php echo $row['safety']; ?></td>
        <th>Destination:</th>
        <td><?php echo $row['location']; ?></td>
      </tr>
      <tr>
        <th>Departing:</th>
        <td><?php echo parseDatetime($row['travel_start']); ?></td>
        <th>Returning:</th>
        <td><?php echo parseDatetime($row['travel_end']); ?></td>
        <th>Length:</th>
        <td><?php echo $row['length']; ?></td>
      </tr>
      <tr>
        <th>Number of Buses:</th>
        <td><?php echo $row['bus_num']; ?></td>
        <th>Drivers Provided by Program:</th>
        <td><?php echo $row['drivers']; ?></td>
        <th>Drivers Assigned:</th>
        <td><?php echo $row['drivers_assigned']; ?></td>
      </tr>
    </table>
  </div>
  <div class="row">
    <h4>
      Trip Costs
    </h4>
  </div>
  <div class="row">
    <table style="width: 50%;" class="mb-3 mt-3">
      <tr>
        <td class="entry"><?php echo $row['driver_num']; ?></td>
        <td>x</td>
        <td class="entry"><?php echo $row['hours']; ?></td>
        <td>=</td>
        <td class="entry">$<?php echo $row['driver_cost']; ?></td>
      </tr>
      <tr class="label">
        <td>Number of Drivers Needed</td>
        <td></td>
        <td>Number of Hours</td>
        <td></td>
        <td>Cost of Drivers ($11/hour)</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['bus_num']; ?></td>
        <td>x</td>
        <td class="entry"><?php echo $row['miles']; ?></td>
        <td>=</td>
        <td class="entry">$<?php echo $row['miles_cost']; ?></td>
      </tr>
      <tr class="label">
        <td>Number of Buses Needed</td>
        <td></td>
        <td>Number of Miles Driven</td>
        <td></td>
        <td>Cost of Buses ($1/mile)</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="entry">$<?php echo $row['total']; ?></td>
      </tr>
      <tr class="label">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Trip Cost</td>
      </tr>
      <tr>
        <td class="entry" colspan="5"><?php echo $row['purchase_code']; ?></td>
      </tr>
      <tr class="label">
        <td colspan="5">Purchase Code for Trip Expenses</td>
      </tr>
    </table>
  </div>

  <table class="mt-3" style="width: 100%;" id="signedTable">
    <tr class="signedRow">
      <td class="signed" width="33%"><?php echo $employee; ?></td>
      <td class="signed" width="33%"><?php echo $transportation; ?></td>
    </tr>
    <tr class="label">
      <td>Employee</td>
      <td>Transportation Director</td>
    </tr>
  </table>
  <p>
      <strong>Form History (#<?php echo $formId; ?>):</strong><br/>
      <em>
        <?php
        $query = "SELECT action, firstname, lastname, date_time FROM forms_log LEFT JOIN staff_list ON (forms_log.user = staff_list.username) WHERE formId = $formId ORDER BY logId ASC";
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
