<?php
    $host = "localhost";
    $db = "root";
    $pass = "";
    $dbname = "test";
    
    $conn = mysqli_connect($host, $db, $pass, $dbname);
    mysqli_set_charset($conn, "utf8");

    if (!$conn) {
        die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . mysqli_connect_error());
        
    }
?>