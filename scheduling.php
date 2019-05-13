<?php

$page_title = 'Scheduling';
include('header.php');

//include other scripts needed here


//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar.php');

//start main
echo '<main>';
  
?>

<div class="row">
  <div class="col s12">
    <h4>
      Scheduling
    </h4>
  </div>
</div>
<div class="row">
  <div class="col s12">
    Developing a master schedule, especially at the high school level, can be a time-consuming and tough task. We try to make it easier with the process outlined below. This is a tried and true method for creating a master schedule built to suit your students' needs.
  </div>
</div>
<div class="row">
  <div class="col s12">
    <h5>
      Course Requests
    </h5>
    <ol>
      <li>Have students use the <a href="courserequests.php">course requests page</a> to select which courses they want to take next year.</li>
      <li>Check the course requests to make sure students selected the courses that best fit their needs. You can use the <a href="#">requests check page</a> to assist you in this task.</li>
    </ol>
  </div>
</div>
<div class="row">
  <div class="col s12">
    <h5>
      Sections
    </h5>
    <ol>
      <li>Use the <a href="#">course load page</a> to determine how many sections of each course you need. This will help you determine how many teachers you need to hire in each department.</li>
      <li>Now that you have your teachers in place, use the <a href="#">teacher load page</a> to determine who will teach which sections.</li>
      <li>Determine specific requirements you have for the master schedule using the <a href="#">schedule requirements page</a>.</li>
      <li>Build the schedule using the <a href="#">build schedule page</a>. Scheduling requirements will be pre-loaded into the schedule page, so complete the previous step before jumping into this one.</li>
    </ol>
  </div>
</div>

<?php

//end main
echo '</main>';

//include footer
include('footer.php');
?>