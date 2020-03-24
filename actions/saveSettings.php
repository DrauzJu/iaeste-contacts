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

$db = getDB();

// save new logo path
$logo_key = "logo";
$stmt = $db->prepare("UPDATE settings SET value=? WHERE `key`=?");
$stmt->bind_param("ss", $_POST["logo"], $logo_key);
$stmt->execute();

if(!empty($db->error)) {
    echo "Error occured!";
    exit();
}

header("Location: ../pages/settings.php?tab=General");
