<?php
require_once "../include/config.php";
global $IAESTE_DOMAIN, $IAESTE_USER, $IAESTE_PW;

if(isset($_POST["user"]) && $_POST["user"] == $IAESTE_USER &&
    isset($_POST["pw"]) && $_POST["pw"] == $IAESTE_PW) {
        // init session
        session_set_cookie_params(300, "/", $IAESTE_DOMAIN, isset($_SERVER["HTTPS"]), TRUE);
        session_start();
        $_SESSION['loggedIn'] = "True";

        header("Location: ../pages/overview.php");
        exit();
} else {
    echo "Invalid!";
}

?>
