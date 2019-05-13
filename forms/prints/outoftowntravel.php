<?php
$page_title = 'Out of Town Travel Request Form';
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
$submit_date = makeDateAmerican($row['submit_datetime']);
$accounts_payable = $row['accounts_payable'];
$superintendent = $row['superintendent'];


$query = "SELECT * FROM forms_outoftown WHERE formId= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$purchase_code = $row['purchase_code'];

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
    <div class="col">
      REQUEST FOR ATTENDANCE AT PROFESSIONAL ACTIVITY OUTSIDE OF DISTRICT
    </div>
  </div>
  <div class="row">
    <table class="mb-3 mt-3 table table-bordered table-sm">
      <tr>
        <th>Employee:</th>
        <td ><?php echo $employee; ?></td>
        <th>Name of Meeting:</th>
        <td><?php echo $program; ?></td>
        <th>Location:</th>
        <td><?php echo $row['location']; ?></td>
      </tr>
      <tr>
        <th>Purpose:</th>
        <td colspan="3"><?php echo $row['purpose']; ?></td>
        <th>Date Submitted:</th>
        <td><?php echo $submit_date; ?></td>
      </tr>
      <tr>
        <th>Dates of Meeting:</th>
        <td><?php echo makeDateAmerican($row['start']) . ' - ' . makeDateAmerican($row['end']); ?></td>
        <th>Dates of Travel:</th>
        <td><?php echo makeDateAmerican($row['travel_start']) . ' - ' . makeDateAmerican($row['travel_end']); ?></td>
        <th>Method of Travel:</th>
        <td><?php echo $row['method']; ?></td>
      </tr>
    </table>
  </div>
  <?php
  $query = "SELECT * FROM forms_outoftown_checks WHERE form_id = $formId";
  $result = mysqli_query($dbc, $query);
  if ($row['expected_cost'] > 0 || mysqli_num_rows($result) > 0) {
    ?>
    <div class="row">
      <table class="mb-3 mt-3 table table-bordered table-sm">
        <tr>
          <th>Type</th>
          <th>Payable To</th>
          <th>Needed By</th>
          <th>Purchasing Code</th>
          <th>Amount</th>
        </tr>
        <?php
        if ($row['expected_cost'] > 0) {
          echo '<tr>';
          echo '<td>Expected Reimbursement</td>';
          echo '<td>N/A</td>';
          echo '<td>N/A</td>';
          echo '<td>' . $purchase_code . '</td>';
          echo '<td>' . $row['expected_cost'] . '</td>';
          echo '</tr>';
        }
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<td>Check - ' . $row['reason'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . makeDateAmerican($row['checkdate']) . '</td>';
            echo '<td>' . $row['purchase_code'] . '</td>';
            echo '<td>' . $row['amount'] . '</td>';
            echo '</tr>';
          }
        }
        ?>
      </table>
    </div>
    <?php
  }
  ?>
  <div class="row">
    <table class="mt-3" style="width: 100%;" id="signedTable">
      <tr class="signedRow">
        <td class="signed" width="33%"><?php echo $employee; ?></td>
        <td class="signed" width="33%"><?php echo $accounts_payable; ?></td>
        <td class="signed" width="33%"><?php echo $superintendent; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>Payroll Director</td>
        <td>Superintendent</td>
      </tr>
    </table>
  </div>
  <div class="row">
    <p>
      <strong>Form History (#<?php echo $formId; ?>):</strong><br/>
      <em>
        <?php
        $query = "SELECT action, firstname, lastname, date_time FROM forms_log LEFT JOIN staff_list ON (forms_log.user = staff_list.username) WHERE formId = $formId";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)) {
          echo $row['action'] . ' by ' . $row['firstname'] . ' ' . $row['lastname'] . ' on ' . parseDatetime($row['date_time']) . '<br/>';
        }
        ?>
      </em>
    </p>
  </div>
  <div class="row">
    <p>
      <strong>Supporting Documents:</strong><br/>
      <?php
      $query = "SELECT docname FROM forms_docs WHERE form_id = $formId";
      $result = mysqli_query($dbc, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
          echo '<a href="../../' . $row['docname'] . '"  target="_blank">' . substr($row['docname'], 10) . '</a><br/>';
        }
      }
      ?>
    </p>
  </div>
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
