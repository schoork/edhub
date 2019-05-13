<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Student Tracker';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="/js/moment.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');
$teacher = $_SESSION['username'];
$today = new DateTime('today');
if ($today->format('w') == 1) {
	$week = new DateTime('today');
}
else {
	$week = new DateTime('last Monday');
}
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Students
		</h1>
        <p class="lead">
            Manage students and interventions
        </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
            <form method="post" action="service.php" id="tracker_form">
                <input type="hidden" name="teacher" value="<?php echo $teacher; ?>">
                <input type="hidden" name="action" value="addTrackerData">
				<input type="hidden" name="week" id="week" value="<?php echo $week->format('Y-m-d'); ?>">
				<p class="lead">
					You are updating trackers for the week of <?php echo $week->format('n/j/y'); ?>.
				</p>
                <div class="alert" id="change_div" style="display: none;">
                    You have unsaved changes. Please click the Update Tracker button at the bottom of the page before leaving this page or selecting a different week.
                </div>
                <?php
				$query = "SELECT firstname, lastname, grade, sl.student_id, replace1, replace2, replace3, replace4, replace5, replace6 FROM rti_checklist_written AS rcw LEFT JOIN student_list AS sl USING (student_id) WHERE (teacher1 = '$teacher' OR teacher2 = '$teacher' OR teacher3 = '$teacher' OR teacher4 = '$teacher') AND replace1 IS NOT NULL GROUP BY sl.student_id ORDER BY lastname, firstname";
                $result = mysqli_query($dbc, $query);
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['replace1'] !== null) {
                        ?>
                        <div class="form-row">
                            <div class="col-12">
                                <h5>
                                    <?php echo $row['firstname'] . ' ' . $row['lastname'] .' (' . $row['grade'] . 'th grade)'; ?>
                                </h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                0 = Behavior not demonstrated (0%)<br/>
                                1 = Behavior poorly demonstrated (1%-25)<br>
								2 = Behavior somewhat demonstrated (26%-50%)
                            </div>
                            <div class="col-md-6">
                                3 = Behavior adequately demonstrated (51%-75%)<br>
								4 = Behavior largely demonstrated (76%-100%)
                            </div>
                        </div>
                        <table class="table tracker_tbl table-striped">
                            <thead>
                                <tr>
                                    <th class="first-col">Replacement Behaviors</th>
									<th>Monday</th>
									<th>Tuesday</th>
									<th>Wednesday</th>
									<th>Thursday</th>
									<th>Friday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 1; $i < 7; $i++) {
                                    if ($row["replace$i"] !== null && $row["replace$i"] != '') {
                                        echo '<tr><td>' . $row["replace$i"] . '</td>';
										// $i is behavior, $j is day
                                        for ($j = 1; $j < 6; $j++) {
                                            ?>
                                            <td>
                                                <select name="input-<?php echo $row['student_id'] . '-' . $j . '-' . $i; ?>" id="input-<?php echo $row['student_id'] . '-' . $j . '-' . $i; ?>" class="custom-select behavior_select day<?php echo $j; ?>">
                                                    <option selected></option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="100">Absent</option>
                                                    <option value="101">Skipped</option>
                                                    <option value="102">Suspended</option>
                                                    <option value="103">Teacher Absent</option>
                                                    <option value="104">No Class</option>
                                                    <option value="105">Not Applicable</option>
                                                </select>
                                            </td>
                                            <?php
                                        }
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="description">Notes</label>
                                <textarea class="form-control" id="notes-<?php echo $row['student_id']; ?>" name="notes-<?php echo $row['student_id']; ?>" rows="3" maxlength="300"></textarea>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div id="alert" class="alert" role="alert">

                </div>
                <a class="btn btn-primary" id="btnSubmitForm" href="#!">Update Tracker</a>
                <a class="btn btn-danger" href="studenttrackers.php">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
		var i = $("#week").val();
        addColumns(i);

		$(".behavior_select").on("change", function() {
			var value = $(this).val();
			var name = $(this).attr("name");
			var ids = name.split("-");
			var student = ids[1];
			var day = ids[2];
			if (value >= 100) {
				$("#input-" + student + '-' + day + '-1').val(value);
				$("#input-" + student + '-' + day + '-2').val(value);
				$("#input-" + student + '-' + day + '-3').val(value);
				$("#input-" + student + '-' + day + '-4').val(value);
				$("#input-" + student + '-' + day + '-5').val(value);
				$("#input-" + student + '-' + day + '-6').val(value);
			}
		});

		$("#btnSubmitForm").click(function() {
			$(this).attr('disabled', true);
			$("#alert").removeClass("alert-danger");
			$("#alert").addClass("alert-info");
			$("#alert").html('<strong>Please Wait!</strong> Your information is being submitted.');
			var data = $("form :input").serializeArray();
			$.post('service.php', data, function(json) {
				if (json.status == 'fail') {
				$(this).attr('disabled', false);
				$("#alert").addClass("alert-danger");
				$("#alert").removeClass("alert-info");
				$("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
				}
				else if (json.status == 'success') {
					$(this).attr('disabled', false);
					$("#alert").addClass("alert-success");
					$("#alert").removeClass("alert-info");
					$("#alert").html("<strong>Well done!</strong> Your form has been submitted.");
					$(".muted-text").each(function() {
						if ($(this).hasClass("text-success")) {
							$(this).removeClass("text-success");
							$(this).addClass("text-danger");
						}
					});
				}
			}, "json");
		});
    });

    function addColumns(i) {
        var monday = $("#week").val();
        //Update with information from DB
        var teacher = '<?php echo $_SESSION['username']; ?>';
        $.getJSON("service.php?action=getInputData&teacher=" + teacher + "&monday=" + monday, function(json) {
            $.each(json.inputs, function() {
                $("select[name='input-" + this.input + "']").val(this.value);
            });
            $.each(json.notes, function() {
                $("#notes-" + this.student_id).text(this.notes);
            });
        });
    }
</script>


<?php

mysqli_close($dbc);

echo '</main>';

//end body
echo '</body>';

//include footer
include('footer.php');

?>
