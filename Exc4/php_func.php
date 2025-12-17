<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Build-in function ฟังชั่นพร้อมใช้งาน php</title>
</head>

<body>
    <h1>PHP Build-in function ฟังชั่นพร้อมใช้งาน php</h1>
    <h2>ทดสอบการใช้ function date()</h2>

    <?php
    echo "วันนี้วันที่ " . date("d/m/Y") . "<br>";
    echo "เวลาปัจจุบัน " . date("H:i:sa") . "<br>";
    echo "วันนี้เป็นวัน " . date("l") . "<br>";
    ?>

    <h2>ทดสอบการใช้ function date_diff</h2>
    <?php
    $date1 = date_create("2005-12-06");
    // $date2 = date_create("2025-12-11");
    $date2 = date_create($datetime = 'now');
    $diff = date_diff($date1, $date2);
    echo "จำนวนระหว่างวันที่ 6 ธันวาคม 2005 ถึง ปัจจุบัน คือ " . $diff->days . "วัน <br>";
    echo "หรือเท่ากับ " . $diff->y . "ปี, " . $diff->m . "เดือน, " . $diff->d . "วัน<br>";
    ?>

    <h2>ทดสอบการใช้ Math function</h2>
    <?php
    $date1 = date_create("2005-12-06");
    $num1 = 10.7;
    $num2 = 15.3;
    $pi = 3.14159265;

    echo "ค่าปัดขึ้นของ $num1 คือ " . ceil($num1) . "<br>";
    echo "ค่าปัดลงของ $num2 คือ " . floor($num2) . "<br>";
    echo "ค่าของ pi ปัดเป็นทษนิยม 2 ตำแหน่งคือ " . round($pi, 2) . "<br>";
    echo "ค่าของ pi คือ " . pi() . "<br>";
    echo "ค่ายกกำลัง 3 ของ 5 คือ " . pow(5, 3) . "<br>";
    echo "ค่ารากที่สองของ 49 คือ " . sqrt(49) . "<br>";
    echo "ค่าสุ่มระหว่ง 1- 100 คือ " . rand(1, 100) . "<br>";
    echo "ค่าสุ่มระหว่ง 50- 150 คือ " . rand(50, 150) . "<br>";
    echo "ค่าสุ่มคือ " . rand() . "<br>";
    $arr = array(3, 5, 1, 8, 2);
    echo "ค่ามากสุดในอาเรย์คือ " . max($arr) . "<br>";
    echo "ค่าน้อยสุดในอาเรย์คือ " . min($arr) . "<br>";
    ?>

    <h2>ทดสอบการใช้ String Function</h2>
    <?php
    $str = "Hello PHP function";
    echo "ความยาวของสตริง '$str' คือ " . strlen($str) . " ตัวอักษร<br>";
    echo "สตริง '$str' ตัวอักษรตัวแรกคือ '" . $str[0] . "'<br>";
    echo "สตริง '$str' ตัวอักษรตัวสุดท้ายคือ '" . $str[1] . chop($str[2]) . "'<br>";
    echo "สตริง '$str' เมื่อแปลงเป็นตัวพิมพ์ใหญ่ทั้งหมดคือ '" . strtoupper($str) . "'<br>";
    echo "สตริง '$str' เมื่อแปลงเป็นตัวพิมพ์เล็กทั้งหมดคือ '" . strtolower($str) . "'<br>";
    echo "สตริง '$str' เมื่อแปลงตัวอักษรตัวแรกเป็นพิมพ์ใหญ่คือ '" . ucfirst($str) . "'<br>";
    echo "สตริง '$str' เมื่อแปลงตัวอักษรตัวแรกของแต่ละคำเป็นพิมพ์ใหญ่คือ '" . ucwords($str) . "'<br>";
    $substr = "PHP";
    echo "ตำแหน่งของคำว่า '$substr' ในสตริง '$str' คือ " . strpos($str, $substr) . "<br>";
    $replace = str_replace("function", "ฟังชั่น", $str);
    echo "เมื่อแทนที่คำว่า 'function' ด้วย 'ฟังชั่น' ในสตริง '$str' จะได้สตริงใหม่ว่า '$replace'<br>";

    $str2 = "   Welcome   to   PHP   programming   ";
    echo "สติงก่อนลบช่องว่าง '$str2'<br>";
    echo "สติงหลังลบช่องว่าง" . trim($str2) . "<br>";

    
    ?>
    <?php
    myfooter("Dusit Phaisan"); // เรียกใช้ฟังก์ชัน myfooter
    ?>
</body>

</html>
<?php
// End of file php_func.php
function myfooter($myname)
{
    echo "<br><center><footer><hr>";
    echo "<p>PHP Build-in function ฟังชั่นพร้อมใช้งาน php</p>";
    echo "<p>สร้างโดย: $myname</p>";
    echo "</footer></center>";
}
?>