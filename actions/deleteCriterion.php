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
$stmt = $db->prepare("DELETE FROM criteria WHERE id=?");
$stmt->bind_param("s", $_POST["id"]);
$stmt->execute();

if(!empty($db->error)) {
    echo "Error occured!";
    exit();
}

header("Location: ../pages/application_criteria.php?year=".$_POST['year']);
