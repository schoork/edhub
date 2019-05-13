<?php

$page_title = 'Course Requests';
include('header.php');

//include other scripts needed here
echo '<script type="text/javascript" src="js/courserequests_scripts.js"></script>';
echo '<link type="text/css" rel="stylesheet" href="css/courserequests_styles.css"/>';

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
      Course Requests
    </h4>
  </div>
</div>
<form id="requests_form" method="post">
  <input type="hidden" name="action" value="addRequest">
  <div class="row">
    <div class="col s12 m6 input-field">
      <input type="text" name="name" id="name"><label for="name">Your Name</label>
    </div>
    <div class="col s12 m6 input-field">
      <select name="grade" id="grade_select">
        <option disabled selected value=""></option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
      </select>
      <label>Your Current Grade</label>
    </div>
  </div>
  <div class="row">
    <div id="info-row" class="col s12 hide">
      Next year you will likely be in the <span class="grade-span"></span>th grade. Students in the <span class="grade-span"></span>th grade usually take those classes colored in <span class="blue-text">blue</span>.
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6 l3">
      <h5>
        English
      </h5>
      <input type="radio" name="english" id="english-1" value="English I"><label for="english-1" class="grade-9">English I</label><br/>
      <input type="radio" name="english" id="english-2" value="English II"><label for="english-2">English II</label><br/>
      <input type="radio" name="english" id="english-3" value="English III"><label for="english-3">English III</label><br/>
      <input type="radio" name="english" id="english-4" value="English IV"><label for="english-4">English IV</label><br/>
      <a class="waves-effect waves-light btn" id="clr-english">Clear English</a>
    </div>
    <div class="col s12 m6 l3">
      <h5>
        Math
      </h5>
      <input type="radio" name="math" id="math-1" value="Algebra I"><label for="math-1" class="grade-9">Algebra I</label><br/>
      <input type="radio" name="math" id="math-2" value="Geometry"><label for="math-2">Geometry</label><br/>
      <input type="radio" name="math" id="math-3" value="Algebra II"><label for="math-3">Algebra II</label><br/>
      <input type="radio" name="math" id="math-4" value="4th Math"><label for="math-4">4th Math</label><br/>
      <a class="waves-effect waves-light btn" id="clr-math">Clear Math</a>
    </div>
    <div class="col s12 m6 l3">
      <h5>
        Science
      </h5>
      <input type="radio" name="science" id="science-1" value="Environmental Science"><label for="science-1" class="grade-9">Environmental Science</label><br/>
      <input type="radio" name="science" id="science-2" value="Biology I"><label for="science-2">Biology I</label><br/>
      <input type="radio" name="science" id="science-3" value="Chemistry"><label for="science-3">Chemistry</label><br/>
      <input type="radio" name="science" id="science-4" value="Physcial Science"><label for="science-4">Physical Science</label><br/>
      <a class="waves-effect waves-light btn" id="clr-science">Clear Science</a>
    </div>
    <div class="col s12 m6 l3">
      <h5>
        History
      </h5>
      <input type="radio" name="history" id="history-1" value="World History"><label for="history-1" class="grade-9">World History</label><br/>
      <input type="radio" name="history" id="history-2" value="US History"><label for="history-2">US History</label><br/>
      <input type="radio" name="history" id="history-3" value="Economics/Government"><label for="history-3">Economics/Government</label><br/>
      <a class="waves-effect waves-light btn" id="clr-history">Clear History</a>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6 input-field">
      <h5>
        Electives
      </h5>
      <input type="checkbox" name="electives[]" id="elec-1" value="Health"><label for="elec-1">Health</label><br/>
      <input type="checkbox" name="electives[]" id="elec-2" value="Spanish I"><label for="elec-2">Spanish I</label><br/>
      <input type="checkbox" name="electives[]" id="elec-3" value="Spanish II"><label for="elec-3">Spanish II</label><br/>
      <input type="checkbox" name="electives[]" id="elec-4" value="ROTC"><label for="elec-4">ROTC</label>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6">
      <h5>
        Your Requests
      </h5>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6">
      1. <span id="req-1"></span><br/>
      2. <span id="req-2"></span><br/>
      3. <span id="req-3"></span><br/>
      4. <span id="req-4"></span><br/>
      5. <span id="req-5"></span><br/>
      6. <span id="req-6"></span><br/>
      7. <span id="req-7"></span>
    </div>
    <div class="col s12 m6">
      Selected Credits: <span id="req-selected">0</span><br/>
      Needed Credits: <span id="req-needed">7</span><br/>
      <span class="error hide">You have selected too many courses! Please de-select courses until you only have 7 credits selected.</span><br/>
      <span class="blue-text" id="core_warning-eng">You have not yet selected an English course.<br/></span>
      <span class="blue-text" id="core_warning-math">You have not yet selected a Math course.<br/></span>
      <span class="blue-text" id="core_warning-science">You have not yet selected a Science course.<br/></span>
      <span class="blue-text" id="core_warning-history">You have not yet selected a History course.</span>
    </div>
  </div>
  <div class="row">
    <div class="col s12">
      <a class="waves-effect waves-light btn" id="btnSubmit">
        <i class="material-icons right">send</i>
        Submit Requests
      </a>
    </div>
  </div>
</form>


<?php

//end main
echo '</main>';

//include footer
include('footer.php');
?>