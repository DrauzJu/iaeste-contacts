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

print_head(array("css/main.css", "css/login.css"), "IAESTE CRM - Login");
?>

<div class="loginMain">
    <p class="loginText">Sign in</p>
    <img src="<?php echo $logo; ?>"/>
    <form id="login-form" class="loginForm" action="actions/login.php" method="post">
        <input name="user" class="loginInput" type="text" placeholder="Username"/>
        <input name="pw" class="loginInput" type="password" placeholder="Password"/>
    </form>
    <button type="submit" class="loginSubmit" form="login-form">
        Sign in
    </button>
</div>

<?php
print_tail();
?>
