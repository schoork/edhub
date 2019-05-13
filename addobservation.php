<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Observation';
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
		        Add Observation
		    </h1>
			<p class="lead">
				Use this page to observe and provide feedback to a teacher.
			</p>
			<p>
				Feedback is sent to the teacher's email when the form is submitted.
			</p>
			<p>
				<a class="btn btn-outline-primary" href="observations.php">View Observations</a>
			</p>
			<?php
			if (!isset($_GET['teacher'])) {
				?>
				<div class="form-group row">
					<label for="teacher" class="col-sm-3 col-form-label">Teacher</label>
					<div class="col-sm-9">
						<select class="form-control" id="teacher">
							<option disabled selected></option>
							<?php
							$query = "SELECT firstname, lastname, username FROM staff_list WHERE /*access = 'Teacher' AND*/ lastname NOT LIKE 'VACANCY%' ORDER BY lastname, firstname";
							$result = mysqli_query($dbc, $query);
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="' . $row['username'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
							}
							?>
						</select>
					</div>
				</div>
				<?php
			}
			else {
				$teacher_username = mysqli_real_escape_string($dbc, $_GET['teacher']);
				$start = new DateTime();
				$start->add(new DateInterval('P0Y0M0DT2H0M0S'));
				?>
				<form method="post" action="service.php">
					<input type="hidden" name="action" value="addObservation">
					<input type="hidden" name="teacher" value="<?php echo $teacher_username; ?>">
					<input type="hidden" name="observer" value="<?php echo $_SESSION['username']; ?>">
					<input type="hidden" name="start" value="<?php echo $start->format('Y-m-d H:i:s') ?>">
					<div class="row">
						<div class="col">
							<p>
								<?php
								$query = "SELECT firstname, lastname FROM staff_list WHERE username = '$teacher_username'";
								$result = mysqli_query($dbc, $query);
								$row = mysqli_fetch_array($result);
								echo 'Teacher: ' . $row['firstname'] . ' ' . $row['lastname'] . '<br><br>';
								echo '<a class="btn btn-outline-danger" href="addobservation.php">Change Teacher</a>';
								?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label for="period" class="col-sm-3 col-form-label">Period</label>
						<div class="col-sm-9">
							<select class="form-control" id="period" name="period">
								<option disabled selected></option>
								<?php
								for ($i = 1; $i < 8; $i++) {
									echo '<option value="Period ' . $i . '">Period ' . $i . '</option>';
								}
								?>
							</select>
							<small class="muted-text text-danger required">Required</small>
						</div>
					</div>
					<div class="form-group row">
						<label for="course" class="col-sm-3 col-form-label">Course/Class</label>
						<div class="col-sm-9">
							<input type="text" name="course" class="form-control" id="course">
							<small class="muted-text text-danger required">Required</small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3">Whiteboard Protocol</label>
						<div class="col-sm-9">
							<small class="muted-text">Check all that are posted.</small>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="posted[]" value="Date">
									Date
								</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="posted[]" value="Objective(s)">
									Objective(s)
								</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="posted[]" value="Standard(s)">
									Standard(s)
								</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="posted[]" value="Agenda">
									Agenda
								</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="posted[]" value="Weekly Data">
									Weekly Data
								</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="posted[]" value="Word Wall">
									Word Wall
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="domain" class="col-sm-3 col-form-label">Focused Domains</label>
						<div class="col-sm-9">
							<select class="form-control" id="domain" name="domain">
								<option value="All" selected>All Domains</option>
								<?php
								for ($i = 1; $i < 4; $i++) {
									echo '<option value="' . $i . '">Domain ' . $i . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div id="domain-1" class="divDomains">
						<h3>
							Domain I: Lesson Design
						</h3>
						<p>
							<em>Note: There are no questions in this form that address Standard 1 of the PGS Rubric. Since that standard is tied directly to lesson plans it is hard to observe in the classroom.</em>
						</p>
						<div class="form-group row">
							<label class="col-sm-6">
								1.1 Does the teacher provide students access to the lesson at different levels or with different learning styles?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-differentiation" aria-expanded="false" aria-controls="collapseExample">
									In what ways can you differentiate?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="differentiation" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="differentiation" value="2">
										Limited differentiation
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="differentiation" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-differentiation" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-differentiation" rows="2" name="evid-differentiation" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-differentiation">
								<div class="card card-body">
									<p>
										<strong>Content.</strong> A teacher can differentiate content. Content consists of facts, concepts, generalizations or principles, attitudes, and skills related to the subject, as well as materials that represent those elements. Content includes both what the teacher plans for students to learn and how the student gains access to the desired knowledge, understanding, and skills. In many instances in a differentiated classroom, essential facts, material to be understood, and skills remain constant for all learners. (Exceptions might be, for example, varying spelling lists when some students in a class spell at a 2nd grade level while others test out at an 8th grade level, or having some students practice multiplying by two a little longer, while some others are ready to multiply by seven.) What is most likely to change in a differentiated classroom is how students gain access to core learning. Some of the ways a teacher might differentiate access to content include
										<ul>
											<li>Using math manipulatives with some, but not all, learners to help students understand a new idea.</li>
											<li>Using texts or novels at more than one reading level.</li>
											<li>Presenting information through both whole-to-part and part-to-whole approaches.</li>
											<li>Using a variety of reading-buddy arrangements to support and challenge students working with text materials.</li>
											<li>Reteaching students who need another demonstration, or exempting students who already demonstrate mastery from reading a chapter or from sitting through a reteaching session.</li>
											<li>Using texts, computer programs, tape recorders, and videos as a way of conveying key concepts to varied learners.</li>
										</ul>
									</p>
									<p>
										<strong>Process.</strong> A teacher can differentiate process. Process is how the learner comes to make sense of, understand, and “own” the key facts, concepts, generalizations, and skills of the subject. A familiar synonym for process is activity. An effective activity or task generally involves students in using an es- sential skill to come to understand an essential idea, and is clearly focused on a learning goal. A teacher can differentiate an activity or process by, for example, providing varied options at differing levels of difficulty or based on differing student interests. He can offer different amounts of teacher and student support for a task. A teacher can give students choices about how they express what they learn during a research exercise—providing options, for example, of creating a political cartoon, writing a letter to the editor, or making a diagram as a way of expressing what they understand about relations between the British and colonists at the onset of the American Revolution.
									</p>
									<p>
										<strong>Products.</strong> A teacher can also differentiate products. We use the term products to refer to the items a student can use to demonstrate what he or she has come to know, understand, and be able to do as the result of an extended period of study. A product can be, for example, a portfolio of student work; an exhibition of solutions to real-world problems that draw on knowledge, understanding, and skill achieved over the course of a semester; an end-of-unit project; or a complex and challenging paper-and-pencil test. A good product causes students to rethink what they have learned, apply what they can do, extend their understanding and skill, and become involved in both critical and creative thinking. Among the ways to differentiate products are to:
										<ul>
											<li>Allow students to help design products around essential learning goals.</li>
											<li>Encourage students to express what they have learned in varied ways.</li>
											<li>Allow for varied working arrangements (for example, working alone or as part of a team to complete the product).</li>
											<li>Provide or encourage use of varied types of resources in preparing products.</li>
											<li>Provide product assignments at varying degrees of difficulty to match student readiness.</li>
											<li>Use a wide variety of kinds of assessments.</li>
											<li>Work with students to develop rubrics of quality that allow for demonstration of both whole-class and individual goals.</li>
										</ul>
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/100216/chapters/Understanding-Differentiated-Instruction@-Building-a-Foundation-for-Leadership.aspx" target="_blank"><em>Leadership for Differentiating Schools & Classrooms</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								1.2 Are students required to think at a high level?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-thinking" aria-expanded="false" aria-controls="collapseExample">
									What are higher order thinking skills?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="thinking" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="thinking" value="2">
										Some students
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="thinking" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-thinking" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-thinking" rows="2" name="evid-thinking" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-thinking">
								<div class="card card-body">
									<p>
										Teachers are good at writing and asking literal questions (e.g., “Name the parts of a flower”), but we tend to do this far too often. Students must be taught to find the information they need, judge its worth, and think at higher levels. There is simply too much information in the world for us to waste students' time with regurgitations of basic facts. As Bellanca (1997) states:
										<div class="row justify-content-center" style="font-size: 80%">
											<div class="col-sm-10">
												Educators need to realize that there are many more ways to teach than by rote alone. There is teaching for understanding, decision making, problem solving, and connecting a part to a whole, detail to concept, and concept to concept. There also is inference, prediction, analysis for bias, and learning for transfer. Each of these processes requires some form of critical thinking. All are processes that students can develop and refine. Opportunities for students to develop critical thinking processes are not found in classrooms dominated by the regurgitation of short answers. They are found in classrooms where active learning is an essential component. (pp. xxi–xxii).
											</div>
										</div>
									</p>
									<p>
										The old instructional paradigm asked students to read from the textbook and discuss the information to see if they learned the content. We then would test them on the material, lament over how many did poorly, move on to the next topic, and repeat the cycle. When we begin applying what we know about reading and learning, the effective content classroom will look quite different from this model. In the new paradigm, we will
										<ul>
											<li>Design prereading activities to activate background knowledge, establish purpose, and formulate questions that can drive inquiry.</li>
											<li>Allow students to use active reading methods that include peer discussion, as well as to try out their thoughts and seek clarifications from one another as they are reading.</li>
											<li>Model our own thought processes for students and ask that they make their own thinking visible as well.</li>
											<li>Design activities that require students to process information at the highest levels of thought.</li>
											<li>Examine our state curriculum standards to cull out the essential topics so that we can extend learning with greater depth, rather than try to teach curriculum that is a mile wide.</li>
										</ul>
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/104428/chapters/Higher-Order_Thinking.aspx" target="_blank"><em>Literacy Strategies for Grades 4–12</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								1.3 Does the teacher use scaffolding techniques to ensure all students perform at a high level?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-scaffold" aria-expanded="false" aria-controls="collapseExample">
									What is scaffolding?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="scaffold" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="scaffold" value="2">
										Limited scaffolding
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="scaffold" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-scaffold" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-scaffold" rows="2" name="evid-scaffold" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-scaffold">
								<div class="card card-body">
									<p>
										In 1997, Hogan and Pressley reviewed and summarized the professional literature and identified eight essential elements of scaffolded instruction. Although the elements are not presented in any particular order, teachers can use them as general guidelines in instructional planning and implementation (Larkin, 2002):
										<ul>
											<li><strong>Pre-engage the student and the curriculum.</strong> The teacher considers curriculum goals and the students' needs to select appropriate tasks.</li>
											<li><strong>Establish a shared goal.</strong> The students may become more motivated and invested in the learning process when the teacher works with each student to plan instructional goals.</li>
											<li><strong>Actively diagnose students' needs and understandings.</strong> The teacher must be knowledgeable of content and sensitive to the students (e.g., aware of the students' background knowledge and misconceptions) to determine if they are making progress.</li>
											<li><strong>Provide tailored assistance.</strong> This may include cueing or prompting, questioning, modeling, telling, or discussing. The teacher uses these techniques as warranted and adjusts them to meet the students' needs.</li>
											<li><strong>Maintain pursuit of the goal.</strong> The teacher can ask questions and request clarification as well as offer praise and encouragement to help students remain focused on their goals.</li>
											<li><strong>Give feedback.</strong> To help students learn to monitor their own progress, the teacher can summarize current progress and explicitly note behaviors that contribute to each student's success.</li>
											<li><strong>Control for frustration and risk.</strong> The teacher can create an environment in which the students feel free to take risks with learning by encouraging them to try alternatives.</li>
											<li><strong>Assist internalization, independence, and generalization to other contexts.</strong> This means that the teacher helps the students to be less dependent on the teacher's extrinsic signals to begin or complete a task and also provides the opportunity to practice the task in a variety of contexts.</li>
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/111017/chapters/Scaffolds-for-Learning@-The-Key-to-Guided-Instruction.aspx" target="_blank"><em>Guided Instruction</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
					</div>
					<div id="domain-2" class="divDomains">
						<h3>
							Domain II: Student Learning
						</h3>
						<div class="form-group row">
							<label class="col-sm-6">
								2.1 Is there evidence that the teacher uses formative assessments to monitor student progress?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-formative" aria-expanded="false" aria-controls="collapseExample">
									What is formative assessment?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="formative" value="3">
										Yes, daily
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="formative" value="2">
										Weekly Tests only
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="formative" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-formative" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-formative" rows="2" name="evid-formative" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-formative">
								<div class="card card-body">
									<p>
										Formative assessment focuses on achieving goals rather than determining if a goal was or was not met, and one of the ways it does so is by helping to clarify learning goals and standards for both teachers and students. Teaching and learning are based on these standards. Students know the criteria for meeting the standards and are frequently shown exemplars. Teachers give frequent and substantive feedback to students about their progress, pointing out both strengths and areas that need improvement. Teachers plan steps to move students closer to learning goals. Work is assessed primarily on quality in relation to standards rather than student attitude or effort.
									</p>
									<p>
										<strong>In brief:</strong> Formative assessment
										<ul>
											<li>Emphasizes learning outcomes</li>
											<li>Makes goals and standards transparent to students</li>
											<li>Provides clear assessment criteria</li>
											<li>Closes the gap between what students know and desired outcomes</li>
											<li>Provides feedback that is comprehensible, actionable, and relevant</li>
											<li>Provides valuable diagnostic information by generating informative data</li>
										</ul>
									</p>
									<p>
										<strong>In practice:</strong> A curricular standard for 10th grade Biology requires that students understand the chemical basis of all living things. In her classroom, Ms. Jefferson asks students to track their progress toward the specific objective of describing, comparing, and contrasting the molecular structure of proteins, carbohydrates, and fats. The applied learning comes from explaining how these differences are exhibited by foods that students eat every day. Ms. Jefferson uses a signaling activity to get a baseline assessment of where her students stand; afterward, she delivers a traditional lecture, beginning the lesson (as she will all lessons) by stating the specific learning outcome students are expected to master and then focusing on transitioning students from what they know to what they need to know. Students keep a record of their learning by recording specific content knowledge in lab report notebooks. In one section, they draw the molecular structure of proteins, carbohydrates, and fats. Later in the unit, they watch a video and fill in a provided empty outline and then complete a lab in which they test a variety of foods for the presence of proteins, carbohydrates, and fats and report their findings in their lab notebooks. Ms. Jefferson reviews these notebooks regularly to monitor student progress and understanding, provide specific feedback, and inform her instructional decisions. Other formative assessment strategies she uses include Bump in the Road and Feathers and Salt.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/110017/chapters/The-Fundamentals-of-Formative-Assessment.aspx" target="_blank"><em>What Teachers Really Need to Know About Formative Assessment</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								2.2 Does the teacher provide students with clear, specific, actionable, and timely feedback?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-feedback" aria-expanded="false" aria-controls="collapseExample">
									What is feedback?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="feedback" value="3">
										Yes, to all students
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="feedback" value="2">
										To some students or some of the time
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="feedback" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-feedback" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-feedback" rows="2" name="evid-feedback" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-feedback">
								<div class="card card-body">
									<p>
										The term feedback is often used to describe all kinds of comments made after the fact, including advice, praise, and evaluation. But none of these are feedback, strictly speaking.
									</p>
									<p>
										Basically, feedback is information about how we are doing in our efforts to reach a goal. I hit a tennis ball with the goal of keeping it in the court, and I see where it lands—in or out. I tell a joke with the goal of making people laugh, and I observe the audience's reaction—they laugh loudly or barely snicker. I teach a lesson with the goal of engaging students, and I see that some students have their eyes riveted on me while others are nodding off.
									</p>
									<p>
										Here are some other examples of feedback:
										<ul>
											<li>A friend tells me, "You know, when you put it that way and speak in that softer tone of voice, it makes me feel better."</li>
											<li>A reader comments on my short story, "The first few paragraphs kept my full attention. The scene painted was vivid and interesting. But then the dialogue became hard to follow; as a reader, I was confused about who was talking, and the sequence of actions was puzzling, so I became less engaged."</li>
											<li>A baseball coach tells me, "Each time you swung and missed, you raised your head as you swung so you didn't really have your eye on the ball. On the one you hit hard, you kept your head down and saw the ball."</li>
										</ul>
									</p>
									<p>
										Note the difference between these three examples and the first three I cited—the tennis stroke, the joke, and the student responses to teaching. In the first group, I only had to take note of the tangible effect of my actions, keeping my goals in mind. No one volunteered feedback, but there was still plenty of feedback to get and use. The second group of examples all involved the deliberate, explicit giving of feedback by other people.
									</p>
									<p>
										Whether the feedback was in the observable effects or from other people, in every case the information received was not advice, nor was the performance evaluated. No one told me as a performer what to do differently or how "good" or "bad" my results were. (You might think that the reader of my writing was judging my work, but look at the words used again: She simply played back the effect my writing had on her as a reader.) Nor did any of the three people tell me what to do (which is what many people erroneously think feedback is—advice). Guidance would be premature; I first need to receive feedback on what I did or didn't do that would warrant such advice.
									</p>
									<p>
										In all six cases, information was conveyed about the effects of my actions as related to a goal. The information did not include value judgments or recommendations on how to improve. (For examples of information that is often falsely viewed as feedback, see "Feedback vs. Advice" above and "Feedback vs. Evaluation and Grades" on p. 15.)
									</p>
									<p>
										Decades of education research support the idea that by teaching less and providing more feedback, we can produce greater learning (see Bransford, Brown, & Cocking, 2000; Hattie, 2008; Marzano, Pickering, & Pollock, 2001). Compare the typical lecture-driven course, which often produces less-than-optimal learning, with the peer instruction model developed by Eric Mazur (2009) at Harvard. He hardly lectures at all to his 200 introductory physics students; instead, he gives them problems to think about individually and then discuss in small groups. This system, he writes, "provides frequent and continuous feedback (to both the students and the instructor) about the level of understanding of the subject being discussed" (p. 51), producing gains in both conceptual understanding of the subject and problem-solving skills. Less "teaching," more feedback equals better results.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/educational-leadership/sept12/vol70/num01/Seven-Keys-to-Effective-Feedback.aspx" target="_blank"><em>Seven Keys to Effective Feedback</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								2.3 Do students apply feedback they have received from the teacher and/or peers?
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="apply" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="apply" value="2">
										Some students do
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="apply" value="1">
										No (or no time provided to do so)
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-apply" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-apply" rows="2" name="evid-apply" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								2.4 Does the teacher connect content across disciplines?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-disciplines" aria-expanded="false" aria-controls="collapseExample">
									What is cross-curriculum teaching?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="disciplines" value="3">
										Yes, and effective for most students
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="disciplines" value="2">
										Yes, and effective for some students
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="disciplines" value="1">
										No (or not effective)
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-disciplines" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-disciplines" rows="2" name="evid-disciplines" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-disciplines">
								<div class="card card-body">
									<p>
										Skills and content have the potential to be doubly integrated: they can be integrated both within a subject and across the curriculum. The cross-curricular version obviously requires more planning and coordination. The essential idea is that teachers at a grade level, representing different subject areas (or an elementary teacher planning instruction in several subject areas) identify thinking and learning skills important for two or more subjects and decide to interrelate instruction in each subject to achieve greater impact. The desired degree of impact can be achieved by using the same language of instruction, so that students are hearing the same terms used in different subjects, and by organizing the curriculum so that the skills selected for common emphasis can be addressed during the same portion of the school year.
									</p>
									<p>
										An elementary teacher or team of middle school teachers, for example, might decide that the skill of making comparisons might be approached profitably in tandem in several subjects. In English, the focus might be on comparison of characters or books; in life science on systems of the body; in social studies on cultural regions; and in math, on types of triangles. Similarly, a high school team might decide to zero in on cause-effect reasoning and then align curricular elements for which this form of explanation might be especially useful: Macbeth in English, for instance; the American Revolution in social studies; oxidation-reduction reactions in chemistry; and, more metaphorically, deductive proofs in geometry.
									</p>
									<p>
										The desirability of developing such cross-curricular skills-content connections can be evaluated by the same criteria proposed in Chapter 3 for the integration of content: validity for each subject, benefit to each subject, value of the skill beyond the confines of the curriculum, contribution to desirable learning habits, and a host of practical criteria such as the availability of time for curriculum development.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/61189156/chapters/Integrating-Thinking-and-Learning-Skills-Across-the-Curriculum.aspx" target="_blank"><em>Interdisciplinary Curriculum</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								2.5 Does the teacher connect the lesson to real-world applications?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-realworld" aria-expanded="false" aria-controls="collapseExample">
									What are real-world connections?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="realworld" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="realworld" value="2">
										Limited differentiation
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="realworld" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-realworld" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-realworld" rows="2" name="evid-realworld" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-realworld">
								<div class="card card-body">
									<p>
										Curriculum, multimedia, real-world connection, assessment, collaboration, extended time, and student decision making—seven dimensions of project-based multimedia projects may seem to be a lot to think about; but if you have a multimedia project with a strong real-world connection, you can hardly go wrong. Student engagement is just about guaranteed. This is a project your students will work hard on now and remember for a long time.
									</p>
									<p>
										Multimedia is like any other practical art form—it makes sense only when it is part of a context. In wood shop, students don't make joints, they make birdhouses with joints. In sewing, they don't make seams, they make clothing with seams. We don't just combine random media elements, we make multimedia that communicates something. In creating a real-world connection, you are embedding multimedia in a rich context in which students will learn and practice skills, gather and present information, and solve problems. Indeed, the real-world connection is a strong distinguishing element of this learning approach that makes it so motivating for students.
									</p>
									<p>
										A real-world connection means that students see a reason to do this project, other than the fact that you assigned it and they will get a grade on it. There are so many ways to connect to the real world that even beginners to the multimedia approach can design a project that students will find worthwhile.
									</p>
									<p>
										To connect to the real world, you don't even have to leave your school—it is also part of the real world, and a big part of the students' real world. Of course, if you want to connect outside the school walls, technology makes it easier than ever before. Your students can e-mail subject matter experts and use the Internet to find primary source data.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/102112/chapters/Making_a_Real-World_Connection.aspx" target="_blank"><em>Increasing Student Learning Through Multimedia Projects</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								2.6 Does the teacher ask effective probing questions?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-probing" aria-expanded="false" aria-controls="collapseExample">
									What are probing questions?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="probing" value="3">
										Yes, and almost all students are required to answer
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="probing" value="2">
										Yes, but only some students are required to answer
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="probing" value="1">
										No, or only a few students are required to answer
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-probing" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-probing" rows="2" name="evid-probing" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-probing">
								<div class="card card-body">
									<p>
										A variety of means are available for checking for understanding, including analysis of student products in written work, spoken language, projects, performances, and assessments (Fisher & Frey, 2007). In guided instruction, questioning is the predominant tool for determining what students know. It is important to recognize that what is done with the question is essential. Consider this exchange:
										<div class="row justify-content-center">
											<div class="col-sm-10">
												<strong>Teacher:</strong> What is a nocturnal animal?<br>
												<strong>Student:</strong> An animal that stays awake at night.<br>
												<strong>Teacher:</strong> Good. What is a diurnal animal?
											</div>
										</div>
									</p>
									<p>
										We would argue that the teacher is quizzing, not questioning. In this case, the teacher is running through a list of technical vocabulary (nocturnal, diurnal) to determine how closely the student's answer matches the book definition. Contrast the preceding exchange with this scenario:
										<div class="row justify-content-center">
											<div class="col-sm-10">
												<strong>Teacher:</strong> What is a nocturnal animal?<br>
												<strong>Student:</strong> An animal that stays awake at night.<br>
												<strong>Teacher:</strong> Tell me more about that. Does a nocturnal animal have special characteristics?<br>
												<strong>Student:</strong> Well, it doesn't sleep a lot.
											</div>
										</div>
									</p>
									<p>
										And so a misconception reveals itself. This student is making a completely reasonable answer, based on what he knows and doesn't know at this time, and incorrectly assumes that nocturnal animals are sleep-deprived. The teacher didn't teach this, but the student believes it nonetheless. It is the follow-up probe that makes the difference. The teacher's intent in using a question is to uncover, not test. And here's where the teacher uses his thin-slicing abilities to make his next instructional decision. He could say the following:
										<div class="row justify-content-center">
											<div class="col-sm-10">
												<strong>Teacher:</strong> I'm thinking of those pictures we saw of the great horned owl and the slow loris in the daytime and at night. Does your answer still work? [a prompt to activate background knowledge]
											</div>
										</div>
									</p>
									<p>
										Alternatively, the teacher might say this:
										<div class="row justify-content-center">
											<div class="col-sm-10">
												<strong>Teacher:</strong> Let's take a look on page 35 and reread the second paragraph. Does the author agree or disagree with you? [a cue to shift the learner's attention to a source of information]
											</div>
										</div>
									</p>
									<p>
										In both cases, the teacher considered what the student knew and did not know and followed up with a prompt or a cue to scaffold the student's understanding. The ability to do this is not innate—we do not believe that some people are "born" teachers. But too often we don't recognize where the learner might get stuck. There's even a name for this phenomenon. It's called the "expert blind spot," and it describes the inability of inexperienced educators to understand the stance of novice learners (their students) in learning a new concept (Nathan & Petrosino, 2003). In particular, experts tend to overestimate the relative ease of a task. That's actually good news for all of us involved in education, because we can turn that expertise into scaffolds for students. The ability to check for understanding, hypothesize, and then follow with a cue or a prompt can be learned through a combination of experience and purposeful attention.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/111017/chapters/Questioning-to-Check-for-Understanding.aspx" target="_blank"><em>Guided Instruction</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
					</div>
					<div id="domain-3" class="divDomains">
						<h3>
							Domain III: Culure and Learning Environment
						</h3>
						<div class="form-group row">
							<label class="col-sm-6">
								3.1 Does the teacher effectively redirect misbehaviors?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-redirect" aria-expanded="false" aria-controls="collapseExample">
									How do inspried teachers manage a class?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="redirect" value="3">
										Yes (or no misbehaviors observed)
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="redirect" value="2">
										Some of the time
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="redirect" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-redirect" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-redirect" rows="2" name="evid-redirect" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-redirect">
								<div class="card card-body">
									<p>
										There are many ways to say "Get back to work." Inspired teachers can walk into a roomful of buzzing students they don't even know and get everyone on task in minutes. They move around, looking at papers to see how much has been written, rotating papers slightly to make them more accessible, or tapping gently at the place to resume work. They start conversations: "Which question are you on? Does it make sense so far? Do you have any questions? Have your read over the directions? Where's your book? Are you having trouble? Do you think you'll be able to finish before the bell rings?" They also make statements and give simple commands: "Here's a pencil. Find chapter 2. Get out some paper. Open your book. The glossary is in the back. You're almost right. Get a dictionary. Check the board for instructions. Time to begin. Try again. Start writing." Each statement redirects a student who is not on task.
									</p>
									<p>
										By paying attention to each student's level of involvement, the teacher eliminates most serious disruptions—bullying, name calling, fighting. The skilled teacher usually doesn't allow students enough down time to engage in disruptive behavior. The organized classroom makes learning seem attainable to easily frustrated students, so they have less anger to act out. When they do misbehave, more redirecting is in order.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/108051/chapters/Managing-a-Classroom.aspx" target="_blank"><em>Inspired Teacher</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								3.2 Are students active participants (writing, speaking) in the lesson?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-participants" aria-expanded="false" aria-controls="collapseExample">
									What is active participation?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="participants" value="3">
										All or almost all of them
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="participants" value="2">
										Most of them
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="participants" value="1">
										Some or none of them
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-participants" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-participants" rows="2" name="evid-participants" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-participants">
								<div class="card card-body">
									<p>
										Two or three students have their hands raised high in this classroom. Who are they? The ones who know the answer to the teacher's question, the ones who want to please, or maybe even the class clowns. Certainly, they are students who enjoy being in the spotlight and are neither shy nor insecure nor unsure. What does the teacher do? In most cases, the teacher calls on these students. She does not want to embarrass or intimidate the students who don't raise their hands. She does not call on them at all.
									</p>
									<p>
										Does this picture look familiar? Having students raise their hands originated as a classroom management strategy. It has also become a primary instructional strategy in most teacher-centered approaches to schooling. Calling on one student at a time is at the heart of the achievement gap, because its unintended consequences involve issues of equity and further student marginalization.
									</p>
									<p>
										Madeline Hunter's work around active participation as a principle of learning was both specific and clear. Active participation is the consistent and simultaneous engagement of the minds of all the learners with the content of the lesson. Such participation increases the rate and degree of learning. Teachers' typical classroom question structures—"Who knows?" or "Can anyone tell me?"—encourage student hand-raising and focus on students with a ready answer.
									</p>
									<p>
										To encourage active participation in the lesson, Hunter would script the teacher to talk in a specific way: "I want all of you to imagine a time when you were challenged." Or the teacher could request that all students write down their ideas to answer the question. Active participation can be either covert or overt: teachers can ask all students to think, imagine, predict, or visualize; or they can ask all students to write, speak, or do. Active participation is the key to successful teaching and learning. When all learners are engaged with the content throughout the lesson, the probability of high learning levels for all increases exponentially.
									</p>
									<p>
										From <a target="_blank" href="http://www.ascd.org/ascd-express/vol6/608-newvoices2.aspx">"What is Active Participation?"</a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								3.3 Do students have something meaningful to do?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-meaningful" aria-expanded="false" aria-controls="collapseExample">
									What is meaningful work?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="meaningful" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="meaningful" value="2">
										Some of the time
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="meaningful" value="1">
										No
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-meaningful" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-meaningful" rows="2" name="evid-meaningful" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-meaningful">
								<div class="card card-body">
									<p>
										Meaningful learning tasks need to challenge every student in some way. It is crucial that no student be able to coast to success time after time; this experience can create the fixed-mindset belief that you are smart only if you can succeed without effort.
									</p>
									<p>
										To prevent this, teachers can identify students who have easily mastered the material and design in-class assignments that include some problems or exercises that require these students to stretch. This way, the teacher will be close at hand to guide students if necessary and get them used to (and ultimately excited about) the challenging work. Some teachers have told me that after a while, students begin to select or create challenging tasks for themselves.
									</p>
									<p>
										When presenting learning tasks to students, the teacher should portray challenges as fun and exciting, while portraying easy tasks as boring and less useful for the brain. When students initially struggle or make mistakes, the teacher should view this as an opportunity to teach students how to try different strategies if the first ones don't work—how to step back and think about what to try next, like a detective solving a mystery.
									</p>
									<p>
										Suppose that a student has attempted a math problem but is now stuck. The teacher can say, "OK, let's solve this mystery!" and ask the student to show the strategies he or she has tried so far. As the student explains a strategy, the teacher can say, "That's an interesting strategy. Let's think about why it didn't work and whether it gives us some clues for a new path. What should we try next?"
									</p>
									<p>
										When, perhaps with the teacher's guidance, the student finds a fruitful strategy, the teacher can say "Great! You tried different ways, you followed the clues, and you found a strategy that worked. You're just like Sherlock Holmes, the great detective. Are you ready to try another one?" In this way, the teacher can simultaneously gain insight into what the student does and does not understand and teach the student to struggle through knotty problems.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/educational-leadership/sept10/vol68/num01/Even-Geniuses-Work-Hard.aspx" target="_blank"><em>Even Geniuses Work Hard</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								3.4 Are transitions and procedures executed quickly, orderly, and efficiently?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-procedures" aria-expanded="false" aria-controls="collapseExample">
									How do you manage through procedures?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="procedures" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="procedures" value="2">
										Some of the time
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="procedures" value="1">
										No (or none observed)
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-procedures" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-procedures" rows="2" name="evid-procedures" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-procedures">
								<div class="card card-body">
									<p>
										Inspired teachers know that their goal is not to prevent misbehavior but to increase learning. Boosting learning for a daydreamer, a frustrated student who gives up, or a quick study who gets bored are separate concerns. Skilled teachers understand this. The procedures and expectations they establish are designed to address each student.
									</p>
									<p>
										Knowing that everyone learns differently, a skilled teacher may adopt the habit of presenting each new concept at least two ways: a lecture and an experiment, a video clip coupled with a hands-on activity, movement or drama followed by a worksheet, a reading assignment plus a discussion, definitions accompanied by three-dimensional objects. Content and management are linked by teachers who use such procedures for their planning. Variety keeps the dreamer focused and gives the frustrated learner multiple entry points for understanding while stimulating the quick learner.
									</p>
									<p>
										Interaction among students can solve management problems. The quick learner may function well in a group with the daydreamer and the frustrated student. Asking questions and teaching one another change the dynamic for all three. Some protest that the fast learner should not be burdened with helping others, but quick learners benefit by learning to state ideas clearly, understand different viewpoints, and practice team skills.
									</p>
									<p>
										In many classrooms, procedures are in place to allow students to get extra help when they're stuck or to pursue personal projects if they finish early. Pre-tests, practice sessions, reteaching and retesting, and extra credit or special assignments help students reach their full potential. The skilled teacher creates routines to accommodate all students. Some teachers say "Ask three before me" to encourage students to help one another. Simply teaching students how to access and return reference materials allows them to meet their own needs when the teacher is busy with others. Such practices are designed to keep students learning for more minutes and in more depth.
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/educational-leadership/sept10/vol68/num01/Even-Geniuses-Work-Hard.aspx" target="_blank"><em>Even Geniuses Work Hard</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								3.5 Are interactions between the teacher and students positive?<br>
								<a class="btn btn-outline-primary" data-toggle="collapse" href="#collapse-pos_rel_tchr" aria-expanded="false" aria-controls="collapseExample">
									How do you correct students in a positive way?
								</a>
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="pos_rel_tchr" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="pos_rel_tchr" value="2">
										Some
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="pos_rel_tchr" value="1">
										No (or none observed)
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-pos_rel_tchr" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-pos_rel_tchr" rows="2" name="evid-pos_rel_tchr" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<div class="row mb-3">
							<div class="collapse col-sm-12" id="collapse-pos_rel_tchr">
								<div class="card card-body">
									<p>
										Correcting and disciplining students for inappropriate behaviors is a necessary and important part of every teacher's job. However, it doesn't have to be a negative part of your job. In fact, you can actually build positive relationships when you correct students. If you don't believe this, think for just a minute about students you have had in the past who came back to school to visit you. Often it is the students who were the most challenging and with whom you had to spend the most time who continue to visit you over the years. This is due to the positive relationships you developed with them.
									</p>
									<p>
										The goal in correcting students should be to have them reflect on what they did, be sorry that they disappointed you, and make a better choice in the future. It should not be that they go away thinking, “I hate my teacher. I'm going to be sure I don't get caught next time.” The difference in students' reactions to being disciplined is often related to the manner in which you correct them. If you allow students to keep their dignity, you increase the chance that they will reflect on their behavior and choose their behaviors more wisely in the future. The correction process will be counterproductive if students are corrected in a manner that communicates bitterness, sarcasm, low expectations, or disgust. The goal is to provide a quick, fair, and meaningful consequence while at the same time communicating that you care for and respect the student.
									</p>
									<p>
										Steps to Use When Correcting Students
										<ol>
											<li>Review what happened</li>
											<li>Identify and accept the student's feelings</li>
											<li>Review alternative actions</li>
											<li>Explain the building policy as it applies to the situation</li>
											<li>Let the student know that all students are treated the same</li>
											<li>Invoke an immediate and meaningful consequence</li>
											<li>Let the student know you are disappointed that you have to invoke a consequence to his or her action</li>
											<li>Communicate an expectation that the student will do better in the future</li>
										</ol>
									</p>
									<p>
										From <a href="http://www.ascd.org/publications/books/105124/chapters/Developing_Positive_Teacher-Student_Relations.aspx" target="_blank"><em>Educator's Guide to Preventing and Solving Discipline Problems</em></a>
									</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label class="col-sm-6">
								3.6 Are interactions between the students positive?
							</label>
							<div class="col-sm-6">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="pos_rel_studs" value="3">
										Yes
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="pos_rel_studs" value="2">
										Some
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="pos_rel_studs" value="1">
										No (or none observed)
									</label>
								</div>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
						<div class="form-group row">
							<label for="evid-pos_rel_studs" class="col-sm-6 col-form-label">Evidence</label>
							<div class="col-sm-6">
								<textarea class="form-control" id="evid-pos_rel_studs" rows="2" name="evid-pos_rel_studs" maxlength="200"></textarea>
								<small class="muted-text spec-req required text-success">Optional</small>
							</div>
						</div>
						<hr>
					</div>


					<div class="form-group row">
						<label for="glows" class="col-sm-3 col-form-label">Glows from the Lesson</label>
						<div class="col-sm-9">
							<textarea class="form-control" id="glows" rows="5" name="glows" maxlength="500"></textarea>
							<small class="muted-text">What stood out in this lesson as being really great?</small>
						</div>
					</div>
					<div class="form-group row">
						<label for="grows" class="col-sm-3 col-form-label">Grows from the Lesson</label>
						<div class="col-sm-9">
							<textarea class="form-control" id="grows" rows="5" name="grows" maxlength="500"></textarea>
							<small class="muted-text">How could this lesson have been improved?</small>
						</div>
					</div>
					<div id="alert" class="alert" role="alert">

			        </div>
			        <a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
			        <a class="btn btn-danger" href="observations.php">Cancel</a>
				</form>
				<?php
			}
			?>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		//Show only domains focused on
		$("#domain").change(function() {
			var value = $(this).val();
			if (value == 'All') {
				$(".divDomains").show();
			}
			else {
				$(".divDomains").hide();
				$("#domain-" + value).show();
			}
		});
		//Make evidence manadatory for level 2-3 responses
		$("input[type='radio']").on('click', function() {
			var name = $(this).prop("name");
			var value = $(this).val();
			if (value > 1) {
				$("#evid-" + name).siblings(".spec-req").html("Required");
				if ($("#evid-" + name).val() == '') {
					$("#evid-" + name).siblings(".spec-req").addClass("text-danger").removeClass("text-success");
				}
			}
			else {
				$("#evid-" + name).siblings(".spec-req").html("Optional").addClass("text-success").removeClass("text-danger");
			}
		});
		//Redirect to page when selecting a teacher to observe
		$("#teacher").change(function() {
			var teacher = $(this).val();
			window.location.href = 'addobservation.php?teacher=' + teacher;
		});
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
