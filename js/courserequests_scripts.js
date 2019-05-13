$(document).ready(function() {
  
  $("#requests_form").change(function() {
    updateRequests();
  });
  
  $("#grade_select").material_select();
  
  $("#clr-english").click(function() {
    $('input[name="english"]').prop("checked", false);
    updateRequests();
  });
  $("#clr-math").click(function() {
    $('input[name="math"]').prop("checked", false);
    updateRequests();
  });
  $("#clr-science").click(function() {
    $('input[name="science"]').prop("checked", false);
    updateRequests();
  });
  $("#clr-history").click(function() {
    $('input[name="history"]').prop("checked", false);
    updateRequests();
  });
  
  $("#grade_select").change(function() {
    $(".grade-9").removeClass("blue-text");
    $("#info-row").addClass("hide");
    $(".grade-span").empty();
    var grade = $(this).val();
    if (grade == 8) {
      $("#info-row").removeClass("hide");
      $(".grade-span").html(9);
      $(".grade-9").addClass("blue-text");
    }
  });
  
  
  function updateRequests() {
    $(".error").addClass("hide");
    $("#core_warning-eng").removeClass("hide");
    $("#core_warning-math").removeClass("hide");
    $("#core_warning-science").removeClass("hide");
    $("#core_warning-history").removeClass("hide");
    var selected = 0;
    for (var j=1; j<8; j++) {
      $("#req-" + j).empty();
    }
    j = 1;
    
    $('input[type="radio"]:checked').each(function() {
      $("#req-" + j).html($(this).val());
      selected++;
      j++;
      if ($(this).prop("name") == "english") {
        $("#core_warning-eng").addClass("hide");
      }
      if ($(this).prop("name") == "math") {
        $("#core_warning-math").addClass("hide");
      }
      if ($(this).prop("name") == "science") {
        $("#core_warning-science").addClass("hide");
      }
      if ($(this).prop("name") == "history") {
        $("#core_warning-history").addClass("hide");
      }
    })
    
    $('input[type="checkbox"]:checked').each(function() {
      $("#req-" + j).html($(this).val());
      selected++;
      j++;
    })
    
    $("#req-selected").html(selected);
    $("#req-needed").html(7 - selected);
    if (selected > 7) {
      $(".error").removeClass("hide");
    }
  }
  
  $("#btnSubmit").click(function() {
    var selected = 0;
    $('input:checked').each(function() {
      selected++;
    });
    if (selected == 7) {
      if ($("#name").val() === '') {
        Materialize.toast('Please enter your name', 4000);
      }
      else if ($("#grade").val() === null) {
        Materialize.toast('Please enter your grade', 4000);
      }
      else {
        var data = $("#requests_form :input").serializeArray();
      $.post('service.php', data, function(json) {
        if (json.status == 'fail') {
          Materialize.toast(json.message, 4000);
        }
        else if (json.status == 'success') {
          Materialize.toast('Course Requests submitted!', 4000);
          $("#requests_form").trigger('reset');
          clearForm();
        }
      }, "json");
      }
    }
    else if (selected < 7) {
      Materialize.toast('You have selected too few credits. Make sure you select exactly 7.', 4000);
    }
    else {
      Materialize.toast('You have selected too many credits. Make sure you select exactly 7.', 4000);
    }
  });
  
  
  function clearForm() {
    $(".error").addClass("hide");
    $("#core_warning-eng").removeClass("hide");
    $("#core_warning-math").removeClass("hide");
    $("#core_warning-science").removeClass("hide");
    $("#core_warning-history").removeClass("hide");
    $(".grade-9").removeClass("blue-text");
    $("#info-row").addClass("hide");
    $(".grade-span").empty();
    for (var j=1; j<8; j++) {
      $("#req-" + j).empty();
    }
    j = 1;
  }
  
})