$(document).ready(function() {
  
  $("#department").on("change", function() {
    $("select").each(function() {
      $(this).attr("disabled", false);
    });
    var dept = $(this).val();
    $(".row-" + dept).find("select").each(function() {
      $(this).attr("disabled", "disabled");
    });
    $.getJSON( "service.php?action=getDeptReport&dept=" + dept, function(json) {
      if (json.events.length > 0) {
        var html = '<ul>';
        $.each(json.events, function() {
          html += '<li>' + this.title + ' at ' + this.location + ' (' + this.start + ' - ' + this.end + ')</li>';
        });
        html += '</ul>'
        $("#old-upcoming").html(html);
        $("#old-upcoming").show();
      } 
      if (json.links.length > 0) {
        var html = '<ul>';
        $.each(json.links, function() {
          html += '<li>' + this.title + ' - ' + this.description + '</li>';
        });
        html += '</ul>'
        $("#old-web").html(html);
        $("#old-web").show();
      }
      if (json.info != '') {
        $("#information").val(json.info);
      } 
    });
  });
  
  var upcoming = 1;
  var web = 1;
  
  $(".addButton").click(function() {
    var id = $(this).prop("id").substring(4);
    var html;
    if (id == 'upcoming') {
      upcoming++;
      html = '<hr/><div class="form-group row"><label for="type-' + upcoming + '" class="col-sm-3 col-form-label">Type</label><div class="col-sm-9"><select class="form-control" id="type-' + upcoming + '" name="type-' + upcoming + '"><option disabled selected></option><option value="Activity Trip">Activity Trip</option><option value="Deadline">Deadline</option><option value="PD or Training Event">PD or Training Event</option><option value="Upcoming Event">Upcoming Event</option></select></div></div><div class="form-group row"><label for="title-' + upcoming + '" class="col-sm-3 col-form-label">Title</label><div class="col-sm-9"><input type="text" name="title-' + upcoming + '" class="form-control" id="title-' + upcoming + '"></div></div><div class="form-group row"><label for="start-' + upcoming + '" class="col-sm-3 col-form-label">Dates</label><div class="col-sm-4"><input type="date" name="start-' + upcoming + '" class="form-control" id="start-' + upcoming + '" placeholder="From"><small class="muted-text">From</small><br/></div><div class="col-sm-4"><input type="date" name="end-' + upcoming + '" class="form-control" id="end-' + upcoming + '" placeholder="To"><small class="muted-text">To</small><br/></div></div><div class="form-group row"><label for="time-' + upcoming + '" class="col-sm-3 col-form-label">Time(s)</label><div class="col-sm-9"><input type="text" name="time-' + upcoming + '" class="form-control" id="time-' + upcoming + '"></div></div><div class="form-group row"><label for="location-' + upcoming + '" class="col-sm-3 col-form-label">Location</label><div class="col-sm-9"><input type="text" name="location-' + upcoming + '" class="form-control" id="location-' + upcoming + '"></div></div>';
      $("#upcomingDiv").append(html);
      $("#upcomingNum").val(upcoming);
    }
    else if (id == 'web') {
      web++;
      html = '<hr/><div class="form-group row"><label for="webtitle-' + web + '" class="col-sm-3 col-form-label">Title</label><div class="col-sm-9"><input type="text" name="webtitle-' + web + '" class="form-control" id="webtitle-' + web + '"></div></div><div class="form-group row"><label for="url-' + web + '" class="col-sm-3 col-form-label">URL</label><div class="col-sm-9"><input type="text" name="url-' + web + '" class="form-control" id="url-' + web + '"></div></div><div class="form-group row"><label for="description-' + web + '" class="col-sm-3 col-form-label">Description</label><div class="col-sm-9"><textarea class="form-control" id="description-' + web + '" name="description-' + web + '" rows="6" maxlength="500"></textarea></div></div>';
      $("#webDiv").append(html);
      $("#webNum").val(web);
    }
  });
  
  $("#btnSubmit").click(function() {
    var data = $("#deptForm :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#alert").addClass("alert-danger");
        $("#alert").removeClass("alert-info");
        $("#alert").html("<strong>Stop!</strong> The report didn't file properly. Try again.");
        console.log(json.message);
      } else if (json.status == 'success') {
        $("#alert").addClass("alert-success");
        $("#alert").removeClass("alert-danger");
        $("#alert").html("<strong>Well done!</strong> Your report has been filed.");
        $("form").trigger("reset");
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


$(document).on("change", "input[type='date']", function() {
  var type = $(this).attr("name").substr(0, 1);
  if (type == 's') {
    var number = $(this).attr("name").substr($(this).attr("name").length - 1);
    var value = $(this).val();
    $("#end-" + number).val(value);
  }
});