<script src="forms/requisition_scripts.js"></script></script>
<script src="js/purchasecode_script.js"></script>
<link rel="stylesheet" href="forms/reimbursement_styles.css">
<input type="hidden" name="itemTotal" id="itemTotal" value="1">
<input type="hidden" name="fileNum" id="fileNum" value="1">
<input type="hidden" name="form_type" value="requisition">

<?php
$funds = array(
    '1140' => 'Alternative School',
    '1145' => 'At Risk',
    '2290' => 'Cost Pool',
    '1120' => 'District Maintenance',
    '2410' => 'EEF',
    '2110' => 'School Food Service',
    '1130' => 'Special Education',
    '2211' => 'Title I',
    '2511' => 'Title II',
    '2811' => 'Title IV',
    '2330' => 'Title V',
    '1153' => '1153',
    '7321' => '7321'
);
$functions = array(
    '1105' => 'Pre-Kindergarten',
    '1110' => 'Kindergarten',
    '1120' => 'Elementary',
    '1140' => 'High School',
    '1142' => 'CTE',
    '1210' => 'Gifted Education',
    '1220' => 'Special Education'
);

?>

<h1>
    Requisition Form
</h1>
<p class="lead">
    Use this form to purchase supplies or pay for services.
</p>
<p>
    This form is tied to your account. You should not complete this form for another employee.
</p>
<h2>
    Vendor Information
</h2>
<div class="form-group row">
    <label for="vendor" class="col-sm-3 col-form-label">Vendor</label>
    <div class="col-sm-9">
        <input type="text" name="vendor" class="form-control" id="vendor">
        <small class="uted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-sm-3 col-form-label">Address</label>
    <div class="col-sm-9">
        <input type="text" name="address" class="form-control" id="address">
        <small class="uted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="city" class="col-sm-3 col-form-label">City, State</label>
    <div class="col-sm-9">
        <input type="text" name="city" class="form-control" id="city">
        <small class="uted-text text-danger required">Required</small>
    </div>
</div>
<div class="form-group row">
    <label for="zipcode" class="col-sm-3 col-form-label">Zipcode</label>
    <div class="col-sm-9">
        <input type="number" name="zipcode" class="form-control" id="zipcode">
        <small class="uted-text text-danger required">Required</small>
    </div>
</div>
<div id="reqDiv">
    <h2>
        Requisition Information
    </h2>
    <div class="form-group row">
        <label for="program" class="col-sm-3 col-form-label">Organization/Program Making Purchase</label>
        <div class="col-sm-9">
            <input type="text" name="program" class="form-control" id="program">
            <small class="uted-text text-danger required">Required</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="objective" class="col-sm-3 col-form-label">Objective</label>
        <div class="col-sm-9">
            <input type="text" name="objective" class="form-control" id="objective">
            <small class="muted-text text-danger required">Required</small><br/>
            <small class="muted-text">Explain the purpose for this requisition</small>
        </div>
    </div>
    <hr>
    <h3>
        Item #1
    </h3>
    <div class="form-group row">
        <label for="part-1" class="col-sm-3 col-form-label">Part Number</label>
        <div class="col-sm-9">
            <input type="text" name="part-1" class="form-control" id="part-1">
        </div>
    </div>
    <div class="form-group row">
        <label for="description-1" class="col-sm-3 col-form-label">Description</label>
        <div class="col-sm-9">
            <input type="text" name="description-1" class="form-control" id="description-1">
            <small class="uted-text text-danger required">Required</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="quantity-1" class="col-sm-3 col-form-label">Quantity</label>
        <div class="col-sm-9">
            <input type="number" name="quantity-1" class="form-control quantity" id="quantity-1">
            <small class="uted-text text-danger required">Required</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="unitCost-1" class="col-sm-3 col-form-label">Unit Cost</label>
        <div class="col-sm-9">
            <input type="number" name="unitCost-1" class="form-control cost" id="unitCost-1" placeholder="0.00"  step="0.0001">
            <small class="uted-text text-danger required">Required</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="price-1" class="col-sm-3 col-form-label">Estimated Price</label>
        <div class="col-sm-9">
            <input type="number" name="price-1" class="form-control price" disabled id="price-1" placeholder="0.00">
        </div>
    </div>
    <div class="form-group row">
        <label for="fund-1" class="col-sm-3 col-form-label">Fund</label>
        <div class="col-sm-9">
            <select name="fund-1" id="fund-1" class="form-control">
                <option disabled selected></option>
                <?php
                foreach ($funds as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . ' (' . $key . ')</option>';
                }
                ?>
            </select>
            <small class="muted-text text-danger required">Required</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="function-1" class="col-sm-3 col-form-label">Function</label>
        <div class="col-sm-9">
            <select name="function-1" id="function-1" class="form-control">
                <option disabled selected></option>
                <?php
                foreach ($functions as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . ' (' . $key . ')</option>';
                }
                ?>
            </select>
            <small class="muted-text text-danger required">Required</small>
        </div>
    </div>
</div>

<p>
    <a class="btn btn-outline-primary" href="#!" id="addItem">Add Item</a>
    <a class="btn btn-outline-danger disabled" href="#!" id="removeItem">Remove Item</a>
</p>
<div class="form-group row">
    <label for="totalCost" class="col-sm-3 col-form-label">Estimated Total Cost</label>
    <div class="col-sm-9">
        <input type="number" name="totalCost" class="form-control" disabled id="totalCost" placeholder="0.00">
    </div>
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
