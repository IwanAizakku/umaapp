<?php

/* 
- Database configuration for server hosting -
$host = "sql305.infinityfree.com";
$username = "if0_37900427";
$password = "Umaapp123";
$database = "if0_37900427_umaapp";
*/

$host = 'localhost'; // Database host
$db = 'umaapp'; // Database name from your file
$user = 'root'; // Database username
$pass = ''; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>