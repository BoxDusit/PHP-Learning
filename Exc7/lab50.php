<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
/* ===============================
   เชื่อมต่อฐานข้อมูล
   =============================== */
include 'db_connect.php';

/* ===============================
   ส่วนลบข้อมูล (DELETE)
   ทำงานเมื่อมี delete_id ส่งมาทาง URL
   =============================== */
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];             // รับ id ที่ต้องการลบ (cast ให้เป็น int)
    $conn->query("DELETE FROM users WHERE id=$id"); // ลบข้อมูลจากตาราง users
    header("Location: lab5.php");        // รีเฟรชกลับหน้าหลัก
    exit;
}

/* ===============================
   ตัวแปรสำหรับเก็บข้อมูลแก้ไข
   ถ้าเป็น null = เพิ่มข้อมูล
   ถ้ามีค่า = แก้ไขข้อมูล
   =============================== */
$editData = null;

/* ===============================
   ดึงข้อมูลมาแก้ไข (EDIT)
   =============================== */
if (isset($_GET['edit_id'])) {
    $id = (int) $_GET['edit_id'];                           // รับ id ที่ต้องการแก้ไข (cast เป็น int)
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    if ($result && $result->num_rows) {
        $editData = $result->fetch_assoc();               // เก็บข้อมูลไว้ในตัวแปร
    }
}

/* ===============================
   เพิ่ม / อัปเดตข้อมูล (INSERT / UPDATE)
   =============================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // รับค่าจากฟอร์ม
    $name     = $conn->real_escape_string($_POST['name']);
    $sex      = $conn->real_escape_string($_POST['sex']);
    $phone    = $conn->real_escape_string($_POST['phone']);
    $email    = $conn->real_escape_string($_POST['email']);
    $birthday = $conn->real_escape_string($_POST['birthday']);

    // ถ้ามี id แสดงว่าเป็นการแก้ไข
    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        $sql = "UPDATE users SET
                name='$name',
                sex='$sex',
                phone='$phone',
                email='$email',
                birthday='$birthday'
                WHERE id=$id";
    }
    // ถ้าไม่มี id คือเพิ่มข้อมูลใหม่
    else {
        $sql = "INSERT INTO users (name, sex, phone, email, birthday)
                VALUES ('$name','$sex','$phone','$email','$birthday')";
    }

    $conn->query($sql);                // รันคำสั่ง SQL
    header("Location: lab5.php");     // กลับหน้าหลัก
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการผู้ใช้</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4">

        <h2 class="text-center fw-bold mb-4">ระบบจัดการผู้ใช้</h2>

        <!-- ===============================
     ตารางแสดงรายชื่อผู้ใช้
     =============================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                รายชื่อผู้ใช้
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>ชื่อ</th>
                            <th>เพศ</th>
                            <th>โทร</th>
                            <th>Email</th>
                            <th>วันเกิด</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
                        $result = $conn->query("SELECT * FROM users");

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $row['id'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td class="text-center"><?= $row['sex'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td class="text-center"><?= $row['birthday'] ?></td>

                                <!-- ปุ่มแก้ไข / ลบ -->
                                <td class="text-center">
                                    <!-- ปุ่มแก้ไข -->
                                    <a href="lab5.php?edit_id=<?= $row['id'] ?>"
                                        class="btn btn-warning btn-sm">
                                        แก้ไข
                                    </a>

                                    <!-- ปุ่มลบ (เปิด Modal) -->
                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= $row['name'] ?>"
                                        data-sex="<?= $row['sex'] ?>"
                                        data-phone="<?= $row['phone'] ?>"
                                        data-email="<?= $row['email'] ?>"
                                        data-birthday="<?= $row['birthday'] ?>">
                                        ลบ
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>

        <!-- ===============================
     ฟอร์มเพิ่ม / แก้ไขข้อมูล
     =============================== -->
        <div class="card shadow-sm">
            <div class="card-header <?= $editData ? 'bg-warning' : 'bg-success' ?> text-white">
                <?= $editData ? 'แก้ไขผู้ใช้' : 'เพิ่มผู้ใช้ใหม่' ?>
            </div>

            <div class="card-body">
                <form method="post">

                    <!-- hidden id ใช้เช็คว่าเพิ่มหรือแก้ไข -->
                    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ชื่อ</label>
                            <input type="text" name="name" class="form-control"
                                value="<?= $editData['name'] ?? '' ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">เพศ</label>
                            <select name="sex" class="form-select">
                                <option value="ชาย" <?= (isset($editData) && $editData['sex'] == 'ชาย') ? 'selected' : '' ?>>
                                    ชาย
                                </option>
                                <option value="หญิง" <?= (isset($editData) && $editData['sex'] == 'หญิง') ? 'selected' : '' ?>>
                                    หญิง
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">เบอร์โทร</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?= $editData['phone'] ?? '' ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= $editData['email'] ?? '' ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">วันเกิด</label>
                            <input type="date" name="birthday" class="form-control"
                                value="<?= $editData['birthday'] ?? '' ?>">
                        </div>

                    </div>

                    <!-- ปุ่มบันทึก / อัปเดต -->
                    <button type="submit" class="btn <?= $editData ? 'btn-warning' : 'btn-success' ?>">
                        <?= $editData ? 'อัปเดตข้อมูล' : 'บันทึกข้อมูล' ?>
                    </button>

                    <!-- ปุ่มยกเลิก -->
                    <?php if ($editData) { ?>
                        <a href="lab5.php" class="btn btn-secondary">ยกเลิก</a>
                    <?php } ?>

                </form>
            </div>
        </div>

    </div>

    <!-- ===============================
     Modal ยืนยันการลบ
     =============================== -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">ยืนยันการลบข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="fw-bold text-danger">คุณกำลังจะลบข้อมูลผู้ใช้ต่อไปนี้</p>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>ชื่อ:</strong> <span id="d_name"></span></li>
                        <li class="list-group-item"><strong>เพศ:</strong> <span id="d_sex"></span></li>
                        <li class="list-group-item"><strong>เบอร์โทร:</strong> <span id="d_phone"></span></li>
                        <li class="list-group-item"><strong>Email:</strong> <span id="d_email"></span></li>
                        <li class="list-group-item"><strong>วันเกิด:</strong> <span id="d_birthday"></span></li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">ยืนยันลบ</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ===============================
   JavaScript ใส่ข้อมูลลง Modal
   =============================== */
        const deleteModal = document.getElementById('deleteModal');

        deleteModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;

            // แสดงข้อมูลผู้ใช้ใน Modal
            document.getElementById('d_name').innerText = btn.getAttribute('data-name');
            document.getElementById('d_sex').innerText = btn.getAttribute('data-sex');
            document.getElementById('d_phone').innerText = btn.getAttribute('data-phone');
            document.getElementById('d_email').innerText = btn.getAttribute('data-email');
            document.getElementById('d_birthday').innerText = btn.getAttribute('data-birthday');

            // ตั้งค่า URL สำหรับลบ
            document.getElementById('confirmDeleteBtn').href =
                'lab5.php?delete_id=' + btn.getAttribute('data-id');
        });
    </script>

</body>

</html>