<?php
$host = 'localhost';
$db   = 'it67040233131';
$user = 'it67040233131';
$pass = 'ใส่เอานะ';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode([
        "status" => 500,
        "message" => "Database connection failed"
    ]));
}
