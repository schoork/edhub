<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Inventory Report';
$page_access = 'All';
include('header.php');

//include other scripts needed here


//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');
  
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
    <p class="lead">
      Manage and submit lesson plans, weekly assessment data, and inventory
    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Inventory Report
      </h1>
      <p class="lead">
        Use this page to view inventory updates
      </p>
      <p>
        <a class="btn btn-outline-primary" href="inventory.php">Inventory Input</a>
				<a class="btn btn-outline-primary" href="inventoryscan.php">Inventory Scan</a>
      </p>
			<h2>
				Last Scanned Items
			</h2>
			<p>
				Listed below are the last 20 items that have been scanned.
			</p>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Item No.</th>
						<th>Name/Title</th>
						<th>Scanned Room</th>
						<th>Inventory Room</th>
						<th>Scanned By</th>
						<th>Scan Date/Time</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query = "SELECT item_id, firstname, lastname, scan.room AS scanroom, i.room AS iroom, title, scan_datetime FROM inventory_scan AS scan LEFT JOIN inventory AS i ON (scan.item_id = i.itemId) LEFT JOIN staff_list AS sl ON (scan.username = sl.username) ORDER BY scan_id DESC LIMIT 20";
					$result = mysqli_query($dbc, $query);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr><td>' . $row['item_id'] . '</td><td>' . $row['title'] . '</td><td>' . $row['scanroom'] . '</td><td>' . $row['iroom'] . '</td><td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td><td>' . parseDatetime($row['scan_datetime']) . '</td></tr>';
						}
					}
					?>
				</tbody>
			</table>
      <h2>
        Missing/Stolen Items
      </h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Item No.</th>
            <th>Location</th>
            <th>Room</th>
            <th>Fund</th>
            <th>Name/Title</th>
            <th>Serial</th>
            <th>Reported by</th>
            <th>Report Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT i.itemId, location, i.room, title, serial, firstname, lastname, date, description FROM inventory AS i LEFT JOIN inventory_updates AS iu USING (itemId) LEFT JOIN staff_list AS sl ON (iu.username = sl.username) WHERE iu.status = 'Missing/Damaged' ORDER BY location, i.room";
          $result = mysqli_query($dbc, $query);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
              $name = $row['firstname'] . ' ' .$row['lastname'];
              echo '<tr><td>' . $row['itemId'] . '</td><td>' . $row['location'] . '</td><td>' . $row['room'] . '</td><td>' . $row['fund'] . '</td><td>' . $row['title'] . '</td><td>' . $row['serial'] . '</td><td>' . $name . '</td><td>' . makeDateAmerican($row['date']) . '</td><td>' . $row['description'] . '</td></tr>';
            }
          }
          ?>
        </tbody>
      </table>
      <h2>
        Last Reporting Date
      </h2>
      <table class="table table-striped table-responsive">
        <thead>
          <tr>
            <th>Location</th>
            <th>Room</th>
            <th>Assigned Teacher</th>
            <th>Last Reporting Date</th>
            <th>Items Status</th>
          </tr>
        </thead>
        <tbpdy>
          <?php
          $query = "SELECT location, i.room, firstname, lastname, date FROM inventory AS i LEFT JOIN inventory_updates AS iu USING (itemId) LEFT JOIN staff_list AS sl ON (FIND_IN_SET(i.room, sl.room) <> 0) GROUP BY location, room ORDER BY location, i.room";
          $result = mysqli_query($dbc, $query);
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_array($result)) {
                $name = $row['firstname'] . ' ' .$row['lastname'];
                echo '<tr';
                if ($row['date'] !== null) {
                  $total = 0;
                  $present = 0;
                  $date = $row['date'];
                  $location = $row['location'];
                  $room = $row['room'];
                  $query = "SELECT itemId, status FROM inventory_updates AS iu LEFT JOIN inventory AS i USING (itemId) WHERE date = '$date' AND location = '$location' AND room = '$room'";
                  $data = mysqli_query($dbc, $query);
                  while ($item = mysqli_fetch_array($data)) {
                    if ($item['status'] == 'Present') {
                      $present++;
                    }
                    $total++;
                  }
                  if ($total == $present) {
                    echo ' class="table-success"';
                  }
                  else {
                    echo ' class="table-danger"';
                  }
                }
                else {
                  echo ' class="table-warning"';
                }
                
                echo '><td>' . $row['location'] . '</td><td>' . $row['room'] . '</td><td>' . $name . '</td><td>';
                if ($row['date'] !== null) {
                  echo makeDateAmerican($row['date']);
                  echo '</td>';
                  if ($total == $present) {
                    echo '<td>All Items Present</td>';
                  }
                  else {
                    $missing = $total - $present;
                    echo '<td>' . $missing . ' ITEM(S) MISSING</td>'; 
                  }
                }
                else {
                  echo '<td>Nothing Submitted</td>';
                }
                echo '</tr>';
              }
            }
          ?>
        </tbpdy>
      </table>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>