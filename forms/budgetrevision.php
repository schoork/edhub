<script src="forms/budgetrevision_scripts.js"></script>
<input type="hidden" name="form_type" value="budgetrevision">
<h1>
  Budget Revision Form
</h1>
<p class="lead">
  Use this form to revise initial budget balances for the funds you control.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<?php
$query = "SELECT line_id, purchase_code FROM budget ORDER BY purchase_code";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) > 0) {
  ?>
  <input type="hidden" name="employee_budget" value="<?php echo $_SESSION['username']; ?>">
  <div id="lineDiv">
    <div class="form-group row">
      <label for="from_line" class="col-sm-3 col-form-label">Moving From</label>
      <div class="col-sm-6">
        <select name="from_line" id="from_line" class="form-control">
          <option disabled selected></option>
          <?php
          while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['line_id'] . '">' . $row['purchase_code'] . '</option>';
          }
           ?>
           <option value="adding">Adding to Budget</option>
        </select>
        <small class="muted-text text-danger required">Required</small>
      </div>
      <div class="col-sm-3">
        Current Balance: $<span id="from_bal">0.00</span>
      </div>
    </div>
    <div class="form-group row">
      <label for="to_line" class="col-sm-3 col-form-label">Moving To</label>
      <div class="col-sm-6">
        <select name="to_line" id="to_line" class="form-control">
          <option disabled selected></option>
          <?php
          $query = "SELECT line_id, purchase_code FROM budget ORDER BY purchase_code";
          $result = mysqli_query($dbc, $query);
          while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['line_id'] . '">' . $row['purchase_code'] . '</option>';
          }
           ?>
           <option value="deleting">Removing from Budget</option>
        </select>
        <small class="muted-text text-danger required">Required</small>
      </div>
      <div class="col-sm-3">
        Current Balance: $<span id="to_bal">0.00</span>
      </div>
    </div>
    <div class="form-group row">
      <label for="amount" class="col-sm-3 col-form-label">Amount</label>
      <div class="col-sm-9">
        <input type="number" class="form-control" id="amount" name="amount" step="0.01">
        <small class="muted-text text-danger required">Required</small>
      </div>
    </div>
  </div>
  <?php
}
else {
  //cannot control and budget lines
  ?>
  <div class="alert alert-danger">
    You do not have access to revise any budget lines.
  </div>
  <?php
}
?>


<p>
  By clicking Submit, I state that I understand that time away from work is subject to management approval and dsitrict policies.
</p>
