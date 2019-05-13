<?php

$page_title = 'Student Tracker';
$page_access = 'All';
$page_type = 'RTI';
include('check_login.php');
include('header.php');

//include other scripts needed here
echo '<script src="/js/moment.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar.php');

echo '<main>';
?>

<form method="post" action="service.php" id="tracker_form">
  <div class="row">
    <div class="col s12 m4 input-field">
      <select id="week" name="week">
      </select>
      <label>Pick a Week</label>
    </div>
    <div class=" col s12 m8 red-text hide" id="change_div">
      You have unsaved changes. Please click the Update Tracker button at the bottom of the page before leaving this page or selecting a different week.
    </div>
  </div>


<?php
include('connectvars.php');
include('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($_SESSION['access'] == 'Admin') {
  echo '<script src="js/trackeradmin_scripts.js"></script>';
  echo '<input type="hidden" name="action" value="addTrackerDataAdmin"><div class="row"><div class="col s12 m4 input-field"><select name="student_id" id="student_id"><option disabled selected></option>';
  $query = "SELECT name, sl.student_id FROM rti_checklist_written RIGHT JOIN student_list2 AS sl USING (student_id) WHERE replace1 IS NOT NULL  ORDER BY name";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    echo '<option value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
  }
  echo '</select><label>Student</label></div></div>';
  echo '<div id="teacher_section"></div>';
}
else {
  echo '<script src="js/tracker_scripts.js"></script>';
  $teacher = $_SESSION['user_name'];
  echo '<input type="hidden" name="teacher" id="teacher_hidden" value="' . $_SESSION['user_name'] . '"><input type="hidden" name="action" value="addTrackerData">';
  $query = "SELECT school FROM staff_list WHERE name = '$teacher'";
  $result = mysqli_query($dbc, $query);
  $school = mysqli_fetch_array($result)['school'];
  if ($school == 'FLY' || $school == 'HIL') {
    ?>
    <div class="row">
      <div class="col s12">
        <em><strong>Directions:</strong> Use the table below to set the week's schedule. You only have to do this once per week. This will automatically fill in some entries for you as "No Class." This table will appear blank when you reload the page. Ignore it.</em><br/>
        <table class="bordered striped">
          <thead>
            <tr>
              <th>Monday</th>
              <th>Tuesday</th>
              <th>Wednesday</th>
              <th>Thursday</th>
              <th>Friday</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="radio" name="sch_day1" id="sch_day1-1" value="type1"><label for="sch_day1-1">1 - 6, no Adv</label></td>
              <td><input type="radio" name="sch_day2" id="sch_day2-1" value="type1"><label for="sch_day2-1">1 - 6, no Adv</label></td>
              <td><input type="radio" name="sch_day3" id="sch_day3-1" value="type1"><label for="sch_day3-1">1 - 6, no Adv</label></td>
              <td><input type="radio" name="sch_day4" id="sch_day4-1" value="type1"><label for="sch_day4-1">1 - 6, no Adv</label></td>
              <td><input type="radio" name="sch_day5" id="sch_day5-1" value="type1"><label for="sch_day5-1">1 - 6, no Adv</label></td>
            </tr>
            <tr>
              <td><input type="radio" name="sch_day1" id="sch_day1-2" value="type2"><label for="sch_day1-2">1 - 3, with Adv</label></td>
              <td><input type="radio" name="sch_day2" id="sch_day2-2" value="type2"><label for="sch_day2-2">1 - 3, with Adv</label></td>
              <td><input type="radio" name="sch_day3" id="sch_day3-2" value="type2"><label for="sch_day3-2">1 - 3, with Adv</label></td>
              <td><input type="radio" name="sch_day4" id="sch_day4-2" value="type2"><label for="sch_day4-2">1 - 3, with Adv</label></td>
              <td><input type="radio" name="sch_day5" id="sch_day5-2" value="type2"><label for="sch_day5-2">1 - 3, with Adv</label></td>
            </tr>
            <tr>
              <td><input type="radio" name="sch_day1" id="sch_day1-3" value="type3"><label for="sch_day1-3">4 - 6, with Adv</label></td>
              <td><input type="radio" name="sch_day2" id="sch_day2-3" value="type3"><label for="sch_day2-3">4 - 6, with Adv</label></td>
              <td><input type="radio" name="sch_day3" id="sch_day3-3" value="type3"><label for="sch_day3-3">4 - 6, with Adv</label></td>
              <td><input type="radio" name="sch_day4" id="sch_day4-3" value="type3"><label for="sch_day4-3">4 - 6, with Adv</label></td>
              <td><input type="radio" name="sch_day5" id="sch_day5-3" value="type3"><label for="sch_day5-3">4 - 6, with Adv</label></td>
            </tr>
            <tr>
              <td><input type="radio" name="sch_day1" id="sch_day1-4" value="type4"><label for="sch_day1-4">No School</label></td>
              <td><input type="radio" name="sch_day2" id="sch_day2-4" value="type4"><label for="sch_day2-4">No School</label></td>
              <td><input type="radio" name="sch_day3" id="sch_day3-4" value="type4"><label for="sch_day3-4">No School</label></td>
              <td><input type="radio" name="sch_day4" id="sch_day4-4" value="type4"><label for="sch_day4-4">No School</label></td>
              <td><input type="radio" name="sch_day5" id="sch_day5-4" value="type4"><label for="sch_day5-4">No School</label></td>
            </tr>
          </tbody>
        </table>
        <br/>
        <a class="btn waves-effect waves-light" id="btnSchSave">Set Schedule</a>
      </div>
    </div>
  <?php
  }

  $query = "SELECT name, grade, period, rcw.student_id, replace1, replace2, replace3, replace4, replace5, replace6 FROM rti_checklist_written AS rcw LEFT JOIN student_list2 AS sl USING (student_id) LEFT JOIN student_schedules2 AS sch USING (student_id) WHERE teacher = '$teacher' AND replace1 IS NOT NULL GROUP BY rcw.student_id ORDER BY period, name";
  $result = mysqli_query($dbc, $query);
  while ($row = mysqli_fetch_array($result)) {
    if ($row['replace1'] !== null && $row['name'] !== null) {
      ?>
      <input type="hidden" name="students[]" value="<?php echo $row['student_id']; ?>">
      <div class="row">
        <div class="col s12 grey lighten-2">
          <h5>
            <?php echo $row['name'] . ' (' . $row['grade'] . 'th grade) - Period ' . $row['period']; ?>
          </h5>
        </div>
      </div>
    <div class="row">
      <div class="col s12 m4">
        0 = Behavior not demonstrated (0%)<br/>
        1 = Behavior poorly demonstrated (1%-25%)
      </div>
      <div class="col s12 m4">
        2 = Behavior somewhat demonstrated (26%-50%)<br/>
        3 = Behavior adequately demonstrated (51%-75%)
      </div>
      <div class="col s12 m4">
        4 = Behavior largely demonstrated (76%-100%)
      </div>
    </div>
      <div class="row">
        <div class="col s12">
          <table class="bordered tracker_tbl">
            <thead>
              <tr>
                <th class="first-col">Replacement Behaviors</th>
              </tr>
            </thead>
            <tbody>
              <?php
              for ($i = 1; $i < 7; $i++) {
                if ($row["replace$i"] !== null && $row["replace$i"] != '') {
                  echo '<tr><td>' . $row["replace$i"] . '</td>';
                  for ($j = 1; $j < 6; $j++) {
                    ?>
                    <td>
                      <select name="input-<?php echo $row['student_id'] . '-' . $j . '-' . $i; ?>" class="period-<?php echo substr($row['period'], 0, 1); ?>-day-<?php echo $j; ?>">
                        <option selected></option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="100">Absent</option>
                        <option value="101">Skipped</option>
                        <option value="102">Suspended</option>
                        <option value="103">Teacher Absent</option>
                        <option value="104">No Class</option>
                        <option value="105">Not Applicable</option>
                      </select>
                    </td>
                    <?php
                  }
                  echo '</tr>';
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col s12 input-field">
          <textarea class="materialize-textarea" name="notes-<?php echo $row['student_id']; ?>" maxlength="300" length="300" id="notes-<?php echo $row['student_id']; ?>"></textarea><label for="notes-<?php echo $row['student_id']; ?>">Weekly Notes</label>
        </div>
      </div>
      <?php
    }
  }
}
?>
  <div class="row">
    <div class="col s12 m4">
      <a class="waves-effect waves-light btn" id="btnSubmit">Update Tracker</a>
    </div>
    <div class="col s12 m8 green-text" id="error_div">

    </div>
  </div>

</form>
<?php

mysqli_close($dbc);

echo '</main>';

//end body
echo '</body>';

//include footer
include('footer.php');

?>
