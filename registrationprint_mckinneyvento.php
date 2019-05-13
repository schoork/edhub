<?php
$page_title = 'McKinney-Vento Form';
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
          Hollandale School District Residency Questionnaire
        </h4>
      </div>
    </div>
    <div class="row">
      <p>
        This document is intended to address the McKinney-Vento Assistance Act.
      </p>
    </div>
    <div class="row">
      <table class="table table-sm table-bordered">
        <tr>
          <th>Name</th>
          <td><?php echo $row['lastname'] . ', ' . $row['firstname']; ?></td>
          <th>Date of Birth</th>
          <?php
  				$today = date("Y-m-d");
  				$diff = date_diff(date_create($row['birthday']), date_create($today));
  				?>
          <td><?php echo makeDateAmerican($row['birthday']); ?></td>
          <th>Age</th>
          <td><?php echo $diff->format('%y'); ?></td>
        </tr>
        <tr>
          <th>School</th>
          <td><?php echo $school; ?></td>
          <th>Grade Level</th>
          <td><?php echo $row['grade']; ?></td>
          <th>Gender</th>
          <td><?php echo $row['gender']; ?></td>
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
          <th>Mailing Address</th>
          <td><?php echo $row['address_mail']; ?></td>
          <th>City, ST</th>
          <td><?php echo $row['city_mail']; ?></td>
          <th>Zipcode</th>
          <td><?php echo $row['zipcode_mail']; ?></td>
        </tr>
      </table>
    </div>
    <div class="row">
      <p>
        <strong>Do you and your student live in a fixed, regular, adequate nighttime residence?</strong> <?php echo $row['reside_stable']; ?><br>
        <strong>You and the student live in: </strong><?php echo $row['residence']; ?><br>
        <strong>The student lives with: </strong><?php echo $row['reside_with']; ?>
        <strong>Does the student have an IEP? </strong><?php echo $row['iep']; ?>
      </p>
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
    if ($row['mckinney_vento'] !== null) {
      ?>
      <div class="row">
        <p>
          <strong>This student may be eligible for McKinney-Vento services.</strong><br><br>
          ___ Student is eligible for McKinney-Vento services.<br>
          ___ Student is not eligible for McKinney-Vento services.
        </p>
      </div>
      <div class="row mt-3">
        <table class="signedTable" width="100%">
          <tr class="signedRow">
            <td width="50%" class="signed"></td>
            <td width="50%" class="entry"></td>
          </tr>
          <tr class="label">
            <td>Principal Signature</td>
            <td>Date Recieved</td>
          </tr>
        </table>
      </div>
      <?php
    }
    else {
      ?>
      <div class="row">
        <p>
          <strong>This student is not eligible for McKinney-Vento services.</strong>
        </p>
      </div>
      <?php
    }
    ?>
    <div class="row">
      <div class="col" style="text-align: center;">
        Local Liaison: Samuel Williams | Federal Programs Director | Hollandale School District | 662-827-2276
      </div>
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
