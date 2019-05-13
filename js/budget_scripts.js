$(document).on("click", "#budgetTbl tbody tr", function() {
  var line = $(this).prop("id").substr(5);
  $.getJSON("service.php?action=getLineForms&line=" + line, function(json) {
    $("#lineTbl tbody").empty();
    $.each(json.codes, function() {
      var html = '<tr>';
      html += '<td>' + this.vendor + '</td>';
      html += '<td>' + this.date + '</td>';
      html += '<td>' + this.form_id + '</td>';
      html += '<td>' + this.description + '</td>';
      html += '<td>$' + this.amount + '</td>';
      html += '</tr>';
      $("#lineTbl tbody").append(html);
    });
    $("#purchase_code").html(json.purchase_code);
    $("#modal1").modal('show');
  });

  /*
  $("#req_div").load('forms/approve/requisition.php?formId=' + form_id);
  $("#req_form_div").load('forms/approve/reqpo.php?formId=' + form_id);
  $("#poModal").modal('show');
  if ($(window).width() < 768) {
    if (formType == 'Out of Town Travel') {
      formType = 'Out of Town';
    }
    window.location.href = 'approveform.php?formId=' + form_id + '&type=' + formType.replace(/ /g, '');
  }
  */
});
