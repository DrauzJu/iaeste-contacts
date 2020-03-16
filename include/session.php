<?php
require_once "config.php";

function is_logged_in() {
    global $IAESTE_DOMAIN;

    session_set_cookie_params(300, "/", $IAESTE_DOMAIN, TRUE, TRUE);
    session_start();

    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == "True") {
        return true;
    }

    return false;
}

?>
