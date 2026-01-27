<?php
include "db_connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Validate input
    if (!empty($title) && !empty($description)) {
        // Prepare and execute insert query
        $stmt = $conn->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);

        if ($stmt->execute()) {
            $message = "Task added successfully!";
            // Clear form data
            $title = "";
            $description = "";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task</title>
</head>
<body>
    <h1>Insert Task</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required rows="4" cols="50"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea><br><br>

        <input type="submit" value="บัณทึกข้อมูล">
    </form>

</body>
</html>