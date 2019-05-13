$(document).ready(function() {

  var itemTotal = 0;
  $("#addItem").click(function() {
    itemTotal++;
    var html = '<div class="testSubDiv"><hr><h3>Item #' + itemTotal + '</h3>';
    html += '<div class="form-group row"><label for="date-' + itemTotal + '" class="col-sm-3 col-form-label">Date</label>';
    html += '<div class="col-sm-9"><input type="date" name="date-' + itemTotal + '" class="form-control" id="date-' + itemTotal + '">';
    html += '<small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label class="col-sm-3">Score Type</label>';
    html += '<div class="col-sm-9"><div class="form-check"><label class="form-check-label">';
    html += '<input type="radio" class="form-check-input" name="type-' + itemTotal + '" value="ela"> ELA</label></div>';
    html += '<div class="form-check"><label class="form-check-label">';
    html += '<input type="radio" class="form-check-input" name="type-' + itemTotal + '" value="math"> Math</label></div>';
    html += '<small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="score-' + itemTotal + '" class="col-sm-3 col-form-label">Score</label>';
    html += '<div class="col-sm-9"><input type="number" name="score-' + itemTotal + '" class="form-control" id="score-' + itemTotal + '" step="0.1">';
    html += '<small class="muted-text text-danger required">Required</small></div></div>';
    html += '</div>';
    $("#testDiv").append(html);
    $("#removeItem").removeClass("disabled");
    $("#testNum").val(itemTotal);
  });

  $("#removeItem").click(function() {
    if (itemTotal > 0) {
      $("#testDiv").children(".testSubDiv").last().remove();
      itemTotal--;
    }
    if (itemTotal == 0) {
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
