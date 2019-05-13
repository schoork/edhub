<script src="js/purchasecode_script.js"></script>
<input type="hidden" name="form_type" value="activity">
<h1>
  Activity Request Form
</h1>
<p class="lead">
  Use this form to request use of school facilities for a school-sponsored activity.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<div class="form-group row">
  <label for="activity" class="col-sm-3 col-form-label">Activity</label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="activity" name="activity">
    <small class="muted-text text-danger required required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="purpose" class="col-sm-3 col-form-label">Purpose</label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="purpose" name="purpose">
    <small class="muted-text text-danger required required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="location" class="col-sm-3 col-form-label">Location of Activity</label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="location" name="location">
    <small class="muted-text text-danger required required">Required</small><br/>
    <small class="muted-text">Be specific, i.e. High School Gym</small>
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
  <label for="date" class="col-sm-3 col-form-label">Date of Activity</label>
  <div class="col-sm-9">
    <input type="date" class="form-control" id="date" name="date">
    <small class="muted-text text-danger required required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="start" class="col-sm-3 col-form-label">Times of Activity</label>
  <div class="col-sm-4">
    <input type="time" name="start" class="form-control" id="start">
    <small class="muted-text">Start Time</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
  <div class="col-sm-4">
    <input type="time" name="end" class="form-control" id="end">
    <small class="muted-text">End Time</small><br/>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>