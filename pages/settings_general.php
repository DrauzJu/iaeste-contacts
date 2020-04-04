<?php

if(!isset($db)) {
    // Script is called directly --> exit
    die();
}

$logo = getOption($db, "logo");
?>

<form class="editForm" action="../actions/saveSettings.php" method="post">
    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token'];?>"/>
    <ul>
        <li>
            <label class="field-split align-left" style="padding: 8px; width: auto;">Logo URL/Path</label>
            <input type="text" name="logo" class="field-style field-split align-right"
                   value="<?php echo $logo;?>" />
        </li>
        <li class="center" style="margin-top: 30px;">
            <input class="align-left" type="submit" value="Save">
        </li>
    </ul>
</form>
