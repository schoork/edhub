<?php

require_once('../../connectvars.php');
require_once('../../appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, $_GET['formId']);

$page_title = 'Requisition Form ' . $formId;
$page_access = 'All';
include('../../header.php');





$query = "SELECT * FROM forms LEFT JOIN staff_list ON (forms.employee = staff_list.username) WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$program = $row['program'];
$location = $row['location'];
$submit_date = makeDateAmerican($row['submit_datetime']);
$employee = $row['firstname'] . ' ' . $row['lastname'];
$school = $row['program'];
$accounts_payable = $row['accounts_payable'];
$superintendent = $row['superintendent'];

$query = "SELECT * FROM forms_requisition WHERE formId= $formId";
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
    <table style="width: 100%;" id="infoTable" class="mb-3">
      <tr>
        <td class="entry"><?php echo $program; ?></td>
        <td rowspan="8" class="districtInfo" style="text-align: center;">
          <span id="districtName">Hollandale School District</span><br/>
          101 W. Washington St. PO Box 128<br/>
          Ship To: 115 North St.<br/>
          Hollandale, MS 38748<br/>
          Phone: (662) 827-2276<br/>
          Fax: (662) 827-5261
        </td>
        <td class="entry"><?php echo $row['vendor']; ?></td>
      </tr>
      <tr class="label">
        <td>School/Program</td>
        <td>Vendor</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $employee; ?></td>
        <td class="entry"></td>
      </tr>
      <tr class="label">
        <td>Person Making Request</td>
        <td>Line 2</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $submit_date; ?></td>
        <td class="entry"><?php echo $row['address']; ?></td>
      </tr>
      <tr class="label">
        <td>Date</td>
        <td>Address of Vendor</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['req']; ?></td>
        <td class="entry"><?php echo $row['city']; ?></td>
      </tr>
      <tr class="label">
        <td>Requisition Number</td>
        <td>City/State</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['vendor']; ?></td>
        <td class="entry" style="text-align: center;"><?php echo $row['po']; ?></td>
        <td class="entry"><?php echo $row['zipcode']; ?></td>
      </tr>
      <tr class="label">
        <td>Vendor</td>
        <td style="text-align: center;">Purchase Order Number</td>
        <td>Zipcode</td>
      </tr>
    </table>
  </div>
  <div class="row">
    <table class="mt-3 mb-1">
      <tr>
        <td>Objective:</td>
        <td class="entry"><?php echo $row['objective']; ?></td>
      </tr>
    </table>
    <table class="bordered-table mt-3 mb-1" style=" text-align: center;">
      <tr style="font-size: .50em;">
        <td>Item No.</td>
        <td>Fund Code</td>
        <td>Expense Code</td>
        <td>Function Code</td>
        <td>Program Code</td>
        <td>Object Code</td>
        <td>Dept. Code</td>
        <td>Modifier Code</td>
        <td>Part No.</td>
        <td width="30%">Description</td>
        <td>Quantity</td>
        <td>Unit Price</td>
        <td>Estimated Price</td>
      </tr>
      <?php
      $query = "SELECT * FROM forms_requisitions_items WHERE formId = $formId";
      $result = mysqli_query($dbc, $query);
      while ($row = mysqli_fetch_array($result)) {
        $purchase_code = explode("-", $row['purchase_code']);
        echo '<tr><td>' . $row['itemId'] . '</td><td>' . $purchase_code[0] . '</td><td>' . $purchase_code[1] . '</td><td>' . $purchase_code[2] . '</td><td>' . $purchase_code[3] . '</td><td>' . $purchase_code[4] . '</td><td>' . $purchase_code[5] . '</td><td>' . $purchase_code[6] . '</td><td>' . $row['part_num'] . '</td><td>' . $row['description'] . '</td><td>' . $row['quantity'] . '</td><td>' . $row['unit_cost'] . '</td><td>' . round($row['price'], 2) . '</td></tr>';
      }
      ?>
    </table>
  </div>
  <div class="row">
    <div class="text_right">
      Total: <span class="text-underline">$<?php echo $total; ?></span>
    </div>
  </div>
  <div class="row">
    <table class="mt-3" style="width: 100%;" id="signedTable">
      <tr class="signedRow">
        <td class="signed" width="33%"><?php echo $employee; ?></td>
        <td class="signed" width="33%"><?php echo $accounts_payable; ?></td>
        <td class="signed" width="33%"><?php echo $superintendent; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>Accounts Payable</td>
        <td>Superintendent</td>
      </tr>
    </table>
  </div>
  <div class="row">
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
