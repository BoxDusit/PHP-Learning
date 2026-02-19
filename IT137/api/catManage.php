<?php
session_start();
// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <title>CATSTATION - Command Center</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Orbitron:wght@400;700&display=swap');

        :root {
            --space-dark: #0b0d17;
            --space-panel: #161b22;
            --neon-cyan: #00f2ff;
            --neon-pink: #ff00e5;
            --neon-green: #39ff14;
        }

        body {
            background-color: var(--space-dark);
            background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
            font-family: 'Kanit', sans-serif;
            color: #e0e0e0;
        }

        /* Navbar Style */
        .navbar-admin {
            background: rgba(22, 27, 34, 0.95) !important;
            border-bottom: 2px solid var(--neon-cyan);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 2px;
            color: var(--neon-cyan) !important;
        }

        /* Panel Card */
        .card-custom {
            background: var(--space-panel);
            border: 1px solid rgba(0, 242, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        /* Table Styling */
        .table {
            color: #e0e0e0;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead {
            font-family: 'Orbitron', sans-serif;
        }

        .table thead th {
            background: rgba(0, 242, 255, 0.1);
            color: var(--neon-cyan);
            border: none;
            padding: 15px;
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.03);
            transition: 0.3s;
        }

        .table tbody tr:hover {
            background: rgba(0, 242, 255, 0.05);
            transform: scale(1.01);
        }

        .table td {
            border: none;
            padding: 15px;
            vertical-align: middle;
        }

        /* Cat Image in Table */
        .cat-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--neon-cyan);
        }

        /* UI Elements */
        .btn-neon-cyan {
            background: transparent;
            color: var(--neon-cyan);
            border: 1px solid var(--neon-cyan);
            border-radius: 10px;
            font-family: 'Orbitron', sans-serif;
            transition: 0.3s;
        }

        .btn-neon-cyan:hover {
            background: var(--neon-cyan);
            color: var(--space-dark);
            box-shadow: 0 0 15px var(--neon-cyan);
        }

        .badge-visible {
            background: rgba(57, 255, 20, 0.1);
            color: var(--neon-green);
            border: 1px solid var(--neon-green);
        }

        .badge-hidden {
            background: rgba(255, 255, 255, 0.1);
            color: #aaa;
            border: 1px solid #555;
        }

        /* Modal Space Theme */
        .modal-content {
            background: var(--space-panel);
            border: 2px solid var(--neon-cyan);
            border-radius: 25px;
            color: white;
        }

        .form-control,
        .form-select {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: var(--neon-cyan);
            color: white;
            box-shadow: 0 0 10px rgba(0, 242, 255, 0.2);
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .table-responsive-stack thead {
                display: none;
            }

            .table-responsive-stack tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--neon-cyan);
                border-radius: 15px;
                padding: 10px;
            }

            .table-responsive-stack td {
                display: block;
                text-align: right;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .table-responsive-stack td:before {
                content: attr(data-label);
                float: left;
                color: var(--neon-cyan);
                font-weight: bold;
            }
        }

        /* --- Space Footer Style --- */
        .space-footer {
            background: rgba(11, 13, 23, 0.95);
            border-top: 1px solid rgba(0, 242, 255, 0.3);
            padding: 60px 0 30px;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }

        /* เพิ่มเอฟเฟกต์แสงฟุ้งที่มุม */
        .space-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 2px;
            background: var(--neon-cyan);
            box-shadow: 0 0 20px var(--neon-cyan), 0 0 40px var(--neon-cyan);
        }

        .footer-logo {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: var(--neon-cyan);
            text-shadow: 0 0 10px rgba(0, 242, 255, 0.5);
            font-size: 1.5rem;
            text-decoration: none;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: 0.3s;
            display: block;
            margin-bottom: 10px;
            font-weight: 200;
        }

        .footer-link:hover {
            color: var(--neon-cyan);
            padding-left: 8px;
            text-shadow: 0 0 8px var(--neon-cyan);
        }

        .social-icon {
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            display: inline-block;
            margin-right: 10px;
            transition: 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .social-icon:hover {
            background: var(--neon-cyan);
            color: var(--space-dark);
            box-shadow: 0 0 15px var(--neon-cyan);
            transform: translateY(-5px);
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: 40px;
            padding-top: 25px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'Orbitron', sans-serif;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin sticky-top shadow-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-cpu-fill me-2"></i>COMMAND CENTER
            </a>
            <div class="ms-auto d-flex gap-2">
                <a href="index.php" class="btn btn-outline-info rounded-pill px-4 btn-sm">
                    <i class="bi bi-rocket-takeoff me-1"></i> VIEW SITE
                </a>
                <a href="logout.php" class="btn btn-outline-danger rounded-pill px-4 btn-sm" onclick="return confirm('TERMINATE SESSION?')">
                    <i class="bi bi-power me-1"></i> LOGOUT
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card card-custom p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold mb-0 text-info" style="font-family: 'Orbitron', sans-serif;">FLEET MANAGEMENT</h3>
                    <p class="text-white-50 small mb-0">ระบบจัดการฐานข้อมูลสายพันธุ์แมวอวกาศ</p>
                </div>
                <button class="btn btn-neon-cyan px-4 py-2 fw-bold" onclick="openAddModal()">
                    <i class="bi bi-plus-circle-fill me-2"></i>ADD NEW SPECIES
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-responsive-stack">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>SPECIES</th>
                            <th>NAME (TH)</th>
                            <th>NAME (EN)</th>
                            <th>STATUS</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="catTable">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold text-info" id="modalTitle" style="font-family: 'Orbitron', sans-serif;">DATA INPUT</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small text-info">IMAGE SOURCE URL</label>
                            <input type="text" id="image_url" class="form-control" placeholder="https://cat-server.io/img.jpg">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-info">VISIBILITY</label>
                            <select id="is_visible" class="form-select">
                                <option value="1">PUBLIC (แสดงผล)</option>
                                <option value="0">ENCRYPTED (ซ่อน)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-info">THAILAND DESIGNATION</label>
                            <input type="text" id="name_th" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-info">GALACTIC NAME (EN)</label>
                            <input type="text" id="name_en" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-info">SPECIES DESCRIPTION</label>
                            <textarea id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-info">BEHAVIORAL TRAITS</label>
                            <textarea id="characteristics" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-info">MAINTENANCE LOGS</label>
                            <textarea id="care_instructions" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">ABORT</button>
                    <button type="button" class="btn btn-neon-cyan px-5" onclick="saveCat()">COMMIT CHANGES</button>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // *** ตรวจสอบ URL ของ API ให้ถูกต้องตามโปรเจกต์คุณ ***
        const API_URL = "http://localhost/IT137/api/product.php";
        let catModal = new bootstrap.Modal(document.getElementById('catModal'));

        function loadTable() {
            fetch(API_URL)
                .then(res => res.json())
                .then(data => {
                    let rows = "";
                    data.forEach(cat => {
                        let imageSrc = cat.image_url || 'https://via.placeholder.com/80?text=NO+DATA';
                        rows += `
                            <tr>
                                <td data-label="ID" class="fw-bold text-info">#${cat.id}</td>
                                <td data-label="SPECIES">
                                    <img src="${imageSrc}" class="cat-img" onerror="this.src='https://via.placeholder.com/80?text=ERROR'">
                                </td>
                                <td data-label="NAME (TH)" class="fw-bold">${cat.name_th}</td>
                                <td data-label="NAME (EN)"><span class="text-white-50">${cat.name_en}</span></td>
                                <td data-label="STATUS">
                                    ${cat.is_visible == 1 
                                        ? '<span class="badge badge-visible">ONLINE</span>' 
                                        : '<span class="badge badge-hidden">OFFLINE</span>'}
                                </td>
                                <td data-label="ACTIONS" class="text-center">
                                    <button class="btn btn-sm btn-outline-info me-1" onclick='openEditModal(${JSON.stringify(cat)})'>
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteCat(${cat.id})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    document.getElementById("catTable").innerHTML = rows;
                })
                .catch(err => console.error("Database Link Failure:", err));
        }

        function openAddModal() {
            document.getElementById("modalTitle").innerText = "REGISTER NEW LIFEFORM";
            document.getElementById("id").value = "";
            clearForm();
            catModal.show();
        }

        function openEditModal(cat) {
            document.getElementById("modalTitle").innerText = "UPDATE SPECIES DATA";
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
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 200 || res.status === 201) {
                        catModal.hide();
                        loadTable();
                    } else {
                        alert("COMMAND FAILED: " + res.message);
                    }
                })
                .catch(err => alert("CONNECTION LOST"));
        }

        function deleteCat(id) {
            if (confirm("CONFIRM DELETION OF UNIT #" + id + "?")) {
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