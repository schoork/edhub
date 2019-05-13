$(document).ready(function() {

  $("#instate_number").on("blur", function() {
    var number = $(this).val();
    $("#instate_perdiem_div").empty();
    for (var i = 1; i <= number; i++) {
      html = '<h3>Night #' + i + '</h3>';
      html += '<div class="form-group row"><label for="breakfast-' + i + '" class="col-sm-3 col-form-label">Breakfast</label><div class="col-sm-9"><input type="number" name="instate_breakfast-' + i + '" class="form-control perdiem perdiem-instate-' + i +'" id="instate_breakfast-' + i + '" placeholder="0.00" step="0.01"></div></div>';
      html += '<div class="form-group row"><label for="lunch-' + i + '" class="col-sm-3 col-form-label">Lunch</label><div class="col-sm-9"><input type="number" name="instate_lunch-' + i + '" class="form-control perdiem perdiem-instate-' + i +'" id="instate_lunch-' + i + '" placeholder="0.00" step="0.01"></div></div>';
      html += '<div class="form-group row"><label for="dinner-' + i + '" class="col-sm-3 col-form-label">Dinner</label><div class="col-sm-9"><input type="number" name="instate_dinner-' + i + '" class="form-control perdiem perdiem-instate-' + i +'" id="instate_dinner-' + i + '" placeholder="0.00" step="0.01"></div></div>';
      html += '<div class="form-group row"><label for="amount-' + i + '" class="col-sm-3 col-form-label">Amount Allowed</label><div class="col-sm-9"><input type="number" disabled class="form-control instate_amounts" id="instate_amount-' + i + '" placeholder="0.00" step="0.01"><small class="muted-text">The amount allowed can be up to, but not more than, $41 per night. The exceptions to this are Oxford ($46), Southhaven ($51), and Starkville ($46).</small></div></div>';
      html += '<input type="hidden" name="instate_amount-' + i + '" id="instate_amountHidden-' + i + '" value="0.00" step="0.01">';
      html += '<hr>';
      $("#instate_perdiem_div").append(html);
    }
  });

  $("#outstate_number").on("blur", function() {
    var number = $(this).val();
    $("#outstate_perdiem_div").empty();
    for (var i = 1; i <= number; i++) {
      html = '<h3>Night #' + i + '</h3>';
      html += '<div class="form-group row"><label for="breakfast-' + i + '" class="col-sm-3 col-form-label">Breakfast</label><div class="col-sm-9"><input type="number" name="outstate_breakfast-' + i + '" class="form-control perdiem perdiem-outstate-' + i +'" id="outstate_breakfast-' + i + '" placeholder="0.00" step="0.01"></div></div>';
      html += '<div class="form-group row"><label for="lunch-' + i + '" class="col-sm-3 col-form-label">Lunch</label><div class="col-sm-9"><input type="number" name="outstate_lunch-' + i + '" class="form-control perdiem perdiem-outstate-' + i +'" id="outstate_lunch-' + i + '" placeholder="0.00" step="0.01"></div></div>';
      html += '<div class="form-group row"><label for="dinner-' + i + '" class="col-sm-3 col-form-label">Dinner</label><div class="col-sm-9"><input type="number" name="outstate_dinner-' + i + '" class="form-control perdiem perdiem-outstate-' + i +'" id="outstate_dinner-' + i + '" placeholder="0.00" step="0.01"></div></div>';
      html += '<div class="form-group row"><label for="amount-' + i + '" class="col-sm-3 col-form-label">Amount Allowed</label><div class="col-sm-9"><input type="number" disabled class="form-control outstate_amounts" id="outstate_amount-' + i + '" placeholder="0.00" step="0.01"><small class="muted-text">The amount allowed can be up to, but not more than, $41 per night.</small></div></div>';
      html += '<input type="hidden" name="amount-' + i + '" id="instate_amountHidden-' + i + '" value="0.00" step="0.01">';
      html += '<hr>';
      $("#outstate_perdiem_div").append(html);
    }
  });

  var fileNum = 1;
  $("#addFile").click(function() {
    fileNum++;
    var html = '<div class="form-group row">';
    html += '<div class="col-md-5">';
    html += '<div class="custom-file">';
    html += '<input type="file" class="custom-file-input" id="file-' +  fileNum + '" name="file-' +  fileNum + '">';
    html += '<label class="custom-file-label" for="file-' +  fileNum + '">Choose...</label>';
    html += '</div></div></div>';
    $("#filesDiv").append(html);
    $("#fileNum").val(fileNum);
  });

});

$(document).on("change", "input[type='file']", function() {
    var fieldVal = $(this).val();
    fieldVal = fieldVal.substr(12);
    if (fieldVal != undefined || fieldVal != "") {
        $(this).next(".custom-file-label").text(fieldVal);
    }
});

$(document).on("blur", ".perdiem", function() {
  var value = parseFloat($(this).val());
  value = value.toFixed(2);
  $(this).val(value);
  var type = $(this).attr("id").split("_")[0];
  var i = $(this).attr("id").split("-")[1];
  var total = 0;
  $(".perdiem-" + type + '-' + i).each(function() {
    if ($(this).val() !== '') {
      total += parseFloat($(this).val());
    }
  });
  if (total > 51) {
    total = 51;
  }
  total = total.toFixed(2);
  $("#" + type + "_amount-" + i).val(total);
  $("#" + type + "_amountHidden-" + i).val(total);
  //
  var total = 0;
  $("." + type + "_amounts").each(function() {
    if ($(this).val() !== '') {
      total += parseFloat($(this).val());
    }
  });
  total = total.toFixed(2);
  $("#" + type + "_perdiem").val(total);
  $("#" + type + "_perdiem_hidden").val(total);
  sumTotals(type);
});

$(document).on("blur", ".monetary", function() {
  var value = parseFloat($(this).val());
  value = value.toFixed(2);
  $(this).val(value);
  var type = $(this).attr("id").split("_")[0];
  sumTotals(type);
});

function sumTotals(type) {
  var subtotal = 0;
  $("." + type).each(function() {
    if ($(this).val() !== '') {
      subtotal += parseFloat($(this).val());
    }
  });
  subtotal = subtotal.toFixed(2);
  $("#" + type + "_total").val(subtotal);
  var total = parseFloat($("#instate_total").val()) + parseFloat($("#outstate_total").val());
  total = total.toFixed(2);
  $("#total").val(total);
}
