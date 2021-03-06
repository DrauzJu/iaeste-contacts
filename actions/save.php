<?php
require_once "../include/db.php";
require_once "../include/session.php";

if(!is_logged_in()) {
    header("Location: ../index.php");
    exit();
}

if(!check_csrf_token($_POST["csrf"])) {
    die("Invalid CSRF Token");
}

$db = getDB();

$disabled = isset($_POST["disabled"]) ? 1 : 0;

if($_POST["mode"] == "update") {
    $stmt = $db->prepare("UPDATE student 
        SET name=?, outYear=?, email=?, epstatus=?, status=?, comment=?, studies=?, disabled=? WHERE id=?");
    $stmt->bind_param("sisiissii", $_POST["name"], $_POST["outYear"], $_POST["email"], $_POST["epstatus"], 
        $_POST["status"], $_POST["comment"], $_POST["studies"], $disabled, $_POST["id"]);
} else {
    $stmt = $db->prepare("INSERT INTO student(name, outYear, email, epstatus, status, comment, studies, disabled)
        VALUES(?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sisiissi", $_POST["name"], $_POST["outYear"], $_POST["email"], $_POST["epstatus"], 
        $_POST["status"], $_POST["comment"], $_POST["studies"], $disabled);
}

$stmt->execute();
if(empty($db->error)) {
    header("Location: ../pages/overview.php");
    exit();
} else {
    echo "Error occured!";
}

?>
