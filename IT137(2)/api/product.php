<?php
session_start(); // เริ่มต้น Session เพื่อตรวจสอบสิทธิ์การล็อกอินและ Role

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// เชื่อมต่อฐานข้อมูล (ตรวจสอบ Path ของไฟล์ database.php ให้ถูกต้อง)
include_once "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

/**
 * 🛡️ ส่วนควบคุมสิทธิ์ (Access Control)
 * - Method 'GET': อนุญาตให้ทุกคนเข้าถึงได้ (เพื่อดูรายชื่อแมวในหน้า catPage.php)
 * - Method 'POST', 'PUT', 'DELETE': อนุญาตเฉพาะผู้ที่มี Session role เป็น 'admin' เท่านั้น
 */
if ($method !== 'GET') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403); // Forbidden
        echo json_encode([
            "status" => 403,
            "message" => "ปฏิเสธการเข้าถึง: เฉพาะผู้ดูแลระบบ (Admin) เท่านั้นที่มีสิทธิ์จัดการข้อมูล"
        ]);
        exit();
    }
}

switch ($method) {

    // 1. GET: ดึงข้อมูลสายพันธุ์แมวทั้งหมด
    case 'GET':
        $sql = "SELECT * FROM catbreeds ORDER BY id DESC";
        $result = $conn->query($sql);

        $cats = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cats[] = $row;
            }
        }
        echo json_encode($cats);
        break;


    // 2. POST: เพิ่มข้อมูลสายพันธุ์แมวใหม่ (เฉพาะ Admin)
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->name_th) || empty($data->name_en)) {
            echo json_encode(["status" => 400, "message" => "กรุณากรอกชื่อสายพันธุ์"]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO catbreeds 
            (name_th, name_en, description, characteristics, care_instructions, image_url, is_visible) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "ssssssi",
            $data->name_th,
            $data->name_en,
            $data->description,
            $data->characteristics,
            $data->care_instructions,
            $data->image_url,
            $data->is_visible
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => 201, "message" => "เพิ่มข้อมูลสำเร็จ"]);
        } else {
            echo json_encode(["status" => 500, "message" => "เกิดข้อผิดพลาดในการเพิ่มข้อมูล"]);
        }
        break;


    // 3. PUT: แก้ไขข้อมูลสายพันธุ์แมว (เฉพาะ Admin)
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->id)) {
            echo json_encode(["status" => 400, "message" => "ไม่พบ ID ที่ต้องการแก้ไข"]);
            break;
        }

        $stmt = $conn->prepare("UPDATE catbreeds SET 
            name_th=?, 
            name_en=?, 
            description=?, 
            characteristics=?, 
            care_instructions=?, 
            image_url=?, 
            is_visible=? 
            WHERE id=?");

        $stmt->bind_param(
            "ssssssii",
            $data->name_th,
            $data->name_en,
            $data->description,
            $data->characteristics,
            $data->care_instructions,
            $data->image_url,
            $data->is_visible,
            $data->id
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => 200, "message" => "แก้ไขข้อมูลสำเร็จ"]);
        } else {
            echo json_encode(["status" => 500, "message" => "เกิดข้อผิดพลาดในการแก้ไขข้อมูล"]);
        }
        break;


    // 4. DELETE: ลบข้อมูลสายพันธุ์แมว (เฉพาะ Admin)
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->id)) {
            echo json_encode(["status" => 400, "message" => "ไม่พบ ID ที่ต้องการลบ"]);
            break;
        }

        $stmt = $conn->prepare("DELETE FROM catbreeds WHERE id=?");
        $stmt->bind_param("i", $data->id);

        if ($stmt->execute()) {
            echo json_encode(["status" => 200, "message" => "ลบข้อมูลสำเร็จ"]);
        } else {
            echo json_encode(["status" => 500, "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"]);
        }
        break;


    default:
        http_response_code(405);
        echo json_encode(["status" => 405, "message" => "Method Not Allowed"]);
        break;
}

$conn->close();
?>