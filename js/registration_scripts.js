$(document).ready(function() {

  $('input[type="checkbox"]').on("change", function() {
    var name = $(this).attr("name");
    var checked = 0;
    $('input[name="' + name + '"]').each(function() {
      if ($(this).prop("checked") === true) {
        checked = 1;
      }
    });
    if (checked == 1) {
      $(this).parent().siblings('.required').removeClass("text-danger");
      $(this).parent().siblings('.required').addClass("text-success");
    } else {
      $(this).parent().siblings('.required').addClass("text-danger");
      $(this).parent().siblings('.required').removeClass("text-success");
      checkStatus();
    }
  });

  $("#house_income").change(function() {
    var value = $(this).val();
    value = parseInt(value);
    var num = '$' + value.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    $(this).val(num);
  });

  var required = 0;
  $("#btnSubmit").click(function() {
    $(".toc-entry a").css({'color': '#99979c'});
    $(".required:visible").each(function() {
      if ($(this).hasClass("text-danger")) {
        required++;
        $("#alert").addClass("alert-danger");
        $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form. Sections with required missing fields have been highlighted red on the right.');
        //make section headers red in TOC
        if ($(this).hasClass("basic-info")) {
          $(".toc-entry a[href='#basic-info']").css({'color': 'red'});
        }
        else if ($(this).hasClass("parent")) {
          $(".toc-entry a[href='#parent']").css({'color': 'red'});
        }
        else if ($(this).hasClass("emergency")) {
          $(".toc-entry a[href='#emergency']").css({'color': 'red'});
        }
        else if ($(this).hasClass("student-health")) {
          $(".toc-entry a[href='#student-health']").css({'color': 'red'});
        }
        else if ($(this).hasClass("residency")) {
          $(".toc-entry a[href='#residency']").css({'color': 'red'});
        }
        else if ($(this).hasClass("income")) {
          $(".toc-entry a[href='#income']").css({'color': 'red'});
        }
        else if ($(this).hasClass("school_specific")) {
          $(".toc-entry a[href='#school_specific']").css({'color': 'red'});
        }
        else if ($(this).hasClass("consents")) {
          $(".toc-entry a[href='#consents']").css({'color': 'red'});
        }
        else if ($(this).hasClass("dha")) {
          $(".toc-entry a[href='#dha']").css({'color': 'red'});
        }
      }
    });
    if (required == 0) {
      $(this).attr('disabled', true);
      $("#alert").removeClass("alert-danger");
      $("#alert").addClass("alert-info");
      $("#alert").html('<strong>Please Wait!</strong> Your registration is being submitted.');
      var data = $("form :input").serializeArray();
      $.post('service-nologin.php', data, function(json) {
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
          /*
          $(".muted-text").each(function() {
            if ($(this).hasClass("text-success")) {
              $(this).removeClass("text-success");
              $(this).addClass("text-danger");
            }
          });
          */
        }
      }, "json");
    }
  });

  $("input[name='mailDiff']").click(function() {
    $("#mailingDiv").toggle();
  });

  $(".asthma-radio").on("click", function() {
    $("#asthmaDiv").toggle();
  });

  $("#grade").on("change", function() {
    if ($(this).val() == 'K') {
      $(".kindergartenDiv").show();
    }
    else {
      $(".kindergartenDiv").hide();
    }
  });

  //Add Parent
  var parentTotal = 1;
  $("#addParent").click(function() {
    parentTotal++;
    var html = '<div class="parentSubDiv"><h4>Parent/Guardian #' + parentTotal + '</h4>';
    html += '<div class="form-group row"><label for="parent_rel-' + parentTotal + '" class="col-sm-3 col-form-label">Relationship to Student</label>';
    html += '<div class="col-sm-9"><select class="form-control" id="parent_rel-' + parentTotal + '" name="parent_rel-' + parentTotal + '">';
    html += '<option disabled selected></option>';
    html += '<option value="Mother">Mother</option>';
    html += '<option value="Father">Father</option>';
    html += '<option value="Grandmother">Grandmother</option>';
    html += '<option value="Grandfather">Grandfather</option>';
    html += '<option value="Aunt">Aunt</option>';
    html += '<option value="Uncle">Uncle</option>';
    html += '<option value="Sibling">Sibling (brother or sister)</option>';
    html += '<option value="Guardian">Guardian (non-kin)</option>';
    html += '<option value="Unaccompanied">No Parent or Guardian</option>';
    html += '</select><small class="muted-text required text-danger parent">Required</small></div></div>';
    html += '<div class="form-group row"><label for="parent_name-' + parentTotal + '" class="col-sm-3 col-form-label">Parent/Guardian Name</label>';
    html += '<div class="col-sm-9"><input type="text" name="parent_name-' + parentTotal + '" class="form-control" id="parent_name-' + parentTotal + '">';
    html += '<small class="muted-text required text-danger parent">Required</small></div></div>';
    html += '<div class="form-group row"><label for="parent_phone1-' + parentTotal + '" class="col-sm-3 col-form-label">Phone Number #1</label>';
    html += '<div class="col-sm-9"><input type="text" name="parent_phone1-' + parentTotal + '" class="form-control" id="parent_phone1-' + parentTotal + '">';
    html += '<small class="muted-text required text-danger parent">Required</small></div></div>';
    html += '<div class="form-group row"><label for="parent_phone2-' + parentTotal + '" class="col-sm-3 col-form-label">Phone Number #2</label>';
    html += '<div class="col-sm-9"><input type="text" name="parent_phone2-' + parentTotal + '" class="form-control" id="parent_phone2-' + parentTotal + '">';
    html += '</div></div>';
    html += '<div class="form-group row"><label for="parent_email-' + parentTotal + '" class="col-sm-3 col-form-label">Email Address</label>';
    html += '<div class="col-sm-9"><input type="text" name="parent_email-1" class="form-control" id="parent_email-1"></div></div>';
    html += '<hr/></div>';
    $("#parentDiv").append(html);
    $("#removeParent").removeClass("disabled");
    $("#parentTotal").val(parentTotal);
  });

  $("#removeParent").click(function() {
    if (parentTotal > 1) {
      $("#parentDiv").children(".parentSubDiv").last().remove();
      parentTotal--;
    }
    if (parentTotal == 1) {
      $(this).addClass("disabled");
    }
  });

  //Add contacts
  var contactTotal = 1;
  $("#addContact").click(function() {
    contactTotal++;
    var html = '<div class="contactSubDiv"><h4>Contact #' + contactTotal + '</h4>';
    html += '<div class="form-group row"><label for="contact_name-' + contactTotal + '" class="col-sm-3 col-form-label">Contact Name</label>';
    html += '<div class="col-sm-9"><input type="text" name="contact_name-' + contactTotal + '" class="form-control" id="contact_name-' + contactTotal + '">';
    html += '<small class="muted-text required text-danger emergency">Required</small></div></div>';
    html += '<div class="form-group row"><label for="contact_phone1-' + contactTotal + '" class="col-sm-3 col-form-label">Phone Number #1</label>';
    html += '<div class="col-sm-9"><input type="text" name="contact_phone1-' + contactTotal + '" class="form-control" id="contact_phone1-' + contactTotal + '">';
    html += '<small class="muted-text required text-danger emergency">Required</small></div></div>';
    html += '<div class="form-group row"><label for="contact_phone2-' + contactTotal + '" class="col-sm-3 col-form-label">Phone Number #2</label>';
    html += '<div class="col-sm-9"><input type="text" name="contact_phone2-' + contactTotal + '" class="form-control" id="contact_phone2-' + contactTotal + '">';
    html += '</div></div><hr/></div>';
    $("#contactDiv").append(html);
    $("#removeContact").removeClass("disabled");
    $("#contactTotal").val(contactTotal);
  });

  $("#removeContact").click(function() {
    if (contactTotal > 1) {
      $("#contactDiv").children(".contactSubDiv").last().remove();
      contactTotal--;
    }
    if (contactTotal == 1) {
      $(this).addClass("disabled");
    }
  });
});

//SSN
$(document).on("keyup", "#ssn",  function(event) {
  // makes a selection in input
  var selection = window.getSelection().toString();
  if ( selection !== '' ) {
    return;
  }

  // Presses arrow keys
  if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
    return;
  }

  // retrieve input
  var $this = $( this );
  var input = $(this).val();

  // remove dashes and other non-numbers
  input = input.replace(/[\W\s\._\-]+/g, '');

  // chunks by lengths below
  var splitArray = [3, 2, 4];
  var split = 0;
  var chunk = [];

  for (var i = 0, len = input.length; i < len; i+= split) {
    if ((i == 0)) {
      split = 3;
    }
    else if (i == 3) {
      split = 2;
    }
    else {
      split = 4;
    }
    chunk.push( input.substr( i, split ) );
  }

  // puts chunks separated by dashes in input
  $(this).val(function() {
     return chunk.join("-");
  });

});
