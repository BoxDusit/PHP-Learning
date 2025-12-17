<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การใช้ Use-define Function ฟังชั่นทีสร้างขึ้นเอง</title>
</head>

<body>
    <h1>การใช้ Use-define Function ฟังชั่นทีสร้างขึ้นเอง</h1>
    <?php

    echo "ผลบวกของ 10 กับ 20 คือ " . sum(10, 20) . "<br>";
    echo "ผลบวกของ 15 กับ 25 คือ " . sum(15, 25) . "<br>";
    $a = 30;
    $b = 45;
    echo "ผลบวกของ a กับ b คือ " . sum($a, $b) . "<br>";
    echo "<hr>";
    $num = 50;
    echo "ค่าของ num ก่อนเพิ่ม add_five() คือ $num <br>";
    $new_value = add_five($num);
    echo "ค่าของ num หลังเรียกใช้ฟังชั่น add_five() คือ $num <br>";
    ?>

    <!-- ------------------------------------------------------------------------------------------------------------------- -->

    <h2>ตัวอย่าง function ที่มีพารามิเตอร์หลายตัว</h2>
    <?php
    // ฟังชั่น
    function sumListofNumber(...$x)
    {
        $n = 0;
        $lenge = count($x);
        for ($i = 0; $i < $lenge; $i++) {
            $n += $x[$i];
        }
        return $n;
    }

    function myFamily($lastname, ...$firstname)
    {
        $txt = "";
        $len = count($firstname);
        for ($i = 0; $i < $len; $i++) {
            $txt = $txt . "Hi, $firstname[$i] $lastname.<br>";
        }
        return $txt;
    }

    // เรียกใช้ฟังชั่น 
    echo "ผลบวกของเลขหลายตัว 5, 10, 15 คือ " . sumListofNumber(5, 10, 15) . "<br>";
    echo "ผลบวกของเลขหลายตัว 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 คือ " . sumListofNumber(1, 2, 3, 4, 5, 6, 7, 8, 9, 10) . "<br>";

    echo "<hr>";
    $a = myFamily("Doe", "Jane", "John", "Joey");
    echo $a;
    ?>

    <!-- ------------------------------------------------------------------------------------------------------------------- -->

    <h2>ตัวอย่าง function ที่มีพารามิเตอร์เริ่มต้น</h2>
    <?php
    function DateThai($strDate = "now")
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }

    echo "วันที่ปัจจุบันในรูปแบบไทยคือ " . DateThai() . "<br>";
    echo "" . DateThai() . "<br>";
    ?>


</body>

</html>

<?php

// create ohter functions
function sum($num1, $num2)
{
    return $num1 + $num2;
}
function add_five($num)
{
    $num += 5;
    echo "ค่าภายในของ num หลังเรียกใช้ฟังชั่น add_five() คือ $num <br>";
}

?>