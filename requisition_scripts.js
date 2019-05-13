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

  var itemTotal = 1;
  $("#addItem").click(function() {
    itemTotal++;
    var html = '<div class="reqSubdiv"><hr><h3>Item #' + itemTotal + '</h3>';
    html += '<div class="form-group row"><label for="part-' + itemTotal + '" class="col-sm-3 col-form-label">Part Number</label><div class="col-sm-9"><input type="text" name="part-' + itemTotal + '" class="form-control" id="part-' + itemTotal + '"></div></div>';
    html += '<div class="form-group row"><label for="description-' + itemTotal + '" class="col-sm-3 col-form-label">Description</label><div class="col-sm-9"><input type="text" name="description-' + itemTotal + '" class="form-control" id="description-' + itemTotal + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="quantity-' + itemTotal + '" class="col-sm-3 col-form-label">Quantity</label><div class="col-sm-9"><input type="number" name="quantity-' + itemTotal + '" class="form-control quantity" id="quantity-' + itemTotal + '"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="unitCost-' + itemTotal + '" class="col-sm-3 col-form-label">Unit Cost</label><div class="col-sm-9"><input type="number" name="unitCost-' + itemTotal + '" class="form-control cost" id="unitCost-' + itemTotal + '"  step="0.0001"><small class="muted-text text-danger required">Required</small></div></div>';
    html += '<div class="form-group row"><label for="price-' + itemTotal + '" class="col-sm-3 col-form-label">Estimated Price</label><div class="col-sm-9"><input type="number" name="price-' + itemTotal + '" class="form-control price" disabled id="price-' + itemTotal + '"></div></div><div class="form-group row"><label for="purchase_code' + itemTotal + '" class="col-sm-3 col-form-label">Purchasing Code</label><div class="col-sm-9"><input type="text" class="form-control purchase_code" name="purchase_code-' + itemTotal + '" id="purchase_code-' + itemTotal + '" maxlength="30"><small class="muted-text text-danger required">Required</small></div></div></div>';
    $("#reqDiv").append(html);
    $("#removeItem").removeClass("disabled");
    $("#itemTotal").val(itemTotal);
  });

  $("#removeItem").click(function() {
    if (itemTotal > 1) {
      $("#reqDiv").children(".reqSubdiv").last().remove();
      itemTotal--;
    }
    if (itemTotal >= 7) {
      $("#addItem").addClass("disabled");
    }
    else {
      $("#addItem").removeClass("disabled");
    }
    if (itemTotal == 1) {
      $(this).addClass("disabled");
    }
  });

});

$(document).on("change", "input[type='file']", function() {
    var fieldVal = $(this).val();
    fieldVal = fieldVal.substr(12);
    if (fieldVal != undefined || fieldVal != "") {
        fieldVal = fieldVal.substr(0);
        $(this).next(".custom-file-label").text(fieldVal);
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
