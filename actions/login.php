<?php
require_once "../include/db.php";

if(isset($_POST["user"]) && isset($_POST["pw"])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, admin, password FROM user WHERE name=?;");
    $stmt->bind_param("s", $_POST["user"]);
    $stmt->execute();

    if(!empty($db->error)) {
        die($db->error);
    }

    $stmt->store_result();
    if($stmt->num_rows() != 1) {
        echo "Invalid user!";
        exit();
    }

    $stmt->bind_result($result_id, $result_admin, $result_pw);
    $stmt->fetch();

    if(password_verify($_POST["pw"], $result_pw)) {
        // init session
        session_set_cookie_params(300, "/", $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), TRUE);
        session_start();
        $_SESSION['loggedIn'] = "True";
        $_SESSION['csrf_token'] = uniqid('', true);
        $_SESSION['user'] = $result_id;
        $_SESSION['admin'] = $result_admin == 1;

        header("Location: ../pages/overview.php");
        exit();
    } else {
        echo "Invalid password!";
        exit();
    }

} else {
    echo "Invalid data!";
}

?>
