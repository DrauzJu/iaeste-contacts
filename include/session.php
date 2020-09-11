<?php
function is_logged_in() {

    $domain = strpos($_SERVER['HTTP_HOST'], ':') ? 
            strtok($_SERVER['HTTP_HOST'], ':') : 
            $_SERVER['HTTP_HOST'];
    session_set_cookie_params(30 * 60, "/", $domain, isset($_SERVER["HTTPS"]), TRUE);
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
