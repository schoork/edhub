$(document).ready(function() {
	
	$("table tbody tr").click(function() {
		var id = $(this).attr("id").split("-");
	    var form_id = id[1];
	    var formType = $("#form_type").val();
	    window.open('forms/prints/' + formType + '.php?formId=' + form_id, '_blank');
    });
	
});