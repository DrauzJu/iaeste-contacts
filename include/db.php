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

function getOption($db, $option) {
    try {
        $option = $db->query("SELECT value FROM settings WHERE `key` = '".$option."'")->fetch_assoc();
        return $option["value"];
    } catch (Exception | Error $e) {
        return "";
    }
}

function setupDatabase($insertData) {
    $db = getDB();
    updateSetupScreen("Connected to DB");

    // epStatus
    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `epstatus` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        PRIMARY KEY (`id`));"
    );

    // status
    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `status` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(50) NOT NULL,
      PRIMARY KEY (`id`));"
    );

    // student
    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `student` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `outYear` int(11) NOT NULL,
      `email` varchar(100) NOT NULL,
      `studies` varchar(100) NOT NULL,
      `epstatus` int(11) NOT NULL,
      `status` int(11) NOT NULL,
      `last_update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `disabled` smallint DEFAULT 0,
      `comment` text NOT NULL,
      FOREIGN KEY (`epstatus`) REFERENCES epstatus(id) ON DELETE RESTRICT ON UPDATE CASCADE,
      FOREIGN KEY (`status`) REFERENCES status(id) ON DELETE RESTRICT ON UPDATE CASCADE, 
      PRIMARY KEY (`id`));"
    );

    // user
    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `user` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `password` char(60) NOT NULL,
      `admin` smallint(6) NOT NULL,
      PRIMARY KEY (`id`));"
    );

    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `settings` (
      `key` varchar(20) NOT NULL,
      `value` text NOT NULL,
      PRIMARY KEY (`key`));"
    );

    executeSetupQuery($db, "INSERT IGNORE INTO settings VALUES ('logo', 'https://www.iaeste.de/files/2019/04/iaeste-logo.png')");

    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `criteria` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `year` int(11) NOT NULL,
      `name` varchar(40),
      `weight` int(11),
      PRIMARY KEY (`id`));"
    );

    executeSetupQuery($db, "CREATE TABLE IF NOT EXISTS `score` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `student` int(11) NOT NULL,
      `criteriaid` int(11) NOT NULL,
      `value` int(11),
      `user` varchar(50),
      FOREIGN KEY (`student`) REFERENCES student(id) ON DELETE CASCADE,
      FOREIGN KEY (`criteriaid`) REFERENCES criteria(id) ON DELETE CASCADE,
      PRIMARY KEY (`id`));"
    );

    updateSetupScreen("Created tables");

    // view: student_status
    executeSetupQuery($db, "CREATE OR REPLACE VIEW `student_status` AS SELECT 
        `student`.`id` AS `id`,`student`.`name` AS `name`,`student`.`outYear` AS `outYear`,
        `student`.`email` AS `email`,`student`.`studies` AS `studies`,`student`.`comment` AS `comment`,
        `student`.`last_update` AS `last_update`,`student`.`disabled` AS `disabled`,
        `epstatus`.`name` AS `EP_Status`,`status`.`name` AS `Status` 
        FROM `student` 
        JOIN `epstatus` on `student`.`epstatus` = `epstatus`.`id` 
        JOIN `status` on `student`.`status` = `status`.`id`;");

    updateSetupScreen("Created views");

    // Create admin user if non exists
    $admin_user = $db->query("SELECT id FROM user WHERE admin = 1;");
    if($admin_user->num_rows < 1) {
        $pw = password_hash("iaeste", PASSWORD_BCRYPT);
        executeSetupQuery($db, "INSERT INTO user(name, password, admin) VALUES ('admin', '".$pw."', 1);");
        updateSetupScreen("Created admin user");
    } else {
        updateSetupScreen("Admin user already exists");
    }

    if($insertData) {
        // Insert status values
        executeSetupQuery($db, "INSERT INTO `status` VALUES (1,'Unknown'),(2,'EP Account created'),(3,'Contacted by LC via mail'),(4,'Student contacted LC (no EP Account)'),(5,'Student contacted LC'),(6,'Looking for internship'),(7,'Internship found'),(8,'Back from internship'),(9,'Doing internship'),(10,'Not interested anymore');");

        // Insert EP status values
        executeSetupQuery($db, "INSERT INTO `epstatus` VALUES (1,'Pending verification'),(2,'Approved'),(3,'None');");

        updateSetupScreen("Filled with initial data");
    }

    updateSetupScreen("Done");
}

function executeSetupQuery($db, $query) {
    $success = $db->query($query);
    if($success == FALSE) {
        die("A error occured setting up the DB: ".$db->error);
    }
}

function updateSetupScreen($text) {
    echo "<script>document.getElementById(\"list\").innerHTML += \"<li><i class='glyphicon glyphicon-ok-circle' style='color: green;'></i>$text</li>\"</script>";
    ob_flush();
    flush();
}

?>
