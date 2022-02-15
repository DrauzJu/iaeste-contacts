<?php
require_once "../include/session.php";
require_once "../include/html.php";
require_once "../include/db.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$settings_tab = htmlspecialchars($_GET["tab"]);

print_head(array("../css/main.css", "../css/menu.css", "../css/table.css", "../css/settings.css", "../css/editForm.css"), "IAESTE CRM - Settings");
$db = getDB();
?>

<h1 class="center">Settings - <?php echo $settings_tab; ?></h1>
<br>

<?php
switch ($settings_tab) {
    case "General": include "settings_general.php";
                    break;
    case "User":    include "settings_user.php";
                    break;
}
?>

<div class="menu">
    <a href="overview.php">
        <div>
            Back to Home
        </div>
    </a>

    <a href="settings.php?tab=General.php">
        <div>
            General
        </div>
    </a>

    <a href="settings.php?tab=User.php">
        <div>
            User
        </div>
    </a>
</div>

<?php
print_tail();
?>
