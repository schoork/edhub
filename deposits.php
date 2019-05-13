<?php
$page_title = 'Revenues Report';
$page_access = 'Admin Dept Head Principal Superintendent';
include('header.php');

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Deposits
$query = "SELECT revenue_code, sum(amount) AS total, submit_datetime, account, status, form_id FROM forms_deposits AS dep LEFT JOIN forms AS f ON (dep.formId = f.form_id) WHERE revenue_code IS NOT NULL AND revenue_code NOT LIKE '%--%' AND status NOT LIKE 'Denied%' GROUP BY revenue_code ORDER BY revenue_code";
$result = mysqli_query($dbc, $query);
$codes = array();
$used_codes = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
    array_push($codes, array('purchase_code' => $row['revenue_code'], 'total' => $row['total'], 'date' => makeDateAmerican($row['submit_datetime']), 'description' => $row['objective'], 'status' => $row['status'], 'form_id' => $row['form_id'], 'type' => 'Requisition'));
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
      District Revenues Report
    </h5>
    <table class="table-striped mt-3 mb-1">
      <tbody>
        <?php
        $fund = '';
        $pcode = '';
        $total = 0;
        $ptotal = 0;
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
            echo '<tr><td></td><th colspan="2">' . $pcode . '</th><td colspan="2"></td></tr>';
          }
          echo '<tr';
          if ($code['status'] != 'Approved') {
            echo ' style="color: blue;"';
          }
          echo '><td colspan="2"></td><td>' .  $code['date'] . '</td><td>(' . $code['type'] . ' - Form ' . $code['form_id'] . ') ' . $code['account'] . '</td><td style="text-align: right;">$' . number_format($code['total'], 2) . '</td></tr>';
          $total += $code['total'];
          $ptotal += $code['total'];
          $i++;
        }
        echo '<tr><th colspan="4" style="text-align: right;">' . $fund . ' Total:</th><th style="text-align: right;">$' . number_format($total, 2) . '</th></tr>';
        ?>
      </tbody>
    </table>
    <p style="color: blue;">
      Blue items have not yet been fully approved.
    </p>
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