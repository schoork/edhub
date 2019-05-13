<?php
$page_title = 'DHA Forms';
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
          Delta Health Alliance Consent Agreement
        </h4>
      </div>
    </div>
    <div class="row">
      <div class="col" style="text-align: center;">
        <img src="images/dha_logo.jpg">
      </div>
    </div>
    <div class="row mt-3" style="font-size: 60%">
      <p>
        By signing this agreement, you give consent to disclose and share personally identifiable information on the person listed below with authorized partners in the Deer Creek Promise Community (DCPC). The purpose of sharing this information is to allow the DCPC to provide well-informed, coordinated services to participants and their families, to conduct ongoing evaluation and improvement of programs to better serve the community, and to report results of programs and activities to residents, partners, and funders. The DCPC takes every precaution to protect personally identifiable information from unauthorized use or disclosure. Information obtained on persons shall not be published in a manner that will lead to the identification of any individual. This information is used solely for service provision and program evaluation purposes and identified information shall not be further redisclosed to third parties not covered by this Consent Agreement without your prior written consent.<br/>
        <br/>
        I understand that the records to be disclosed and shared with DCPC may include but are not limited to:
        <ul>
          <li>Education records from the Hollandale School District and/or Leland School District including:
            <ul>
              <li>Enrollment information</li>
              <li>Classroom performance and behavior</li>
              <li>Performance on state assessments and other standardized assessments.</li>
              <li>Grade reports and transcripts</li>
              <li>Attendance</li>
              <li>Survey data (i.e. Youth Behavior Risk Survey, Family Home Reading Practices, etc)</li>
            </ul>
          </li>
          <li>Records from DCPC Service Providers, including:
            <ul>
              <li>Intake information collected on participants (such as name, address, and date of birth)</li>
              <li>Participation data (such as services received, attendance dates, and length of time participating)</li>
              <li>Program results and assessments (such as test results and observations by program staff)</li>
            </ul>
          </li>
          <li>Photographs
            <ul>
              <li>Use of photography in any DCPC publication or advertising materials. All rights of privacy or compensation, which may be in connection with use of the photograph are waived.</li>
            </ul>
          </li>
        </ul>
        I consent that the following parties may obtain the information described above stripped of direct identifiers:
        <ul>
          <li>The U.S. Department of Education and its authorized contractor(s).</li>
          <li>The Delta Health Alliance external evaluator and its team of authorized researcher(s).</li>
        </ul>
        For up to date information and questions, please contact the DCPC at: Delta Health Alliance; Deer Creek Promise Community; 135 Front Street; P.O. Box 150; Indianola, MS 38751; Ph (662)-686-3937<br/>
      </p>
    </div>
    <div class="row" style="font-size: 60%">
      <p>
        I HAVE READ, UNDERSTOOD AND ACCEPTED THE ABOVE STATEMENTS: I hereby give my consent to release information as deemed beneficial to me and/or my family and will be an active participant in the process. This Consent Agreement is valid for the duration of the DCPC initiative. Until such time as I withdraw my consent, which must be communicated in writing and addressed to the DCPC my consent shall remain in place, valid and effective. I have a right to receive a copy of this document. I reserve all rights provided to me by law not waived by the scope of this consent and authorization.
      </p>
    </div>
    <?php
    if ($row['dha'] == 'Yes') {
      ?>
      <div class="row mt-3">
        <table class="signedTable" width="100%">
          <tr class="signedRow">
            <td width="50%" class="entry"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
            <td width="50%" class="entry"><?php echo makeDateAmerican($row['birthday']); ?></td>
          </tr>
          <tr class="label">
            <td>Participant's Name</td>
            <td>Date of Birth</td>
          </tr>
          <tr class="signedRow">
            <td width="50%" class="signed"></td>
            <td width="50%" class="signed"></td>
          </tr>
          <tr class="label">
            <td>Parent/Guardian's Signature</td>
            <td>Date</td>
          </tr>
        </table>
      </div>
      <?php
    }
    else {
      ?>
      <div class="row mt-3">
        <table class="signedTable" width="100%">
          <tr class="signedRow">
            <td width="50%" class="entry"></td>
            <td width="50%" class="entry"></td>
          </tr>
          <tr class="label">
            <td>Participant's Name</td>
            <td>Date of Birth</td>
          </tr>
          <tr class="signedRow">
            <td width="50%" class="signed"></td>
            <td width="50%" class="signed"></td>
          </tr>
          <tr class="label">
            <td>Parent/Guardian's Signature</td>
            <td>Date</td>
          </tr>
        </table>
      </div>
      <?php
    }
    if ($row['grade'] == 'K') {
      ?>
      <div class="row page-start">
        <div class="col" style="text-align: center;">
          <h4>
            Kindergarten Intake Survey
          </h4>
        </div>
      </div>
      <div class="row mt-3">
        <table class="table table-sm table-bordered">
          <tr>
            <th>Child Name</th>
            <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
            <th>Date of Birth</th>
            <td><?php echo makeDateAmerican($row['birthday']); ?></td>
            <th>Gender</th>
            <td><?php echo $row['gender']; ?></td>
          </tr>
          <?php
          $query = "SELECT * FROM registration_kindergarten WHERE student_id = $student_id";
          $result = mysqli_query($dbc, $query);
          $line = mysqli_fetch_array($result);
          ?>
          <tr>
            <th colspan="2">Highest Education Level (Mother)</th>
            <td><?php echo $line['mother_educ']; ?></td>
            <th colspan="2">Yearly Household Income</th>
            <td><?php echo $row['house_income']; ?></td>
          </tr>
        </table>
      </div>
      <div class="row">
        <p>
          <strong>Does your child have a regular bedtime routine?</strong> <?php echo $line['bedtime']; ?><br>
          <strong>Is there a place that your student usually goes when he/she is sick or you need advice about his/her health?</strong> <?php echo $line['sick']; ?><br>
          <strong>How often does someone in your home read a picture book with your child?</strong> <?php echo $line['pic_book']; ?><br>
          <strong>How often does someone in your home talk about a book after reading it with your child?</strong> <?php echo $line['talk_book']; ?><br>
          <strong>How often does someone in your home sing or say the alphabet with your child?</strong> <?php echo $line['alphabet']; ?><br>
          <strong>How often does someone in your home sing or say nursery rhymes with your child?</strong> <?php echo $line['nursery']; ?><br>
          <strong>How often does someone in your home tell your child stories without using books?</strong> <?php echo $line['stories']; ?><br>
          <strong>How often does someone in your home go to the library with your child?</strong> <?php echo $line['library']; ?><br>
          <strong>How often does your child ask to be read to?</strong> <?php echo $line['ask_read']; ?><br>
          <strong>How often does your child look at books by himself or herself?</strong> <?php echo $line['books_self']; ?><br>
          <strong>How often does your child see someone in your home reading for fun?</strong> <?php echo $line['fun']; ?><br>
          <strong>How many picture books do you have in your home?</strong> <?php echo $line['picbooks_have']; ?><br>
          <strong>How much does your child like being read to?</strong> <?php echo $line['like_read']; ?><br>
          <strong>How comfortable are you reading to your child?</strong> <?php echo $line['comfort']; ?><br>
          <strong>Did you child attend one of the following programs this past year before Kindergarten?</strong> <?php echo $line['before_k']; ?><br>
          <strong>My child received Dolly Parton's Imagination Library books through the mail.</strong> <?php echo $line['dolly']; ?><br>
          <strong>Do you have internet on a computer at home?</strong> <?php echo $line['internet']; ?><br>
        </p>
      </div>
      <?php
    }
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
