<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include_once "../config/database.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ======================
    // GET (อ่านข้อมูลทั้งหมด)
    // ======================
    case 'GET':
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        echo json_encode($products);
        break;


    // ======================
    // POST (เพิ่มข้อมูล)
    // ======================
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("INSERT INTO products (product_name, price) VALUES (?, ?)");
        $stmt->bind_param("sd", $data->product_name, $data->price);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => 201,
                "message" => "Product created successfully"
            ]);
        }

        break;


    // ======================
    // PUT (แก้ไขข้อมูล)
    // ======================
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=? WHERE id=?");
        $stmt->bind_param("sdi", $data->product_name, $data->price, $data->id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => 200,
                "message" => "Product updated successfully"
            ]);
        }

        break;


    // ======================
    // DELETE (ลบข้อมูล)
    // ======================
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $data->id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => 200,
                "message" => "Product deleted successfully"
            ]);
        }

        break;


    default:
        echo json_encode([
            "status" => 400,
            "message" => "Invalid request"
        ]);
}
?>
