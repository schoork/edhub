<input type="hidden" name="form_type" value="outoftown">
<script src="js/purchasecode_script.js"></script>
<script src="forms/outoftown_scripts.js"></script>
<h1>
  Out of Town Travel Form
</h1>
<p class="lead">
  Use this form to request attendance at a professional activity outside the district.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<h2>
  Event Information
</h2>
<div class="form-group row">
  <label for="title" class="col-sm-3 col-form-label">Name of Meeting</label>
  <div class="col-sm-9">
    <input type="text" name="title" class="form-control" id="title">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="purpose" class="col-sm-3 col-form-label">Purpose of Meeting</label>
  <div class="col-sm-9">
    <input type="text" name="purpose" class="form-control" id="purpose">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="location" class="col-sm-3 col-form-label">Location of Meeting</label>
  <div class="col-sm-9">
    <input type="text" name="location" class="form-control" id="location" placeholder="City, ST">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="start" class="col-sm-3 col-form-label">Dates of Meeting</label>
  <div class="col-sm-4">
    <input type="date" name="start" class="form-control" id="start" placeholder="From">
    <small class="muted-text">From</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
  <div class="col-sm-4">
    <input type="date" name="end" class="form-control" id="end" placeholder="To">
    <small class="muted-text">To</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<h2>
  Travel Information
</h2>
<div class="form-group row">
  <label for="travel_start" class="col-sm-3 col-form-label">Dates of Travel</label>
  <div class="col-sm-4">
    <input type="date" name="travel_start" class="form-control" id="travel_start" placeholder="From">
    <small class="muted-text">Departure</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
  <div class="col-sm-4">
    <input type="date" name="travel_end" class="form-control" id="travel_end" placeholder="To">
    <small class="muted-text">Return</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="method" class="col-sm-3 col-form-label">Method of Travel</label>
  <div class="col-sm-9">
    <select name="method" class="form-control" id="method">
      <option disabled selected></option>
      <option value="Personal Vehicle">Personal Vehicle</option>
      <option value="District Vehicle">District Vehicle</option>
      <option value="Public Transportation">Public Transportation</option>
      <option value="Other">Other</option>
    </select>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="purchase_code" class="col-sm-3 col-form-label">Purchase Code for Payment</label>
  <div class="col-sm-9">
    <input type="text" name="purchase_code" class="form-control purchase_code" id="purchase_code" maxlength="30">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="expected_cost" class="col-sm-3 col-form-label">Expected Cost of Travel</label>
  <div class="col-sm-9">
    <input type="number" name="expected_cost" class="form-control monetary" id="expected_cost" placeholder="0.00" step="0.01">
    <small class="muted-text text-danger required">Required</small><br/>
    <small class="muted-text">When calculating expected cost, remember that Per Diem is $41/night and mileage is $0.535/mile driven. Overnight trips should have per diem, lodging, and mileage in the expected cost. One day trips will only have mileage. All trips should also include the expected cost for dues and fees required. <strong>Do NOT include Advance Travel Payments in the expected cost.</strong></small>
  </div>
</div>
<div class="advanceDiv" >
  <h2>
    Advance Travel Payments
  </h2>
  <div class="alert alert-info">
    Advance Travel Payments are used to pay for travel before the employee makes the trip. This can be in the form of a check to the employee for reimbursable expenses (meals, miles, lodging) or to an institution (lodging to hotel, fees to conference organizer). Federal funds only allow for advance travel to be paid for lodging.
  </div>
  <input type="hidden" name="itemTotal" id="itemTotal" value="0">
  <div id="checkDiv">

  </div>
  <p>
    <a class="btn btn-outline-primary" href="#!" id="addCheck">Add Check</a>
    <a class="btn btn-outline-danger disabled" href="#!" id="removeCheck">Remove Check</a>
  </p>
</div>
<h2>
  Supporting Documents
</h2>
<p>
  Use this section to add quotes, selection sheets, etc.
</p>
<div class="form-group row">
  <div class="col-sm-12">
    <label class="custom-file">
      <input type="file" id="file-1" name="file-1" class="custom-file-input">
      <span class="custom-file-control"></span>
    </label>
  </div>
</div>
<div id="filesDiv">

</div>
<p>
  <a class="btn btn-outline-primary" href="#!" id="addFile">Add File</a>
</p>
