<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Response';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Teachers
		</h1>
	    <p class="lead">
	    	Manage and observe teachers
	    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      		<h1>
		        Add Response
		    </h1>
			<p class="lead">
				Use this page to respond to observation feedback.
			</p>
			<p>
				Your response is sent to the observer's email when the form is submitted.
			</p>
			<p>
				<a class="btn btn-outline-primary" href="observations.php">View Observations</a>
			</p>
			<?php
			$obs_id = $_GET['id'];
			?>
			<form method="post" action="service.php">
				<input type="hidden" name="action" value="addObsResponse">
				<input type="hidden" name="obs_id" value="<?php echo $obs_id; ?>">
				<input type="hidden" name="teacher" value="<?php echo $_SESSION['username']; ?>">
				<h3>
					General Feedback
				</h3>
				<div class="form-group row">
					<label for="change" class="col-sm-6 col-form-label">
						1. Based on the observation feedback, what is one change you will make in your classroom?
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="change" rows="5" name="change" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="question" class="col-sm-6 col-form-label">
						2. What is one question you have about the observation feedback?
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="question" rows="5" name="question" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<hr>
				<h3>
					Engaging Pre-Conceptions
				</h3>
				<div class="form-group row">
					<label for="conceptions" class="col-sm-6 col-form-label">
						3. What pre-conceptions did students have prior to the lesson?
						<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-engineer" aria-expanded="false" aria-controls="collapseExample">
							What are pre-conceptions?
						</a>
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="conceptions" rows="5" name="conceptions" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="row mb-3">
					<div class="collapse col-sm-12" id="collapse-engineer">
						<div class="card card-body">
							<p>
								The first important principle about how people learn is that students come to the classroom with preconceptions about how the world works which include beliefs and prior knowledge acquired through various experiences. In many cases, the preconceptions include faulty mental models about concepts and phenomena. If their initial understanding is not engaged, they may fail to grasp the new concepts and information that are taught, or they may learn them for purposes of a test but revert to their preconceptions outside the classroom. Research on early learning suggests that the process of making sense of the world begins at a very young age. Children begin in preschool years to develop sophisticated understandings (whether accurate or not) of the phenomena around them. Those initial understandings can have a powerful effect on the integration of new concepts and information. Sometimes those understandings are accurate, providing a foundation for building new knowledge. But sometimes they are inaccurate. In science, students often have misconceptions of physical properties that cannot be easily observed. In humanities, their preconceptions often include stereotypes or simpli cations, as when history is understood as a struggle between good guys and bad guys. A critical feature of effective teaching is that it elicits from students their preexisting understanding of the subject matter to be taught and provides opportunities to build on, or challenge, the initial understanding.
							</p>
							<p>
								Drawing out and working with existing understandings is important for learners of all ages. Numerous research studies demonstrate the persistence of preexisting understandings even after a new model has been taught that contradicts the naïve understanding. Students at a variety of ages persist in their beliefs that seasons are caused by the earth’s distance from the sun rather than by the tilt of the earth, or that an object that had been tossed in the air has both the force of gravity and the force of the hand that tossed it acting on it, despite training to the contrary. For the scienti c understanding to replace the naïve understanding, students must reveal the latter and have the opportunity to see where it falls short.
							</p>
							<p>
								From <a href="https://drive.google.com/a/hollandalesd.org/file/d/1SB81bX3zBM58eBFpT58CsjkrZVtwU6xU/view?usp=sharing" target="_blank"><em>Rethinking and Redesigning Curriculum, Instruction and Assessment: What Comtemporary Research and Theory Suggests</em></a>
							</p>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="engineer" class="col-sm-6 col-form-label">
						4. How did you engineer opportunities for students to engage those pre-conceptions  and replace them with new concepts and information when appropriate?
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="engineer" rows="5" name="engineer" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<hr>
				<h3>
					Organizing Knowledge in Conceptual Frameworks
				</h3>
				<div class="form-group row">
					<label for="concept" class="col-sm-6 col-form-label">
						5. How did you engineer opportunities for students to organize knowledge in conceptual frameworks?
						<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-concept" aria-expanded="false" aria-controls="collapseExample">
							What are conceptual frameworks?
						</a>
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="concept" rows="5" name="concept" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="row mb-3">
					<div class="collapse col-sm-12" id="collapse-concept">
						<div class="card card-body">
							<p>
								The second important principle about how people learn is that to develop competence in an area of inquiry, students must: (a) have a deep foundation of factual knowledge, (b) understand facts and ideas in the context of a conceptual framework, and (c) organize knowledge in ways that facilitate retrieval and application. This principle emerges from research that compares the performance of experts and novices, and from research on learning and transfer. Experts, regardless of the field, always draw on a richly structured information base; they are not just “good thinkers” or “smart people.” The ability to plan a task, to notice patterns, to generate reasonable arguments and explanations, and to draw analogies to other problems, are all more closely intertwined with factual knowledge than was once believed.
							</p>
							<p>
								But knowledge of a large set of disconnected facts is not suf cient. To develop competence and expertise in an area of inquiry, students must have opportunities to learn with understanding rather than memorizing factual content. Key to expertise is a deep understanding of subject matter that transforms factual information into “usable knowledge.” A pronounced difference between experts and novices is that experts’ command of concepts shapes their understanding of new information: it allows them to see patterns, relationships, or discrepancies that are not apparent to novices. They do not necessarily have better overall memories than other people. But their conceptual understanding allows them to extract a level of meaning from information that is not apparent to novices, and this helps them select and remember relevant information. Experts are also able to  uently access relevant knowledge because their understanding of subject matter allows them to quickly identify what is relevant. Hence, their attention is not overtaxed by complex events.
							</p>
							<p>
								A key finding in the learning and transfer literature is that organizing information into a conceptual framework allows for greater “transfer”; that is, it allows the student to apply what was learned in new situations and to learn related information more quickly. The student who has learned geographical information for the Americas in a conceptual framework approaches the task of learning the geography of another part of the globe with questions, ideas, and expectations that help guide acquisition of the new information. Understanding the geographical importance of the Mississippi River sets the stage for the student’s understanding of the geographical importance of the Nile. And as concepts are reinforced, the student will transfer learning beyond the classroom, observing and inquiring about the geographic features of a visited city that help explain its location and size.
							</p>
							<p>
								From <a href="https://drive.google.com/a/hollandalesd.org/file/d/1SB81bX3zBM58eBFpT58CsjkrZVtwU6xU/view?usp=sharing" target="_blank"><em>Rethinking and Redesigning Curriculum, Instruction and Assessment: What Comtemporary Research and Theory Suggests</em></a>
							</p>
						</div>
					</div>
				</div>
				<hr>
				<h3>
					Metacognition
				</h3>
				<div class="form-group row">
					<label for="goals" class="col-sm-6 col-form-label">
						6. How did you engage students in defining learning goals?
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="goals" rows="5" name="goals" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="progress" class="col-sm-6 col-form-label">
						7. How did you engineer opportunities for students to monitor their own progress in achieving the learning goals?
						<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-progress" aria-expanded="false" aria-controls="collapseExample">
							What is metacognition?
						</a>
					</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="progress" rows="5" name="progress" maxlength="500"></textarea>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="row mb-3">
					<div class="collapse col-sm-12" id="collapse-progress">
						<div class="card card-body">
							<p>
								A third critical idea about how people learn is that a “metacognitive” approach to instruction can help students learn to take control of their own learning by de ning learning goals and monitoring their progress
in achieving them. In research with experts who were asked to verbalize their thinking as they worked, it was revealed that they monitored their own understanding carefully, making note of when additional information was required for understanding, whether new information was consistent with what they already knew, and what analogies could be drawn that would advance their understanding. These metacognitive monitoring activities are an important component of what is called adaptive expertise.
							</p>
							<p>
								Because metacognition often takes the form of an internal conversation, it can easily be assumed that individuals will develop the internal dialogue on their own. Yet many of the strategies we use for thinking re ect cultural norms and methods of inquiry. Research has demonstrated that children can be taught these strategies, including the ability to predict outcomes, explain to oneself in order to improve understanding, note failures to comprehend, activate background knowledge, plan ahead, and apportion time and memory. The teaching of metacognitive activities must be incorporated into the subject matter that students are learning. These strategies are not generic across subjects, and attempts to teach them as generic can lead to failure to transfer. Teaching metacognitive strategies in context has been shown to improve understanding in physics, written composition, and heuristic methods for mathematical problem solving. And metacognitive practices have been shown to increase the degree to which students transfer to new settings and events.
							</p>
							<p>
								From <a href="https://drive.google.com/a/hollandalesd.org/file/d/1SB81bX3zBM58eBFpT58CsjkrZVtwU6xU/view?usp=sharing" target="_blank"><em>Rethinking and Redesigning Curriculum, Instruction and Assessment: What Comtemporary Research and Theory Suggests</em></a>
							</p>
						</div>
					</div>
				</div>
				<div id="alert" class="alert" role="alert">

		        </div>
		        <a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
		        <a class="btn btn-danger" href="observations.php">Cancel</a>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		//submit form
		$("#btnSubmit").click(function() {
	      if ($(".muted-text:visible").hasClass("text-danger")) {
	        $("#alert").addClass("alert-danger");
	        $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form.');
	      } else {
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
	            console.log(json.message);
	          } else if (json.status == 'success') {
	            $("#alert").addClass("alert-success");
	            $("#alert").removeClass("alert-info");
	            $("#alert").html("<strong>Well done!</strong> Your form has been submitted.");
	          }
	        }, "json");
	      }
	    });
	});
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
