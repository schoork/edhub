<script src="forms/timeoff.js"></script>
<input type="hidden" name="form_type" value="timeoff">
<h1>
    Time Off Request Form
</h1>
<p class="lead">
    Use this form to request time off from work.
</p>
<p>
    This form is tied to your account. You should not complete this form for another employee.
</p>
<div class="form-group row">
    <label for="number" class="col-sm-3 col-form-label">Number of Days</label>
    <div class="col-sm-9">
        <input type="number" name="number" class="form-control" id="number">
        <small class="muted-text text-danger required">Required</small><br>
        <small class="muted-text">If requesting a half day, put a 1 in this box.</small>
    </div>
</div>
<div class="form-group row" id="halfDayDiv" style="display: none;">
    <label class="col-sm-3">Length</label>
    <div class="col-sm-9">
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="length" value="Full Day" checked="checked">
                Full Day
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="length" value="Half Day (AM)">
                Half Day (AM)
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="length" value="Half Day (PM)">
                Half Day (PM)
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="start" class="col-md-3 col-form-label">Start Date</label>
    <div class="col-md-4">
        <input type="date" name="start" class="form-control" id="start">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="end" class="col-md-3 col-form-label">End Date</label>
    <div class="col-md-4">
        <input type="date" name="end" class="form-control" id="end">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3">Type of Request</label>
    <div class="col-sm-9">
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="type" value="Bereavement">
                Bereavement
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="type" value="Jury Duty">
                Jury Duty
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="type" value="Personal">
                Personal
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="type" value="Professional">
                Professional
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="type" value="Sick">
                Sick
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="type" value="Vacation">
                Vacation
            </label>
        </div>
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row" id="relativeDiv" style="display: none;">
    <label for="relationship" class="col-sm-3 col-form-label">Relationship</label>
    <div class="col-sm-9">
        <input type="text" name="relationship" class="form-control" id="relationship">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-sm-3 col-form-label">Description of Absence</label>
    <div class="col-sm-9">
        <textarea class="form-control" id="description" rows="3" name="description"></textarea>
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
