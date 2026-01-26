<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "grocery_store";
$port="3308";
$conn = new mysqli($host, $user, $pass, $db ,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
