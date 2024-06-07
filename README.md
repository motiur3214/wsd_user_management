A simple User Management System with an admin and user dashboard

-Pull from the development branch

-Features:

-User:

    -Login
    -User info
    -Logout
    -Update
    -Remove

-Admin

    -Login
    -UserList
    -Logout
    -Register New User
    -Update
    -Remove

-Requirements

    -PHP 8.2
    -Composer
    -Xamp/Laragon

-Setup

    -composer install
    -composer dump-autoload
    -Import the SQL file(inside sql folder)
    -example-env to .env
    -Database credential in .env
    -Base Url in .env
    -localhost/wsd_user_management to go to user management panel 

-Credential

    -admin
       -admin@gmail.com 
       -12345678

    -user
       -user1@gmail.com
       -12345678

       -user2@gmail.com
       -12345678

-technical features

    -Routes Implemented
    -validations
    -PDO
    -Singletone for DB
    -Dependency Injection
    -Some Unit test

-Note (when running unit test only)
    
    if loading .env gives error please give DB credential on config.php manually
    and comment out previously Defined database constants

-reach me out to anublar.motiur@gmail.com for any query 
