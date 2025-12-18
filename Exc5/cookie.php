<?php
//ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if(isset($_POST['submit'])){
    //รับค่าจากฟอร์ม
    $username = $_POST['username'];
    //กำหนดค่า cookie
    setcookie('username', $username, time() + 3600); //หมดอายุใน 1 ชั่วโมง
}

//ตรวจสอบว่ามี cookie อยู่หรือไม่
if(isset($_COOKIE['username'])){
   $welcome_message = "ยินดีต้อนรับกลับ คุณ " . $_COOKIE['username'] . "!";
} else {
    $welcome_message = "ยินดีต้อนรับผู้ใช้ใหม่!";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การใช้งาน Cookie</title>
</head>
<body>
    <h1><?php echo $welcome_message; ?></h1>
    <form action="" method="post">
        ชื่อผู้ใช้: <input type="text" name="username" required>
        <input type="submit" name="submit" value="ส่งค่า">
    </form>
    
</body>
</html>