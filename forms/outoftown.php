<input type="hidden" name="form_type" value="outoftown">
<link rel="stylesheet" href="forms/reimbursement_styles.css">
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
    <label class="col-sm-3">Traveling out of State?</label>
    <div class="col-sm-9">
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="outofstate" value="Yes">
                Yes
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="outofstate" value="">
                No
            </label>
        </div>
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
<div class="alert alert-info" role="alert">
    <strong>Do NOT include Advance Travel Payments in the expected cost of reimbursement. You will not be reimbursed money that you have already been paid.</strong> When calculating the expected cost of reimbursement, remember that Per Diem is $41/night and mileage is $0.535/mile driven. One day trips will only have mileage. All trips should also include the expected cost for dues and fees required.
</div>
<div class="form-group row">
    <label for="expected_cost" class="col-sm-3 col-form-label">Expected Cost of Reimbursement</label>
    <div class="col-sm-9">
        <input type="number" name="expected_cost" class="form-control monetary" id="expected_cost" placeholder="0.00" step="0.01">
        <small class="muted-text text-danger required">Required</small><br/>
    </div>
</div>
<div class="form-group row">
    <label for="purchase_code" class="col-sm-3 col-form-label">Purchase Code for Payment</label>
    <div class="col-sm-9">
        <input type="text" name="purchase_code" class="form-control purchase_code" id="purchase_code" maxlength="30">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="advanceDiv">
    <h2>
        Advance Travel Payments
    </h2>
    <div class="alert alert-info">
        <strong>Federal funds will only pay advance travel for lodging. Miles and per diem must be recouped in a reimbursement AFTER the trip.</strong> Advance Travel Payments are used to pay for travel before the employee makes the trip. This can be in the form of a check to the employee for reimbursable expenses (meals, miles, lodging) or to an institution (lodging to hotel, fees to conference organizer).<br/>
        <br/>
        <strong>There is NO need to complete a requisition for a check if you have requested a check through this form.</strong>
    </div>
    <input type="hidden" name="itemTotal" id="itemTotal" value="0">
    <div id="checkDiv">

    </div>
    <p>
        <a class="btn btn-outline-primary" href="#!" id="addCheck">Add Check</a>
        <a class="btn btn-outline-danger disabled" href="#!" id="removeCheck">Remove Check</a>
    </p>
    <div class="form-group row">
        <label for="checkdate" class="col-sm-3 col-form-label">Date Check(s) Needed</label>
        <div class="col-sm-9">
            <input type="date" name="checkdate" class="form-control" id="checkdate" disabled>
        </div>
    </div>
</div>
<h2>
    Supporting Documents
</h2>
<p>
    Use this section to add quotes, selection sheets, miles documents, hotel confirmations, etc.
</p>
<div class="alert alert-info" role="alert">
    Supporting documents are REQUIRED when requesting federal funds.
</div>
<input type="hidden" name="fileNum" id="fileNum" value="1">
<div class="form-group row">
    <div class="col-md-5">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="file-1" name="file-1">
            <label class="custom-file-label" for="file-1">Choose...</label>
        </div>
    </div>
</div>
<div id="filesDiv">

</div>
<p>
    <a class="btn btn-outline-primary" href="#!" id="addFile">Add File</a>
</p>
