# iaeste-contacts
Contact Management System for [IAESTE](https://www.iaeste.de/) LCs 

![](https://www.iaeste.de/files/2019/04/iaeste-logo.png)

## Requirements
1. PHP7 (including mysqli)
2. MySQL Database

## Installation
1. Clone the repository to your web root directory
   ```bash
   git clone https://github.com/DrauzJu/iaeste-contacts
   ```
2. (Production environment only) Remove the git folder for security reasons
   ```bash
   rm -rf .git
   ```
3. Edit the file `include/config.php` and set the values for 
    1. Database connection
    2. Domain
    3. Custom logo
4. Setup the DB
   ```mysql
   CREATE TABLE `epstatus` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` varchar(50) NOT NULL,
     PRIMARY KEY (`id`)
   );
   
   CREATE TABLE `status` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` varchar(50) NOT NULL,
     PRIMARY KEY (`id`)
   );
   
   CREATE TABLE `student` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` varchar(100) NOT NULL,
     `outYear` int(11) NOT NULL,
     `email` varchar(100) NOT NULL,
     `studies` varchar(100) NOT NULL,
     `epstatus` int(11) NOT NULL,
     `status` int(11) NOT NULL,
     `comment` text NOT NULL,
     PRIMARY KEY (`id`)
   );
   
   CREATE TABLE `user` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` varchar(100) NOT NULL,
     `password` char(60) NOT NULL,
     `admin` smallint(6) NOT NULL,
     PRIMARY KEY (`id`)
   );
   
   CREATE VIEW `student_status` AS SELECT 
       `student`.`id` AS `id`,
       `student`.`name` AS `name`,
       `student`.`outYear` AS `outYear`,
       `student`.`email` AS `email`,
       `student`.`studies` AS `studies`,
       `student`.`comment` AS `comment`,
       `epstatus`.`name` AS `EP_Status`,
       `status`.`name` AS `Status`
       FROM `student` 
       JOIN `epstatus` ON `student`.`epstatus` = `epstatus`.`id`
       JOIN `status` ON `student`.`status` = `status`.`id`;
   ```
5. (Optional) Insert Data for status and ep_status
    ```mysql
    INSERT INTO `epstatus` VALUES (1,'Pending verification'),(2,'Approved'),(0,'None');
    INSERT INTO `status` VALUES (1,'Unknown'),(2,'EP Account created'),(3,'Contacted by LC via mail'),(4,'Student contacted LC (no EP Account)'),(5,'Student contacted LC'),(6,'Looking for internship'),(7,'Internship found'),(8,'Back from internship'),(9,'Doing internship'),(10,'Not interested anymore');
    ```

## User management
During the setup process, a admin user is created:
```text
User: admin
Password: iaeste
```

After setup, you should login with this user and change the password by navigating to Settings -> User.
You can also create new (non-admin) users there. 
