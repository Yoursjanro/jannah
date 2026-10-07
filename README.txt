DATA STRUCTURES ACTIVITY LAB
============================

Requirements:
- XAMPP (Apache + MySQL)
- Visual Studio Code
- PHP 8.x recommended
- MySQL/MariaDB

ACCESS LEVELS
- Admin: login/logout, add activities, edit activities, delete activities, view/run activities.
- User: register/login/logout, view activities, open reference PDFs, and run activities.
- New registrations are always User accounts.

DEFAULT ACCOUNTS
Admin: admin / Admin@123
User:  user  / User@123

INSTALLATION - XAMPP
1. Start Apache and MySQL in XAMPP.
2. Copy this folder to C:\xampp\htdocs\data_structures_activity_lab
3. Open http://localhost/data_structures_activity_lab/
4. The application automatically creates the database (when the configured MySQL user has permission), tables, and default accounts.
5. If your MySQL account cannot create databases, create a database named data_structures_db in phpMyAdmin, then reload the application. The application will create the tables automatically.
6. You may also import database.sql into the selected database.

INFINITYFREE
Use the MySQL database name, username, password, and host supplied by InfinityFree in config.php. Do not use CREATE DATABASE statements from the SQL file. Select the InfinityFree database first when importing database.sql.

PAGES
- index.php       Home / Landing
- login.php       Login
- register.php    User registration
- logout.php      Logout
- activities.php  Activity Catalog
- activity.php    Activity Detail + Runner + Visualizer
- admin.php       Admin activity management
- about.php       Documentation

IMPORTANT RUNNER NOTE
The browser runner executes JavaScript activity snippets stored in the database. It is designed for the educational activities included in this project.
