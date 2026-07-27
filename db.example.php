<?php

$host = "localhost";
$username = "YOUR_USERNAME";
$password = "YOUR_PASSWORD";
$database = "YOUR_DATABASE_NAME";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed");
}

?>