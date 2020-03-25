# iaeste-contacts
Contact Management System for [IAESTE](https://www.iaeste.de/) LCs 

![](https://www.iaeste.de/files/2019/04/iaeste-logo.png)

## Requirements
1. PHP7.1 (including mysqli)
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
3. Edit the file `include/config.php` and set the values for your Database connection. Please ensure
the given user has all rights for the given database!
4. Setup the DB: visit https://yourdomain/setup.php to automatically setup the configured database

## User management
During the setup process, a admin user is created:
```text
User: admin
Password: iaeste
```

After setup, you should login with this user and change the password by navigating to Settings -> User.
You can also create new (non-admin) users there. 
