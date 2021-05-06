$(document).ready(function () {
    $('#year-select').change((elem) => {
        elem.target.form.submit();
    });
});
