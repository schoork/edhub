<?php
$page_title = 'Teacher Attendance Report';
$page_access = 'All';
include('header.php');

echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
echo '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
echo '<script src="../js/moment.js"></script>';
echo '<script src="js/plugin-pointLabel.js"></script>';
echo '<script src="js/plugin-chartaccessibility.js"></script>';
echo '<script src="js/plugin-legend.js"></script>';
echo '<link rel="stylesheet" href="css/plugin-legend.css">';
echo '<script src="js/teacherattendance_scripts.js"></script>';
echo '<link rel="stylesheet" href="css/teacherattendance_styles.css">';

//include other scripts needed here

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <!-- Write HTML just like a web page -->
  <div class="container">
  <div class="row">
    <h1>
      Teacher Attendance Report
    </h1>
  </div>
  <div class="row mt-3">
    <div class="col">
      <div class="ct-chart ct-major-eleventh" id="chart1"></div>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col">
      <div class="ct-chart ct-golden-section" id="chart2"></div>
    </div>
  </div>
</div>
</body>
</html>
