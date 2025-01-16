<?php

// Set default timezone (Malaysia area)
date_default_timezone_set('Asia/Kuala_Lumpur');

/* 
- Database configuration for server hosting -
$host = "sql305.infinityfree.com";
$username = "if0_37900427";
$password = "Umaapp123";
$database = "if0_37900427_umaapp";
*/

$host = "localhost"; // Database host
$username = "root"; // Database username
$password = ""; // Database password
$database = "umaapp"; // Database name from your file

try {
    $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>