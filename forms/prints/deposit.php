<?php

require_once('../../connectvars.php');
require_once('../../appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, $_GET['formId']);

$page_title = 'Deposit Form ' . $formId;
$page_access = 'All';
include('../../header.php');

$query = "SELECT * FROM forms LEFT JOIN staff_list ON (forms.employee = staff_list.username) WHERE form_id = $formId ";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$program = $row['program'];
$employee = $row['firstname'] . ' ' . $row['lastname'];
$superintendent = $row['superintendent'];
$payroll = $row['payroll'];
$submit_date = makeDateAmerican($row['submit_datetime']);

$query = "SELECT * FROM forms_deposits WHERE formId = $formId";
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
      Deposit
    </h3>
  </div>
  <div class="row">
    <table class="mb-3 mt-3 table table-bordered table-sm">
      <tr>
        <th>Employee:</th>
        <td><?php echo $employee; ?></td>
        <th>Amount:</th>
        <td>$<?php echo number_format($row['amount'], 2); ?></td>
        <th>Date:</th>
        <td><?php echo $submit_date; ?></td>
      </tr>
      <tr>
        <th>Revenue Code:</th>
        <td><?php echo $row['revenue_code']; ?></td>
        <th>Account:</th>
        <td><?php echo $row['account']; ?></td>
        <th>School:</th>
        <td><?php echo $row['school']; ?></td>
      </tr>
      <tr>
        <th>Descripion:</th>
        <td colspan="3"><?php echo $row['description']; ?></td>
        <th>Receipt Number:</th>
        <td><?php echo $row['receipt']; ?></td>
      </tr>
    </table>
  </div>

  <table class="mt-3" style="width: 100%;" id="signedTable">
    <tr class="signedRow">
      <td class="signed" width="33%"><?php echo $employee; ?></td>
      <td class="signed" width="33%"><?php echo $payroll; ?></td>
      <td class="signed" width="33%"><?php echo $superintendent; ?></td>
    </tr>
    <tr class="label">
      <td>Employee</td>
      <td>Payroll</td>
      <td>Superintendent</td>
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
