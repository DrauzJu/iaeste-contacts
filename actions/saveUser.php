<?php
require_once "../include/session.php";
require_once "../include/db.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

if(!check_csrf_token($_POST["csrf"])) {
    die("Invalid CSRF Token");
}

if(!$_SESSION["admin"]) {
    die("Not sufficient privileges");
}

$db = getDB();
$stmt = $db->prepare("UPDATE user SET password=? WHERE id=?");

// check if any password changes requested
foreach($_POST as $key => $value) {
    if (!empty($value) && substr($key, 0, strlen("pw-")) === "pw-") {
        $user_id = (int) substr($key, 3);
        $stmt->bind_param("si", password_hash($value, PASSWORD_BCRYPT), $user_id);
        $stmt->execute();

        if(empty($db->error)) {
            header("Location: ../pages/settings.php?tab=User");
            exit();
        } else {
            echo "Error occured!";
        }
    }
}

// check if new user requested
if (!empty($_POST["newUser"]) && !empty($_POST["newPw"])) {
    $admin_false = 0;
    $stmt = $db->prepare("INSERT INTO user(name, password, admin) VALUES (?,?,?)");
    $stmt->bind_param("ssi", $_POST["newUser"], password_hash($_POST["newPw"], PASSWORD_BCRYPT), $admin_false);
    $stmt->execute();

    if(empty($db->error)) {
        header("Location: ../pages/settings.php?tab=User");
        exit();
    } else {
        echo "Error occured!";
    }
}
