<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Inventory';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/inventory_scripts.js"></script>';

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
        Inventory
      </h1>
      <p class="lead">
        Use this page to manage inventory
      </p>
      <form method="post" action="service.php">
        <input type="hidden" name="action" value="verifyInventory">
        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
        <?php 
        if (strpos('Principal Dept Head Admin Superintendent', $_SESSION['access']) !== false) {
          ?>
					<p>
						<a class="btn btn-outline-primary" href="inventoryreport.php">Inventory Report</a>
						<a class="btn btn-outline-primary" href="inventoryscan.php">Inventory Scan</a>	
					</p>
				<!--
          <div class="form-group row">
            <label for="location" class="col-sm-3 col-form-label">Location</label>
            <div class="col-sm-9">
              <select id="location" class="form-control" name="location">
								<option></option>
                <option value="1">001 - District Office</option>
                <option value="4">004 - Chambers Middle School</option>
                <option value="8">008 - Sanders Elementary School</option>
                <option value="12">012 - Simmons High School</option>
              </select>
            </div>
          </div>
-->
          <div class="form-group row">
            <label for="room" class="col-sm-3 col-form-label">Room</label>
            <div class="col-sm-9">
              <select id="room" class="form-control" name="room">
								<option></option>
                <?php 
                $query = "SELECT room FROM inventory GROUP BY room ORDER BY room";
                $result = mysqli_query($dbc, $query);
                while ($row = mysqli_fetch_array($result)) {
                  echo '<option value="' . $row['room'] . '">' . $row['room'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>
          <?php
        }
        else {
          $location = $_SESSION['school'];
          switch ($location) {
            case 'Sanders':
              echo '<input type="hidden" name="location" id="location" value="8">';
              break;
            case 'Simmons':
              echo '<input type="hidden" name="location" id="location" value="12">';
              break;
            default:
              echo '<input type="hidden" name="location" id="location" value="1">';
          }
          $username = $_SESSION['username'];
          $query = "SELECT room FROM staff_list WHERE username = '$username'";
          $result = mysqli_query($dbc, $query);
          $room = mysqli_fetch_array($result)['room'];
          echo '<input type="hidden" name="room" value="' . $room . '" id="room">';
        }
        ?>
        <table class="table table-striped" id="inventoryTbl">
          <thead>
            <tr>
							<th>Room</th>
              <th>Item No.</th>
              <th>Name/Title</th>
              <th>Class</th>
              <th>Serial No.</th>
              <th>Condition</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
				<div id="lostDiv" style="display: none;" class="alert alert-danger" role="alert">
					<h5>Lost or Stolen Items</h5> 
					If you have any missing or lost items, please contact your principal and Ms. Latarsha Brown immediately. You will need to complete a Fixed Asset Lost or Stolen Property Affadavit within the next 24 hours (if you have not already done so for this item).
				</div>
				<h5>
					Ackowledgement
				</h5>
				<p>
					By clicking Submit, I acknowledge that I understand that the assets listed above may not be moved to another room without first completing a Fixed Assets Transfer Form and having this approved by my principal, supervisor, and the Business Director.
				</p>
				<div id="alert" class="alert" role="alert">

				</div>
				<a class="btn btn-primary disabled" id="btnSubmitForm" href="#!">Submit</a> 
				<a class="btn btn-danger" href="classes.php">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>