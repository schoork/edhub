$(document).ready(function() {

    $("table").DataTable({
        "order": [[3, 'dsc']]
    });

    $("table tbody tr").click(function() {
        var status = $(':nth-child(7)', this).html();
        var id = $(this).prop('id').substr(9);
        if (status == 'Submitted') {
            window.location.href = 'viewreferral.php?id=' + id;
        }
        else if (status == 'Completed') {
            window.location.href = 'print_referral.php?id=' + id;
        }
    });
});
