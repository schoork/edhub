<?php

$page_title = 'Inventory Scan';
$page_access = 'Principal DeptHead Admin Superintendent';
include('header.php');

//include other scripts needed here
echo '<script src="js/iscan_scripts.js"></script>';

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
			Teachers
		</h1>
    <p class="lead">
      Manage teachers and forms
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
      <h1>
        Inventory Scan
      </h1>
      <p class="lead">
        Use this page to scan inventory items. Items must already be in the inventory.
      </p>
      <?php
      if (isset($_POST['action']) && $_POST['action'] == 'inventoryScan') {
        $item = mysqli_real_escape_string($dbc, $_POST['item']);
        $room = mysqli_real_escape_string($dbc, $_POST['room']);
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $query = "INSERT INTO inventory_scan (item_id, room, username, scan_datetime) VALUES ($item, '$room', '$username', NOW() + INTERVAL 2 HOUR)";
        mysqli_query($dbc, $query);
				$scan_id = mysqli_insert_id($dbc);
        $query = "SELECT location, room, serial, title FROM inventory WHERE itemId = $item";
        $result = mysqli_query($dbc, $query);
        echo '<h5>Scanned Item Details</h5>';
        if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_array($result);
          echo '<p>Item ID: ' . $item . '<br/>';
          echo 'Location: ';
          switch ($row['location']) {
            case 1:
              echo 'District Office';
              break;
            case 4:
              echo 'Chambers Middle School';
              break;
            case 8:
              echo 'Sanders Elementary School';
              break;
            case 12:
              echo 'Simmons High School';
              break;
            default:
              $row['location'] . ' - Unlisted';
          }
          echo '<br/>';
          echo 'Room: ' . $row['room'] . '<br/>';
          echo 'Scanned Room: ' . $room . '<br/>';
          echo 'Serial: ' . $row['serial'] . '<br/>';
          echo 'Description: ' . $row['title'] . '</p>';
					echo '<p><a class="btn btn-danger" href="#!" id="btnDelete">Delete this Scan</a></p>';
					echo '<form method="post" action="service.php" id="deleteForm"><input type="hidden" name="action" value="deleteIScan"><input type="hidden" name="scan_id" value="' . $scan_id . '"></form>';
					echo '<div class="alert" id="deleteAlert"></div>';
        }
        else {
          echo '<p>This item is not listed in the inventory. Use the form below to add the item.</p>';
          ?>
          <form method="post" action="service.php" id="newItemForm">
            <input type="hidden" name="action" value="addToInventory">
            <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
            <p>
              Room
            </p>
          </form>
          <?php
        }
      }
      ?>
      <p>
        You must select a room before scanning.
      </p>
      <form method="post" action="inventoryscan.php" id="scanForm">
        <input type="hidden" name="action" value="inventoryScan">
        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
        <input type="hidden" name="itemTotal" value="1">
        <div class="form-group row">
          <label for="room" class="col-sm-3 col-form-label">Room</label>
          <div class="col-sm-9">
            <input type="text" name="room" class="form-control" id="room" value="<?php echo $room; ?>">
          </div>
        </div>
				<a class="btn btn-primary hidden-sm-up" href="#!" id="newScan">New Scan</a>
        <div class="form-group row">
          <label for="item" class="col-sm-3 col-form-label">Item ID</label>
          <div class="col-sm-9">
            <input type="text" name="item" class="form-control" id="item">
          </div>
        </div>
      </form>
      
    </div>
  </div>
</div>

<?php

//include footer
include('footer.php');
?>

