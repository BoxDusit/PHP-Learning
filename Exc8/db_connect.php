<?php 
$host = 'localhost';
$db   = 'it67040233115';
$user = 'it67040233115';
$pass = 'G8U1E8Y3';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>