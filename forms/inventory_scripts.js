$(document).ready(function() {
  $("#invent_action").change(function() {
    $(".type_div").hide();
    var value = $(this).val();
    if (value == 'Disposal') {
      $("#disposal_div").show();
    }
    else if (value == 'Donation') {
      $("#donation_div").show();
    }
    else if (value == 'Lost/Stolen') {
      $("#lost-stolen_div").show();
    }
    else if (value == 'Transfer') {
      $("#transfer_div").show();
    }
  });

  var fileNum = 1;
  $("#addFile").click(function() {
    fileNum++;
    var html = '<div class="form-group row"><div class="col-sm-12"><label class="custom-file"><input type="file" id="file-' + fileNum + '" name="file-' + fileNum + '" class="custom-file-input"><span class="custom-file-control"></span></label></div></div>';
    $("#filesDiv").append(html);
    $("#fileNum").val(fileNum);
  });

  var itemTotal = 1;
  $("#addItem").click(function() {
    itemTotal++;
    var html = '<div class="itemSubDiv"><h3>Item #' + itemTotal + '</h3>';
    html += '<div class="form-group row"><label for="tag-' + itemTotal + '" class="col-sm-3 col-form-label">Asset Tag Number</label>';
    html += '<div class="col-sm-9"><input type="number" name="tag-' + itemTotal + '" class="form-control" id="tag-' + itemTotal + '">';
    html += '<small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="description-' + itemTotal + '" class="col-sm-3 col-form-label">Asset Description</label>';
    html += '<div class="col-sm-9"><input type="text" name="description-' + itemTotal + '" class="form-control" id="description-' + itemTotal + '">';
    html += '<small class="muted-text text-danger required">Required</small></div></div><hr></div>';
    $("#items_div").append(html);
    $("#removeItem").removeClass("disabled");
    $("#itemTotal").val(itemTotal);
  });

  $("#removeItem").click(function() {
    if (itemTotal > 1) {
      $("#items_div").children(".reqSubdiv").last().remove();
      itemTotal--;
    }
    if (itemTotal == 1) {
      $(this).addClass("disabled");
    }
  });
});

$(document).on("change", "input[type='file']", function() {
  var fieldVal = $(this).val();
  console.log(fieldVal);
  fieldVal = fieldVal.substr(12);
  if (fieldVal != undefined || fieldVal != "") {
    fieldVal = fieldVal.substr(0, 20) + '...';
    $(this).next(".custom-file-control").attr('data-content', fieldVal);
  }
});

$(document).on("blur", ".monetary", function() {
  var value = parseFloat($(this).val());
  value = value.toFixed(2);
  $(this).val(value);
});
