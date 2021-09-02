$(document).ready(function () {
    $('#data_table').DataTable({
        paging: false,
        info: false,
        order: [[ 1, "desc" ], [ 5, "asc" ], [ 0, "asc" ]]
    });

    $('#checkDisabledAccounts').click((elem) => {
        if ($(elem.target).is(":checked")) {
            window.location.href = "overview.php?showDisabled";
        } else {
            window.location.href = "overview.php";
        }
    });
});
