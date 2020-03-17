<?php
require_once "../include/session.php";
require_once "../include/html.php";
require_once "../include/db.php";
require_once "../include/config.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

print_head(array("../css/main.css", "../css/overview.css"));
$db = getDB();
$data = $db->query("SELECT * FROM student_status ORDER BY outYear DESC");
if ($data == FALSE) {
    die("Error occured during data fetch from DB");
}

global $IAESTE_LOGO;
?>

<body>
<img class="logo" src="<?php echo $IAESTE_LOGO; ?>"/>

<h1>Overview</h1>
<br>
<a href="edit.php">Create new</a>

<table class="blueTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Year</th>
            <th>Mail</th>
            <th>Studies</th>
            <th>EP Status</th>
            <th>Status</th>
            <th>Comments</th>
        </tr>
    </thead>
    <tbody>
        
<?php
while ($row = $data->fetch_assoc()) {
?>
        <tr>
            <td>
                <a href="edit.php?id=<?php echo $row['id']?>"><?php echo $row['name']?></a>
            </td>
            <td><?php echo $row['outYear']?></td>
            <td><?php echo $row['email']?></td>
            <td><?php echo $row['studies']?></td>
            <td <?php if($row['EP_Status'] == "None") echo "style=\"color: red;\"" ?>>
                <?php echo $row['EP_Status']?>
            </td>
            <td><?php echo $row['Status']?></td>
            <td><?php echo $row['comment']?></td>
        </tr>
<?php
}
?>

    </tbody>
</table>
</body>

<?php
print_tail();
?>
