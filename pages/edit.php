<?php
require_once "../include/db.php";
require_once "../include/session.php";
require_once "../include/html.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

print_head(array("../css/main.css", "../css/editForm.css"), "IAESTE CRM - Edit Student");
$db = getDB();

if(isset($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM student WHERE id=?");
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
} else {
    $data = array(
        "id"=>"",
        "name"=>"",
        "outYear"=>"",
        "email"=>"",
        "studies"=>"",
        "comment"=>"",
        "epstatus"=>"",
        "status"=>""
    );
}

$epstatus = $db->query("SELECT * FROM epstatus");
$status = $db->query("SELECT * FROM status");
?>

<h1 class="center">Edit/New</h1>
<br>

<form class="editForm" action="../actions/save.php" method="post">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token'];?>"/>
    <input type="hidden" name="id" value="<?php echo $data["id"]?>"/>
    <input type="hidden" name="mode" value="<?php if(isset($_GET["id"])) {
            echo "update";
        } else {
            echo "new";
        }?>"/>
    <ul>
        <li>
            <input type="text" name="name" class="field-style field-split align-left" 
                    placeholder="Name" value="<?php echo $data["name"]?>" />
            <input type="text" name="email" class="field-style field-split align-right" 
                    placeholder="Email" value="<?php echo $data["email"]?>" />
        </li>
        <li>
            <input type="number" name="outYear" class="field-style field-split align-left" 
                    placeholder="Outgoing Year" value="<?php echo $data["outYear"]?>" />
            <input type="text" name="studies" class="field-style field-split align-right" 
                    placeholder="Studies" value="<?php echo $data["studies"]?>" />
        </li>
        <li>
            <label class="field-split align-left">EP Status</label>
            <select name="epstatus" class="field-select field-style field-split align-right">
<?php
while ($row = $epstatus->fetch_assoc()) {
?>
                <option value="<?php echo $row["id"];?>" <?php if($data["epstatus"] == $row["id"]) echo "selected"?>>
                    <?php echo $row["name"];?>
                </option>
<?php
}
?>                
            </select>
        </li>
        <li>
            <label class="field-split align-left">Status</label>
            <select name="status" class="field-select field-style field-split align-right">
<?php
while ($row = $status->fetch_assoc()) {
?>
                <option value="<?php echo $row["id"];?>" <?php if($data["status"] == $row["id"]) echo "selected"?>>
                    <?php echo $row["name"];?>
                </option>
<?php
}
?>                
            </select>
        </li>
        <li>
            <textarea name="comment" class="field-style" placeholder="Comment"><?php echo $data["comment"]?></textarea>
        </li>
        <li>
            <input type="submit" value="Save" class="align-left" />
            <?php if(isset($_GET["id"])) {?>
                <input type="submit" style="margin-left: 5px" value="Delete" formaction="../actions/delete.php">
            <?php } ?>
            <input type="submit" onclick="window.location='overview.php'; return false;" value="Cancel" class="align-right" />
        </li>
    </ul>
</form>

<?php 
print_tail();
?>
