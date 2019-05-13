<?php
$page_title = 'Weekly Data - Schools';
$page_access = 'All';
include('../header.php');

echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
echo '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
echo '<link rel="stylesheet" href="../css/datacharts_print.css">';
echo '<script src="../../js/moment.js"></script>';
echo '<script src="../js/plugin-pointLabel.js"></script>';
echo '<script src="js/schoollevel.js"></script>';

//include other scripts needed here

//end header
echo '</head>';
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>

  <!-- Write HTML just like a web page -->
  <div class="row hidden-print">
    <div class="alert alert-info">
      When printing, (1) make sure you print in landscape and (2) uncheck Headers and Footers from the Options section. This can be found in the Google Chrome dialog under <em>+ More Settings</em>
    </div>
  </div>
  <div class="row hidden-print">
    <a class="btn btn-primary" href="weeklydata.php">Back to Weekly Data</a>
  </div>
  <div class="row">
    <h1>
      School-Level Weekly Data
    </h1>
  </div>
  <div class="row mt-3">
    <div class="col">
      <div class="ct-chart ct-golden-section" id="chart1"></div>
    </div>
  </div>
</body>
</html>
