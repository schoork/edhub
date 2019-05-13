<?php
$page_title = 'Student Health Form';
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
          Hollandale School District Student Health Record
        </h4>
      </div>
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
        <?php
        $query = "SELECT insurance, insure_number FROM registration_health WHERE student_id = $student_id";
        $result = mysqli_query($dbc, $query);
        $line = mysqli_fetch_array($result);
        ?>
        <tr>
          <th>SSN</th>
          <td><?php echo $row['ssn']; ?></td>
          <th>Insurance</th>
          <td><?php echo $line['insurance']; ?></td>
          <th>Insurance Number</th>
          <td><?php echo $line['insure_number']; ?></td>
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
    $query = "SELECT * FROM registration_health WHERE student_id = $student_id";
    $result = mysqli_query($dbc, $query);
    $line = mysqli_fetch_array($result);
    ?>
    <div class="row">
      <table class="table table-sm">
        <tr>
          <th>Allergies</th>
          <th>Allergy Notes</th>
        </tr>
        <tr>
          <td><?php echo $line['allergies']; ?></td>
          <td><?php echo nl2br($line['allergy_notes']); ?></td>
        </tr>
        <tr>
          <th>Medical History</th>
          <th>Medical History Notes</th>
        </tr>
        <tr>
          <td><?php echo $line['medical_history']; ?></td>
          <td><?php echo nl2br($line['medical_history_notes']); ?></td>
        </tr>
        <tr>
          <th>Asthma Triggers</th>
          <th>Asthma Medications</th>
        </tr>
        <tr>
          <td><?php echo $line['asthma_triggers']; ?></td>
          <td><?php echo nl2br($line['asthma_medications']); ?></td>
        </tr>
        <tr>
          <th>Special Needs</th>
          <th>Daily Medications</th>
        </tr>
        <tr>
          <td><?php echo $line['special_needs']; ?></td>
          <td><?php echo nl2br($line['daily_medication']); ?></td>
        </tr>
      </table>
    </div>
    <div class="row">
      <table class="table table-sm table-bordered">
        <tr>
          <th>Doctor</th>
          <td><?php echo $line['doctor']; ?></td>
          <th>Doctor's Number</th>
          <td><?php echo $line['doctor_number']; ?></td>
          <th>Hospital Preference</th>
          <td><?php echo $line['hospital']; ?></td>
        </tr>
        <tr>
          <th>Dentist</th>
          <td><?php echo $line['dentist']; ?></td>
          <th>Dentist's Number</th>
          <td><?php echo $line['dentist_number']; ?></td>
          <th>Transport to ER</th>
          <td><?php echo $line['er']; ?></td>
        </tr>
      </table>
    </div>

    <!--Delta Health Center page-->
    <div class="row page-start">
      <div class="col" style="text-align: center;">
        <h4>
          Delta Health Center Consent Form
        </h4>
      </div>
    </div>
    <div class="row">
      <div class="col" style="text-align: center;">
        <img src="images/dhc_logo.gif">
      </div>
    </div>
    <div class="row mt-3">
      <h6>
        Acknowledgement of Notice of Privacy and Consent to Use/Disclose Health Information
      </h6>
    </div>
    <div class="row">
      <p>
        I understand that as part of my healthcare, DHC originates and maintains health records describing a child's health history, symptoms, examinations, test results, diagnoses, treatment, and any plans for future care or treatment. This information serves as:
        <ul>
          <li>A basis for planning a child's care and treatment</li>
          <li>A means of communication among many healthcare professionals who contribute to a child's care</li>
          <li>A source of information for applying a child's diagnosis to any bill</li>
          <li>A means by which reimbursement agencies can certify that services billed were actually provided, and</li>
          <li>A tool for routine healthcare operations, such as assessing quality and reviewing the competence of the healthcare professionals</li>
        </ul>
      </p>
    </div>
    <div class="row">
      <table class="table table-sm">
        <tr>
          <th>Consent Given</th>
          <th>Consent Statement</th>
        </tr>
        <tr>
          <td><?php echo $line['consent_dhc_privacy']; ?></td>
          <td>I have received a copy of Delta Health Center's Notice of Privacy Practices.</td>
        </tr>
        <tr>
          <td><?php echo $line['consent_dhc_records']; ?></td>
          <td>I authorize members of Delta Health Center, Inc. and Hollandale School District to exchange health education/records for my child.</td>
        </tr>
        <tr>
          <td><?php echo $line['consent_dhc_screenings']; ?></td>
          <td>I give my consent for Delta Health Center, Inc to complete FREE medical/dental screenings for my child.</td>
        </tr>
      </table>
    </div>
    <div class="row mt-3">
      <table class="signedTable" width="100%">
        <tr class="signedRow">
          <td width="33%" class="entry"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
          <td width="33%" class="signed"></td>
          <td width="33%" class="entry"></td>
        </tr>
        <tr class="label">
          <td>Student Name</td>
          <td>Parent/Guardian Signature</td>
          <td>Date</td>
        </tr>
      </table>
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
