<?php
require_once "include/html.php";
require_once "include/db.php";

print_head(array("css/main.css", "css/editForm.css"), "IAESTE CRM - Setup");
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<h1>Setup</h1>
<br>

During the setup all database tables will be created and populated with default data (if desired).
Additionally, a default user is created.

<form class="editForm" action="setup.php">
    <input type="hidden" name="Confirmed" value="true">
    <ul>
        <li>
            <label class="align-left">
                Insert data for status and EP status
                <input style="margin-left: 10px;" type="checkbox" checked name="insertData" value="true">
            </label>
        </li>
        <li>
            <input class="field-style align-left" type="submit" value="Go!">
        </li>
    </ul>
</form>

<ul style="list-style-type: none" id="list">
</ul>

<?php
if(isset($_GET['Confirmed'])) {
    setupDatabase(isset($_GET['insertData']));
}

print_tail();
?>
