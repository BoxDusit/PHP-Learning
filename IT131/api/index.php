<?php
session_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <title>Cat Catalog - น้องแมวสุดน่ารัก</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');

        :root {
            --primary-pink: #ff85a2;
            --soft-pink: #ffb3c1;
            --bg-pink: #fff5f7;
            --dark-pink: #ef476f;
        }

        body {
            background-color: var(--bg-pink);
            font-family: 'Kanit', sans-serif;
            color: #5a4a4d;
        }

        /* Navbar Customization */
        .navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--soft-pink);
        }

        .navbar-brand {
            color: var(--dark-pink) !important;
            font-size: 1.5rem;
        }

        /* Button Themes */
        .btn-pink-outline {
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
            border-radius: 30px;
            transition: 0.3s;
        }

        .btn-pink-outline:hover {
            background-color: var(--primary-pink);
            color: white;
        }

        .btn-pink-filled {
            background-color: var(--dark-pink);
            color: white;
            border: none;
            border-radius: 30px;
        }

        .btn-pink-filled:hover {
            background-color: #d6335c;
            color: white;
            box-shadow: 0 5px 15px rgba(239, 71, 111, 0.3);
        }

        /* Card & UI Design */
        .cat-card {
            border: none;
            border-radius: 25px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            background: white;
            height: 100%;
            box-shadow: 0 10px 20px rgba(255, 133, 162, 0.1);
        }

        .cat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(255, 133, 162, 0.2);
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
        }

        .badge-type {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--dark-pink);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Search Box */
        .search-box {
            border-radius: 30px;
            padding: 15px 25px;
            border: 2px solid var(--soft-pink);
            box-shadow: 0 5px 15px rgba(255, 179, 193, 0.2);
        }

        .search-box:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 5px 20px rgba(255, 133, 162, 0.3);
        }

        /* Modal Customization */
        .modal-content {
            border: none;
            border-radius: 30px;
        }

        .modal-header {
            background-color: var(--bg-pink);
            border-radius: 30px 30px 0 0;
        }
        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, var(--soft-pink) 0%, var(--primary-pink) 100%);
            border-radius: 0 0 50px 50px;
            padding: 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.3);
        }

        .hero-banner::after {
            content: '🐾';
            position: absolute;
            font-size: 200px;
            bottom: -50px;
            right: -30px;
            opacity: 0.1;
            transform: rotate(-20deg);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-heart-fill me-2"></i>Cat Lover's Catalog
            </a>

            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn <?php echo isset($_SESSION['role']) ? 'btn-pink-filled' : 'btn-pink-outline'; ?> px-4 fw-bold dropdown-toggle"
                        type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>
                        <?php
                        if (isset($_SESSION['role'])) {
                            echo ($_SESSION['role'] === 'admin') ? 'ผู้ดูแลระบบ' : 'บัญชีผู้ใช้';
                        } else {
                            echo 'บัญชีผู้ใช้';
                        }
                        ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 20px;">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><h6 class="dropdown-header text-muted small">เมนูจัดการ</h6></li>
                            <li><a class="dropdown-item py-2" href="catManage.php"><i class="bi bi-gear-fill me-2 text-secondary"></i>ระบบจัดการแมว</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a></li>
                        <?php else: ?>
                            <li>
                                <a class="dropdown-item py-3 text-center" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <span class="btn btn-pink-filled btn-sm w-100">เข้าสู่ระบบ (Admin)</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <header class="hero-banner text-center">
        <div class="container py-4">
            <h1 class="display-4 fw-bold mb-3">เมี๊ยววว... ยินดีต้อนรับ!</h1>
            <p class="fs-5 opacity-90">รวบรวมสายพันธุ์แมวสุดน่ารักจากทั่วทุกมุมโลก</p>
        </div>
        <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control search-box" placeholder="พิมพ์ชื่อสายพันธุ์น้องแมวที่ต้องการ..." onkeyup="searchCat()">
                </div>
            </div>
    </header>

    <div class="container py-5">
        
        <div class="row g-4" id="catGrid"></div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content shadow-lg">
                <div class="modal-body p-5 text-center">
                    <div class="mb-3">
                        <div class="bg-pink d-inline-block p-3 rounded-circle" style="background: var(--bg-pink);">
                            <i class="bi bi-shield-lock-fill" style="font-size: 2.5rem; color: var(--primary-pink);"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-4">Admin Login</h4>
                    <form id="loginForm">
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" id="username" class="form-control rounded-pill border-2" style="border-color: var(--bg-pink);" required>
                        </div>
                        <div class="mb-4 text-start">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" id="password" class="form-control rounded-pill border-2" style="border-color: var(--bg-pink);" required>
                        </div>
                        <button type="submit" class="btn btn-pink-filled w-100 fw-bold py-2 shadow-sm mb-3" id="btnLogin">
                            <span id="btnText">เข้าสู่ระบบ</span>
                        </button>
                    </form>
                    <a href="#" class="text-muted small text-decoration-none" data-bs-dismiss="modal">ไว้คราวหลังนะ</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pe-4 pt-4">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="row">
                        <div class="col-md-5 mb-4">
                            <img id="detailImg" src="" class="img-fluid rounded-custom shadow-sm w-100" style="border-radius: 20px;">
                        </div>
                        <div class="col-md-7">
                            <h5 id="detailNameEn" class="text-uppercase tracking-wider mb-1" style="color: var(--soft-pink); font-size: 0.9rem; letter-spacing: 1px;"></h5>
                            <h2 id="detailNameTh" class="fw-bold mb-3" style="color: var(--dark-pink);"></h2>
                            <p id="detailDesc" class="text-muted lh-lg"></p>
                            
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: #fff0f3;">
                                        <h6 class="fw-bold small mb-1" style="color: var(--dark-pink);"><i class="bi bi-stars me-2"></i>ลักษณะนิสัย</h6>
                                        <p id="detailChar" class="mb-0 small text-muted"></p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background: #fff0f3;">
                                        <h6 class="fw-bold small mb-1" style="color: var(--dark-pink);"><i class="bi bi-hand-thumbs-up me-2"></i>การดูแล</h6>
                                        <p id="detailCare" class="mb-0 small text-muted"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = "http://localhost/IT131/api/product.php"; 
        let allCats = [];
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

        async function fetchCats() {
            try {
                const res = await fetch(API_URL);
                const data = await res.json();
                allCats = data.filter(item => item.is_visible == 1);
                displayCats(allCats);
            } catch (err) {
                console.error("Fetch Error:", err);
            }
        }

        function displayCats(cats) {
            const grid = document.getElementById('catGrid');
            if (cats.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">ไม่พบน้องแมวที่คุณค้นหาเลย...</p></div>';
                return;
            }
            grid.innerHTML = cats.map(cat => `
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card cat-card shadow-sm">
                        <div class="img-container">
                            <span class="badge-type">${cat.name_en}</span>
                            <img src="${cat.image_url}" class="cat-img" onerror="this.src='https://via.placeholder.com/400x300?text=Lovely+Cat'">
                        </div>
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold mb-3">${cat.name_th}</h5>
                            <button class="btn btn-pink-outline w-100 py-2 fw-bold" onclick='showDetails(${JSON.stringify(cat)})'>ดูรายละเอียด</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function showDetails(cat) {
            document.getElementById('detailNameTh').innerText = cat.name_th;
            document.getElementById('detailNameEn').innerText = cat.name_en;
            document.getElementById('detailDesc').innerText = cat.description;
            document.getElementById('detailChar').innerText = cat.characteristics;
            document.getElementById('detailCare').innerText = cat.care_instructions;
            document.getElementById('detailImg').src = cat.image_url;
            detailModal.show();
        }

        function searchCat() {
            const term = document.getElementById('searchInput').value.toLowerCase();
            const filtered = allCats.filter(cat => 
                cat.name_th.toLowerCase().includes(term) || 
                cat.name_en.toLowerCase().includes(term)
            );
            displayCats(filtered);
        }

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btnText = document.getElementById('btnText');
            btnText.innerText = "กำลังเข้าเหมียว...";

            try {
                const response = await fetch('login_process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value
                    })
                });

                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert("❌ " + result.message);
                    btnText.innerText = "เข้าสู่ระบบ";
                }
            } catch (error) {
                alert("เหมียวเกิดข้อผิดพลาดในการเชื่อมต่อ!");
                btnText.innerText = "เข้าสู่ระบบ";
            }
        });

        window.onload = fetchCats;
    </script>
</body>

</html>