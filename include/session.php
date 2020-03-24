<?php
function is_logged_in() {

    session_set_cookie_params(300, "/", $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), TRUE);
    session_start();

    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == "True") {
        return true;
    }

    return false;
}

function check_csrf_token($given_token) {
    return $given_token === $_SESSION['csrf_token'];
}

?>
