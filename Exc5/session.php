<?php
session_start();

$_SESSION['username'] = "student01";
$_SESSION['role'] = "admin";

echo "สร้าง Session เรียบร้อย";
?>