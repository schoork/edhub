$(document).ready(function() {
  
  
});

$(document).on("blur", ".quantity, .cost", function() {
  var id = $(this).attr("id").split("-")[1];
  var product;
  if ($("#unitCost-" + id).val() !== '') {
    var value = parseFloat($("#unitCost-" + id).val());
    value = value.toFixed(2);
    $("#unitCost-" + id).val(value);
  }
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
  $("#total").val(total);
  
  getProfit();
  
});

$(document).on("blur", ".monetary", function() {
  var value = parseFloat($(this).val());
  value = value.toFixed(2);
  $(this).val(value);
  
  getProfit();
});

function getProfit() {
  var total = 0;
  if ($("#total").val() !== '') {
    total = parseFloat($("#total").val());
  }
  var revenue = parseFloat($("#revenue").val());
  if (total > 0) {
    $("#purchase_code_input").show();
  }
  else {
    $("#purchase_code_input").hide();
  }
  var profit = revenue - total;
  profit = profit.toFixed(2);
  $("#profit").val(profit);
  if (profit <= 0) {
    $("#profit-alert").show();
  }
  else {
    $("#profit-alert").hide();
  }
}