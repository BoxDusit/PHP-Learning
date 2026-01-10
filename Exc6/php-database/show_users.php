<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>เเสดงข้อมูลจากฐานข้อมูล</h1>

    <?php
        include 'db_connect.php';

        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>ID</th><th>ชื่อ-นามสกุล</th><th>อีเมล</th><th>หลักสูตร</th></tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['fullname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['course'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "ไม่มีข้อมูลในฐานข้อมูล";
        }

        $conn->close();
    ?>
</body>
</html>