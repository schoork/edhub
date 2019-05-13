$(document).ready(function() {

  $("#btnReturn").click(function() {
    $("#modal-alert").removeClass('alert-danger');
    $("#modal-alert").removeClass('alert-success');
    $("#modal-alert").empty();
    if ($("#denyReason").val() === '') {
      var html = '<strong>Oh snap!</strong> Please provide a reason for denying this form.';
      $("#modal-alert").html(html);
      $("#modal-alert").addClass('alert-danger');
    }
    else {
      $("#approve_deny").val('deny');
      submitForm();
    }
  });

  $("#btnSubmitPO").click(function() {
    var formId = $("#formId").val();
    var data = $("#poForm :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#po-alert").addClass("alert-danger");
        $("#po-alert").removeClass("alert-info");
        $("#po-alert").html("<strong>Oh snap!</strong> The form didn't delete properly. Try again.");
        console.log(json.message);
      } else if (json.status == 'success') {
        $("#po-alert").addClass("alert-success");
        $("#po-alert").removeClass("alert-info");
        $("#po-alert").html("<strong>Well done!</strong> Your form has been deleted.");
        $("#btnDelete").addClass("disbaled");
        $("#form-" + formId).remove();
      }
    }, "json");
  });

  $("#btnApprove").click(function() {
    $("#modal-alert").removeClass('alert-danger');
    $("#modal-alert").removeClass('alert-success');
    $("#modal-alert").empty();
    $("#approve_deny").val('approve');
    submitForm();
  });

  $("#btnOveride").click(function() {
    $("#modal-alert").removeClass('alert-danger');
    $("#modal-alert").removeClass('alert-success');
    $("#modal-alert").empty();
    $("#approve_deny").val('override');
    submitForm();
  });

  $("#btnInactive").click(function() {
    $(".hidden").toggle();
  });

});

$(document).on("click", "#tblApprove tbody tr", function() {
  $("#modal-alert").removeClass("alert-success");
  $("#modal-alert").removeClass("alert-info");
  $("#modal-alert").removeClass("alert-danger");
  $("#modal-alert").empty();
  $("#denyReason").val('');
  var status = $(':nth-child(7)', this).html();
  if (status == '') {
    var id = $(this).attr("id").split("-");
    var form_id = id[1];
    var formType = $(':nth-child(2)', this).html().toLowerCase().replace(/ /g, '');
    window.open('forms/prints/' + formType + '.php?formId=' + form_id, '_blank');
  }
  else if (status == 'PO Needed') {
    var id = $(this).attr("id").split("-");
    var form_id = id[1];
    $("#poFormId").val(form_id);
    $("#req_div").load('forms/approve/requisition.php?formId=' + form_id);
    $("#req_form_div").load('forms/approve/reqpo.php?formId=' + form_id);
    $("#poModal").modal('show');
  }
  else {
    var id = $(this).attr("id").split("-");
    var form_id = id[1];
    var formType = $(':nth-child(2)', this).html();
    if ($(window).width() < 768) {
      if (formType == 'Out of Town Travel') {
        formType = 'Out of Town';
      }
      window.location.href = 'approveform.php?formId=' + form_id + '&type=' + formType.replace(/ /g, '');
    }
    else {
      $("#formId").val(form_id);
      switch (formType) {
        case 'Bus Request':
          $("#specificDiv").load('forms/approve/busrequest.php?formId=' + form_id);
          break;
        case 'Requisition':
          $("#specificDiv").load('forms/approve/requisition.php?formId=' + form_id);
          break;
        case 'Reimbursement':
          $("#specificDiv").load('forms/approve/reimbursement.php?formId=' + form_id);
          break;
        case 'Out of Town Travel':
          $("#specificDiv").load('forms/approve/outoftown.php?formId=' + form_id);
          break;
        case 'Time Off Request':
          $("#specificDiv").load('forms/approve/timeoff.php?formId=' + form_id);
          break;
        case 'Activity':
          $("#specificDiv").load('forms/approve/activity.php?formId=' + form_id);
          break;
        case 'Deposit':
          $("#specificDiv").load('forms/approve/deposit.php?formId=' + form_id);
          break;
        case 'Fundraiser':
          $("#specificDiv").load('forms/approve/fundraiser.php?formId=' + form_id);
          break;
      }
      $("#formModal").modal('show');
    }
  }
});

$(document).on('click', '#viewApproved', function() {
  $(".hidden").toggle();
});

$(document).on('click', '#viewDenied', function() {
  $(".denied").toggle();
});

$(document).on('click', '#btnDelete', function() {
  var formId = $("#delFormId");
  var data = $("#deleteForm :input").serializeArray();
  $.post('service.php', data, function(json) {
    if (json.status == 'fail') {
      $("#modal-alert").addClass("alert-danger");
      $("#modal-alert").removeClass("alert-info");
      $("#modal-alert").html("<strong>Oh snap!</strong> The form didn't delete properly. Try again.");
      console.log(json.message);
    } else if (json.status == 'success') {
      $("#modal-alert").addClass("alert-success");
      $("#modal-alert").removeClass("alert-info");
      $("#modal-alert").html("<strong>Well done!</strong> Your form has been deleted.");
      $("#btnDelete").addClass("disbaled");
      $("#form-" + formId).remove();
      $("#denyReason").val('');
    }
  }, "json");
});

$(document).on("click", "#tblSubmit tbody tr", function() {
  //$(".modalDivs").hide();
  var status = $(':nth-child(6)', this).html();
  var id = $(this).attr("id").split("-");
  var form_id = id[1];
  var formType = $(':nth-child(2)', this).html().toLowerCase().replace(/\s/g, "");
  console.log(formType);
  if (status == 'Approved') {
    window.open('forms/prints/' + formType + '.php?formId=' + form_id, '_blank');
  }
  else if (status != 'Approved') {
    var id = $(this).attr("id").split("-");
    var form_id = id[1];
    $("#delFormId").val(form_id);
    var formType = $(':nth-child(2)', this).html();
    switch (formType) {
      case 'Bus Request':
        $("#deniedDiv").load('forms/approve/busrequest.php?formId=' + form_id);
        break;
      case 'Requisition':
        $("#deniedDiv").load('forms/approve/requisition.php?formId=' + form_id);
        break;
      case 'Reimbursement':
        $("#deniedDiv").load('forms/approve/reimbursement.php?formId=' + form_id);
        break;
      case 'Out of Town Travel':
        $("#deniedDiv").load('forms/approve/outoftown.php?formId=' + form_id);
        break;
      case 'Time Off Request':
        $("#deniedDiv").load('forms/approve/timeoff.php?formId=' + form_id);
        break;
      case 'Deposit':
        $("#deniedDiv").load('forms/approve/deposit.php?formId=' + form_id);
        break;
      case 'Fundraiser':
        $("#deniedDiv").load('forms/approve/fundraiser.php?formId=' + form_id);
        break;
    }
    $("#deniedModal").modal('show');
  }
});


function submitForm() {
    $("#modal-alert").addClass('alert-info');
    $("#modal-alert").html('<strong>Please wait! </strong>Currently submitting...');
    $("button").attr('disabled', true);
  var formId = $("#formId").val();
  var data = $("#formApproval :input").serializeArray();
  $.post('service.php', data, function(json) {
    if (json.status == 'fail') {
      $("#modal-alert").addClass("alert-danger");
      $("#modal-alert").removeClass("alert-info");
      $("#modal-alert").html("<strong>Oh snap!</strong> The information didn't update properly. Try again.");
      console.log(json.message);
    } else if (json.status == 'success') {
        $("button").attr('disabled', false);
      $("#modal-alert").addClass("alert-success");
      $("#modal-alert").removeClass("alert-info");
      $("#modal-alert").html("<strong>Well done!</strong> Your form has been submitted.");
      $("#btnApprove").addClass("disbaled");
      $("#btnReturn").addClass("disbaled");
      $("#form-" + formId).hide();
      $("#form-" + formId).addClass('hidden');
    }
  }, "json");
}

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
