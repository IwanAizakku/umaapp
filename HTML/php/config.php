<?php
// config.php

/*for local or online website 
$servername = "localhost";
$username = "Webs392024";
$password = "Webs392024";
$dbname = "fasttrack_gym";

$servername = "localhost";
$username = "u467650909_Webs392024";
$password = "Webs392024";
$dbname = "u467650909_fasttrack_gym";

for local but another database name
$servername = "localhost";
$username = "Webs392024";
$password = "Webs392024";
$dbname = "u467650909_fasttrack_gym";
*/

// Database credentials
$servername = "localhost";
$username = "root";
$password = "uma123";
$dbname = "uma_app";
// Create a new connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}