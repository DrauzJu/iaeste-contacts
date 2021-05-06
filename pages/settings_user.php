<?php

if(!isset($db)) {
    // Script is called directly --> exit
    die();
}

if(!$_SESSION["admin"]) {
    die("Not sufficient privileges");
}

$data = $db->query("SELECT id, name, admin FROM user ORDER BY admin DESC , name");
if ($data == FALSE) {
    die("Error occured during data fetch from DB");
}

?>

<form class="center" autocomplete="off" action="../actions/saveUser.php" method="post">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token'];?>"/>
    <table class="blueTable">
        <thead>
        <tr>
            <th>User</th>
            <th>New Password</th>
        </tr>
        </thead>
        <tbody>

        <?php
        while ($row = $data->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); if($row['admin'] == 1) echo ' (Admin)';?></td>
                <td>
                    <input type="password" name="pw-<?php echo htmlspecialchars($row['id'])?>" value="" autocomplete="new-password"/>
                </td>
            </tr>
            <?php
        }
        ?>

        <tr>
            <td>
                <input type="text" name="newUser" value="" placeholder="new User" autocomplete="new-password"/>
            </td>
            <td>
                <input type="password" name="newPw" value="" autocomplete="new-password"/>
            </td>
        </tr>

        </tbody>
    </table>

    <input type="submit" value="Save" style="margin-top: 10px"/>
</form>
