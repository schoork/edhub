<?php
$page_title = 'Enrollment Form';
$page_access = 'Superintendent Principal Admin Dept Head Designee';
include('header.php');

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$student_id = mysqli_real_escape_string($dbc, $_GET['id']);
if ($student_id == 'ALL') {
  $query = "SELECT * FROM student_registration WHERE status <> 'Denied'";
}
else {
  $query = "SELECT * FROM student_registration WHERE student_id = $student_id";
}
$result = mysqli_query($dbc, $query);

//include other scripts needed here
echo '<link rel="stylesheet" href="css/registrationprint_styles.css">';

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <!-- Write HTML just like a web page -->
  <?php
  while ($row = mysqli_fetch_array($result)) {
    $student_id = $row['student_id'];
    if ($row['grade'] > 6) {
      $school = 'Simmons';
      $school_code = '7611-012';
    }
    else {
      $school = 'Sanders';
      $school_code = '7611-008';
    }
    ?>
    <div class="row page-start">
      <div class="col" style="text-align: center;">
        <h4>
          Hollandale School District Student Enrollment Form
        </h4>
      </div>
    </div>
    <div class="row">
      <table class="table table-sm table-bordered">
        <tr>
          <th>Name</th>
          <td colspan="3"><?php echo $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename']; ?></td>
          <th>SSN</th>
          <td><?php echo $row['ssn']; ?></td>
        </tr>
        <tr>
          <th>Current Address</th>
          <td><?php echo $row['address_curr']; ?></td>
          <th>City, ST</th>
          <td><?php echo $row['city_curr']; ?></td>
          <th>Zipcode</th>
          <td><?php echo $row['zipcode_curr']; ?></td>
        </tr>
        <tr>
          <th>Grade Level</th>
          <td><?php echo $row['grade']; ?></td>
          <th>School</th>
          <td><?php echo $school; ?></td>
          <th>School Code</th>
          <td><?php echo $school_code; ?></td>
        </tr>
        <tr>
          <th>Race/Ethnicity</th>
          <td><?php echo $row['race']; ?></td>
          <th>Gender</th>
          <td><?php echo $row['gender']; ?></td>
          <th>Birthdate</th>
          <td><?php echo makeDateAmerican($row['birthday']); ?></td>
        </tr>
        <tr>
          <th>Birth State</th>
          <td><?php echo $row['birthstate']; ?></td>
          <th>Birth Certificate #</th>
          <td><?php echo $row['birth_number']; ?></td>
          <th>Birth City (County)</th>
          <td><?php echo $row['birthcity'] . ' (' . $row['birthcounty'] . ')'; ?></td>
        </tr>
        <tr>
          <th>Previous School</th>
          <td><?php echo $row['prev_school']; ?></td>
          <th>City, ST</th>
          <td><?php echo $row['prev_city']; ?></td>
          <th>Phone Number</th>
          <td><?php echo $row['prev_number']; ?></td>
        </tr>
      </table>
    </div>
    <div class="row">
      <h6>
        Parent/Guardian(s)
      </h6>
    </div>
    <div class="row">
      <table class="table table-sm table-bordered">
        <tr>
          <th>Parent/Guardian Name</th>
          <th>Relationship</th>
          <th>Phone Number 1</th>
          <th>Phone Number 2</th>
          <th>Email</th>
        </tr>
        <?php
        $query = "SELECT * FROM registration_parents WHERE student_id = $student_id";
        $result = mysqli_query($dbc, $query);
        while ($line = mysqli_fetch_array($result)) {
          echo '<tr>';
          echo '<td>' . $line['parent_name'] . '</td>';
          echo '<td>' . $line['parent_rel'] . '</td>';
          echo '<td>' . $line['parent_phone1'] . '</td>';
          echo '<td>' . $line['parent_phone2'] . '</td>';
          echo '<td>' . $line['parent_email'] . '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>
    <div class="row">
      <h6>
        Emergency Contact(s)
      </h6>
    </div>
    <div class="row">
      <table class="table table-sm table-bordered">
        <tr>
          <th>Contact Name</th>
          <th>Phone Number 1</th>
          <th>Phone Number 2</th>
        </tr>
        <?php
        $query = "SELECT * FROM registration_contacts WHERE student_id = $student_id";
        $result = mysqli_query($dbc, $query);
        while ($line = mysqli_fetch_array($result)) {
          echo '<tr>';
          echo '<td>' . $line['contact_name'] . '</td>';
          echo '<td>' . $line['contact_phone1'] . '</td>';
          echo '<td>' . $line['contact_phone2'] . '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>
    <?php
    $query = "SELECT er FROM registration_health WHERE student_id = $student_id";
    $result = mysqli_query($dbc, $query);
    $line = mysqli_fetch_array($result);
    ?>
    <div class="row">
      <table class="table table-sm">
        <tr>
          <td><?php echo $row['home_lang']; ?></td>
          <td>Does your child speak a language other than English?</td>
        </tr>
        <tr>
          <td><?php echo $row['expelled']; ?></td>
          <td>Has your child ever been expelled or party to an expulsion hearing?</td>
        </tr>
        <tr>
          <td><?php echo $line['er']; ?></td>
          <td>In the event of an emergency (and you cannot be reached) does the school have permission to take your child to the emergency room?</td>
        </tr>
        <tr>
          <td><?php echo $row['iep']; ?></td>
          <td>Does your child have an IEP?</td>
        </tr>
      </table>
    </div>
    <div class="row">
      Medical History: <?php echo $line['medical_history']; ?>
    </div>
    <div class="row">
      Medical History Notes: <?php echo $line['medical_history_notes']; ?>
    </div>
    <div class="row">
      Daily Medications: <?php echo $line['daily_medication']; ?>
    </div>
    <div class="row mt-3">
      <h6>
        Registration History
      </h6>
    </div>
    <div class="row">
      <?php
      $query = "SELECT * FROM registration_logs WHERE student_id = $student_id ORDER BY row_id ASC";
      $result = mysqli_query($dbc, $query);
      while ($line = mysqli_fetch_array($result)) {
        echo $line['action'] . ' by ' . $line['user'] . ' on ' . makeDateAmerican($line['date']) . '<br>';
      }
      ?>
    </div>
    <div class="row">
      <strong>Mark Date Completed</strong>
    </div>
    <div class="row">
      ____ Entered into SAM7 and MSIS<br>
      ____ Records requested<br>
      ____ Documentation of residency<br>
      ____ Long form birth certificate<br>
      ____ Immunization complete<br>
      ____ Social Security Card<br>
    </div>

    <?php
  }
  ?>

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
