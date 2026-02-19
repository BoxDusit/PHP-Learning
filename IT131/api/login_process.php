<?php
session_start();
header("Content-Type: application/json");
include_once "../config/db.php"; 

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
    // ต้องมี id, password, และ role
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $data->username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // ตรวจสอบ Hash รหัสผ่าน
    if ($user && password_verify($data->password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // สำคัญมาก: ต้องเก็บ role ลง Session
        
        echo json_encode([
            "success" => true,
            "role" => $user['role']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"]);
    }
}
?>