$(document).ready(function() {
  
  
  
});

$(document).on("blur", "#travel_start, #travel_end", function() {
  if ($("#travel_start").val() !== '' && $("#travel_end").val() !== '') {
    var start = new Date($("#travel_start").val());
    var end = new Date($("#travel_end").val());
    var hours = Math.round(Math.abs(end - start) / 36e5 * 10) / 10;
    $("#length").val(hours + ' hours');
    $("#hours").val(hours);
    $("#submit_hours").val(hours);
  }
});

$(document).on("blur", "#driver_num", function() {
  if ($("#driver_num").val() !== '' && $("#hours").val() !== 0) {
    var cost = $("#driver_num").val() * $("#hours").val() * 11;
    var total = 0;
    if (cost !== 0) {
      total = parseInt(cost);
    }
    cost = cost.toFixed(2);
    $("#driver_cost").val(cost);
    
    if ($("#miles_cost").val() != 0) {
      total += parseInt($("#miles_cost").val());
    }
    total = parseInt(total).toFixed(2);
    $("#total").val(total);
  }
});

$(document).on("blur", "#bus_num, #miles", function() {
  if ($("#bus_num").val() !== '' && $("#miles").val() !== 0) {
    var cost = $("#bus_num").val() * $("#miles").val();
    var total = 0;
    if (cost !== 0) {
      total = parseInt(cost);
    }
    cost = cost.toFixed(2);
    $("#miles_cost").val(cost);
    
    if ($("#driver_cost").val() != 0) {
      total += parseInt($("#driver_cost").val());
    }
    total = parseInt(total).toFixed(2);
    $("#total").val(total);
  }
});