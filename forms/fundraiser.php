<script src="forms/fundraiser.js"></script>
<input type="hidden" name="form_type" value="fundraiser">
<input type="hidden" name="hours" value="0" id="submit_hours">
<h1>
  Fundraiser Request Form
</h1>
<p class="lead">
  Use this form to request the ability to raise funds for a school or organization.
</p>
<p>
  This form is tied to your account. You should not complete this form for another employee.
</p>
<h2>
  Fundraiser Information
</h2>
<div class="alert alert-info">
  <strong>Fundraising Guidelines:</strong><br/>
  <ul>
    <li>All fundraisers must make money.</li>
    <li>All monies received during a fundraiser should be receipted to individuals at the time of payment. All monies should be turned into the school's office secretary at the end of each day (if after hours, then at the beginning of the next school day).</li>
    <li>Do not accept personal checks for fundraisers. Only cash may be used as payment.</li>
    <li>If the fundraiser requires a school district facility (after hours), a facility use request must be completed. This is built into this form.</li>
    <li>If the fundraiser requires supplies to be purchased beforehand, a requisition must be completed. This is built into this form.</li>
    <li>Fundraising shall not conflict with the school lunch programs or classroom activities.</li>
    <li>Ticket sales for regular school events, such as athletic contests, musicals, school pictures or similar regular building functions are not covered by the fundraising policy and do not require the use of this form.</li>
    <li>Student participation in fundraising activities shall be voluntary at all times.</li>
  </ul>
</div>
<div class="form-group row">
  <label for="program" class="col-sm-3 col-form-label">Fundraising Group</label>
  <div class="col-sm-9">
    <input type="text" name="program" class="form-control" id="program">
    <small class="muted-text text-danger required required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="description" class="col-sm-3 col-form-label">Describe the Fundraising Activity</label>
  <div class="col-sm-9">
    <textarea class="form-control" id="description" rows="3" name="description"></textarea>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="objective" class="col-sm-3 col-form-label">Proposed Use of Profits</label>
  <div class="col-sm-9">
    <textarea class="form-control" id="objective" rows="3" name="objective"></textarea>
    <small class="muted-text text-danger required">Required</small>
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
  <label class="col-sm-3">Solicitation Location</label>
  <div class="col-sm-9">
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="location" value="School Only">
        Soliciting in School Only
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="location" value="School and Community">
        Soliciting in School and Community
      </label>
    </div>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-3">Was this fundraiser done in the past?</label>
  <div class="col-sm-9">
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="past" value="Yes">
        Yes
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="past" value="No">
        No
      </label>
    </div>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="start" class="col-sm-3 col-form-label">Dates of Fundraiser</label>
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
<!--Vending Div-->
<div class="form-group row">
  <label class="col-sm-3">Do you need to purchase items for this fundraiser?</label>
  <div class="col-sm-9">
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="purchase" value="Yes">
        Yes
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="purchase" value="No">
        No
      </label>
    </div>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<!--
<div id="vending-div" style="display: none;">
  <h2>
    Requisition Information
  </h2>
  <div class="form-group row">
    <label for="vendor" class="col-sm-3 col-form-label">Vendor</label>
    <div class="col-sm-9">
      <input type="text" name="vendor" class="form-control" id="vendor">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="address" class="col-sm-3 col-form-label">Address</label>
    <div class="col-sm-9">
      <input type="text" name="address" class="form-control" id="address">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="city" class="col-sm-3 col-form-label">City, State</label>
    <div class="col-sm-9">
      <input type="text" name="city" class="form-control" id="city">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="zipcode" class="col-sm-3 col-form-label">Zipcode</label>
    <div class="col-sm-9">
      <input type="number" name="zipcode" class="form-control" id="zipcode">
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="program" class="col-sm-3 col-form-label">Does this Requisition involve any of following departments?</label>
    <div class="col-sm-9">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="departments[]" value="athletics"> Athletics
      </label><br/>
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="departments[]" value="federal_programs"> Federal Programs
      </label><br/>
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="departments[]" value="food_services"> Food Services
      </label><br/>
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="departments[]" value="sped"> SPED
      </label><br/>
      <label class="form-check-label">
      <input class="form-check-input" type="checkbox" name="departments[]" value="student_health"> Student Health
    </label><br/>
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="departments[]" value="technology"> Technology
      </label><br/>
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="departments[]" value="transportation"> Transportation
      </label><br/>
    </div>
  </div>
  <div id="reqDiv">
    <input type="hidden" name="itemTotal" id="itemTotal" value="1">
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
        <small class="muted-text text-danger required">Required</small>
      </div>
    </div>
    <div class="form-group row">
      <label for="quantity-1" class="col-sm-3 col-form-label">Quantity</label>
      <div class="col-sm-9">
        <input type="number" name="quantity-1" class="form-control quantity" id="quantity-1">
        <small class="muted-text text-danger required">Required</small>
      </div>
    </div>
    <div class="form-group row">
      <label for="unitCost-1" class="col-sm-3 col-form-label">Unit Cost</label>
      <div class="col-sm-9">
        <input type="number" name="unitCost-1" class="form-control cost" id="unitCost-1" placeholder="0.00"  step="0.01">
        <small class="muted-text text-danger required">Required</small>
      </div>
    </div>
    <div class="form-group row">
      <label for="price-1" class="col-sm-3 col-form-label">Estimated Price</label>
      <div class="col-sm-9">
        <input type="number" name="price-1" class="form-control price" disabled id="price-1" placeholder="0.00" step="0.01">
      </div>
    </div>
  </div>
  <p>
    <a class="btn btn-outline-primary" href="#!" id="addItem">Add Item</a>
    <a class="btn btn-outline-danger disabled" href="#!" id="removeItem">Remove Item</a>
  </p>
  <hr/>
</div>
-->
<!--Facilities Div-->
<div class="form-group row">
  <label class="col-sm-3">Will you need to use school district facilities (after hours) for this fundraiser?</label>
  <div class="col-sm-9">
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="facility" value="Yes">
        Yes
      </label>
    </div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="facility" value="No">
        No
      </label>
    </div>
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<!--
<div id="facility-div" style="display: none;">
  <h2>
    Facilities Use Information
  </h2>
  <div class="alert alert-info">
    <strong>Facilities Use Guidelines:</strong><br/>
    <ul>
      <li>If you are using security and/or custodians for this event, payment for their services will come from the fundraiser's revenue.</li>
      <li>If you are not using custodians for this event, you will need to ensure the facility is cleaned after use. Failure to do so will incure a fee that will be paid from the fundraiser's revenue.</li>
      <li>Failure to maintain order during this fundraiser may result in future fundraisers being denied.</li>
    </ul>
  </div>
  <div class="form-group row">
    <label class="col-sm-3">School</label>
    <div class="col-sm-9">
      <div class="form-check">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="facility_school" value="Sanders">
          Sanders Elementary School
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="facility_school" value="Simmons">
          Simmons Jr/Sr High School
        </label>
      </div>
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3">Facilities Needed</label>
    <div class="col-sm-9">
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Baseball Field">
          Baseball Field
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Cafeteria">
          Cafeteria
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Computer Lab">
          Computer Lab
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Football Field">
          Football Field
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Kitchen">
          Kitchen
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Gymnasium">
          Gymnasium
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Library">
          Libary
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="facilities[]" value="Other">
          Other
        </label>
      </div>
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="other_facility" class="col-sm-3 col-form-label">Other Facility</label>
    <div class="col-sm-9">
      <input type="text" name="other_facility" class="form-control" id="other_facility">
    </div>
  </div>
  <div class="form-group row">
    <label for="number_people" class="col-sm-3 col-form-label">Number of Anticipated Attendance</label>
    <div class="col-sm-9">
      <input type="number" name="number_people" class="form-control" id="number_people">
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3">Will you need security at the event?</label>
    <div class="col-sm-9">
      <div class="form-check">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="security" value="Yes">
          Yes
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="security" value="No">
          No
        </label>
      </div>
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-3">Will you need custodians to clean up after the event?</label>
    <div class="col-sm-9">
      <div class="form-check">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="custodians" value="Yes">
          Yes
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="custodians" value="No">
          No
        </label>
      </div>
      <small class="muted-text text-danger required">Required</small>
    </div>
  </div>
  <hr/>
</div>
-->
<h2>
  Revenue and Profits
</h2>
<div class="form-group row">
  <label for="total" class="col-sm-3 col-form-label">Estimated Upfront Costs</label>
  <div class="col-sm-9">
    <input type="number" class="form-control monetary" name="cost" id="total" placeholder="0.00" step="0.01">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row" style="display: none;" id="purchase_code_input">
  <label for="purchase_code" class="col-sm-3 col-form-label">Account paying for upfront costs</label>
  <div class="col-sm-9">
    <input type="text" class="form-control purchase_code" name="purchase_code" maxlength="30">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="revenue" class="col-sm-3 col-form-label">Estimated Total Revenue</label>
  <div class="col-sm-9">
    <input type="number" class="form-control monetary" id="revenue" name="revenue" placeholder="0.00" step="0.01">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="revenue_code" class="col-sm-3 col-form-label">Account revenue will be deposited in</label>
  <div class="col-sm-9">
    <input type="text" class="form-control purchase_code" name="revenue_code" maxlength="30">
    <small class="muted-text text-danger required">Required</small>
  </div>
</div>
<div class="form-group row">
  <label for="profit" class="col-sm-3 col-form-label">Estimated Total Profit</label>
  <div class="col-sm-9">
    <input type="profit" class="form-control monetary" disabled id="profit" placeholder="0.00">
  </div>
</div>

<div class="alert alert-danger" id="profit-alert" style="display: none;">
  <strong>Stop!</strong> All fundraisers must generate a profit.
</div>
<!--Fundraiser Assurances-->

<div class="alert alert-info">
  <h2>
    Fundraiser Assurances
  </h2>
  By clicking submit below I understand and agree to the following.<br/>
  <ul>
    <li>Sales tax must be paid on all items purchased for resale, even if the purchase is not intended to raise a profit (exception: textbooks, pe uniforms, and coupon books or cards where taxes will be paid when coupon or card is used)</li>
    <li>The Superintendent must approve all fundraising activities held at the school and involving students. A contract must be signed between the Superintendent and the fundraising representative.</li>
    <li>The Superintendent must inform the School Board of all planned fundraisers expecting to raise more than $500.</li>
    <li>Fundraising companies shall be selected from a list of approved vendors. Note that the approved fundraiser list is not the same as the approved vendor list.</li>
    <li>Fundraising by clubs may be used to benefit the individual clubs; however, school-wide fundraiser proceeds must be deposited in the general fund and not an individual club account.</li>
    <li>An income statement (Money Collection/Fundraising Accounting Form) must be prepared at the end of each fundraising activity and be made available to students, teachers, and parents.</li>
    <li>All fundraising funds must be accounted for.</li>
    <li>A debt list must be maintained for students receiving but not paying for fundraising items.</li>
    <li>The use of school property and facilities in fundraising efforts shall be in accordance with Board policy.</li>
    <li>Expenditures of money raised through fundraising activities shall be made in accordance with proper purchasing procedures and Board policies.</li>
    <li>Academic credit shall not be given or deducted due to participation or non-participation in any fundraising event.</li>
    <li>Student incentives for fundraising programs, which include exclusion from regular school attendance or regular instructional time, should be minimal and must have prior approval of the Principal.</li>
  </ul>
</div>
