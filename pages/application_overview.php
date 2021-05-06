<?php
require_once "../include/session.php";
require_once "../include/html.php";
require_once "../include/db.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

print_head(array("../css/main.css", "../css/table.css", "../css/menu.css", "../css/application.css", "../css/editForm.css"),
    "IAESTE CRM - Application Overview");
$db = getDB();
$currentYear = (int) date("Y");
$_GET["year"] = isset($_GET["year"]) ? $_GET["year"] : $currentYear;

$stmt = $db->prepare("SELECT student.name, AVG(perUserScore) as result FROM (
    SELECT score.student, SUM(value * weight)/100 as perUserScore
    FROM score
    JOIN criteria c on score.criteriaid = c.id
    WHERE c.year=?
    GROUP BY score.student, score.user
) as preCalc
JOIN student ON preCalc.student = student.id
GROUP BY student
ORDER BY result DESC ;");
$stmt->bind_param("i", $_GET["year"]);
$stmt->execute();

if(!empty($db->error)) {
    die($db->error);
}

$res = $stmt->get_result();

?>

<h1 class="center">Application Manager - Overview</h1>
<br>

<form  class="center paramForm" method="get" action="application_overview.php">
    <label class="field-style" style="margin-right: 0;">Year:
        <select id="year-select" class="field-style" name="year">
            <?php
            for($i=-2; $i<3; $i++) {
                ?>
                <option value="<?php echo ($currentYear + $i);?>" <?php if($currentYear + $i == $_GET["year"]) echo "selected"?>>
                    <?php echo ($currentYear + $i);?>
                </option>
                <?php
            }
            ?>
        </select>
    </label>
</form>

<?php
if(isset($res)) {
?>
<table class="blueTable center" style="margin-bottom: 8vh;">
    <thead>
    <tr>
        <th>Student</th>
        <th>Result</th>
    </tr>
    </thead>
    <tbody>
<?php
    while($data = $res->fetch_assoc()) {
?>
        <tr>
            <td><?php echo htmlspecialchars($data['name'])?></td>
            <td><?php echo htmlspecialchars($data['result'])?></td>
        </tr>
<?php
    }
?>

    </tbody>
</table>
<?php
}
?>

<div class="menu">
    <div>
        <a href="application.php">Back</a>
    </div>
</div>

<script src="../js/jquery.slim.js"></script>
<script src="../js/application.js"></script>

<?php
print_tail();
?>
