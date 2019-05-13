$(document).ready(function() {

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

  var itemTotal = 0;
  $("#addCheck").click(function() {
    itemTotal++;
    $("#checkdate").prop("disabled", false);
    var html = '<div class="checkSubDiv"><hr><h3>Check #' + itemTotal + '</h3>';
    html += '<div class="form-group row"><label for="name-' + itemTotal + '" class="col-sm-3 col-form-label">Check Payable To</label><div class="col-sm-9"><input type="text" name="name-' + itemTotal + '" class="form-control" id="name-' + itemTotal + '"><small class="muted-text text-danger required">Required</small><br/><small class="muted-text">If the check is to you, type your full name.</small></div></div>';
    html += '<div class="form-group row"><label for="address-' + itemTotal + '" class="col-sm-3 col-form-label">Address</label><div class="col-sm-9"><input type="text" name="address-' + itemTotal + '" class="form-control" id="address-' + itemTotal + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="city-' + itemTotal + '" class="col-sm-3 col-form-label">City, ST</label><div class="col-sm-9"><input type="text" name="city-' + itemTotal + '" class="form-control" id="city-' + itemTotal + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="zipcode-' + itemTotal + '" class="col-sm-3 col-form-label">Zipcode</label><div class="col-sm-9"><input type="number" name="zipcode-' + itemTotal + '" class="form-control" id="zipcode-' + itemTotal + '" maxlength="5"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="reason-' + itemTotal + '" class="col-sm-3 col-form-label">Reason</label><div class="col-sm-9"><select name="reason-' + itemTotal + '" class="form-control" id="method"><option disabled selected></option><option value="Advance Travel">Advance Travel (miles, per diem, etc.)</option><option value="Fees and Dues">Conference Fees and Dues</option><option value="Hotel">Hotel</option><option value="Other">Other</option></select><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="amount-' + itemTotal + '" class="col-sm-3 col-form-label">Check Amount</label><div class="col-sm-9"><input type="number" name="amount-' + itemTotal + '" class="form-control monetary" id="amount-' + itemTotal + '" placeholder="0.00" step="0.01"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="purchase_code-' + itemTotal + '" class="col-sm-3 col-form-label">Purchasing Code</label><div class="col-sm-9"><input type="text" class="form-control purchase_code" name="purchase_code-' + itemTotal + '" id="purchase_code-' + itemTotal + '" maxlength="30"><small class="muted-text text-danger required">Required</small></div></div></div>';
    $("#checkDiv").append(html);
    $("#removeCheck").removeClass("disabled");
    $("#itemTotal").val(itemTotal);
  });

  $("#removeCheck").click(function() {
    if (itemTotal > 0) {
      $("#checkDiv").children(".checkSubDiv").last().remove();
      itemTotal--;
    }
    if (itemTotal == 0) {
      $(this).addClass("disabled");
      $("#checkdate").prop("disabled", true);
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

$(document).on("blur", ".quantity, .cost", function() {
  var id = $(this).attr("id").split("-")[1];
  var product;
  if ($.isNumeric($("#quantity-" + id).val()) && $.isNumeric($("#unitCost-" + id).val())) {
    product = $("#quantity-" + id).val() * $("#unitCost-" + id).val();
    product = product.toFixed(2);
    $("#price-" + id).val(product);
  }
  else {
    $("#price-" + id).val('');
  }
  var total = 0;
  var itemTotal = $("#itemTotal").val();
  $(".price").each(function() {
    total = (total) + parseFloat($(this).val());
  });
  total = total.toFixed(2);
  $("#totalCost").val(total);

});

$(document).on("keyup", ".purchase_code",  function(event) {
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
    var splitArray = [4, 3, 4, 3, 3, 3, 4];
    var split = 0;
    var chunk = [];

    for (var i = 0, len = input.length; i < len; i+= split) {
      if ((i > 0 && i < 7) || (i >= 11 && i < 20)) {
        split = 3;
      }
      else {
        split = 4;
      }
      chunk.push( input.substr( i, split ) );
    }

    // puts chunks separated by dashes in input
    $this.val(function() {
       return chunk.join("-");
    });

  });
