<?php
header('Content-Type: application/json');

include "db_connect.php";

$id = (int)$_REQUEST['id'];
$sql = "SELECT * FROM tasks WHERE id = $id";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($query);
$result['id'] = (int)$result['id'];

echo json_encode($result);

?>