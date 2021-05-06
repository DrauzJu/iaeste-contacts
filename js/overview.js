$(document).ready(function () {
    $('#data_table').DataTable({
        paging: false,
        info: false,
        order: [[ 1, "desc" ], [ 5, "asc" ], [ 0, "asc" ]]
    });
});

function change_show_disabled_accounts(elem) {
    if ($(elem).is(":checked")) {
        window.location.href = "overview.php?showDisabled";
    } else {
        window.location.href = "overview.php";
    }
}
