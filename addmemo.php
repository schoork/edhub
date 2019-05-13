<?php

$page_title = 'Add Memo';
$page_access = 'Principal Dept Head Admin Superintendent Designee Counselor';
include('header.php');

//include other scripts needed here
echo '<script src="js/addmemo_scripts.js"></script>';

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
        Add Accountability Memo
      </h1>
      <p class="lead">
        Use this page to send a teacher or teachers an accountability memo.
      </p>
      <form method="post" action="service.php" id="memoForm">
        <input type="hidden" name="action" value="addMemo">
        <input type="hidden" name="sender" value="<?php echo $_SESSION['username']; ?>">
        <input type="hidden" id="admin" name="admin" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
        <div class="form-group row">
          <label for="recipients" class="col-sm-2 col-form-label">Recipient(s)</label>
          <div class="col-sm-10">
            <select multiple class="form-control" id="recipients" name="recipients[]">
              <?php
              $query = "SELECT firstname, lastname, username FROM staff_list WHERE status = 1 ORDER BY lastname, firstname";
              $result = mysqli_query($dbc, $query);
              while ($row = mysqli_fetch_array($result)) {
                echo '<option value="' . $row['username'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2">Glows</label>
          <div class="col-sm-10">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for being on duty on time."> Thanks for being on duty on time.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for arriving to work on time."> Thanks for arriving to work on time.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Your objective looks great."> Your objective looks great.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for being at the PLC meeting today."> Thanks for being at the PLC meeting today.
              </label>
            </div>
			<div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for posting your weekly data in a timely manner."> Thanks for posting your weekly data in a timely manner.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for having your data prepared for the PLC meeting."> Thanks for having your data prepared for the PLC meeting.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for turning in your lesson plans on time."> Thanks for turning in your lesson plans on time.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for having your whiteboard protocol prepared before class."> Thanks for having your whiteboard protocol prepared before class.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="glows[]" value="Thanks for showing your commitment to the team by ___."> Thanks for showing your commitment to the team by ___.
              </label>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2">Grows</label>
          <div class="col-sm-10">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="You were not on time to duty today. Please make sure you are on time to duty every day."> You were not on time to duty today. Please make sure you are on time to duty every day.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="You were not on time to work today. Please make sure you arrive to work on time every day."> You were not on time to work today. Please make sure you arrive to work on time every day.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="Your objective was not posted. Please make sure you post it every day before class."> Your objective was not posted. Please make sure you post it every day before class.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="We missed you at the PLC meeting today. It is important to be there so we can norm as a team."> We missed you at the PLC meeting today. It is important to be there so we can norm as a team.
              </label>
            </div>
			<div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="Please post your weekly data in a timely manner."> Please post your weekly data in a timely manner.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="Please make sure you have your data prepared for our next PLC meeting."> Please make sure you have your data prepared for our next PLC meeting.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="You did not turn your lesson plans in on time. Please make sure you do so that I may provide you with timely feedback."> You did not turn your lesson plans in on time. Please make sure you do so that I may provide you with timely feedback.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="Your whiteboard protocol was lacking today. Please make sure you have it posted every day before class."> Your whiteboard protocol was lacking today. Please make sure you have it posted every day before class.
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="grows[]" value="Please show your commitment to the team by ___."> Please show your commitment to the team by ___.
              </label>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <label for="message" class="col-sm-2 col-form-label">Message</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="message" name="message" rows="10"></textarea>
          </div>
        </div>
        <div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
        <a class="btn btn-danger" href="acctmemos.php">Cancel</a>
      </form>

    </div>
  </div>
</div>

<?php

//include footer
include('footer.php');
?>
