<script src="forms/deposit_scripts.js"></script>
<input type="hidden" name="form_type" value="deposit">
<h1>
  Daily Deposit Form
</h1>
<p class="lead">
  Use this form to indicate monies deposited at the bank.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<div class="form-group row">
  <label class="col-sm-3">School</label>
  <div class="col-sm-9">
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="school" value="1151">
        Sanders Elementary School
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="school" value="1153">
        Simmons Jr/Sr High School
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="school" value="2330">
        District Office
      </label>
    </div>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row hidden">
  <label for="account" class="col-sm-3 col-form-label">Source of Revenue</label>
  <div class="col-sm-9">
    <select name="account" id="account" class="form-control">
      <option></option>
      <option value="Band">Band</option>
      <option value="Baseball">Baseball</option>
      <option value="Basketball (Sr High)">Basketball (Sr High)</option>
      <option value="Basketball (Jr High)">Basketball (Jr High)</option>
      <option value="Dance">Dance</option>
      <option value="Donation">Donation</option>
      <option value="Fees">Fees (book fines, library fines, cellphone fines, transcripts, etc.)</option>
      <option value="Football (Sr High)">Football (Sr High)</option>
      <option value="Football (Jr High)">Football (Jr High)</option>
      <option value="Sales">Sales</option>
      <option value="School Pictures">School Pictures</option>
      <option value="Other Revenues">Other Revenues</option>
      <option value="Miscellaneous">Miscellaneous</option>
    </select>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="revenue_code" class="col-sm-3 col-form-label">Revenue Code</label>
  <div class="col-sm-9">
    <input type="text" class="form-control purchase_code" id="revenue_code" name="revenue_code" maxlength="30">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="receipt" class="col-sm-3 col-form-label">Receipt Number</label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="receipt" name="receipt" maxlength="30">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="description" class="col-sm-3 col-form-label">Description</label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="description" name="description" maxlength="60">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="amount" class="col-sm-3 col-form-label">Amount Deposited</label>
  <div class="col-sm-9">
    <input type="number" class="form-control" id="amount" name="amount" step="0.01">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<p>
  By clicking Submit, I state that I understand that time away from work is subject to management approval and dsitrict policies.
</p>
