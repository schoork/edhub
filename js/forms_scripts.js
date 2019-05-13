$(document).ready(function() {

  $("input").on("blur", function() {
    if ($(this).val() === '') {
      $(this).siblings('.required').addClass("text-danger");
      $(this).siblings('.required').removeClass("text-success");
    }
    else {
      $(this).siblings('.required').removeClass("text-danger");
      $(this).siblings('.required').addClass("text-success");
      checkStatus();
    }
  });

  $('input[type="radio"]').on("change", function() {
    $(this).parent().parent().siblings('.required').removeClass("text-danger");
    $(this).parent().parent().siblings('.required').addClass("text-success");
    checkStatus();
  });

  $('input[type="checkbox"]').on("change", function() {
    var name = $(this).attr("name");
    var checked = 0;
    $('input[name="' + name + '"]').each(function() {
      if ($(this).prop("checked") === true) {
        checked = 1;
      }
    });
    if (checked == 1) {
      $(this).parent().parent().siblings('.required').removeClass("text-danger");
      $(this).parent().parent().siblings('.required').addClass("text-success");
    } else {
      $(this).parent().parent().siblings('.required').addClass("text-danger");
      $(this).parent().parent().siblings('.required').removeClass("text-success");
      checkStatus();
    }
  });

  $("#btnSubmitForm").click(function() {
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
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-success");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Well done!</strong> Your form has been submitted.");
          $("form").trigger("reset");
          $(".muted-text").each(function() {
            if ($(this).hasClass("text-success")) {
              $(this).removeClass("text-success");
              $(this).addClass("text-danger");
            }
          });
        }
      }, "json");
    }
  });

});

$(document).on("change", 'input[type="radio"]', function() {
  $(this).parent().parent().siblings('.required').removeClass("text-danger");
  $(this).parent().parent().siblings('.required').addClass("text-success");
  checkStatus();
});

$(document).on("change", "select", function() {
  $(this).siblings('.required').removeClass("text-danger");
  $(this).siblings('.required').addClass("text-success");
  checkStatus();
});

$(document).on("change keyup paste", "textarea", function() {
  if ($(this).val() === '') {
    $(this).siblings('.required').addClass("text-danger");
  } else {
    $(this).siblings('.required').removeClass("text-danger");
    $(this).siblings('.required').addClass("text-success");
    checkStatus();
  }
});

$(document).on("blur", 'input', function() {
  if ($(this).val() === '') {
    $(this).siblings('.required').addClass("text-danger");
    $(this).siblings('.required').removeClass("text-success");
  }
  else {
    $(this).siblings('.required').removeClass("text-danger");
    $(this).siblings('.required').addClass("text-success");
    checkStatus();
  }
});

function checkStatus() {
  if ($(".required:visible").hasClass("text-danger")) {
    $("#btnSubmit").attr("disabled", true);
  }
  else {
    $("#btnSubmit").attr("disabled", false);
  }
}
