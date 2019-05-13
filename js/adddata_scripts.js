$(document).ready(function() {

  var classes = 1;
  $("#addItem").click(function() {
    classes++;
    var html = '<div class="classSubDiv"><h3>Class ' + classes + '</h3>';
    html += '<div class="form-group row"><label for="class-' + classes + '" class="col-sm-3 col-form-label">Class Name/Period</label><div class="col-sm-9"><input type="text" class="form-control" id="class-' + classes + '" name="class-' + classes + '"><small class="muted-text required text-danger">Required</small></div></div>';
    html += '<div class="form-group row"><label for="average-' + classes + '" class="col-sm-3 col-form-label">Class Average</label><div class="col-sm-9"><input type="number" class="form-control" id="average-' + classes + '" name="average-' + classes + '" step="0.1"><small class="muted-text text-danger required">Required</small><br/><small class="muted-text">Round to the nearest tenth (0.x).</small></div></div>';
    html += "<p>Use students' test scores to answer the following questions.</p>";
    html += '<div class="form-group row"><label for="minimal-' + classes + '" class="col-sm-3 col-form-label">Number 0% - 64%</label><div class="col-sm-9"><input type="number" class="form-control" id="minimal-' + classes + '" name="minimal-' + classes + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="basic-' + classes + '" class="col-sm-3 col-form-label">Number 65% - 74%</label><div class="col-sm-9"><input type="number" class="form-control" id="basic-' + classes + '" name="basic-' + classes + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="pass-' + classes + '" class="col-sm-3 col-form-label">Number 75% - 84%</label><div class="col-sm-9"><input type="number" class="form-control" id="pass-' + classes + '" name="pass-' + classes + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="pro-' + classes + '" class="col-sm-3 col-form-label">Number 85% - 94%</label><div class="col-sm-9"><input type="number" class="form-control" id="pro-' + classes + '" name="pro-' + classes + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="adv-' + classes + '" class="col-sm-3 col-form-label">Number 95% - 100%</label><div class="col-sm-9"><input type="number" class="form-control" id="adv-' + classes + '" name="adv-' + classes + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="practice-' + classes + '" class="col-sm-3 col-form-label">What does this tell you about your practice?</label><div class="col-sm-9"><textarea class="form-control" id="practice-' + classes + '" rows="3" name="practice-' + classes + '" maxlength="250"></textarea><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="victories-' + classes + '" class="col-sm-3 col-form-label">Where are the victories?</label><div class="col-sm-9"><textarea class="form-control" id="victories-' + classes + '" rows="3" name="victories-' + classes + '" maxlength="250"></textarea><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="better-' + classes + '" class="col-sm-3 col-form-label">How can you get better from this?</label><div class="col-sm-9"><textarea class="form-control" id="better-' + classes + '" rows="3" name="better-' + classes + '" maxlength="250"></textarea><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<hr></div>';
    $("#classDiv").append(html);
    $("#removeItem").removeClass("disabled");
    $("#classTotal").val(classes);
  });

  $("#removeItem").click(function() {
    if (classes > 1) {
      $("#classDiv").children(".classSubDiv").last().remove();
      classes--;
      $("#classTotal").val(classes);
    }
    if (classes == 1) {
      $(this).addClass("disabled");
    }
  });

});

var matches = [];
$.getJSON("service.php?action=getClassNames", function(json) {
  matches = json.classes;
});

$(document).on("change", ".course-select", function() {
  var value = $(this).val();
  console.log(value);
  if (value == 'Other') {
    $(this).parent().parent().siblings(".other-row").show();
  }
  else {
    $(this).parent().parent().siblings(".other-row").hide();
  }
});
