<script src="forms/inventory_scripts.js"></script>
<link rel="stylesheet" href="forms/reimbursement_styles.css">
<input type="hidden" name="itemTotal" id="itemTotal" value="1">
<input type="hidden" name="form_type" value="inventory">
<input type="hidden" name="location" value="<?php echo $_SESSION['school']; ?>">
<h1>
  Inventory Management Form
</h1>
<p class="lead">
  Use this form to request for the movement or disposal of items on inventory.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<div class="form-group row">
  <label for="invent_action" class="col-sm-3 col-form-label">Action</label>
  <div class="col-sm-9">
    <select name="invent_action" class="form-control" id="invent_action">
      <option disabled selected></option>
      <option value="Disposal">Disposal</option>
      <option value="Donation">Donation</option>
      <option value="Lost/Stolen">Lost/Stolen</option>
      <option value="Transfer">Transfer</option>
    </select>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<!--Disposal-->
<div id="disposal_div" class="type_div" style="display: none;">
  <div class="alert alert-info">
    All asset disposals must be made in accordance with Section 37-7-451, et. seq., Mississippi Code.
  </div>
  <div class="form-group row">
    <label for="reason" class="col-sm-3 col-form-label">Reason for Disposal</label>
    <div class="col-sm-9">
      <input type="text" name="reason" class="form-control" id="reason">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="disposal_room" class="col-sm-3 col-form-label">Current Room</label>
    <div class="col-sm-9">
      <input type="text" name="disposal_room" class="form-control" id="disposal_room">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
</div>
<!--Transfer-->
<div id="transfer_div" class="type_div" style="display: none;">
  <div class="alert alert-info">
    This form is to be used for <em>permanent</em> asset transfers only. <em>Temporary</em> transfers or assignments should be reported on the Assignment/Check-out of Fixed Assets form.
  </div>
  <div class="form-group row">
    <label for="old_room" class="col-sm-3 col-form-label">Current Room</label>
    <div class="col-sm-9">
      <input type="text" name="old_room" class="form-control" id="old_room">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="new_room" class="col-sm-3 col-form-label">New Room</label>
    <div class="col-sm-9">
      <input type="number" name="new_room" class="form-control" id="new_room">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
</div>
<!--Lost/Stolen-->
<div id="lost-stolen_div" class="type_div" style="display: none;">
  <div class="alert alert-info">
    A police or sheriff's report must accompany a lost or stolen item form. You may attach the report in the <em>Supporting Documents</em> section.
  </div>
  <div class="form-group row">
    <label for="report_number" class="col-sm-3 col-form-label">Police/Sherrif's Report Number</label>
    <div class="col-sm-9">
      <input type="text" name="report_number" class="form-control" id="report_number">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="explain_loss" class="col-sm-3 col-form-label">Detailed Explanation of Loss</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="explain_loss" rows="5" name="explain_loss" maxlength="500"></textarea>
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
</div>
<!--Donation-->
<div id="donation_div" class="type_div" style="display: none;">
  <div class="form-group row">
    <label for="donation_date" class="col-sm-3 col-form-label">Date of Donation</label>
    <div class="col-sm-9">
      <input type="date" name="donation_date" class="form-control" id="donation_date">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="donation_from" class="col-sm-3 col-form-label">Donated By</label>
    <div class="col-sm-9">
      <input type="text" name="donation_from" class="form-control" id="donation_from">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="total_cost" class="col-sm-3 col-form-label">Estimated Total Cost of Donation(s)</label>
    <div class="col-sm-9">
      <input type="number" name="total_cost" class="form-control monetary" id="total_cost" placeholder="0.00" step="0.01">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="donation_room" class="col-sm-3 col-form-label">Assigned Room</label>
    <div class="col-sm-9">
      <input type="text" name="donation_room" class="form-control monetary" id="donation_room">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
</div>

<!--Items-->
<div id="items_div">
  <h2>
    Items
  </h2>
  <h3>
    Item #1
  </h3>
  <div class="form-group row">
    <label for="tag-1" class="col-sm-3 col-form-label">Asset Tag Number</label>
    <div class="col-sm-9">
      <input type="number" name="tag-1" class="form-control" id="tag-1">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="description-1" class="col-sm-3 col-form-label">Asset Description</label>
    <div class="col-sm-9">
      <input type="text" name="description-1" class="form-control" id="description-1">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <hr>
</div>
<p>
  <a class="btn btn-outline-primary" href="#!" id="addItem">Add Item</a>
  <a class="btn btn-outline-danger disabled" href="#!" id="removeItem">Remove Item</a>
</p>


<h2>
  Supporting Documents
</h2>
<p>
  Use this sectionto attach a police report when reporting items lost/stolen.
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
