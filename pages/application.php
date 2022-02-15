<?php
require_once "../include/session.php";
require_once "../include/html.php";
require_once "../include/db.php";
require_once "../classes/ApplicationData/Score.php";
require_once "../classes/ApplicationData/Criterion.php";
require_once "../classes/ApplicationData/Student.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

print_head(array("../css/main.css", "../css/menu.css", "../css/table.css", "../css/application.css"),
    "IAESTE CRM - Application");
$db = getDB();

$currentYear = (int) date("Y");

/*
 * @return Criterion[]
*/
function getCriteria() {
    global $db;
    $given_year = (int) $_GET["year"];

    $stmt = $db->prepare("SELECT id, name FROM criteria WHERE year=? ORDER BY name");
    $stmt->bind_param("i", $given_year);
    $stmt->execute();
    $res = $stmt->get_result();

    $result = array();
    while($data = $res->fetch_assoc()) {
        array_push($result, new Criterion($data["id"], $data["name"]));
    }

    return $result;
}

/*
 * @return Student[] - associative array: studentID => Student Object
 *
 * Using keys for associative array:
 *  - SXX for studentID (since ID only can't be used as a key since it's a number)
 *  - CXX for criterionID
*/
function getData() {
    global $db;
    $given_year = (int) $_GET["year"];
    $given_user = $_GET["user"];

    $stmt = $db->prepare("SELECT student.name as studentName, student.id as student, 
        c.id AS criterion, c.name as criterionName, s.id, s.value 
        FROM student
        LEFT JOIN (SELECT * FROM score WHERE user=?) s ON student.id = s.student
        LEFT JOIN criteria c ON s.criteriaid = c.id AND c.year = student.outYear
        WHERE student.outYear=? ORDER BY student, c.name");
    $stmt->bind_param("si", $given_user, $given_year);
    $stmt->execute();

    if(!empty($db->error)) {
        die($db->error);
    }

    $res = $stmt->get_result();

    $result = array();
    while($data = $res->fetch_assoc()) {
        if(!array_key_exists("S".$data["student"], $result)) {
            $result["S".$data["student"]] = new Student($data["student"], $data["studentName"]);
        }

        if(isset($data["id"])) {
            $criterion = new Criterion($data["criterion"], $data["criterionName"]);
            $score = new Score($data["id"], $data["student"], $criterion, $data["value"]);

            $result["S".$data["student"]]->addScore($score);
        }
    }

    return $result;
}

?>

<h1 class="center">Application Manager</h1>
<br>

<form class="center paramForm" method="get" action="application.php">
    <select name="year" class="field-style">
    <?php
    for($i=-2; $i<3; $i++) {
        ?>
        <option value="<?php echo ($currentYear + $i);?>"
            <?php if(isset($_GET["year"]) && $_GET["year"] == $currentYear + $i) echo "selected"; else if($i == 0) echo "selected"?>>
            <?php echo ($currentYear + $i);?>
        </option>
        <?php
    }
    ?>
    </select>

    <label class="field-style">Examiner:
        <input class="field-style" type="text" name="user" value="<?php if(isset($_GET["user"])) echo htmlspecialchars($_GET['user']); ?>">
    </label>

    <input class="field-style" type="submit" value="Go">
</form>

<?php
if(isset($_GET["year"]) && isset($_GET["user"])) {
    $criteria = getCriteria();
?>

    <form class="center" method="post" action="../actions/saveScores.php">
        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token'];?>"/>
        <input type="hidden" name="year" value="<?php echo htmlspecialchars($_GET["year"]);?>"/>
        <input type="hidden" name="user" value="<?php echo htmlspecialchars($_GET["user"]);?>"/>
        <table class="blueTable">
            <thead>
            <tr>
                <th>Name</th>
                <?php
                foreach ($criteria as $criterion) {
                ?>
                    <th><?php echo htmlspecialchars($criterion->getName()); ?></th>
                <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach(getData() as $id => $student) {
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($student->getName())?></td>
                    <?php
                    foreach ($criteria as $criterion) {
                        $criterionId = $criterion->getId();
                        $score = null;
                        if(isset($student->getScores()["C".$criterionId])) {
                            $score = $student->getScores()["C".$criterionId];
                        }

                        $name = $score ? "Upd-".$score->getId() : "Ins-".$id."-"."$criterionId";
                        $value = $score ? $score->getValue(): "";
                    ?>
                        <td>
                            <input type="number" name="<?php echo htmlspecialchars($name); ?>"
                                   value="<?php echo htmlspecialchars($value); ?>">
                        </td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
            }
            ?>

            </tbody>
        </table>

        <input type="submit" value="Save" class="save">
    </form>

<?php
}
?>

<div class="menu">
    <a href="overview.php">
        <div>
            Back to Home
        </div>
    </a>

    <a href="application_overview.php">
        <div>
            Overview
        </div>
    </a>

    <a href="application_criteria.php">
        <div>
            Set criteria
        </div>
    </a>
</div>

<?php
print_tail();
?>
