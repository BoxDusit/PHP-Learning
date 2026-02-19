<?php
session_start();
// ถ้าไม่ได้ล็อกอิน หรือล็อกอินแล้วแต่ไม่ใช่ admin ให้เด้งกลับหน้า login
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <title>CatBreeds Admin - Responsive</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
            min-height: 100vh;
            font-family: 'Kanit', sans-serif;
        }

        .card-custom {
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }

        /* ปรับแต่งตาราง */
        .table thead {
            background: #212529;
            color: white;
        }

        /* Responsive Table: ซ่อนตารางในจอเล็กแล้วแสดงเป็น Card แทน */
        @media (max-width: 768px) {
            .table-responsive-stack thead {
                display: none;
            }

            .table-responsive-stack tr {
                display: block;
                border: 1px solid #eee;
                margin-bottom: 1rem;
                background: #fff;
                border-radius: 15px;
                padding: 10px;
            }

            .table-responsive-stack td {
                display: block;
                text-align: right;
                font-size: 14px;
                border-bottom: 1px solid #f8f9fa;
            }

            .table-responsive-stack td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
                color: #666;
            }

            .cat-img {
                width: 60px;
                height: 60px;
            }

            /* ปรับปุ่มเพิ่มข้อมูลให้เต็มความกว้างในมือถือ */
            .header-flex {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .btn-add {
                width: 100%;
            }
        }

        .cat-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-action {
            padding: 5px 15px;
            border-radius: 8px;
            margin: 2px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shield-lock me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <div class="navbar-nav ms-auto gap-2">
                    <a href="index.php" class="btn btn-outline-light rounded-pill px-4">
                        <i class="bi bi-house-door me-1"></i> ดูหน้าเว็บหลัก
                    </a>
                    <a href="logout.php" class="btn btn-danger rounded-pill px-4" onclick="return confirm('คุณต้องการออกจากระบบใช่หรือไม่?')">
                        <i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4 my-md-5">
        <div class="card card-custom p-3 p-md-4">

            <div class="d-flex justify-content-between align-items-center mb-4 header-flex">
                <h3 class="fw-bold mb-0">ระบบจัดการสายพันธุ์แมว</h3>
                <button class="btn btn-success shadow-sm px-4 py-2 btn-add btn-action" onclick="openAddModal()">
                    <b>+</b> เพิ่มสายพันธุ์
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-responsive-stack">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อไทย</th>
                            <th>ชื่ออังกฤษ</th>
                            <th>สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="catTable">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content card-custom border-radius-20">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalTitle">ข้อมูลสายพันธุ์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" id="id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Image URL</label>
                            <input type="text" id="image_url" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">สถานะ</label>
                            <select id="is_visible" class="form-select">
                                <option value="1">แสดง (Public)</option>
                                <option value="0">ซ่อน (Hidden)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ชื่อไทย</label>
                            <input type="text" id="name_th" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ชื่ออังกฤษ</label>
                            <input type="text" id="name_en" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">คำอธิบาย</label>
                            <textarea id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ลักษณะนิสัย</label>
                            <textarea id="characteristics" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">วิธีดูแล</label>
                            <textarea id="care_instructions" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-3">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button class="btn btn-primary px-5" onclick="saveCat()">บันทึกข้อมูล</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = "http://localhost/PHP-Learning/Exc10/api/product.php";
        let catModal = new bootstrap.Modal(document.getElementById('catModal'));

        function loadTable() {
            fetch(API_URL)
                .then(res => res.json())
                .then(data => {
                    let rows = "";
                    data.forEach(cat => {
                        let imageSrc = cat.image_url || 'https://via.placeholder.com/80?text=No+Image';

                        rows += `
                    <tr>
                        <td data-label="ID">${cat.id}</td>
                        <td data-label="รูปภาพ">
                            <img src="${imageSrc}" class="cat-img" onerror="this.src='https://via.placeholder.com/80?text=Error'">
                        </td>
                        <td data-label="ชื่อไทย" class="fw-bold">${cat.name_th}</td>
                        <td data-label="ชื่ออังกฤษ"><i>${cat.name_en}</i></td>
                        <td data-label="สถานะ">
                            ${cat.is_visible == 1 
                                ? '<span class="badge bg-success">แสดง</span>' 
                                : '<span class="badge bg-secondary">ซ่อน</span>'}
                        </td>
                        <td class="text-md-center">
                            <button class="btn btn-sm btn-primary btn-action"
                                onclick='openEditModal(${JSON.stringify(cat)})'>
                                แก้ไข
                            </button>
                            <button class="btn btn-sm btn-danger btn-action"
                                onclick="deleteCat(${cat.id})">
                                ลบ
                            </button>
                        </td>
                    </tr>
                `;
                    });
                    document.getElementById("catTable").innerHTML = rows;
                })
                .catch(err => console.error("Error loading data:", err));
        }

        // --- ส่วน function อื่นๆ (openAddModal, openEditModal, saveCat, deleteCat) 
        // --- ใช้ logic เดิมที่คุณมีได้เลยครับ (ผมคงไว้เพื่อความต่อเนื่อง)

        function openAddModal() {
            document.getElementById("modalTitle").innerText = "เพิ่มข้อมูลสายพันธุ์";
            clearForm();
            catModal.show();
        }

        function openEditModal(cat) {
            document.getElementById("modalTitle").innerText = "แก้ไขข้อมูลสายพันธุ์";
            document.getElementById("id").value = cat.id;
            document.getElementById("name_th").value = cat.name_th;
            document.getElementById("name_en").value = cat.name_en;
            document.getElementById("description").value = cat.description;
            document.getElementById("characteristics").value = cat.characteristics;
            document.getElementById("care_instructions").value = cat.care_instructions;
            document.getElementById("image_url").value = cat.image_url;
            document.getElementById("is_visible").value = cat.is_visible;
            catModal.show();
        }

        function saveCat() {
            const id = document.getElementById("id").value;
            const method = id ? "PUT" : "POST";

            fetch(API_URL, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: id,
                        name_th: document.getElementById("name_th").value,
                        name_en: document.getElementById("name_en").value,
                        description: document.getElementById("description").value,
                        characteristics: document.getElementById("characteristics").value,
                        care_instructions: document.getElementById("care_instructions").value,
                        image_url: document.getElementById("image_url").value,
                        is_visible: document.getElementById("is_visible").value
                    })
                })
                .then(res => res.json())
                .then(() => {
                    catModal.hide();
                    loadTable();
                });
        }

        function deleteCat(id) {
            if (confirm("คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?")) {
                fetch(API_URL, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(() => loadTable());
            }
        }

        function clearForm() {
            document.querySelectorAll("#catModal input, #catModal textarea").forEach(el => el.value = "");
            document.getElementById("is_visible").value = "1";
        }

        window.onload = loadTable;
    </script>
</body>

</html>