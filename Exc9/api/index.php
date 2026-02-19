<!DOCTYPE html>
<html lang="th">

<head>
    <title>Cat Catalog - เลือกชมสายพันธุ์แมว</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');

        body {
            background-color: #f8f9fa;
            font-family: 'Kanit', sans-serif;
        }

        /* Card Design */
        .cat-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
            height: 100%;
        }

        .cat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .img-container {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .cat-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .cat-card:hover .cat-img {
            transform: scale(1.1);
        }

        .badge-type {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-detail {
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
        }

        /* Search Bar */
        .search-box {
            border-radius: 30px;
            padding: 12px 25px;
            border: 2px solid #eee;
            box-shadow: none !important;
        }

        .search-box:focus {
            border-color: #0d6efd;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="bi bi-cat-fill me-2"></i>Cat Catalog
            </a>
            <div class="ms-auto">
                <a href="catManage.php">
                    <button class="btn btn-outline-primary rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-person-circle me-2"></i>จัดการข้อมูล (Admin)
                </button>
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark mb-2">ค้นหาสายพันธุ์แมวที่ใช่</h1>
            <p class="text-muted">รวบรวมข้อมูลแมวจากทั่วทุกมุมโลก พร้อมวิธีดูแลเบื้องต้น</p>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control search-box"
                        placeholder="พิมพ์ชื่อสายพันธุ์แมว (ไทย/อังกฤษ)..." onkeyup="searchCat()">
                </div>
            </div>
        </div>

        <div class="row g-4" id="catGrid">
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius: 25px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="detailTitle">รายละเอียด</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <img id="detailImg" src="" class="img-fluid rounded-4 shadow-sm w-100">
                        </div>
                        <div class="col-md-7">
                            <h4 id="detailNameEn" class="text-primary mb-1 small uppercase"></h4>
                            <h3 id="detailNameTh" class="fw-bold mb-3"></h3>
                            <p id="detailDesc" class="text-muted"></p>

                            <div class="bg-light p-3 rounded-4 mb-3">
                                <h6 class="fw-bold">ลักษณะนิสัย</h6>
                                <p id="detailChar" class="mb-0 small"></p>
                            </div>

                            <div class="bg-light p-3 rounded-4">
                                <h6 class="fw-bold">การดูแล</h6>
                                <p id="detailCare" class="mb-0 small"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = "https://hosting.udru.ac.th/~it67040233115/PHP-Learning/Exc10/api/product.php";
        let allCats = [];
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

        // โหลดข้อมูลจาก API
        async function fetchCats() {
            try {
                const res = await fetch(API_URL);
                const data = await res.json();
                // กรองเฉพาะสายพันธุ์ที่ตั้งค่าให้ "แสดง" (is_visible == 1)
                allCats = data.filter(item => item.is_visible == 1);
                displayCats(allCats);
            } catch (err) {
                console.error("Fetch error:", err);
                document.getElementById('catGrid').innerHTML = `<p class="text-center">ไม่สามารถโหลดข้อมูลได้ในขณะนี้</p>`;
            }
        }

        // ฟังก์ชันสร้าง Card แมว
        function displayCats(cats) {
            const grid = document.getElementById('catGrid');
            if (cats.length === 0) {
                grid.innerHTML = `<div class="col-12 text-center my-5">ไม่พบข้อมูลแมวที่ค้นหา</div>`;
                return;
            }

            grid.innerHTML = cats.map(cat => `
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card cat-card shadow-sm">
                        <div class="img-container">
                            <span class="badge-type">${cat.name_en}</span>
                            <img src="${cat.image_url || 'https://via.placeholder.com/400x300?text=No+Image'}" 
                                 class="cat-img" onerror="this.src='https://via.placeholder.com/400x300?text=Error'">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-2">${cat.name_th}</h5>
                            <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${cat.description || 'ไม่มีรายละเอียด'}
                            </p>
                            <button class="btn btn-primary w-100 btn-detail mt-3" onclick='showDetails(${JSON.stringify(cat)})'>
                                ดูข้อมูลเพิ่มเติม
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // ระบบค้นหา
        function searchCat() {
            const term = document.getElementById('searchInput').value.toLowerCase();
            const filtered = allCats.filter(cat =>
                cat.name_th.toLowerCase().includes(term) ||
                cat.name_en.toLowerCase().includes(term)
            );
            displayCats(filtered);
        }

        // แสดง Modal รายละเอียด
        function showDetails(cat) {
            document.getElementById('detailTitle').innerText = `ข้อมูลสายพันธุ์: ${cat.name_th}`;
            document.getElementById('detailNameTh').innerText = cat.name_th;
            document.getElementById('detailNameEn').innerText = cat.name_en;
            document.getElementById('detailDesc').innerText = cat.description || '-';
            document.getElementById('detailChar').innerText = cat.characteristics || 'ไม่มีข้อมูล';
            document.getElementById('detailCare').innerText = cat.care_instructions || 'ไม่มีข้อมูล';
            document.getElementById('detailImg').src = cat.image_url || 'https://via.placeholder.com/400x300?text=No+Image';

            detailModal.show();
        }

        // โหลดข้อมูลเมื่อเปิดหน้าเว็บ
        window.onload = fetchCats;
    </script>
</body>

</html>