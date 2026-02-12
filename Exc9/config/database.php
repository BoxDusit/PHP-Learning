<?php
$host = "localhost";
$username = "it67040233115";
$password = "G8U1E8Y3";
$db = "it67040233115";
$conn = new mysqli($host, $username, $password, $db);
if ($conn->connect_error) {
    die(json_encode([
        "status" => 500,
        "message" => "Database connection failed"
    ]));
}
