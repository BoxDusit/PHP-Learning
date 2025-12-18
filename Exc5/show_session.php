<?php
session_start();

echo "ชื่อผู้ใช้: " . $_SESSION['username'];
echo "สิทธิ์: " . $_SESSION['role'];
?>