<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
require 'db_connect_pdo.php';

$editData = null;

// DELETE
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: lab55_pdo.php');
    exit;
}

// EDIT: load existing data
if (isset($_GET['edit_id'])) {
    $id = (int) $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// INSERT / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $sex      = trim($_POST['sex'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');

    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        $stmt = $conn->prepare(
            "UPDATE users SET name = ?, sex = ?, phone = ?, email = ?, birthday = ? WHERE id = ?"
        );
        $stmt->execute([$name, $sex, $phone, $email, $birthday, $id]);
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO users (name, sex, phone, email, birthday) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $sex, $phone, $email, $birthday]);
    }

    header('Location: lab5_pdo.php');
    exit;
}

// Read all users
$stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบจัดการผู้ใช้ (PDO)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4">

        <h2 class="text-center fw-bold mb-4">ระบบจัดการผู้ใช้ (PDO)</h2>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">รายชื่อผู้ใช้</div>
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
                        <?php if ($users): ?>
                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['sex']) ?></td>
                                    <td><?= htmlspecialchars($row['phone']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['birthday']) ?></td>
                                    <td class="text-center">
                                        <a href="lab5_pdo.php?edit_id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-name="<?= htmlspecialchars($row['name']) ?>"
                                            data-sex="<?= htmlspecialchars($row['sex']) ?>"
                                            data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                            data-email="<?= htmlspecialchars($row['email']) ?>"
                                            data-birthday="<?= htmlspecialchars($row['birthday']) ?>">
                                            ลบ
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">ไม่มีข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header <?= $editData ? 'bg-warning' : 'bg-success' ?> text-white">
                <?= $editData ? 'แก้ไขผู้ใช้' : 'เพิ่มผู้ใช้ใหม่' ?>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id'] ?? '') ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ชื่อ</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editData['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เพศ</label>
                            <select name="sex" class="form-select">
                                <option value="ชาย" <?= (isset($editData) && ($editData['sex'] ?? '') == 'ชาย') ? 'selected' : '' ?>>ชาย</option>
                                <option value="หญิง" <?= (isset($editData) && ($editData['sex'] ?? '') == 'หญิง') ? 'selected' : '' ?>>หญิง</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เบอร์โทร</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($editData['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editData['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">วันเกิด</label>
                            <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($editData['birthday'] ?? '') ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn <?= $editData ? 'btn-warning' : 'btn-success' ?>"><?= $editData ? 'อัปเดตข้อมูล' : 'บันทึกข้อมูล' ?></button>
                    <?php if ($editData): ?>
                        <a href="lab5_pdo.php" class="btn btn-secondary">ยกเลิก</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

    </div>

    <!-- Delete Modal -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            document.getElementById('d_name').innerText = btn.getAttribute('data-name');
            document.getElementById('d_sex').innerText = btn.getAttribute('data-sex');
            document.getElementById('d_phone').innerText = btn.getAttribute('data-phone');
            document.getElementById('d_email').innerText = btn.getAttribute('data-email');
            document.getElementById('d_birthday').innerText = btn.getAttribute('data-birthday');
            document.getElementById('confirmDeleteBtn').href = 'lab5_pdo.php?delete_id=' + btn.getAttribute('data-id');
        });
    </script>
</body>

</html>