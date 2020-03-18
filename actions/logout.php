<?php
session_start();
$_SESSION['loggedIn'] = "False";

header("Location: ../index.php");
?>
