<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "test";

    $conn = mysqli_connect($host, $user, $pass, $dbname);

    if (!$conn) {
        die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . mysqli_connect_error());
    }

    // ใช้ utf8mb4 เพื่อรองรับตัวอักษรไทยและอีโมจิ
    mysqli_set_charset($conn, "utf8mb4");
?>