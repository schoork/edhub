<?php
$page_title = 'Sanders Expenditures Report';
$page_access = 'Admin Dept Head Principal Superintendent';
include('header.php');

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Requisitions
$query = "SELECT form_id, objective, purchase_code, sum(price) AS total, submit_datetime, description, status FROM forms_requisitions_items AS fri LEFT JOIN forms AS f ON (fri.formId = f.form_id) LEFT JOIN forms_requisition AS fr ON (fri.formId = fr.formId) WHERE purchase_code IS NOT NULL AND purchase_code LIKE '%-008-%' AND status NOT LIKE 'Denied%' GROUP BY purchase_code, form_id ORDER BY purchase_code, form_id";
$result = mysqli_query($dbc, $query);
$codes = array();
$used_codes = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
    array_push($codes, array('purchase_code' => $row['purchase_code'], 'total' => $row['total'], 'date' => makeDateAmerican($row['submit_datetime']), 'description' => $row['objective'], 'status' => $row['status'], 'form_id' => $row['form_id'], 'type' => 'Requisition'));
  }
}
//Reimbursements
$query = "SELECT form_id, program, purchase_code, sum(total), program AS description, submit_datetime, status FROM forms_reimbursement AS fr LEFT JOIN forms AS f ON (fr.formId = f.form_id) WHERE purchase_code IS NOT NULL AND purchase_code LIKE '%-008-%' AND status NOT LIKE 'Denied%' ORDER BY purchase_code, form_id";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
    if ($row['form_id'] != '') {
      array_push($codes, array('purchase_code' => $row['purchase_code'], 'total' => $row['total'], 'date' => makeDateAmerican($row['submit_datetime']), 'description' => $row['program'], 'status' => $row['status'], 'form_id' => $row['form_id'], 'type' => 'Reimbursement'));
    }
  }
}
//Bus Requests
$query = "SELECT purchase_code, sum(total) AS total, title, submit_datetime, program, status, form_id FROM forms_busrequest AS fr LEFT JOIN forms AS f ON (fr.formId = f.form_id) WHERE purchase_code IS NOT NULL AND purchase_code LIKE '%-008-%' AND status NOT LIKE 'Denied%' GROUP BY purchase_code ORDER BY purchase_code";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
    if ($row['form_id'] != '') {
      array_push($codes, array('purchase_code' => $row['purchase_code'], 'total' => $row['total'], 'date' => makeDateAmerican($row['submit_datetime']), 'description' => $row['program'], 'status' => $row['status'], 'form_id' => $row['form_id'], 'type' => 'Bus Request'));
    }
  }
}
//Out of Town Travel
$query = "SELECT fr.purchase_code, sum(expected_cost) AS total, submit_datetime, program, status, form_id FROM forms_outoftown AS fr LEFT JOIN forms AS f ON (fr.formId = f.form_id) LEFT JOIN forms_reimbursement AS rei ON (fr.formId = rei.travel_form) WHERE fr.purchase_code IS NOT NULL AND fr.purchase_code LIKE '%-008-%' AND status NOT LIKE 'Denied%' AND travel_form IS NULL GROUP BY fr.purchase_code ORDER BY fr.purchase_code";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
    if ($row['form_id'] != '') {
      array_push($codes, array('purchase_code' => $row['purchase_code'], 'total' => $row['total'], 'date' => makeDateAmerican($row['submit_datetime']), 'description' => $row['program'], 'status' => $row['status'], 'form_id' => $row['form_id'], 'type' => 'Out of Town Travel'));
    }
  }
}
array_multisort(array_map(function($element) {
    return $element['purchase_code'];
}, $codes), SORT_ASC, $codes);


//include other scripts needed here
echo '<link rel="stylesheet" href="forms/prints/forms_print.css">';

//end header
echo '</head>';
  
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <!-- Write HTML just like a web page -->
  <div class="row">
    <h5 style="text-align: center;">
      Sanders Expenditures Report
    </h5>
    
    <table class="table-striped mt-3 mb-1">
      <tbody>
        <?php
        $fund = '';
        $pcode = '';
        $total = 0;
        $ptotal = 0;
        $stotal = 0;
        $i = 0;
        foreach ($codes as $code) {
          if ($pcode != $code['purchase_code'] && $i > 0) {
            echo '<tr><th colspan="4" style="text-align: right;">' . $pcode . ' Total:</th><th style="text-align: right;">$' . number_format($ptotal, 2) . '</th></tr>';
          }
          if (substr($code['purchase_code'], 0, 4) != $fund) {
            if ($i > 0) {
              echo '<tr><th colspan="4" style="text-align: right;">' . $fund . ' Total:</th><th style="text-align: right;">$' . number_format($total, 2) . '</th></tr>';
              $total = 0;
            }
            $fund = substr($code['purchase_code'], 0, 4);
            echo '<tr><th>Fund: ' . $fund . '</th><td colspan="4"></td></tr>';
          }
          if ($pcode != $code['purchase_code']) {
            $ptotal = 0;
            $pcode = $code['purchase_code'];
            echo '<tr><td></td><th colspan="3">' . $pcode . '</th><td colspan="1"></td></tr>';
          }
          echo '<tr';
          if ($code['status'] != 'Approved') {
            echo ' style="color: blue;"';
          }
          echo '><td colspan="2" width="10%"></td><td>' .  $code['date'] . '</td><td>(' . $code['type'] . ' - Form ' . $code['form_id'] . ') ' . $code['description'];
          if ($code['status'] == 'Approved') {
            echo ' <a href="forms/prints/' . str_replace(" ", "", strtolower($code['type'])) . '.php?formId=' . $code['form_id'] . '" target="_blank">[Link]</a>';
          }
          echo '</td><td style="text-align: right;">$' . number_format($code['total'], 2) . '</td></tr>';
          $total += $code['total'];
          $ptotal += $code['total'];
          $stotal += $code['total'];
          $i++;
        }
        echo '<tr><th colspan="4" style="text-align: right;">' . $fund . ' Total:</th><th style="text-align: right;">$' . number_format($total, 2) . '</th></tr>';
        echo '<tr><th colspan="4" style="text-align: right;">School Total:</th><th style="text-align: right;">$' . number_format($stotal, 2) . '</th></tr>';
        ?>
      </tbody>
    </table>
  </div>
  <div class="row">
    <p style="color: blue;">
      Blue items have not yet been fully approved.
    </p>
  </div>
  <div class="row">
    <p>
      This report was created at <?php echo date("m/d/Y h:ia"); ?><br/>
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