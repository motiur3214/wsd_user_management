<?php

// Define database constants
define("DB_HOST", $_ENV['DATABASE_HOST']);
define("DB_NAME", $_ENV['DATABASE_NAME']);
define("DB_USERNAME", $_ENV['DATABASE_USER']);
define("DB_PASSWORD", $_ENV['DATABASE_PASSWORD']);
define("BASE_URL", $_ENV['BASE_URL']);

//uncomment this portion if env is not loading
//const DB_HOST = 'localhost';
//const DB_NAME = 'user_management';
//const DB_USERNAME = 'root';
//const DB_PASSWORD = '';
//const BASE_URL = 'http://localhost/user_management';

const USER_ROLE = [
    "admin" => 1,
    "user" => 2
];
