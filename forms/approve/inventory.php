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
  Submitted: <?php echo parseDatetime($row['submit_datetime']); ?>
</p>
<?php
$query = "SELECT * FROM forms_inventory WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<input type="hidden" name="form_type" value="inventory">
<input type="hidden" name="form_employee" id="<?php echo $row['employee']; ?>">
<h5>
  <?php
  $invent_action = $row['invent_action'];
  echo 'Items Requested for ' . $invent_action;
  ?>
</h5>
<p>
  <?php
  if ($invent_action == 'Disposal') {
    echo 'Reason: ' . $row['explanation'] . '<br>';
    echo 'Current Room: ' . $row['old_room'];
  }
  else if ($invent_action == 'Donation') {
    echo 'Donation Date: ' . makeDateAmerican($row['donation_date']) . '<br>';
    echo 'Donated by: ' . $row['donation_from'] . '<br>';
    $cost = $row['total_cost'];
    echo 'Estimated Cost of Donation: $' . number_format($cost, 2) . '<br>';
    echo 'New Room: ' . $row['new_room'];
  }
  else if ($invent_action == 'Transfer') {
    echo 'Current Room: ' . $row['old_room'] . '<br>';
    echo 'New Room: ' . $row['new_room'];
  }
  else if ($invent_action == 'Lost/Stolen') {
    echo 'Police/Sheriff Report Number: ' . $row['report_number'] . '<br>';
    echo 'Explanation of Loss: ' . $row['explanation'];
  }
  ?>
</p>
<table class="table table-sm">
  <thead>
    <tr>
      <th>Asset Tag</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $query = "SELECT tag, description FROM forms_inventory_items WHERE form_id = $formId";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
      echo '<tr>';
      echo '<td>' . $row['tag'] . '</td>';
      echo '<td>' . $row['description'] . '</td>';
      echo '</tr>';
    }
    ?>
  </tbody>
</table>
<h5>
  Supporting Documents
</h5>
<p>
  <?php
  $query = "SELECT docname FROM forms_reimbursements_docsf WHERE formId = $formId";
  $result = mysqli_query($dbc, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      echo '<a href="documents/' . $row['docname'] . '" target="_blank" download>' . $row['docname'] . '</a><br/>';
    }
  }
  ?>
</p>
<?php
mysqli_close($dbc);
?>
