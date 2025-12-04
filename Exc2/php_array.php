<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Array-04/12/2568</title>
</head>

<body>
    <h1>ทดสอบ Array เเบบ Index Array</h1>
    <?php
    $cars = array("Volvo", "BMW", "Toyota");
    echo "I like " . $cars[0] . ", " . $cars[1] . " and " . $cars[2] . ".";
    ?>

    <h1>ทดสอบ Array เเบบ Associative Array</h1>
    <?php
    $age = array("Peter" => "35", "Ben" => "37", "Joe" => "43", "Mon" => "19");
    echo "Peter is " . $age['Peter'] . " years old.";
    echo "<br>\n";
    echo "Ben is " . $age['Ben'] . " years old.";
    echo "<br>\n";
    echo "Joe is " . $age['Joe'] . " years old.";
    echo "<br>\n";
    echo "Mon is " . $age['Mon'] . " years old.";

    ?>

    <?php
    foreach ($age as $x => $x_value) {
        echo "Key=" . $x . ", Value=" . $x_value;
        echo "<br>\n";
    }
    ?>

    <h1>ใช้คำสั่ง for loop กับ Index array</h1>
    <?php
    $fruit = array("เเอปเปิ้ล", "องุ่น", "มะละกอ", "มะม่วง", "ส้ม");

    for ($x = 0; $x < count($fruit); $x++) {
        echo $fruit[$x];
        echo "<br>\n";
    }

    ?>

    <?php
    foreach ($fruit as $value) {
        echo $value;
        echo "<br>\n";
    }
    ?>

    <?php
    foreach ($fruit as $value) {
        echo $value;
        echo "<br>\n";
    }
    ?>

    <h1>การใช้ Array 2 มิติ (Two-Dimension Array)</h1>
    <?php
        $cars = array(
            array("Volvo", 22, 18),
            array("BMW",15,13),
            array("Saab",5,2),
            array("Land Rover",17,15)
        )

    ?>

    <!-- แสดงผลลัพธ์เเบบตรงๆ -->
    <?php
        $rows = count($cars);
        for($row = 0; $row < $rows ; $row++){
            echo "<p><b>Row number $row</b></p>";
            echo "<ul>";
            $cols = count($cars[$row]);
            for($col = 0; $col < $cols ; $col++){
                echo "<li>".$cars[$row][$col]."</li>";
            }
            echo "</ul>";
        }
    ?>


    

</body>

</html>