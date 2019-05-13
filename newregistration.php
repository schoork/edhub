<?php

$page_title = 'Student Registration';
include('header_nosignin.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<link rel="stylesheet" href="css/registration_styles.css"/>';
echo '<script src="js/registration_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-logo.php');
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Student Registration
		</h1>
    <p class="lead">
      Register your returning or new student here
    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row flex-xl-nowrap">
		<div id="list-example" class="list-group col-xl-3 d-none d-xl-block bd-toc">
		  <ul class="section-nav">
				<li class="toc-entry toc-h2"><a href="#basic-info">Basic Information</a></li>
				<li class="toc-entry toc-h2"><a href="#parent">Parent/Guardian</a></li>
				<li class="toc-entry toc-h2"><a href="#emergency">Emergency Contacts</a></li>
				<li class="toc-entry toc-h2"><a href="#student-health">Student Health</a></li>
				<li class="toc-entry toc-h2"><a href="#residency">Residency</a></li>
				<li class="toc-entry toc-h2"><a href="#income">Household Income</a></li>
				<li class="toc-entry toc-h2"><a href="#school_specific">School Specifc</a></li>
				<li class="toc-entry toc-h2"><a href="#consents">Consent Agreements</a></li>
				<li class="toc-entry toc-h2"><a href="#dha">Delta Health Alliance</a></li>
			</ul>
		</div>
		<div class="col-12 col-xl-9">
      <form method="post" action="service.php">
        <input type="hidden" name="action" value="registerStudent">
        <h2 id="basic-info">
          Basic Information
        </h2>
        <div class="form-group row">
          <label for="firstname" class="col-sm-3 col-form-label">First Name</label>
          <div class="col-sm-9">
            <input type="text" name="firstname" class="form-control" id="firstname">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="middlename" class="col-sm-3 col-form-label">Middle Name</label>
          <div class="col-sm-9">
            <input type="text" name="middlename" class="form-control" id="middlename">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="lastname" class="col-sm-3 col-form-label">Last Name</label>
          <div class="col-sm-9">
            <input type="text" name="lastname" class="form-control" id="lastname">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="ssn" class="col-sm-3 col-form-label">Social Security Number</label>
          <div class="col-sm-9">
            <input type="text" name="ssn" class="form-control" id="ssn" maxlength="11">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="birthday" class="col-sm-3 col-form-label">Birthday</label>
          <div class="col-sm-9">
            <input type="date" name="birthday" class="form-control" id="birthday" placeholder="MM/DD/YYYY">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
					<label for="grade" class="col-sm-3 col-form-label">Grade Level</label>
					<div class="col-sm-9">
						<select class="form-control grade-select" id="grade" name="grade">
							<option disabled selected></option>
							<option value="PreK">Pre-Kindergarten</option>
							<option value="K">Kindergarten</option>
							<?php
							for ($i = 1; $i < 13; $i++) {
								echo '<option value="' . $i . '">Grade ' . $i . '</option>';
							}
							?>
						</select>
						<small class="muted-text required text-danger basic-info">Required</small>
					</div>
				</div>
				<div class="form-group row">
				  <label class="col-sm-3">Gender</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="radio" class="form-check-input" name="gender" value="Female">
				        Female
				      </label>
				    </div>
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="radio" class="form-check-input" name="gender" value="Male">
				        Male
				      </label>
				    </div>
						<small class="muted-text text-danger required basic-info">Required</small>
				  </div>
				</div>
				<h3>
					Current Address
				</h3>
				<div class="form-group row">
          <label for="address_curr" class="col-sm-3 col-form-label">Address</label>
          <div class="col-sm-9">
            <input type="text" name="address_curr" class="form-control" id="address_curr">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="city_curr" class="col-sm-3 col-form-label">City</label>
          <div class="col-sm-9">
            <input type="text" name="city_curr" class="form-control" id="city_curr">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="state_curr" class="col-sm-3 col-form-label">State</label>
          <div class="col-sm-9">
            <input type="text" name="state_curr" id="state_curr" disabled value="Mississippi" class="form-control">
          </div>
        </div>
				<div class="form-group row">
          <label for="zipcode_curr" class="col-sm-3 col-form-label">Zipcode</label>
          <div class="col-sm-9">
            <input type="number" name="zipcode_curr" class="form-control" id="zipcode_curr" maxlength="5">
            <small class="muted-text required text-danger basic-info">Required</small>
          </div>
        </div>
				<h3>
					Mailing Address
				</h3>
				<div class="form-group">
					<div class="row">
				  	<label class="col-sm-12">Do you have a different mailing address?</label>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="mailDiff" value="Yes">
					        Yes
					      </label>
					    </div>
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="mailDiff" value="No" checked="checked">
					        No
					      </label>
					    </div>
						</div>
				  </div>
				</div>
				<div id="mailingDiv" style="display: none;">
					<div class="form-group row">
	          <label for="address_mail" class="col-sm-3 col-form-label">Address</label>
	          <div class="col-sm-9">
	            <input type="text" name="address_mail" class="form-control" id="address_mail">
	            <small class="muted-text required text-danger basic-info">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="city_mail" class="col-sm-3 col-form-label">City</label>
	          <div class="col-sm-9">
	            <input type="text" name="city_mail" class="form-control" id="city_mail">
	            <small class="muted-text required text-danger basic-info">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="state_mail" class="col-sm-3 col-form-label">State</label>
	          <div class="col-sm-9">
	            <input type="text" name="state_mail" id="state_mail" disabled value="Mississippi" class="form-control">
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="zipcode_mail" class="col-sm-3 col-form-label">Zipcode</label>
	          <div class="col-sm-9">
	            <input type="text" name="zipcode_mail" class="form-control" id="zipcode_mail">
	            <small class="muted-text required text-danger basic-info">Required</small>
	          </div>
	        </div>
				</div>
				<div class="kindergartenDiv" style="display: none;">
					<h3>Kindergarten Information</h3>
					<div class="alert alert-warning">
						All incoming Kindergarteners to the Hollandale School District are required to provide the services that were provided to them at the age of four (4). This information is used for state purposes and will help us to better serve the needs of your child over the next school year. Please check one of the following that applies.
					</div>
					<div class="form-group row">
					  <label class="col-sm-3">Age Four Service (choose the one that best applies)</label>
					  <div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Child Care/Daycare Center">
					        Child Care/Daycare Center
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Family Member/Friend">
					        Family Member/Friend
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Head Start Center">
					        Head Start Center
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Home School">
					        Home School
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Kindergarten (repeating)">
					        Kindergarten (repeating)
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Out-of-State">
					        Out-of-State
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Private PreK">
					        Private PreK
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Public PreK">
					        Public PreK
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="agefour" value="Other">
					        Other
					      </label>
					    </div>
							<small class="muted-text required text-danger basic-info">Required</small>
						</div>
					</div>
					<div class="form-group row">
	          <label for="agefour_text" class="col-sm-3 col-form-label">Provider Name</label>
	          <div class="col-sm-9">
	            <input type="text" name="agefour_text" class="form-control" id="agefour_text">
	            <small class="muted-text required text-danger basic-info">Required</small>
	          </div>
	        </div>
				</div>
				<h2 id="parent">
					Parent/Guardian Information
				</h2>
				<div id="parentDiv">
					<input type="hidden" name="parentTotal" id="parentTotal" value="1">
					<div class="alert alert-warning">
						In this section, please only list individuals who have legal guardianship of the student.
					</div>
					<h4>
						Parent/Guardian #1
					</h4>
					<div class="form-group row">
						<label for="parent_rel-1" class="col-sm-3 col-form-label">Relationship to Student</label>
						<div class="col-sm-9">
							<select class="form-control grade-select" id="parent_rel-1" name="parent_rel-1">
								<option disabled selected></option>
								<option value="Mother">Mother</option>
								<option value="Father">Father</option>
								<option value="Grandmother">Grandmother</option>
								<option value="Grandfather">Grandfather</option>
								<option value="Aunt">Aunt</option>
								<option value="Uncle">Uncle</option>
								<option value="Sibling">Sibling (brother or sister)</option>
								<option value="Guardian">Guardian (non-kin)</option>
								<option value="Unaccompanied">No Parent or Guardian</option>
							</select>
							<small class="muted-text required text-danger parent">Required</small>
						</div>
					</div>
					<div class="form-group row">
	          <label for="parent_name-1" class="col-sm-3 col-form-label">Parent/Guardian Name</label>
	          <div class="col-sm-9">
	            <input type="text" name="parent_name-1" class="form-control" id="parent_name-1">
	            <small class="muted-text required text-danger parent">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="parent_phone1-1" class="col-sm-3 col-form-label">Phone Number #1</label>
	          <div class="col-sm-9">
	            <input type="text" name="parent_phone1-1" class="form-control" id="parent_phone1-1">
	            <small class="muted-text required text-danger parent">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="parent_phone2-1" class="col-sm-3 col-form-label">Phone Number #2</label>
	          <div class="col-sm-9">
	            <input type="text" name="parent_phone2-1" class="form-control" id="parent_phone2-1">
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="parent_email-1" class="col-sm-3 col-form-label">Email Address</label>
	          <div class="col-sm-9">
	            <input type="text" name="parent_email-1" class="form-control" id="parent_email-1">
	          </div>
	        </div>
					<hr/>
				</div>
				<p>
			    <a class="btn btn-outline-primary" href="#!" id="addParent">Add Parent/Guardian</a>
			    <a class="btn btn-outline-danger disabled" href="#!" id="removeParent">Remove Parent/Guardian</a>
			  </p>
				<h2 id="emergency">
					Emergency Contacts
				</h2>
				<div id="contactDiv">
					<input type="hidden" name="contactTotal" value="1" id="contactTotal">
					<div class="alert alert-warning">
						In this section, please list all indivduals who can be contacted in the case of an emergency and the school cannot contact the parent(s)/guardian(s) listed above.
					</div>
					<h4>
						Contact #1
					</h4>
					<div class="form-group row">
	          <label for="contact_name-1" class="col-sm-3 col-form-label">Contact Name</label>
	          <div class="col-sm-9">
	            <input type="text" name="contact_name-1" class="form-control" id="contact_name-1">
	            <small class="muted-text required text-danger emergency">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="contact_phone1-1" class="col-sm-3 col-form-label">Phone Number #1</label>
	          <div class="col-sm-9">
	            <input type="text" name="contact_phone1-1" class="form-control" id="contact_phone1-1">
	            <small class="muted-text required text-danger emergency">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="contact_phone2-1" class="col-sm-3 col-form-label">Phone Number #2</label>
	          <div class="col-sm-9">
	            <input type="text" name="contact_phone2-1" class="form-control" id="contact_phone2-1">
	          </div>
	        </div>
					<hr/>
				</div>
				<p>
			    <a class="btn btn-outline-primary" href="#!" id="addContact">Add Contact</a>
			    <a class="btn btn-outline-danger disabled" href="#!" id="removeContact">Remove Contact</a>
			  </p>
				<h2 id="student-health">
					Student Health
				</h2>

				<div class="form-group row">
					<label for="insurance" class="col-sm-3 col-form-label">Health Insurance</label>
					<div class="col-sm-9">
						<input type="text" name="insurance" class="form-control" id="insurance" placeholder="Medicaid, Chips, AlwaysCare, etc.">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="insure_number" class="col-sm-3 col-form-label">Insurance Number</label>
					<div class="col-sm-9">
						<input type="text" name="insure_number" class="form-control" id="insure_number">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group row">
				  <label class="col-sm-3">Allergies (check all that apply)</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="allergies[]" value="Food">
				        Food
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="allergies[]" value="Medicine">
				        Medicine
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="allergies[]" value="Insect Bites or Stings">
				        Insect Bites or Stings
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="allergies[]" value="Other (including seasonal)">
				        Other (including seasonal)
				      </label>
				    </div>
					</div>
				</div>
				<div class="form-group row">
				  <label for="allergy_notes" class="col-sm-3 col-form-label">List Symptoms and Medicines Needed for Allergies</label>
				  <div class="col-sm-9">
				    <textarea class="form-control" id="allergy_notes" rows="5" name="allergy_notes" maxlength="500"></textarea>
				  </div>
				</div>
				<div class="form-group row">
				  <label class="col-sm-3">Medical History (check all that apply)</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input asthma-radio" name="medical_history[]" value="Asthma">
				        Asthma
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Attention Deficit (ADD, ADHD)">
				        Attention Deficit (ADD, ADHD)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Birth Defect/Physical Handicap">
				        Birth Defect/Physical Handicap
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Bone or Joint Problems">
				        Bone or Joint Problems
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Convulsions (seizure/epilepsy)">
				        Convulsions (seizure/epilepsy)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Diabetes (high blood sugar)">
				        Diabetes (high blood sugar)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Earaches">
				        Earaches
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Emotional/Psychological Disorder">
				        Emotional/Psychological Disorder
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Headaches (frequent or takes medicine)">
				        Headaches (frequent or takes medicine)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Heart Problems">
				        Heart Problems
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Hypertension (high blood pressure)">
				        Hypertension (high blood pressure)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Nose Bleeds">
				        Nose Bleeds
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Sinus Problems">
				        Sinus Problems
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Speech and/or Hearing Problems">
				        Speech and/or Hearing Problems
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Stomach or Digestive Problems">
				        Stomach or Digestive Probelms
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Surgery">
				        Surgery
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="medical_history[]" value="Vision (seeing) Problems">
				        Vision (seeing) Problems
				      </label>
				    </div>
				  </div>
				</div>
				<div class="form-group row">
				  <label for="medical_history_notes" class="col-sm-3 col-form-label">Medical History Notes</label>
				  <div class="col-sm-9">
				    <textarea class="form-control" id="medical_history_notes" rows="5" name="medical_history_notes" maxlength="500"></textarea>
				  </div>
				</div>
				<div id="asthmaDiv" style="display: none;">
					<div class="form-group row">
					  <label for="asthma_triggers" class="col-sm-3 col-form-label">Asthma Triggers</label>
					  <div class="col-sm-9">
					    <textarea class="form-control" id="asthma_triggers" rows="5" name="asthma_triggers" maxlength="500"></textarea>
							<small class="muted-text text-danger required student-health">Required</small>
					  </div>
					</div>
					<div class="form-group row">
					  <label for="asthma_medications" class="col-sm-3 col-form-label">Asthma Medications</label>
					  <div class="col-sm-9">
					    <textarea class="form-control" id="asthma_medications" rows="5" name="asthma_medications" maxlength="500"></textarea>
							<small class="muted-text text-danger required student-health">Required</small>
					  </div>
					</div>
				</div>
				<div class="form-group row">
				  <label for="special_needs" class="col-sm-3 col-form-label">Describe any Handicaps or Special Needs of Student</label>
				  <div class="col-sm-9">
				    <textarea class="form-control" id="special_needs" rows="5" name="special_needs" maxlength="500"></textarea>
				  </div>
				</div>
				<div class="form-group row">
					<label for="doctor" class="col-sm-3 col-form-label">Doctor or Primary Care Physician</label>
					<div class="col-sm-9">
						<input type="text" name="doctor" class="form-control" id="doctor">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="doctor_number" class="col-sm-3 col-form-label">Doctor or Primary Care Physician's Phone Number</label>
					<div class="col-sm-9">
						<input type="text" name="doctor_number" class="form-control" id="doctor_number">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="hospital" class="col-sm-3 col-form-label">Hospital Preference</label>
					<div class="col-sm-9">
						<input type="text" name="hospital" class="form-control" id="hospital">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
				  	<label class="col-sm-12">In the event of an emergency (and you cannot be reached) does the school have permission to take your child to the emergency room?</label>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="er" value="Yes">
					        Yes
					      </label>
					    </div>
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="er" value="No">
					        No
					      </label>
					    </div>
							<small class="muted-text text-danger required student-health">Required</small>
					  </div>
					</div>
				</div>
				<div class="form-group row">
					<label for="dentist" class="col-sm-3 col-form-label">Dentist</label>
					<div class="col-sm-9">
						<input type="text" name="dentist" class="form-control" id="dentist">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="dentist_number" class="col-sm-3 col-form-label">Dentist's Phone Number</label>
					<div class="col-sm-9">
						<input type="text" name="dentist_number" class="form-control" id="dentist_number">
						<small class="muted-text text-danger required student-health">Required</small>
					</div>
				</div>
				<div class="form-group row">
				  <label class="col-sm-3">Taking Daily Medication</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="radio" class="form-check-input" name="daily_meds" value="No">
				        No
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="radio" class="form-check-input" name="daily_meds" value="Yes">
				        Yes, please list:
				      </label>
				    </div>
						<input type="text" name="daily_medication" class="form-control" id="daily_medication" placeholder="Medications..." maxlength="100">
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
					  <label class="form-check-label">
					    <input class="form-check-input" type="checkbox" value="Yes" name="consent-healthprogram">
					    I give my permission for my child to participate in the school's health program, which includes health information and basic screenings (vision, hearing, scoliosis, etc.). I also give my permission for my child to recieve standing orders/first aid care as needed.
					  </label>
					</div>
					<small class="muted-text text-danger required student-health">Required</small>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
					  <label class="form-check-label">
					    <input class="form-check-input" type="checkbox" value="Yes" name="consent-medications">
					    I understand that a Doctor's order is required for ALL medications, including over the counter medications.
					  </label>
					</div>
					<small class="muted-text text-danger required student-health">Required</small>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
					  <label class="form-check-label">
					    <input class="form-check-input" type="checkbox" value="Yes" name="consent-medications">
					    I give my consent for pertinent medical information to be shared between the medical provider and the school nurse and/or school personnel directly involved with my child at school.
						</label>
					</div>
					<small class="muted-text text-danger required student-health">Required</small>
				</div>
				<h3 class="mt-3">Delta Health Center Information and Consent</h3>
				<div class="alert alert-warning">
					Delta Health Center, Inc conducts free medical and dental screenings for students in the Hollandale School District. As part of this, DHC originates and maintains health records describing a child's health history, symptoms, examinations, test results, diagnoses, treatment, and any plans for future care or treatment. This information serves as:
					<ul>
						<li>A basis for planning a child's care and treatment</li>
						<li>A means of communication among many healthcare professionals who contribute to a child's care</li>
						<li>A source of information for applying a child's diagnosis to any bill</li>
						<li>A means by which reimbursement agencies can certify that services billed were actually provided, and</li>
						<li>A tool for routine healthcare operations, such as assessing quality and reviewing the competence of the healthcare professionals</li>
					</ul>
				</div>
				<div class="form-group row">
					<label for="dhc_restrictions" class="col-sm-3 col-form-label">Restrictions to Disclosure of Child's Medical Records</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="dhc_restrictions" rows="5" name="dhc_restrictions" maxlength="500"></textarea>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
					  <label class="form-check-label">
					    <input class="form-check-input" type="checkbox" value="Yes" name="consent-dhc-privacy">
					    I have received a copy of Delta Health Center's Notice of Privacy Practices.
						</label>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
					  <label class="form-check-label">
					    <input class="form-check-input" type="checkbox" value="Yes" name="consent-dhc-records">
					    I authorize members of Delta Health Center, Inc. and Hollandale School District to exchange health education/records for my child.
						</label>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
					  <label class="form-check-label">
					    <input class="form-check-input" type="checkbox" value="Yes" name="consent-dhc-screenings">
					    I give my consent for Delta Health Center, Inc to complete FREE medical/dental screenings for my child.
						</label>
					</div>
				</div>
				<h2 id="residency">
					Residency Information
				</h2>
				<div class="form-group row">
				  <label class="col-sm-3">Your student lives in... (check all that apply)</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="House">
				        House
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Apartment">
				        Apartment
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Shelter">
				        Shelter
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Motel or Hotel">
				        Motel or Hotel
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Temporarily with Another Family">
				        Temporarily with Another Family
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Car or RV">
				        Car or RV
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Campsite">
				        Campsite
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Transitional Housing">
				        Transitional Housing
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="No Stable Housing">
				        No Stable Housing
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="residence[]" value="Other">
				        Other
				      </label>
				    </div>
						<small class="muted-text text-danger required residency">Required</small>
					</div>
				</div>
				<div class="form-group row">
				  <label class="col-sm-3">Your student lives with... (check all that apply)</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="One Parent">
				        One Parent
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="Two Parents">
				        Two Parents
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="One or More Guardians">
				        One or More Guardians
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="A Relative (not a guardian)">
				        A Relative (not a guardian)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="Friend(s)">
				        Friend(s)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="Adult (not a guardian)">
				        Adult (not a guardian)
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="reside_with[]" value="Alone (with no adults)">
				        Alone (with no adults)
				      </label>
				    </div>
						<small class="muted-text text-danger required residency">Required</small>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
				  	<label class="col-sm-12">Does your student live in a fixed, adequate, stable nighttime residence?</label>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="reside_stable" value="Yes">
					        Yes
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="reside_stable" value="No">
					        No
					      </label>
					    </div>
							<small class="muted-text text-danger required residency">Required</small>
						</div>
					</div>
				</div>
				<h2 id="income">
					Household Income
				</h2>
				<div class="alert-warning alert">
					The following questions ask about household income. The answers to these questions will not be shared with anyone and are used for district purposes only. They will not be used to make any decisions concerning your child.<br/>
					<br/>
					For Household Income, add the amounts earned by all adult members living with you from
					<ul>
						<li>wages and salaries</li>
						<li>welfare, child support, and alimony</li>
						<li>pensions, retirement, social security, supplemental security income, veteran's benefits, and disability benefits</li>
						<li>military housing allowances and combat pay</li>
						<li>overtime pay (only if regular)</li>
						<li>all other income</li>
					</ul>
				</div>
				<div class="form-group row">
					<label for="house_size" class="col-sm-3 col-form-label">Number of K-12 Children Living in Household</label>
					<div class="col-sm-9">
						<input type="number" name="house_size" class="form-control" id="house_size">
						<small class="muted-text text-danger required income">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="house_income" class="col-sm-3 col-form-label">What is Your Approximate Household Income?</label>
					<div class="col-sm-9">
						<input type="text" name="house_income" class="form-control" id="house_income" placeholder="0.00">
						<small class="muted-text text-danger required income">Required</small>
					</div>
				</div>
				<h2 id="school_specific">
					School Specific information
				</h2>
				<div class="form-group">
					<div class="row">
				  	<label class="col-sm-12">Does your child speak a language other than English?</label>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="home_lang" value="No">
					        No
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="home_lang" value="Yes">
					        Yes, please list:
					      </label>
					    </div>
							<input type="text" name="home_lang_list" class="form-control" id="home_lang_list">
							<small class="muted-text text-danger required school_specific">Required</small>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
				  	<label class="col-sm-12">Has your child ever been expelled or party to an expulsion hearing?</label>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="expelled" value="No">
					        No
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="expelled" value="Yes">
					        Yes
					      </label>
					    </div>
							<small class="muted-text text-danger required school_specific">Required</small>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
				  	<label class="col-sm-12">Does your child have an IEP?</label>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
					    <div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="iep" value="No">
					        No
					      </label>
					    </div>
							<div class="form-check">
					      <label class="form-check-label">
					        <input type="radio" class="form-check-input" name="iep" value="Yes">
					        Yes
					      </label>
					    </div>
							<small class="muted-text text-danger required school_specific">Required</small>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="prev_school" class="col-sm-3 col-form-label">Previous School</label>
					<div class="col-sm-9">
						<input type="text" name="prev_school" class="form-control" id="prev_school">
						<small class="muted-text text-danger required school_specific">Required</small>
					</div>
				</div>
				<h2 id="consents">
					Consents
				</h2>
				<div id="sandersDiv">
					<h3>iPad User Agreement</h3>
					<div class="alert alert-warning">
						As part of the T.R. Sanders Elementary curriculum, we will be using the iPad in most of your child's academic classes this school year. The iPad will help to personalize instruction, address a variety of learning styles, and create highly interactive classrooms. All K-6 students will be participating in this program. Before we can issue iPads, there are rules and guidelines that parents and students should follow and agree in order to participate.
						<ol>
							<li>iPads are the sole property of the Hollandale School District and issued on a daily checkout for instructional use only.</li>
							<li>Safegaurds will be in place to ensure security on the iPad. iPads are in protective cases, but if your child damages the iPad, you will be responsible for a total replacement of $450.00. By signing this contract, you and your child agree to the replacement or repair if the iPad is lost, stolen, or damaged due to negligence while in your child's possession.</li>
							<li>Parents and students agree to abide by all internet and technology rules outline in the Sanders Elementary handbook.</li>
							<li>Sanders staff has the right to take temporary possession of the iPad at any time without notice if students are in violation of school technology policies. Alternative assignments will be given if needed.</li>
							<li>Usage is a privilege, not a right.</li>
						</ol>
					</div>
					<div class="alert-info alert mt-3 mb-3">
						<div class="form-check">
						  <label class="form-check-label">
						    <input class="form-check-input" type="checkbox" value="Yes" name="consent-ipad">
						    I have read, understand, and agree to follow all responsiblities as outline in the iPad user agreement.
							</label>
							<small class="muted-text">Failure to consent to this, will mean your child will not be able to use an iPad.</small>
						</div>
					</div>
					<h3>
						Corporal Punishment
					</h3>
					<div class="alert alert-warning">
						Corporal Punishment may be administered by the principal or his/her designee as a last resort to a student whose behavior is interfering with the normal operation of the classroom or school. Prior to the administration of corporal punishment, the principal or designee shall advise the student of the particular misconduct of which he/she is accused. The student shall be given the opportunity to explain his/her version of the facts prior to the imposition of such corporal punishment. The name of the second official present during the administration of corporal punishment shall be kept on file in the principal's office.
					</div>
					<div class="alert-info alert mt-3 mb-3">
						<div class="form-check">
						  <label class="form-check-label">
						    <input class="form-check-input" type="checkbox" value="Yes" name="consent-corporal">
						    YES, my child may receive corporal punishment as a form of discipline following the guidelines listed above.
							</label>
							<small class="muted-text">You do not have to consent to this, however, your child may receive another form of discipline, including out-of-school suspension.</small>
						</div>
					</div>
				</div>
				<h3>
					Internet Parental Consent
				</h3>
				<div class="alert alert-warning">
					Due to the nature of the internet, it is neither practical nor possible for the Hollandale School District to enforce compliance with user rules at all times. Accordingly, parents and students must recognize that students will be required to make independent decisions and use good judgement in their use of the internet. Therefore, parents must participate in the decision whether to allow their children access to the internet and must communicate their own expectations to their children regarding its approrpiate educational use.
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet1">
							I have read the board-adopted policies on the Internet Use by Students, the administrative procedures, and the Internet Network Access Agreement.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet2">
							I understand that internet access is designed for educational purposes and the school/district will attempt to discourage access to objectionable material and communications that are intended to exploit, harass, or abuse students.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet3">
							I recognize it is impossible for the school district to restrict access to all objectionale material, and I will not hold the school or school district responsible for materials acquired or contacts made on the internet.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet4">
							I understand that a variety of inappropriate and offensive materials are available over the internet and that it may be possible for my child to access these materials if he/she chooses to behave irresponsibly.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet5">
							I understand that is possible for undesirable or ill-intended individuals to communicate with my child over the internet, that there is no practical means for the school or school district to prevent this from happening, and that my child must take responsiblity to avoid such communications.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet6">
							I recognize that it is not the responsilbity of the school or school district monitor all such communications.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet7">
							I have determined that the benefits of my child having access to the internet outweighs potential risks.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet8">
							I understand that in conduct by my child that is in conflict with these responsiblities is inappropriate, and such behavior may result in termination of access and possible disciplinary action.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet9">
							I agree to compensate the school and school district for any expenses or costs they incur as a result of my child's violation of internet policies or administrative procedures.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-internet10">
							I have reviewed these policies with my child, and I hereby grant permission to the school and school district to provide internet network access.
						</label>
						<small class="muted-text text-danger required consents">Required</small>
					</div>
				</div>
				<h2 id="dha">
					Delta Health Alliance Consent Agreement
				</h2>
				<div class="alert alert-warning">
					By signing this agreement, you give consent to disclose and share personally identifiable information on the person listed below with authorized partners in the Deer Creek Promise Community (DCPC). The purpose of sharing this information is to allow the DCPC to provide well-informed, coordinated services to participants and their families, to conduct ongoing evaluation and improvement of programs to better serve the community, and to report results of programs and activities to residents, partners, and funders. The DCPC takes every precaution to protect personally identifiable information from unauthorized use or disclosure. Information obtained on persons shall not be published in a manner that will lead to the identification of any individual. This information is used solely for service provision and program evaluation purposes and identified information shall not be further redisclosed to third parties not covered by this Consent Agreement without your prior written consent.<br/>
					<br/>
					I understand that the records to be disclosed and shared with DCPC may include but are not limited to:
					<ul>
						<li>Education records from the Hollandale School District and/or Leland School District including:
							<ul>
								<li>Enrollment information</li>
								<li>Classroom performance and behavior</li>
								<li>Performance on state assessments and other standardized assessments.</li>
								<li>Grade reports and transcripts</li>
								<li>Attendance</li>
								<li>Survey data (i.e. Youth Behavior Risk Survey, Family Home Reading Practices, etc)</li>
							</ul>
						</li>
						<li>Records from DCPC Service Providers, including:
							<ul>
								<li>Intake information collected on participants (such as name, address, and date of birth)</li>
								<li>Participation data (such as services received, attendance dates, and length of time participating)</li>
								<li>Program results and assessments (such as test results and observations by program staff)</li>
							</ul>
						</li>
						<li>Photographs
							<ul>
								<li>Use of photography in any DCPC publication or advertising materials. All rights of privacy or compensation, which may be in connection with use of the photograph are waived.</li>
							</ul>
						</li>
					</ul>
					I consent that the following parties may obtain the information described above stripped of direct identifiers:
					<ul>
						<li>The U.S. Department of Education and its authorized contractor(s).</li>
						<li>The Delta Health Alliance external evaluator and its team of authorized researcher(s).</li>
					</ul>
					For up to date information and questions, please contact the DCPC at: Delta Health Alliance; Deer Creek Promise Community; 135 Front Street; P.O. Box 150; Indianola, MS 38751; Ph (662)-686-3937<br/>
					<br/>
					DCPC Authorized Partners
					<ul>
						<li>Care Bears Child Development Center Children’s Defense Fund</li>
						<li>Delta Council</li>
						<li>Delta Health Alliance</li>
						<li>Delta Housing Development Corporation Fun-shine Daycare Center</li>
						<li>Guaranty Bank &amp; Trust</li>
						<li>Hollandale School District</li>
						<li>Keplere Institute</li>
						<li>Leland Deacon Alliance</li>
						<li>Leland Medical Clinic</li>
						<li>Leland School District</li>
						<li>Lil Darlings Learning Center</li>
						<li>Mississippi Department of Education</li>
						<li>Mississippi Delta Community College</li>
						<li>Mother Goose Learning Center</li>
						<li>South Delta Regional Housing Authority</li>
						<li>United States Department of Education</li>
						<li>Washington County Opportunities, Inc.</li>
						<li>Washington County Economic Alliance</li>
					</ul>
				</div>
				<div class="alert-info alert mt-3 mb-3">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" value="Yes" name="consent-dha">
							I HAVE READ, UNDERSTOOD AND ACCEPTED THE ABOVE STATEMENTS: I hereby give my consent to release information as deemed beneficial to me and/or my family and will be an active participant in the process. This Consent Agreement is valid for the duration of the DCPC initiative. Until such time as I withdraw my consent, which must be communicated in writing and addressed to the DCPC my consent shall remain in place, valid and effective. I have a right to receive a copy of this document. I reserve all rights provided to me by law not waived by the scope of this consent and authorization.
						</label>
					</div>
				</div>
				<!--K intake survey for DHA-->
				<div class="kindergartenDiv" style="display: none;">
					<h3>Kindergarten Intake Survey</h3>
					<div class="form-group row">
						<label for="mother_educ" class="col-sm-3 col-form-label">Highest Level of Education Completed by Student's Mother</label>
						<div class="col-sm-9">
							<select class="form-control" id="mother_educ" name="mother_educ">
								<option disabled selected></option>
								<option value="Less than High School">Less than high school</option>
								<option value="High School or GED">High school or GED</option>
								<option value="Some years of college">Some years of college</option>
								<option value="Associate's degree">Associate's degree</option>
								<option value="Bachelor's degree">Bachelor's degree</option>
								<option value="Graduate degree">Gradute degree</option>
							</select>
							<small class="muted-text required text-danger dha">Required</small>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">Does your student have a regular bedtime routine?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="bedtime" value="Yes">
						        Yes
						      </label>
						    </div>
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="bedtime" value="No">
						        No
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">Is there a place that your student usually goes when he/she is sick or you need advice about his/her health?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="sick" value="Yes">
						        Yes
						      </label>
						    </div>
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="sick" value="No">
						        No
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does someone in your home read a picture book with your child?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="pic_book" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="pic_book" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="pic_book" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="pic_book" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does someone in your home talk about a book after reading it with your child?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="talk_book" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="talk_book" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="talk_book" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="talk_book" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does someone in your home sing or say the alphabet with your child?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="alphabet" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="alphabet" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="alphabet" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="alphabet" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does someone in your home sing or say nursery rhymes with your child?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="nursery" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="nursery" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="nursery" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="nursery" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does someone in your home tell your child stories without using books?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="stories" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="stories" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="stories" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="stories" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does someone in your home go to the library with your child?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="library" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="library" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="library" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="library" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does your child ask to be read to?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="read" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="read" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="read" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="read" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does your child look at books by himself or herself?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="books_self" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="books_self" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="books_self" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="books_self" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How often does your child see someone in your home reading for fun?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="fun" value="Never">
						        Never
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="fun" value="1-2 times a week">
						        1-2 times a week
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="fun" value="2-3 times a week">
						        2-3 times a weeek
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="fun" value="Daily">
						        Daily
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How many picture books do you have in your home?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="picbooks_have" value="0">
						        0
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="picbooks_have" value="1-10">
						        1-10
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="picbooks_have" value="11-25">
						        11-25
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="picbooks_have" value="26-50">
						        26-50
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="picbooks_have" value="50+">
						        50+
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How much does your child like being read to?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="like_read" value="Not at all">
						        Not at all
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="like_read" value="A little">
						        A little
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="like_read" value="A lot">
						        A lot
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="like_read" value="Love it">
						        Love it
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">How comfortable are you reading to your child?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="comfort" value="Not at all">
						        Not at all
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="comfort" value="A little">
						        A little
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="comfort" value="A lot">
						        A lot
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="comfort" value="Love it">
						        Love it
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">Did you child attend one of the following programs this past year before Kindergarten?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="checkbox" class="form-check-input" name="before_k[]" value="Head Start">
						        Head Start
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="checkbox" class="form-check-input" name="before_k[]" value="Private Child Care">
						        Private Child Care
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="checkbox" class="form-check-input" name="before_k[]" value="Public PreK">
						        Public PreK
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="checkbox" class="form-check-input" name="before_k[]" value="Home Based">
						        Home Based
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="checkbox" class="form-check-input" name="before_k[]" value="Stayed home with parent/relative">
						        Stayed home with parent/relative
						      </label>
						    </div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">My child received Dolly Parton's Imagination Library books through the mail.</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="dolly" value="Yes">
						        Yes
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="dolly" value="No">
						        No
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
					<div class="form-group">
						<div class="row">
					  	<label class="col-sm-12">Do you have internet on a computer at home?</label>
						</div>
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-9">
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="internet" value="Yes">
						        Yes
						      </label>
						    </div>
								<div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="internet" value="No">
						        No
						      </label>
						    </div>
								<small class="muted-text required text-danger dha">Required</small>
							</div>
					  </div>
					</div>
				</div>
				<div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
        <a class="btn btn-danger" href="newregistration.php">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

//include footer
include('footer.php');
?>
