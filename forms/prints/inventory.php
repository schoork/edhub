<?php

require_once('../../connectvars.php');
require_once('../../appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, $_GET['formId']);

$page_title = 'Asset Management Form ' . $formId;
$page_access = 'All';
include('../../header.php');

$query = "SELECT * FROM forms LEFT JOIN staff_list ON (forms.employee = staff_list.username) WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$submit_date = makeDateAmerican($row['submit_datetime']);
$employee = $row['firstname'] . ' ' . $row['lastname'];
$asset = $row['asset'];
$supervisor = '';
if ($row['school'] == 'Sanders') {
  $supervisor = $row['sanders'];
}
else if ($row['school'] == 'Simmons') {
  $supervisor = $row['simmons'];
}
else if ($row['school'] == 'District Office') {
  $supervisor = $row['superintendent'];
}

$query = "SELECT * FROM forms_inventory WHERE form_id = $formId";
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
    <div class="row d-print-none">
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
      ITEMS REQUESTED FOR <?php echo strtoupper($row['invent_action']); ?>
    </div>
  </div>
  <?php
  $invent_action = $row['invent_action'];
  if ($invent_action == 'Disposal') {
    ?>
    <div class="row">
      <table class="mb-3 mt-3 table table-bordered">
        <tr>
          <th>Reason</th>
          <td ><?php echo $row['explanation']; ?></td>
          <th>Current Room</th>
          <td><?php echo $row['old_room']; ?></td>
        </tr>
      </table>
    </div>
    <?php
  }
  else if ($invent_action == 'Donation') {
    ?>
    <div class="row">
      <table class="mb-3 mt-3 table table-bordered">
        <tr>
          <th>Donation Date</th>
          <td><?php echo makeDateAmerican($row['donation_date']); ?></td>
          <th>Donated by</th>
          <td><?php echo $row['donation_from']; ?></td>
        </tr>
        <tr>
          <th>Estimated Cost of Donation</th>
          <td>$<?php echo number_format($row['total_cost'], 2); ?></td>
          <th>Assigned Room</th>
          <td><?php echo $row['new_room']; ?></td>
        </tr>
      </table>
    </div>
    <?php
  }
  else if ($invent_action == 'Transfer') {
    ?>
    <div class="row">
      <table class="mb-3 mt-3 table table-bordered">
        <tr>
          <th>Current Room</th>
          <td ><?php echo $row['old_room']; ?></td>
          <th>New Room</th>
          <td><?php echo $row['new_room']; ?></td>
        </tr>
      </table>
    </div>
    <?php
  }
  else if ($invent_action == 'Lost/Stolen') {
    ?>
    <div class="row">
      <table class="mb-3 mt-3 table table-bordered table-responsive">
        <tr>
          <th>Police/Sherrif Report Number</th>
          <td ><?php echo $row['explanation']; ?></td>
        </tr>
        <tr>
          <th>Explanation of Loss</th>
          <td><?php echo $row['old_room']; ?></td>
        </tr>
      </table>
    </div>
    <?php
  }
  ?>
  <div class="row">
    <table class="mb-3 mt-3 table table-bordered table-responsive table-sm">
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
  </div>
  <div class="row">
    <table class="mt-3" style="width: 100%;" id="signedTable">
      <tr class="signedRow">
        <td class="signed" width="33%"><?php echo $employee; ?></td>
        <td class="signed" width="33%"><?php echo $asset; ?></td>
        <td class="signed" width="33%"><?php echo $supervisor; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>Asset Management</td>
        <td>Supervisor</td>
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
