<script src="forms/reimbursement_scripts.js"></script>
<script src="js/purchasecode_script.js"></script>
<input type="hidden" name="fileNum" id="fileNum" value="1">
<input type="hidden" name="form_type" value="reimbursement">
<link rel="stylesheet" href="forms/reimbursement_styles.css">
<h1>
    Travel Reimbursement Form
</h1>
<p class="lead">
    Use this form to request reimbursement of expenses incurred during official travel.
</p>
<p>
    This form is tied to your account. You should not complete this form for another employee.
</p>
<div class="form-group row">
    <label for="program" class="col-sm-3 col-form-label">Reason for Expenses</label>
    <div class="col-sm-9">
        <input type="text" name="program" class="form-control" id="program">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="purchase_code" class="col-sm-3 col-form-label">Purchase Code for Reimbursement</label>
    <div class="col-sm-9">
        <input type="text" name="purchase_code" class="form-control purchase_code" id="purchase_code" maxlength="30">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="travel_form" class="col-sm-3 col-form-label">Out of Town Travel Form Number</label>
    <div class="col-sm-9">
        <input type="number" name="travel_form" class="form-control" id="travel_form">
        <small class="muted-text text-danger required">Required</small>
    </div>
</div>
<h2>
    In-State Travel
</h2>
<div class="form-group row">
    <label for="instate_number" class="col-sm-3 col-form-label">Number of Nights Stayed</label>
    <div class="col-sm-9">
        <input type="number" name="instate_number" class="form-control" id="instate_number" placeholder="0">
    </div>
</div>
<div id="instate_perdiem_div">
</div>
<div class="form-group row">
    <label for="instate_perdiem" class="col-sm-3 col-form-label">Per Diem</label>
    <div class="col-sm-9">
        <input type="number" disabled class="form-control monetary instate" id="instate_perdiem" placeholder="0.00" step="0.01">
    </div>
</div>
<input type="hidden" name="instate_perdiem" id="instate_perdiem_hidden">
<h3>
    Other Expenses
</h3>
<div class="form-group row">
    <label for="instate_mealsTax" class="col-sm-3 col-form-label">Taxable Meals</label>
    <div class="col-sm-9">
        <input type="number" name="instate_mealstax" class="form-control monetary instate" id="instate_mealsTax" placeholder="0.00" step="0.01">
    </div>
</div>
<div class="form-group row">
    <label for="instate_mealsNoTax" class="col-sm-3 col-form-label">Non-Taxable Meals</label>
    <div class="col-sm-9">
        <input type="number" name="instate_mealsnotax" class="form-control monetary instate" id="instate_mealsNoTax" placeholder="0.00" step="0.01">
    </div>
</div>
<div class="form-group row">
    <label for="instate_lodging" class="col-sm-3 col-form-label">Lodging</label>
    <div class="col-sm-9">
        <input type="number" name="instate_lodging" class="form-control monetary instate" id="instate_lodging" placeholder="0.00" step="0.01">
    </div>
</div>
<div class="form-group row">
    <label for="instate_travelPrivate" class="col-sm-3 col-form-label">Travel (Personal Vehicle)</label>
    <div class="col-sm-9">
        <input type="number" name="instate_travelprivate" class="form-control monetary instate" id="instate_travelPrivate" placeholder="0.00" step="0.01">
        <small class="muted-text">This is calculated per miles driven ($0.535 per mile). Multiply the number of miles from the District Office to the destination by 1.07.</small>
    </div>
</div>
<div class="form-group row">
    <label for="instate_travelPublic" class="col-sm-3 col-form-label">Travel (Public Vehicle)</label>
    <div class="col-sm-9">
        <input type="number" name="instate_travelpublic" class="form-control monetary instate" id="instate_travelPublic" placeholder="0.00" step="0.01">
        <small class="muted-text">This includes, but is not limited to, bus lines, airlines, taxis, etc.</small>
    </div>
</div>
<div class="form-group row">
    <label for="instate_other" class="col-sm-3 col-form-label">Other Travel Costs</label>
    <div class="col-sm-9">
        <input type="number" name="instate_other" class="form-control monetary instate" id="instate_other" placeholder="0.00" step="0.01">
    </div>
</div>
<hr>
<div class="form-group row">
    <label for="instate_total" class="col-sm-3 col-form-label">In-State Subtotal</label>
    <div class="col-sm-9">
        <input type="number" class="form-control monetary" id="instate_total" disabled value="0.00" step="0.01">
    </div>
</div>
<h2>
    Out-of-State Travel
</h2>
<div class="form-group row">
    <label for="outstate_number" class="col-sm-3 col-form-label">Number of Nights Stayed</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_number" class="form-control" id="outstate_number" placeholder="0">
    </div>
</div>
<div id="outstate_perdiem_div">
</div>
<div class="form-group row">
    <label for="outstate_perdiem" class="col-sm-3 col-form-label">Per Diem</label>
    <div class="col-sm-9">
        <input type="number" disabled class="form-control monetary outstate" id="outstate_perdiem" placeholder="0.00" step="0.01">
    </div>
</div>
<input type="hidden" name="outstate_perdiem" id="outstate_perdiem_hidden">
<h3>
    Other Expenses
</h3>
<div class="form-group row">
    <label for="outstate_mealsTax" class="col-sm-3 col-form-label">Taxable Meals</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_mealstax" class="form-control monetary outstate" id="outstate_mealsTax" placeholder="0.00" step="0.01">
    </div>
</div>
<div class="form-group row">
    <label for="outstate_mealsNoTax" class="col-sm-3 col-form-label">Non-Taxable Meals</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_mealsnotax" class="form-control monetary outstate" id="outstate_mealsNoTax" placeholder="0.00" step="0.01">
    </div>
</div>
<div class="form-group row">
    <label for="outstate_lodging" class="col-sm-3 col-form-label">Lodging</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_lodging" class="form-control monetary outstate" id="outstate_lodging" placeholder="0.00" step="0.01">
    </div>
</div>
<div class="form-group row">
    <label for="outstate_travelPrivate" class="col-sm-3 col-form-label">Travel (Personal Vehicle)</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_travelprivate" class="form-control monetary outstate" id="outstate_travelPrivate" placeholder="0.00" step="0.01">
        <small class="muted-text">This is calculated per miles driven ($0.52 per mile). Multiply the number of miles from the District Office to the destination by 1.04.</small>
    </div>
</div>
<div class="form-group row">
    <label for="outstate_travelPublic" class="col-sm-3 col-form-label">Travel (Public Vehicle)</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_travelpublic" class="form-control monetary outstate" id="outstate_travelPublic" placeholder="0.00" step="0.01">
        <small class="muted-text">This includes, but is not limited to, bus lines, airlines, taxis, etc.</small>
    </div>
</div>
<div class="form-group row">
    <label for="outstate_other" class="col-sm-3 col-form-label">Other Travel Costs</label>
    <div class="col-sm-9">
        <input type="number" name="outstate_other" class="form-control monetary outstate" id="outstate_other" placeholder="0.00" step="0.01">
    </div>
</div>
<hr>
<div class="form-group row">
<label for="outstate_total" class="col-sm-3 col-form-label">Out-of-State Subtotal</label>
    <div class="col-sm-9">
        <input type="number" class="form-control monetary" id="outstate_total" disabled value="0.00" step="0.01">
    </div>
</div>
<hr>
<div class="form-group row">
    <label for="total" class="col-sm-3 col-form-label">Total Reimbursement</label>
    <div class="col-sm-9">
        <input type="number" class="form-control monetary" id="total" disabled placeholder="0.00" step="0.01">
        <small class="muted-text">This total is subject to verification. Any funds provided advanced to you prior to travel will be subtracted from this amount.</small>
    </div>
</div>
<h2>
    Supporting Documents
</h2>
<p>
    Use this section to upload supporting documents. All reimbursements should be supported by documents added here. Per Diem is supported by lodging documents.
</p>
<div class="form-group row">
    <div class="col-md-5">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="file-1" name="file-1">
            <label class="custom-file-label" for="file-1">Choose...</label>
        </div>
    </div>
</div>
<br/>
<div id="filesDiv">

</div>
<br/>
<p>
    <a class="btn btn-outline-primary" href="#!" id="addFile">Add Another File</a>
</p>
<p>
    By clicking Submit, I certify that the above amount claimed by me for travel expenses for the period indicated is true and accurate in all respects and that payment for any part has been received.
</p>

<script>

</script>
