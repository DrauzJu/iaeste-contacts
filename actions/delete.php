<?php
require_once "../include/db.php";
require_once "../include/session.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$db = getDB();
$stmt = $db->prepare("DELETE FROM student WHERE id=?");
$stmt->bind_param("i", $_POST["id"]);
$stmt->execute();

if(empty($db->error)) {
    header("Location: ../pages/overview.php");
    exit();
} else {
    echo "Error occured!";
}

?>
