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
    <title>Cat Catalog Admin - จัดการน้องแมว</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');

        :root {
            --primary-pink: #ff85a2;
            --dark-pink: #ef476f;
            --bg-pink: #fff5f7;
        }

        body {
            background-color: var(--bg-pink);
            font-family: 'Kanit', sans-serif;
            color: #5a4a4d;
        }

        /* Card Style */
        .card-custom {
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.1);
            border: none;
            background: white;
        }

        /* Navbar */
        .navbar-admin {
            background: white !important;
            border-bottom: 2px solid var(--primary-pink);
        }

        /* Table Design */
        .table thead {
            background-color: var(--primary-pink);
            color: white;
        }
        
        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: rgba(255, 179, 193, 0.05);
        }

        /* Responsive Table for Mobile */
        @media (max-width: 768px) {
            .table-responsive-stack thead { display: none; }
            .table-responsive-stack tr {
                display: block;
                border: 2px solid var(--bg-pink);
                margin-bottom: 1.5rem;
                border-radius: 20px;
                padding: 15px;
                background: white;
            }
            .table-responsive-stack td {
                display: block;
                text-align: right;
                border-bottom: 1px solid #eee;
                padding: 8px 0;
            }
            .table-responsive-stack td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                color: var(--dark-pink);
            }
            .cat-img { width: 80px !important; height: 80px !important; }
        }

        .cat-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid var(--bg-pink);
        }

        /* Buttons */
        .btn-pink {
            background-color: var(--dark-pink);
            color: white;
            border-radius: 30px;
            padding: 8px 25px;
            border: none;
            transition: 0.3s;
        }
        .btn-pink:hover {
            background-color: #d6335c;
            color: white;
            transform: scale(1.05);
        }

        .btn-edit { background-color: #74c0fc; color: white; border-radius: 10px; }
        .btn-delete { background-color: #ff8787; color: white; border-radius: 10px; }
        
        .badge-visible { background-color: #63e6be; color: #099268; }
        .badge-hidden { background-color: #dee2e6; color: #495057; }

        .modal-content { border-radius: 30px; border: none; }
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid var(--bg-pink);
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: var(--primary-pink);
            box-shadow: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-admin sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" style="color: var(--dark-pink);" href="#">
                <i class="bi bi-gear-fill me-2"></i>Admin Center
            </a>
            <div class="ms-auto d-flex gap-2">
                <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
                    <i class="bi bi-eye me-1"></i> ดูหน้าเว็บ
                </a>
                <a href="logout.php" class="btn btn-danger rounded-pill px-4 btn-sm" onclick="return confirm('ออกจากระบบใช่ไหมเมี๊ยว?')">
                    <i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card card-custom p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold mb-0" style="color: var(--dark-pink);">จัดการสายพันธุ์แมว</h3>
                    <p class="text-muted small mb-0">เพิ่ม ลบ หรือแก้ไขข้อมูลน้องแมวในระบบ</p>
                </div>
                <button class="btn btn-pink shadow-sm fw-bold" onclick="openAddModal()">
                    <i class="bi bi-plus-lg me-2"></i>เพิ่มสายพันธุ์ใหม่
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover table-responsive-stack">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 80px; border-radius: 15px 0 0 0;">ID</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อไทย</th>
                            <th>ชื่ออังกฤษ</th>
                            <th>สถานะ</th>
                            <th style="border-radius: 0 15px 0 0;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="catTable" class="text-center">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold" id="modalTitle" style="color: var(--dark-pink);">รายละเอียดข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Image URL (ที่อยู่รูปภาพ)</label>
                            <input type="text" id="image_url" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">สถานะการแสดงผล</label>
                            <select id="is_visible" class="form-select">
                                <option value="1">แสดงสู่สาธารณะ</option>
                                <option value="0">ซ่อนข้อมูล</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ชื่อ (ภาษาไทย)</label>
                            <input type="text" id="name_th" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ชื่อ (English Name)</label>
                            <input type="text" id="name_en" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">รายละเอียดสายพันธุ์</label>
                            <textarea id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ลักษณะนิสัย</label>
                            <textarea id="characteristics" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">วิธีดูแลเบื้องต้น</label>
                            <textarea id="care_instructions" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-pink px-5 shadow-sm" onclick="saveCat()">บันทึกข้อมูล</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // *** ตรวจสอบ URL ของ API ให้ถูกต้องตามโปรเจกต์คุณ ***
        const API_URL = "http://localhost/IT131/api/product.php"; 
        let catModal = new bootstrap.Modal(document.getElementById('catModal'));

        function loadTable() {
            fetch(API_URL)
                .then(res => res.json())
                .then(data => {
                    let rows = "";
                    data.forEach(cat => {
                        let imageSrc = cat.image_url || 'https://via.placeholder.com/80?text=No+Photo';
                        rows += `
                            <tr>
                                <td data-label="ID" class="fw-bold text-muted">${cat.id}</td>
                                <td data-label="รูปภาพ">
                                    <img src="${imageSrc}" class="cat-img" onerror="this.src='https://via.placeholder.com/80?text=Error'">
                                </td>
                                <td data-label="ชื่อไทย" class="fw-bold">${cat.name_th}</td>
                                <td data-label="ชื่ออังกฤษ"><span class="badge bg-light text-dark fw-normal">${cat.name_en}</span></td>
                                <td data-label="สถานะ">
                                    ${cat.is_visible == 1 
                                        ? '<span class="badge badge-visible">แสดงผล</span>' 
                                        : '<span class="badge badge-hidden">ซ่อนอยู่</span>'}
                                </td>
                                <td data-label="จัดการ">
                                    <button class="btn btn-sm btn-edit me-1" onclick='openEditModal(${JSON.stringify(cat)})'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-delete" onclick="deleteCat(${cat.id})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    document.getElementById("catTable").innerHTML = rows;
                })
                .catch(err => console.error("Error:", err));
        }

        function openAddModal() {
            document.getElementById("modalTitle").innerText = "✨ เพิ่มน้องแมวสายพันธุ์ใหม่";
            document.getElementById("id").value = "";
            clearForm();
            catModal.show();
        }

        function openEditModal(cat) {
            document.getElementById("modalTitle").innerText = "📝 แก้ไขข้อมูลน้องแมว";
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

            const data = {
                id: id,
                name_th: document.getElementById("name_th").value,
                name_en: document.getElementById("name_en").value,
                description: document.getElementById("description").value,
                characteristics: document.getElementById("characteristics").value,
                care_instructions: document.getElementById("care_instructions").value,
                image_url: document.getElementById("image_url").value,
                is_visible: document.getElementById("is_visible").value
            };

            fetch(API_URL, {
                method: method,
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if(res.status === 200 || res.status === 201) {
                    catModal.hide();
                    loadTable();
                } else {
                    alert("มีบางอย่างผิดพลาด: " + res.message);
                }
            })
            .catch(err => alert("เกิดข้อผิดพลาดในการเชื่อมต่อ"));
        }

        function deleteCat(id) {
            if (confirm("จะลบน้องแมวรหัส " + id + " ออกจากระบบจริงๆ หรอเมี๊ยว?")) {
                fetch(API_URL, {
                    method: "DELETE",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: id })
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