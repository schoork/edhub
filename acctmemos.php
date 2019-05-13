<?php

$page_title = 'Accountability Memos';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/acctmemos_scripts.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';

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
        Accountability Memos
      </h1>
      <p class="lead">
        Use this page to view and manage accountability memos that you have recieved or sent.
      </p>
      <p>
        Click on an individual memo to view it in more detail.
      </p>
      <?php
      $access = $_SESSION['access'];
      if (strpos('Superintendent Admin', $_SESSION['access']) !== false) {
			echo '<p><a href="addmemo.php" class="btn btn-primary">Add Memo</a></p>';
			$query = "SELECT memoId, date, sender, recipient, glows, grows FROM acct_memos ORDER BY date DESC";
      }
	  else if (strpos('Principal', $_SESSION['access']) !== false) {
		  echo '<p><a href="addmemo.php" class="btn btn-primary">Add Memo</a></p>';
		  $location = $_SESSION['school'];
		  $query = "SELECT memoId, date, sender, recipient, glows, grows FROM acct_memos AS am LEFT JOIN staff_list AS sl ON (am.recipient = sl.username) WHERE school = '$location' ORDER BY date DESC";
	  }
	  else if (strpos('Dept Head', $_SESSION['access']) !== false) {
		  echo '<p><a href="addmemo.php" class="btn btn-primary">Add Memo</a></p>';
		  $username = $_SESSION['username'];
		  $query = "SELECT memoId, date, sender, recipient, glows, grows FROM acct_memos WHERE sender = '$username' OR recipient = '$username' ORDER BY date DESC";
	  } else if (strpos('Counselor', $_SESSION['access']) !== false) {
		  echo '<p><a href="addmemo.php" class="btn btn-primary">Add Memo</a></p>';
		  $username = $_SESSION['username'];
		  $query = "SELECT memoId, date, sender, recipient, glows, grows FROM acct_memos WHERE sender = '$username' OR recipient = '$username' ORDER BY date DESC";
	  }
	  else {
		  $username = $_SESSION['username'];
		  $query = "SELECT memoId, date, sender, recipient, glows, grows FROM acct_memos WHERE sender = '$username' OR recipient = '$username' ORDER BY date DESC";
	  }
      ?>
      <table class="table table-hover dataTbl table-striped">
        <thead>
          <tr>
            <th>Date</th>
            <th>Sender</th>
            <th>Recipient</th>
            <th>Glows</th>
            <th>Grows</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = mysqli_query($dbc, $query);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
              $sender = $row['sender'];
              $recipient = $row['recipient'];
              $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$sender'";
              $data = mysqli_query($dbc, $query);
              $names = mysqli_fetch_array($data);
              $sender = $names['firstname'] . ' ' . $names['lastname'];
              $query = "SELECT firstname, lastname FROM staff_list WHERE username = '$recipient'";
              $data = mysqli_query($dbc, $query);
              $names = mysqli_fetch_array($data);
              $recipient = $names['firstname'] . ' ' . $names['lastname'];
              echo '<tr id="memo-' . $row['memoId'] . '"><td>' . makeDateAmerican($row['date']) . '</td><td>' . $sender . '</td><td>' . $recipient . '</td><td>' . $row['glows'] . '</td><td>' . $row['grows'] . '</td></tr>';
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!--Acct Memo Detailed Modal-->
<div class="modal fade" id="formModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Accountability Memo</h5>
      </div>
      <div class="modal-body">
        <p>
          Memo ID: <span id="memo_id"></span><br/>
          Sender: <span id="sender"></span><br/>
          Recipient: <span id="recipient"></span><br/>
          <br/>
          Message: <br/>
          <span id="message"></span>
        </p>
        <form method="post" action="service.php" id="memoForm">
          <input type="hidden" name="memoId" id="memoId">
          <input type="hidden" name="action" value="deleteMemo">
        </form>
        <div class="alert" role="alert" id="alert">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
mysqli_close($dbc);

//include footer
include('footer.php');
?>
