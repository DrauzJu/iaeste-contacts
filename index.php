<?php
require_once "include/session.php";
require_once "include/html.php";
require_once "include/db.php";

if(is_logged_in()) {
    header("Location: pages/overview.php");
    exit();
}

$db = getDB();
if($db) {
    $logo = getOption($db, "logo");
} else {
    $logo = "";
}

print_head(array("css/main.css", "css/login.css"));
?>

<div class="loginMain">
    <p class="loginText" align="center">Sign in</p>
    <img src="<?php echo $logo; ?>"/>
    <form style="padding-top: 40px;" action="actions/login.php" method="post">
        <input name="user" class="loginInput" type="text" align="center" placeholder="Username"/>
        <input name="pw" class="loginInput" type="password" align="center" placeholder="Password"/>
        <input type="submit" class="loginSubmit" align="center" value="Sign in"/>
</div>

<?php
print_tail();
?>
