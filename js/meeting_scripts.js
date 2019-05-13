$(document).ready(function() {

  $("a").click(function() {
    var id = $(this).attr("id");
    if (id == 'submitSignIn') {
      var data = $("#signInForm :input").serializeArray();
    }
    else {
      var data = $("#createMeetingForm :input").serializeArray();
    }
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#alert").addClass("alert-danger");
        $("#alert").removeClass("alert-success");
        $("#alert").html("<strong>Stop!</strong> " +  json.msg);
        console.log(json.message);
      } else if (json.status == 'success') {
        $("#alert").addClass("alert-success");
        $("#alert").removeClass("alert-danger");
        $("#alert").html("<strong>Well done!</strong> " +  json.msg);
        if (id != 'submitSignIn') {
          $("#participantDiv").show();
          $("#set_meeting_id").val(json.meeting_id);

        }
      }
    }, "json");
    var i = setInterval(function() {
      var meeting_id = $("#set_meeting_id").val()
      $("#participants").empty();
      console.log('Run');
      $.getJSON("service.php?action=getMeetingParticipants&id=" + meeting_id, function(json) {
        $.each(json.participants, function() {
          $("#participants").append(this.name + '<br>');
        });
      });
    }, 10000);
    setTimeout(function( ) { clearInterval( i ); }, 1800000);
  });

});
