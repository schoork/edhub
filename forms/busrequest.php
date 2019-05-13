<script src="forms/busrequest_scripts.js"></script>
<script src="js/purchasecode_script.js"></script>
<input type="hidden" name="form_type" value="busrequest">
<input type="hidden" name="hours" value="0" id="submit_hours">
<h1>
  Bus Request Form
</h1>
<p class="lead">
  Use this form to request use of a bus(es) for an activity trip.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<h2>
  Trip Information
</h2>
<div class="form-group row">
  <label for="title" class="col-sm-3 col-form-label">Purpose/Name of Trip</label>
  <div class="col-sm-9">
    <input type="text" name="title" class="form-control" id="title">
    <small class="muted-text text-danger required required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-3">School</label>
  <div class="col-sm-9">
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="school" value="Sanders">
        Sanders Elementary School
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="school" value="Simmons">
        Simmons Jr/Sr High School
      </label>
    </div>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="location" class="col-sm-3 col-form-label">Destination</label>
  <div class="col-sm-9">
    <input type="text" name="location" class="form-control" id="location" placeholder="City, ST">
    <small class="muted-text text-danger required">Required</small><br/>
    <small class="muted-text">Trips out of the state must have prior board approval and should be submitted at least 30 days in advance</small>
  </div>
</div>
<div class="form-group row">
  <label for="number" class="col-sm-3 col-form-label">Number of Students</label>
  <div class="col-sm-9">
    <input type="number" name="number" class="form-control" id="number">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="faculty" class="col-sm-3 col-form-label">Supervising Faculty Member(s)</label>
  <div class="col-sm-9">
    <textarea class="form-control" id="faculty" rows="3" name="faculty"></textarea>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="safety" class="col-sm-3 col-form-label">Safety Council Member(s)</label>
  <div class="col-sm-9">
    <textarea class="form-control" id="safety" rows="3" name="safety"></textarea>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="travel_start" class="col-sm-3 col-form-label">Dates and Times of Trip</label>
  <div class="col-sm-4">
    <input type="datetime-local" name="travel_start" class="form-control" id="travel_start" placeholder="From">
    <small class="muted-text">Departure</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
  <div class="col-sm-4">
    <input type="datetime-local" name="travel_end" class="form-control" id="travel_end" placeholder="To">
    <small class="muted-text">Return</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="length" class="col-sm-3 col-form-label">Estimated Length of Trip</label>
  <div class="col-sm-9">
    <input type="text" name="length" class="form-control" id="length" disabled value="0 hours">
  </div>
</div>
<h2>
  Expense Information
</h2>
<div class="form-group row">
  <label for="pay_group" class="col-sm-3 col-form-label">Payment Provided by</label>
  <div class="col-sm-9">
    <input type="text" name="pay_group" class="form-control" id="pay_group">
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
<h3>
  Mileage Expenses
</h3>
<div class="form-group row">
  <label for="bus_num" class="col-sm-3 col-form-label">Number of Buses Needed</label>
  <div class="col-sm-9">
    <input type="number" name="bus_num" class="form-control" id="bus_num" >
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="row">
  <div class="col-sm-9 offset-sm-3">
    x<br/>
    <br/>
  </div>
</div>
<div class="form-group row">
  <label for="miles" class="col-sm-3 col-form-label">Number of Miles</label>
  <div class="col-sm-9">
    <input type="number" name="miles" class="form-control" id="miles">
    <small class="muted-text text-danger required">Required</small><br/>
    <small class="muted-text">Miles provided should be both ways from 101 W. Washington St to destination.</small>
  </div>
</div>
<div class="row">
  <div class="col-sm-9 offset-sm-3">
    x $1.00 per mile
  </div>
</div>
<hr>
<div class="form-group row">
  <label for="miles_cost" class="col-sm-3 col-form-label">Total Mileage Cost</label>
  <div class="col-sm-9">
    <input type="number" class="form-control" id="miles_cost" disabled name="miles_cost" value="0.00">
  </div>
</div>
<h3>
  Driver Expenses
</h3>
<div class="form-group row">
  <label for="driver_num" class="col-sm-3 col-form-label">Number of Drivers Needed</label>
  <div class="col-sm-9">
    <input type="number" name="driver_num" class="form-control" id="driver_num">
    <small class="muted-text text-danger required">Required</small><br/>
    <small class="muted-text">If your program is providing drivers, put zero here.</small>
  </div>
</div>
<div class="form-group row">
  <label for="drivers" class="col-sm-3 col-form-label">Drivers</label>
  <div class="col-sm-9">
    <input type="text" name="drivers" class="form-control" id="drivers">
    <small class="muted-text">Only put driver names if you are providing your own drivers (to reduce costs). Drivers provided by the program will not be paid.</small>
  </div>
</div>
<div class="row">
  <div class="col-sm-9 offset-sm-3">
    x<br/>
    <br/>
  </div>
</div>
<div class="form-group row">
  <label for="hours" class="col-sm-3 col-form-label">Number of Hours</label>
  <div class="col-sm-9">
    <input type="number" class="form-control" id="hours" name="hours" disabled value="0">
  </div>
</div>
<div class="row">
  <div class="col-sm-9 offset-sm-3">
    x $11.00 per hour
  </div>
</div>
<hr>
<div class="form-group row">
  <label for="driver_cost" class="col-sm-3 col-form-label">Total Driver Cost</label>
  <div class="col-sm-9">
    <input type="number" class="form-control" id="driver_cost" name="driver_cost" disabled value="0.00">
  </div>
</div>
<hr>
<div class="form-group row">
  <label for="total" class="col-sm-3 col-form-label">Total Cost of Trip</label>
  <div class="col-sm-9">
    <input type="number" class="form-control" id="total" disabled value="0.00">
  </div>
</div>
<p>
  By clicking Submit, I state that I understand that time away from work is subject to management approval and dsitrict policies.
</p>