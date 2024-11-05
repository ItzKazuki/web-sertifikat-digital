<?php

include_once 'MyDatabase.php';

$server_name = 'localhost';
$username = 'root';
$password = '';
$database = "certificate";

// $conn = new mysqli($server_name, $username, $password, $database);

// if ($conn->connect_errno) {
//   die("Failed Connect to database: " . $conn->connect_error);
// }

// Example usage:
$db = new MyConnection($server_name, $username, $password, $database);
$conn = $db->getConnection();
