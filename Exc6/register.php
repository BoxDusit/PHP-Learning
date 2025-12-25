<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ฟอร์มลงทะเบียนอบรม</title>

    <style>
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: #f2f4f8;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            background: #ffffff;
            padding: 25px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            text-align: center;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .group {
            margin-bottom: 15px;
        }

        .inline input {
            margin-right: 5px;
        }

        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th {
            background: #3498db;
            color: white;
            padding: 8px;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .success {
            background: #e8f8f5;
            padding: 10px;
            border-left: 5px solid #1abc9c;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>ฟอร์มลงทะเบียนอบรม</h2>

        <form method="post">

            <div class="group">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" required>
            </div>

            <div class="group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="group">
                <label>หัวข้ออบรม</label>
                <select name="course">
                    <option value="AI สำหรับงานสำนักงาน">AI สำหรับงานสำนักงาน</option>
                    <option value="Excel สำหรับการทำงาน">Excel สำหรับการทำงาน</option>
                    <option value="การเขียนเว็บด้วย PHP">การเขียนเว็บด้วย PHP</option>
                </select>
            </div>

            <div class="group inline">
                <label>อาหารที่ต้องการ</label><br>
                <input type="checkbox" name="food[]" value="ปกติ"> ปกติ
                <input type="checkbox" name="food[]" value="มังสวิรัติ"> มังสวิรัติ
                <input type="checkbox" name="food[]" value="ฮาลาล"> ฮาลาล
            </div>

            <div class="group inline">
                <label>รูปแบบการเข้าร่วม</label><br>
                <input type="radio" name="type" value="Onsite" required> Onsite
                <input type="radio" name="type" value="Online"> Online
            </div>

            <button type="submit" name="submit">ลงทะเบียน</button>

            <?php
            if (isset($_POST['submit'])) {

                $fullname = $_POST['fullname'];
                $email    = $_POST['email'];
                $course   = $_POST['course'];
                $type     = $_POST['type'];

                if (isset($_POST['food'])) {
                    $food = implode(", ", $_POST['food']);
                } else {
                    $food = "ไม่ระบุ";
                }

                $price = ($type == "Onsite") ? 1500 : 800;

                $data = $fullname . "|" . $email . "|" . $course . "|" . $food . "|" . $type . "|" . $price . "\n";
                file_put_contents("register.txt", $data, FILE_APPEND);

                echo "<div class='success'>";
                echo "<h3>ลงทะเบียนสำเร็จ</h3>";
                echo "ชื่อ: $fullname <br>";
                echo "อีเมล: $email <br>";
                echo "หัวข้ออบรม: $course <br>";
                echo "อาหาร: $food <br>";
                echo "รูปแบบ: $type <br>";
                echo "ค่าลงทะเบียน: " . number_format($price, 2) . " บาท";
                echo "</div>";
            }
            ?>

            <h3>รายชื่อผู้ลงทะเบียนทั้งหมด</h3>

            <?php
            if (file_exists("register.txt")) {

                $lines = file("register.txt");

                echo "<table border='1'>";
                echo "<tr>
            <th>ชื่อ</th>
            <th>Email</th>
            <th>หัวข้อ</th>
            <th>อาหาร</th>
            <th>รูปแบบ</th>
            <th>ค่าลงทะเบียน</th>
          </tr>";

                foreach ($lines as $line) {
                    list($name, $email, $course, $food, $type, $price) = explode("|", trim($line));

                    echo "<tr>
                <td>$name</td>
                <td>$email</td>
                <td>$course</td>
                <td>$food</td>
                <td>$type</td>
                <td>" . number_format($price, 2) . "</td>
              </tr>";
                }

                echo "</table>";
            } else {
                echo "ยังไม่มีข้อมูลการลงทะเบียน";
            }
            ?>

        </form>
    </div>

</body>

</html>