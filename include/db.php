<?php
require_once "config.php";

function getDB() {
    global $IAESTE_DB_HOST, $IAESTE_DB_NAME, $IAESTE_DB_USER, $IAESTE_DB_PASSWORD;

    $mysqli = new mysqli($IAESTE_DB_HOST, $IAESTE_DB_USER, $IAESTE_DB_PASSWORD, $IAESTE_DB_NAME);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    return $mysqli;
}

?>
