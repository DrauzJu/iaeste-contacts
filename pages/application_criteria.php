<?php
require_once "../include/session.php";
require_once "../include/html.php";
require_once "../include/db.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

print_head(array("../css/main.css", "../css/menu.css", "../css/table.css", "../css/application.css", "../css/editForm.css"),
    "IAESTE CRM - Application Criteria");
$db = getDB();

$currentYear = (int) date("Y");
$year = isset($_GET["year"]) ? $_GET["year"] : $currentYear;

$stmt = $db->prepare("SELECT id, name, weight FROM criteria WHERE year=?");
$stmt->bind_param("i", $year);
$stmt->execute();

if(!empty($db->error)) {
    die($db->error);
}

$res = $stmt->get_result();

?>

<h1 class="center">Application Manager - Criteria</h1>
<br>

<form  class="center paramForm" method="get" action="application_criteria.php">
    <label class="field-style" style="margin-right: 0;">Year:
        <select id="year-select" class="field-style" name="year">
            <?php
            for($i=-2; $i<3; $i++) {
                ?>
                <option value="<?php echo ($currentYear + $i);?>" <?php if($currentYear + $i == $year) echo "selected"?>>
                    <?php echo ($currentYear + $i);?>
                </option>
                <?php
            }
            ?>
        </select>
    </label>
</form>

<form id="mainForm" action="../actions/saveCriteria.php" method="post"></form>

<input form="mainForm" type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token'];?>"/>
<input form="mainForm" type="hidden" name="year" value="<?php echo htmlspecialchars($year);?>"/>
<table class="blueTable center">
    <thead>
    <tr>
        <th>Criterion</th>
        <th>Weight (sum must be 100)</th>
    </tr>
    </thead>
    <tbody>

    <?php
    while ($row = $res->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name'])?></td>
            <td><?php echo htmlspecialchars($row['weight'])?></td>
            <td>
                <form action="../actions/deleteCriterion.php" method="post" class="no-margin-end">
                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token'];?>"/>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']);?>"/>
                    <input type="hidden" name="year" value="<?php echo htmlspecialchars($year);?>"/>
                    <input type="submit" value="Delete"/>
                </form>
            </td>
        </tr>
        <?php
    }
    ?>

    <tr>
        <td>
            <input form="mainForm" type="text" name="name" value="" placeholder="new criterion"/>
        </td>
        <td>
            <input form="mainForm" type="number" name="weight" value="" placeholder="weight"/>
        </td>
    </tr>

    </tbody>
</table>

<input  class="center" form="mainForm" type="submit" value="Save" style="display:block; margin-top: 10px"/>

<div class="menu">
    <a href="application.php">
        <div>
            Back
        </div>
    </a>
</div>

<script src="../js/jquery.slim.js"></script>
<script src="../js/application.js"></script>

<?php
print_tail();
?>
