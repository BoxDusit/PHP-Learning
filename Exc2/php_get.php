<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form  method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Name: <input type="text" name="fname">
        LastName: <input type="text" name="lname">
        <input type="submit">
    </form>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['fname']) && isset($_GET['lname'])) {
            $name = $_GET['fname'];
            $surname = $_GET['lname'];

            if (empty($name)) {
                echo "Name is empty";
            } else {
                echo $name." ".$surname;
            }
        }
    }
    ?>
</body>

</html>