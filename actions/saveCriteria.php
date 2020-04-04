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
$stmt = $db->prepare("INSERT INTO criteria VALUES (NULL, ?, ?, ?)");
$stmt->bind_param("isi", $_POST["year"], $_POST["name"], $_POST["weight"]);
$stmt->execute();

if(!empty($db->error)) {
    echo "Error occured!";
    exit();
}

header("Location: ../pages/application_criteria.php?year=".$_POST["year"]);
