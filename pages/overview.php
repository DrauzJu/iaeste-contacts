<?php
require_once "../include/session.php";
require_once "../include/html.php";
require_once "../include/db.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

print_head(array("../css/main.css", "../css/menu.css"), "IAESTE CRM - Home", "data_table");

$selectCondition = isset($_GET['showDisabled']) ? '' : 'WHERE disabled=0';

$db = getDB();
$data = $db->query("SELECT * FROM student_status ".$selectCondition." ORDER BY outYear DESC, status, name ");
if ($data == FALSE) {
    die("Error occured during data fetch from DB");
}
?>

<h1 class="center">Overview</h1>
<br>

<div style="margin-bottom: 30px;">
    <input type="checkbox" id="checkDisabledAccounts" name="scales" <?php if(isset($_GET['showDisabled'])) { ?> checked <?php } ?>>
    <label for="checkDisabledAccounts">Show also disabled accounts</label>
</div>

<table id="data_table" class="stripe hover cell-border" style="width:100%; margin-bottom: 10vh;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Year</th>
            <th>Mail</th>
            <th>Studies</th>
            <th>EP Status</th>
            <th>Status</th>
            <th>Last Changed</th>
            <th>Comments</th>
        </tr>
    </thead>
    <tbody>
        
<?php
while ($row = $data->fetch_assoc()) {
?>
        <tr>
            <td>
                <a href="edit.php?id=<?php echo htmlspecialchars($row['id'])?>"><?php echo htmlspecialchars($row['name'])?></a>
            </td>
            <td class="dt-center" ><?php echo htmlspecialchars($row['outYear'])?></td>
            <td class="dt-center" ><?php echo htmlspecialchars($row['email'])?></td>
            <td class="dt-center" ><?php echo htmlspecialchars($row['studies'])?></td>
            <td class="dt-center" <?php if($row['EP_Status'] == "None") echo "style=\"color: red;\"" ?>>
                <?php echo htmlspecialchars($row['EP_Status'])?>
            </td>
            <td class="dt-center" ><?php echo htmlspecialchars($row['Status'])?></td>
            <td class="dt-center" ><?php echo $row['last_update']?></td>
            <td><?php echo htmlspecialchars($row['comment'])?></td>
        </tr>
<?php
}
?>

    </tbody>
</table>

<div class="menu">
    <a href="edit.php">
        <div>
            New student
        </div>
    </a>
    <a href="application.php">
        <div>
            Application
        </div>
    </a>
    <a href="settings.php?tab=General">
        <div>
            Settings
        </div>
    </a>
    <a href="../actions/logout.php">
        <div>
            Logout
        </div>
    </a>
</div>

<script src="../js/overview.js"></script>

<?php
print_tail();
?>
