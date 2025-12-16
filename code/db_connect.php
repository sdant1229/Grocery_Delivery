<?php
$servername = "localhost";
$username = "root"; // your DB username
$password = "";     // your DB password
$dbname = "grocery_system"; // Updated to match database.sql

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to avoid encoding issues
$conn->set_charset("utf8");
?>
