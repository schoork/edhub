$(document).ready(function() {

    $("input[name='type']").on("change", function() {
        var value = $(this).val();
        if (value == 'Bereavement' || value == 'Emergency Leave') {
            $("#relativeDiv").show();
        }
        else {
            $("#relativeDiv").hide();
        }
    });

    $("#number").on("blur", function() {
        var value = $(this).val();
        if (value == 1) {
            $("#halfDayDiv").show();
        }
        else {
            $("#halfDayDiv").hide();
        }
    });

});
