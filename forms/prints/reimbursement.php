<?php
$page_title = 'Reimbursement Voucher';
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
$payroll = $row['payroll'];
$superintendent = $row['superintendent'];

$query = "SELECT * FROM forms_reimbursement WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$purchase_code = $row['purchase_code'];
$travelForm = $row['travel_form'];

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
      VOUCHER FOR REIMBURSEMENT OF EXPENSE INCIDENT TO OFFICIAL TRAVEL
    </div>
  </div>
  <div class="row">
    <table class="mb-3 mt-3 table table-bordered">
      <tr>
        <th>Employee</th>
        <td ><?php echo $employee; ?></td>
        <th>Total Reimbursement</th>
        <td>$<?php echo $row['total']; ?></td>
        <th>Purchasing Code</th>
        <td><?php echo $purchase_code; ?></td>
        <th>Date Submitted</th>
        <td><?php echo $submit_date; ?></td>
      </tr>
    </table>
  </div>
  <div class="row">
    <table class="mb-3 mt-3 table table-bordered table-responsive table-sm table-striped reimbTable">
      <tr>
        <th colspan="2">In-State Travel</th>
        <td style="background-color: black;" rowspan="9"></td>
        <th colspan="2">Out-of-State Travel</th>
      </tr>
      <tr>
        <th>Per Diem</th>
        <td >$<?php echo $row['instate_perdiem']; ?></td>
        <th>Per Diem</th>
        <td >$<?php echo $row['outstate_perdiem']; ?></td>
      </tr>
      <tr>
        <th>Taxable Meals</th>
        <td >$<?php echo $row['instate_mealstax']; ?></td>
        <th>Taxable Meals</th>
        <td >$<?php echo $row['outstate_mealstax']; ?></td>
      </tr>
      <tr>
        <th>Non-Taxable Meals</th>
        <td >$<?php echo $row['instate_mealsnotax']; ?></td>
        <th>Non-Taxable Meals</th>
        <td >$<?php echo $row['outstate_mealsnotax']; ?></td>
      </tr>
      <tr>
        <th>Lodging</th>
        <td >$<?php echo $row['instate_lodging']; ?></td>
        <th>Lodging</th>
        <td >$<?php echo $row['outstate_lodging']; ?></td>
      </tr>
      <tr>
        <th>Private Travel</th>
        <td >$<?php echo $row['instate_travelprivate']; ?></td>
        <th>Private Travel</th>
        <td >$<?php echo $row['outstate_travelprivate']; ?></td>
      </tr>
      <tr>
        <th>Public Travel</th>
        <td >$<?php echo $row['instate_travelpublic']; ?></td>
        <th>Public Travel</th>
        <td >$<?php echo $row['outstate_travelpublic']; ?></td>
      </tr>
      <tr>
        <th>Other</th>
        <td >$<?php echo $row['instate_other']; ?></td>
        <th>Other</th>
        <td >$<?php echo $row['outstate_other']; ?></td>
      </tr>
      <tr>
        <th>Subtotal</th>
        <td >$<?php echo $row['instate_total']; ?></td>
        <th>Subtotal</th>
        <td >$<?php echo $row['outstate_total']; ?></td>
      </tr>
    </table>
  </div>
  <div class="row">
    <table class="mt-3" style="width: 100%;" id="signedTable">
      <tr class="signedRow">
        <td class="signed" width="20%"><?php echo $employee; ?></td>
        <td class="signed" width="20%"><?php echo $payroll; ?></td>
        <td class="signed" width="20%"><?php echo $superintendent; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>Payroll</td>
        <td>Superintendent</td>
      </tr>
    </table>
  </div>
  <div class="row">
    <p class="mt-3">
      <strong>Form History (#<?php echo $formId; ?>):</strong><br/>
      <em>
        <?php
        $query = "SELECT action, user, date_time FROM forms_log WHERE formId = $formId";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)) {
          echo $row['action'] . ' by ' . $row['user'] . ' on ' . parseDatetime($row['date_time']) . '<br/>';
        }
        ?>
      </em>
    </p>
  </div>
  <div class="row">
    <p>
      <strong>Supporting Documents:</strong><br/>
      <?php
      $query = "SELECT docname FROM forms_reimbursements_docsf WHERE formId = $formId";
      $result = mysqli_query($dbc, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
          echo '<a href="../../documents/' . $row['docname'] . '"  target="_blank">' . $row['docname'] . '</a><br/>';
        }
      }
      echo '<a href="outoftowntravel.php?formId=' . $travelForm . '" target="_blank">Out of District Travel Form #' . $travelForm . '</a>';
      ?>
    </p>
  </div>
  <?php
  $query = "SELECT * FROM forms_reimbursement_breakdown WHERE form_id = $formId ORDER BY row_id ASC";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    ?>
    <div class="row">
      <div class="col" style="text-align: center;">
        <strong>BREAKDOWN OF SUBSISTENCE AND TRAVEL EXPENSES</strong>
      </div>
    </div>
    <div class="row">
      <table class="mb-3 mt-3 table table-bordered table-sm table-striped table-center">
        <tr>
          <th>Type</th>
          <th>Day</th>
          <th>Breakfast</th>
          <th>Lunch</th>
          <th>Dinner</th>
          <th>Total Allowed</th>
        </tr>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
          echo '<tr>';
          if ($row['type'] == 'instate') {
            echo '<td>In-State</td>';
          }
          if ($row['type'] == 'instate') {
            echo '<td>Out-of-State</td>';
          }
          echo '<td>Day ' . $i . '</td>';
          echo '<td>$' . $row['breakfast'] . '</td>';
          echo '<td>$' . $row['lunch'] . '</td>';
          echo '<td>$' . $row['dinner'] . '</td>';
          echo '<td>$' . $row['amount'] . '</td>';
          echo '</tr>';
          $i++;
        }
        ?>
      </table>
    </div>
    <?php
  }
  ?>

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
