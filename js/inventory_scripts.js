$(document).ready(function() {
  
  getInventory();
  
  $("#btnSubmitForm").click(function() {
    if ($(".required").hasClass("text-danger")) {
      $("#alert").addClass("alert-danger");
      $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form.');
    } else {
      $("#alert").removeClass("alert-danger");
      $("#alert").addClass("alert-info");
      $("#alert").html('<strong>Please Wait!</strong> Your information is being submitted.');
      var data = $("form :input").serializeArray();
      $.post('service.php', data, function(json) {
        if (json.status == 'fail') {
          $("#alert").addClass("alert-danger");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
          console.log(json.message);
        } else if (json.status == 'success') {
          $("#alert").addClass("alert-success");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Well done!</strong> Your information has been submitted.");
        }
      }, "json");
    }
  });
  
});

$(document).on("change", "#location, #room", function() {
  getInventory();
});

function getInventory() {
  var location = $("#location").val();
  var room = $("#room").val();
  if (room !== '') {
    $("#inventoryTbl tbody").empty();
    $.getJSON( "service.php?action=getInventoryItems&location=" + location + '&room=' + room, function(json) {
      if (json.status == 'success') {
        $.each(json.items, function() {
          var html = '<tr><td>' + this.room + '</td><td>' + this.itemId + '</td><td>' + this.title + '</td><td>' + this.class + '</td><td>' + this.serial + '</td><td>' + this.condition + '</td><td><div class="form-check"><label class="form-check-label"><input class="form-check-input" type="radio" name="item-' + this.itemId + '" value="Present"> Present</label></div><br/><div class="form-check"><label class="form-check-label"><input class="form-check-input" type="radio" name="item-' + this.itemId + '" value="Missing/Damaged"> Missing/Damaged</label></div></td></tr>';
          $("#inventoryTbl tbody").append(html);
        });
      }
    });
  }
}

$(document).on("change", "input[type=radio]", function() {
  var value = $(this).val();
  var id = $(this).attr("name").substr(5);
  if (value == 'Missing/Damaged') {
    var html = '<tr id="row-' + id + '"><td colspan="6"><div class="form-group"><label for="desc-' + id + '">Please describe</label><textarea class="form-control" id="desc-' + id + '" name="desc-' + id + '" rows="3"></textarea><small class="muted-text text-danger required">Required</small></div></td></tr>';
    $(html).insertAfter($(this).closest('tr'));
    $("#lostDiv").show();
  }
  else {
    $("#row-" + id).remove();
  }
  var clicked = 0;
  var total = 0;
  $("input[type=radio]").each(function() {
    if ($(this).is(":checked")) {
      clicked++;
    }
    total++;
  });
  var required = 0;
  if (clicked * 2 == total) {
    $("#btnSubmitForm").removeClass("disabled");
  }
  else {
    $("#btnSubmitForm").addClass("disabled", true);
  }
});

$(document).on("keyup", "textarea", function() {
  if ($(this).val() === '') {
    $(this).siblings('.required').addClass("text-danger");
  } else {
    $(this).siblings('.required').removeClass("text-danger");
    $(this).siblings('.required').addClass("text-success");
  }
});