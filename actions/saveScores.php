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
$test_insert = '/Ins\-S(\d+)\-(\d+)/m';
$test_update = '/Upd\-(\d+)/m';

$update_stmt = $db->prepare("UPDATE score SET value=? WHERE id=?");
$insert_stmt = $db->prepare("INSERT INTO score VALUES (NULL, ?,?,?,?)");

// Insert or update score
foreach ($_POST as $key => $value) {
    if(preg_match($test_insert, $key, $matches) === 1 && !empty($value)) {
        $insert_stmt->bind_param("iiis", $matches[1], $matches[2], $value, $_POST["user"]);
        $insert_stmt->execute();

        if(!empty($db->error)) {
            echo "Error occurred during update of a score!";
            exit();
        }

    } else if(preg_match($test_update, $key, $matches) === 1) {
        $update_stmt->bind_param("ii", $value, $matches[1]);
        $update_stmt->execute();

        if(!empty($db->error)) {
            echo "Error occurred during update of a score!";
            exit();
        }
    }
}

header("Location: ../pages/application.php?year=".$_POST["year"]."&user=".$_POST["user"]);
