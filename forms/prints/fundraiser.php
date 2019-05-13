<?php

require_once('../../connectvars.php');
require_once('../../appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, $_GET['formId']);

$page_title = 'Fundraiser Form ' . $formId;
$page_access = 'All';
include('../../header.php');





$query = "SELECT * FROM forms LEFT JOIN staff_list ON (forms.employee = staff_list.username) WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$program = $row['program'];
$location = $row['location'];
$submit_date = makeDateAmerican($row['submit_datetime']);
$employee = $row['firstname'] . ' ' . $row['lastname'];
$superintendent = $row['superintendent'];

$query = "SELECT * FROM forms_fundraiser WHERE form_id= $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

//include other scripts needed here
echo '<link rel="stylesheet" href="forms_print.css">';

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <?php
  if ($_SESSION['access'] == 'Admin') {
    ?>
    <div class="row hidden-print">
     <p>
       <a class="btn btn-success" href="../../approveform.php?formId=<?php echo $formId;?>&type=BusRequest">Edit Form</a>
     </p>
    </div>
    <?php
  }
  ?>
<!-- Write HTML just like a web page -->
 <div class="row">
    <div class="col" style="text-align: center;">
      <span id="districtName">Hollandale School District</span><br/>
      101 W. Washington St. PO Box 128<br/>
      Hollandale, MS 38748<br/>
      Phone: (662) 827-2276<br/>
      Fax: (662) 827-5261
    </div>
  </div>
  <div class="row">
    <h3>
      Fundraiser Request Form
    </h3>
  </div>
  <div class="row">
    <table style="width: 100%;" id="infoTable" class="mb-3 mt-3">
      <tr>
        <td class="entry" colspan="3"><?php echo $row['description']; ?></td>
      </tr>
      <tr class="label">
        <td colspan="3">Description of Fundraiser</td>
      </tr>
      <tr>
        <td class="entry" colspan="3"><?php echo $row['objective']; ?></td>
      </tr>
      <tr class="label">
        <td colspan="3">Proposed Use of Profits</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $program; ?></td>
        <td class="entry"><?php echo $row['school']; ?></td>
        <td class="entry"><?php echo $row['location']; ?></td>
      </tr>
      <tr class="label">
        <td>Fundraising Group</td>
        <td>School</td>
        <td>Solicitation Location(s)</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['past']; ?></td>
        <td class="entry"><?php echo makeDateAmerican($row['start']); ?></td>
        <td class="entry"><?php echo makeDateAmerican($row['end']); ?></td>
      </tr>
      <tr class="label">
        <td>Have you done this fundraiser in the past?</td>
        <td>Starting Date</td>
        <td>Ending Date</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['purchase']; ?></td>
        <td class="entry" colspan="2"><?php echo $row['facility']; ?></td>
      </tr>
      <tr class="label">
        <td>Do you need to purchase items for this fundraiser?</td>
        <td colspan="2">Will you need to use school district facilities (after hours) for this fundraiser?</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['cost']; ?></td>
        <td class="entry"><?php echo $row['revenue']; ?></td>
        <td class="entry"><?php echo $row['profit']; ?></td>
      </tr>
      <tr class="label">
        <td>Expected Startup Costs</td>
        <td>Expected Revenues</td>
        <td>Expected Profits</td>
      </tr>
      <tr>
        <td class="entry"><?php echo $row['purchase_code']; ?></td>
        <td class="entry"><?php echo $row['revenue_code']; ?></td>
      </tr>
      <tr class="label">
        <td>Purchasing Code</td>
        <td>Revenue Code</td>
      </tr>
    </table>
  </div>
  <div class="row">
    <p>
      <strong>Fundraiser Assurances:</strong><br/>
      By submitting this form, the employee has stated that he/she understands and agrees to the following.<br/>
      <ul>
        <li>Sales tax must be paid on all items purchased for resale, even if the purchase is not intended to raise a profit (exception: textbooks, pe uniforms, and coupon books or cards where taxes will be paid when coupon or card is used)</li>
        <li>The Superintendent must approve all fundraising activities held at the school and involving students. A contract must be signed between the Superintendent and the fundraising representative.</li>
        <li>The Superintendent must inform the School Board of all planned fundraisers expecting to raise more than $500.</li>
        <li>Fundraising companies shall be selected from a list of approved vendors. Note that the approved fundraiser list is not the same as the approved vendor list.</li>
        <li>Fundraising by clubs may be used to benefit the individual clubs; however, school-wide fundraiser proceeds must be deposited in the general fund and not an individual club account.</li>
        <li>An income statement (Money Collection/Fundraising Accounting Form) must be prepared at the end of each fundraising activity and be made available to students, teachers, and parents.</li>
        <li>All fundraising funds must be accounted for.</li>
        <li>A debt list must be maintained for students receiving but not paying for fundraising items.</li>
        <li>The use of school property and facilities in fundraising efforts shall be in accordance with Board policy.</li>
        <li>Expenditures of money raised through fundraising activities shall be made in accordance with proper purchasing procedures and Board policies.</li>
        <li>Academic credit shall not be given or deducted due to participation or non-participation in any fundraising event.</li>
        <li>Student incentives for fundraising programs, which include exclusion from regular school attendance or regular instructional time, should be minimal and must have prior approval of the Principal.</li>
      </ul>
    </p>
  </div>
  <div class="row">
    <table class="mt-3" style="width: 100%;" id="signedTable">
      <tr class="signedRow">
        <td class="signed" width="50%"><?php echo $employee; ?></td>
        <td class="signed" width="50%"><?php echo $superintendent; ?></td>
      </tr>
      <tr class="label">
        <td>Employee</td>
        <td>Superintendent</td>
      </tr>
    </table>
  </div>
  <div class="row">
    <p>
      <strong>Form History (#<?php echo $formId; ?>):</strong><br/>
      <em>
        <?php
        $query = "SELECT action, firstname, lastname, departments, date_time FROM forms_log LEFT JOIN staff_list ON (forms_log.user = staff_list.username) WHERE formId = $formId ORDER BY logId ASC";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)) {
          echo $row['action'] . ' by ' . $row['firstname'] . ' ' . $row['lastname'];
          if ($row['departments'] != '' || $row['departments'] !== null) {
            echo ' (' . str_replace("_", " ", ucwords($row['departments'])) . ')';
          }
          echo ' on ' . parseDatetime($row['date_time']). '<br/>';
        }
        ?>
      </em>
    </p>
  </div>
  <div class="row">
    <p>
      <strong>Supporting Documents:</strong><br/>
      <?php
      $query = "SELECT docname FROM forms_docs WHERE form_id = $formId";
      $result = mysqli_query($dbc, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
          echo '<a href="../../' . $row['docname'] . '"  target="_blank">' . substr($row['docname'], 10) . '</a><br/>';
        }
      }
      ?>
    </p>
  </div>

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
