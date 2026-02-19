<?php
session_start();
// ทำลาย Session ทั้งหมด
session_unset();
session_destroy();

// ส่งกลับไปหน้า Login หรือหน้าแรก
header("Location: login.html");
exit();
?>