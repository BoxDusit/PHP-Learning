<?php
$university = "มหาวิทยาลัยราชภัฏอุดรธานี";
$faculty = "คณะวิทยาศาสตร์";
$major = "สาขาเทคโนโลยีสารสนเทศ";
$student_name = "ดุสิต ไพศาล";
$introduction = "สวัสดีครับ ผมชื่อดุสิต ไพศาล นักศึกษาสาขาเทคโนโลยีสารสนเทศ ชั้นปีที่ 2 ยินดีที่ได้รู้จักทุกคนครับ";
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Work 1</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .profile-img {
            width: 240px;
            height: 240px;
            border-radius: 12px;
            object-fit: cover;
            display: block;
            margin: auto;
            margin-bottom: 20px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.12);
        }

        h3 {
            color: #222;
            margin-bottom: 12px;
            margin-top: 25px;
            padding-left: 10px;
        }

        p {
            font-size: 16px;
            color: #555;
            line-height: 1.7;
        }

        .info-box {
            padding: 20px;
            background: #fafafa;
            border-radius: 12px;
            border: 1px solid #eee;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .loop-wrapper {
            width: 90%;
            margin: 20px auto;
            display: flex;
            gap: 20px;
        }

        .loop-box {
            flex: 1;
            background-color: #fff;
            padding: 10px;
            border-radius: 12px;
            border: 1px solid #eee;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .line {
            width: 100%;
            height: 1px;
            background: #e6e6e6;
            margin: 25px 0;
        }
    </style>
</head>

<body>

    <div class="container">

        <img src="./img/profile.png" class="profile-img">

        <h3>ข้อมูลนักศึกษา</h3>

        <div class="info-box">
            <p><strong>ชื่อ-สกุล:</strong> <?= $student_name ?></p>
            <div class="line"></div>

            <p><strong>มหาวิทยาลัย:</strong> <?= $university ?></p>
            <div class="line"></div>

            <p><strong>คณะ:</strong> <?= $faculty ?></p>
            <div class="line"></div>

            <p><strong>สาขา:</strong> <?= $major ?></p>
        </div>

        <h3>แนะนำตัว</h3>
        <p><?= $introduction ?></p>

        <!-- 2.1 เพิ่มดาวทีละแถว -->
        <h3>1.การใช้ลูปเพิ่มดาวทีละแถว</h3>
        <div class="loop-wrapper">

            
            <div class="loop-box">
                <b><p>ใช้ Loop while</p></b>
                <?php
                $i = 1;
                while ($i <= 4) {
                    $j = 1;
                    while ($j <= $i) {
                        echo "*";
                        $j++;
                    }
                    echo "<br>";
                    $i++;
                }
                ?>
            </div>

            
            <div class="loop-box">
                <b><p>ใช้ Loop do while</p></b>
                <?php
                $i = 1;
                do {
                    $j = 1;
                    do {
                        echo "*";
                        $j++;
                    } while ($j <= $i);
                    echo "<br>";
                    $i++;
                } while ($i <= 4);
                ?>
            </div>

           
            <div class="loop-box">
                <b><p>ใช้ Loop for</p></b>
                <?php
                for ($i = 1; $i <= 4; $i++) {
                    for ($j = 1; $j <= $i; $j++) {
                        echo "*";
                    }
                    echo "<br>";
                }
                ?>
            </div>
        </div>

        <!-- 2.2 วาดตัวเลขซ้ำกัน -->
        <h3>2.การใช้ลูปวาดตัวเลขซ้ำกันในแต่ละแถว</h3>
        <div class="loop-wrapper">

            <div class="loop-box">
                <b><p>ใช้ Loop while</p></b>
                <?php
                $i = 1;
                while ($i <= 3) {
                    $j = 1;
                    while ($j <= 4) {
                        echo $i . " ";
                        $j++;
                    }
                    echo "<br>";
                    $i++;
                }
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop do while</p></b>
                <?php
                $i = 1;
                do {
                    $j = 1;
                    do {
                        echo $i . " ";
                        $j++;
                    } while ($j <= 4);

                    echo "<br>";
                    $i++;
                } while ($i <= 3);
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop for</p></b>
                <?php
                for ($i = 1; $i <= 3; $i++) {
                    for ($j = 1; $j <= 4; $j++) {
                        echo $i . " ";
                    }
                    echo "<br>";
                }
                ?>
            </div>
        </div>

        <!-- 2.3 ขั้นบันได -->
        <h3>3.การใช้ลูปวาดตัวเลขแบบขั้นบันได</h3>
        <div class="loop-wrapper">

            <div class="loop-box">
                <b><p>ใช้ Loop while</p></b>
                <?php
                $i = 1;
                while ($i <= 3) {
                    $j = 1;
                    while ($j <= $i) {
                        echo $i . " ";
                        $j++;
                    }
                    echo "<br>";
                    $i++;
                }
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop do while</p></b>
                <?php
                $i = 1;
                do {
                    $j = 1;
                    do {
                        echo $i . " ";
                        $j++;
                    } while ($j <= $i);
                    echo "<br>";
                    $i++;
                } while ($i <= 3);
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop for</p></b>
                <?php
                for ($i = 1; $i <= 3; $i++) {
                    for ($j = 1; $j <= $i; $j++) {
                        echo $i . " ";
                    }
                    echo "<br>";
                }
                ?>
            </div>
        </div>

        <!-- 2.4 กรอบล้อม -->
        <h3>4.กรอบล้อมดาวและตัวเลข</h3>
        <div class="loop-wrapper">
            <?php $rows = 5; $cols = 6; ?>

            <div class="loop-box">
                <b><p>ใช้ Loop while</p></b>
                <?php
                $i = 1;
                while ($i <= $rows) {
                    $j = 1;
                    while ($j <= $cols) {
                        if ($i == 1 || $i == $rows)
                            echo "* ";
                        else if ($j == 1 || $j == $cols)
                            echo "* ";
                        else
                            echo ($i - 1) . " ";
                        $j++;
                    }
                    echo "<br>";
                    $i++;
                }
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop do while</p></b>
                <?php
                $i = 1;
                do {
                    $j = 1;
                    do {
                        if ($i == 1 || $i == $rows)
                            echo "* ";
                        else if ($j == 1 || $j == $cols)
                            echo "* ";
                        else
                            echo ($i - 1) . " ";
                        $j++;
                    } while ($j <= $cols);
                    echo "<br>";
                    $i++;
                } while ($i <= $rows);
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop for</p></b>
                <?php
                for ($i = 1; $i <= $rows; $i++) {
                    for ($j = 1; $j <= $cols; $j++) {
                        if ($i == 1 || $i == $rows)
                            echo "* ";
                        else if ($j == 1 || $j == $cols)
                            echo "* ";
                        else
                            echo ($i - 1) . " ";
                    }
                    echo "<br>";
                }
                ?>
            </div>
        </div>

        <!-- 2.5 ตัวเลขกลับด้าน -->
        <h3>5.ตัวเลขกลับด้าน</h3>
        <div class="loop-wrapper">

            <div class="loop-box">
                <b><p>ใช้ Loop while</p></b>
                <?php
                $i = 3;
                while ($i >= 1) {
                    $j = 1;
                    while ($j <= $i) {
                        echo $i . " ";
                        $j++;
                    }
                    echo "<br>";
                    $i--;
                }
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop do while</p></b>
                <?php
                $i = 3;
                do {
                    $j = 1;
                    do {
                        echo $i . " ";
                        $j++;
                    } while ($j <= $i);
                    echo "<br>";
                    $i--;
                } while ($i >= 1);
                ?>
            </div>

            <div class="loop-box">
                <b><p>ใช้ Loop for</p></b>
                <?php
                for ($i = 3; $i >= 1; $i--) {
                    for ($j = 1; $j <= $i; $j++) {
                        echo $i . " ";
                    }
                    echo "<br>";
                }
                ?>
            </div>
        </div>

    </div>
</body>
</html>
