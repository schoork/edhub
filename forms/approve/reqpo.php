<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/hollandale/connectvars.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/hollandale/appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$formId = mysqli_real_escape_string($dbc, trim($_GET['formId']));
$query = "SELECT * FROM forms WHERE form_id = $formId";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

?>
<div class="form-group row">
  <label for="req" class="col-form-label col-sm-3">Requisition Number</label><br/>
  <div class="col-sm-9">
    <input type="text" class="form-control" name="req" value="<?php echo $row['req']; ?>">
  </div>
</div>
<div class="form-group row">
  <label for="po" class="col-form-label col-sm-3">PO Number</label><br/>
  <div class="col-sm-9">
    <input type="text" class="form-control" name="po"  value="<?php echo $row['req']; ?>">
  </div>
</div>
<div class="alert" role="alert" id="po-alert">

</div>