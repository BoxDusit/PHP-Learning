<?php
session_start();

if(isset($_POST['submit'])){
    $_SESSION['username'] = $_POST['username'];
    header("Location: home.php");
}
?>
<h1>ทดสอบการใช้ Session</h1>
<form method ="post">
    ชื่อผู้ใช้: <input type="text" name="username" require>
    <input type="submit" name="submit" value="login">
</form>