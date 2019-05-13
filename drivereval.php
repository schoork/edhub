<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Bus Driver Evaluation';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-district.php');

$drivers = array(
    'Brussard, Valera',
    'Ford, Randy',
    'Howard, Barry',
    'Johnson, Cortez',
    'Lucas, Carl',
    'Preston, Clarie',
    'Ratliff, James',
    'Robinson, James',
    'Tackett, Wade',
    'Vallery, Jessye',
    'Williams, Jarvis',
    'Wilson, Lucenda'
)

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			District
		</h1>
        <p class="lead">
          Manage budgets, forms, and inventory
        </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Bus Driver Evaluation
			</h1>
			<p class="lead">
				Use this form to complete a bus driver evaluation after an activity trip.
			</p>
            <form method="post" action="service.php">
                <input type="hidden" name="action" value="addObservation">
                <input type="hidden" name="teacher" value="<?php echo $teacher_username; ?>">
                <input type="hidden" name="observer" value="<?php echo $_SESSION['username']; ?>">
                <div class="form-group row">
                    <label for="driver" class="col-sm-3 col-form-label">Driver</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="driver" name="driver">
                            <option disabled selected></option>
                            <?php
                            foreach ($drivers as $driver) {
                                echo '<option value="' . $driver . '">' . $driver . '</option>';
                            }
                            ?>
                        </select>
                        <small class="muted-text text-danger required">Required</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="trip_form" class="col-sm-3 col-form-label">Bus Request Form Number</label>
                    <div class="col-sm-9">
                        <input type="number" name="trip_form" class="form-control" id="trip_form">
                        <small class="muted-text text-danger required">Required</small>
                    </div>
                </div>
                <p>
                    <strong>Please answer the following questions as best as you can. Notes are optional but provide us with a better understanding so we can better serve you and the district.</strong>
                </p>
                <div class="form-group row">
                    <label class="col-sm-6">
                        1. Was the driver on time?
                    </label>
                    <div class="col-sm-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="time" value="2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="time" value="1">
                                No
                            </label>
                        </div>
                        <small class="muted-text text-danger required">Required</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notes-time" class="col-sm-6 col-form-label">Notes</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="notes-time" rows="2" name="notes-time" maxlength="150"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-6">
                        2. Was the driver courteous to you, other staff, and students?
                    </label>
                    <div class="col-sm-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="courteous" value="2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="courteous" value="1">
                                No
                            </label>
                        </div>
                        <small class="muted-text text-danger required">Required</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notes-courteous" class="col-sm-6 col-form-label">Notes</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="notes-courteous" rows="2" name="notes-courteous" maxlength="150"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-6">
                        3. Was the bus clean when you first got on it?
                    </label>
                    <div class="col-sm-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="clean" value="2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="clean" value="1">
                                No
                            </label>
                        </div>
                        <small class="muted-text text-danger required">Required</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notes-clean" class="col-sm-6 col-form-label">Notes</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="notes-clean" rows="2" name="notes-clean" maxlength="150"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-6">
                        4. Did you feel comfortable riding on the bus?
                    </label>
                    <div class="col-sm-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="safe" value="2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="safe" value="1">
                                No
                            </label>
                        </div>
                        <small class="muted-text text-danger required">Required</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notes-safe" class="col-sm-6 col-form-label">Notes</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="notes-safe" rows="2" name="notes-safe" maxlength="150"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="notes" class="col-sm-6 col-form-label">Other Notes</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="notes" rows="4" name="notes" maxlength="250"></textarea>
                        <small class="muted-text">Please use this box to provide us with any other information that will help us improve our service in the future.</small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
