<?php
session_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <title>Galactic Cat Catalog - Explorer Edition</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Orbitron:wght@400;700;900&display=swap');

        :root {
            --space-black: #020617;
            --space-deep: #0f172a;
            --neon-purple: #a855f7;
            --neon-blue: #22d3ee;
            --text-main: #f8fafc;
        }

        body {
            background-color: var(--space-black);
            font-family: 'Kanit', sans-serif;
            color: var(--text-main);
            margin: 0;
            overflow-x: hidden;
        }

        /* Star Background Animation */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(1px 1px at 20px 30px, #fff, rgba(0, 0, 0, 0)),
                radial-gradient(1px 1px at 40px 70px, #fff, rgba(0, 0, 0, 0)),
                radial-gradient(2px 2px at 50px 160px, var(--neon-blue), rgba(0, 0, 0, 0)),
                radial-gradient(1px 1px at 90px 40px, #fff, rgba(0, 0, 0, 0));
            background-size: 200px 200px;
            animation: starsAnim 100s linear infinite;
            z-index: -1;
            opacity: 0.5;
        }

        @keyframes starsAnim {
            from {
                background-position: 0 0;
            }

            to {
                background-position: 0 10000px;
            }
        }

        /* Glow Orbs */
        .glow-orb {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.2;
        }

        .orb-1 {
            top: -100px;
            left: -100px;
            background: var(--neon-purple);
        }

        .orb-2 {
            bottom: -100px;
            right: -100px;
            background: var(--neon-blue);
        }

        /* Navbar */
        .navbar {
            background: rgba(2, 6, 23, 0.7) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(168, 85, 247, 0.2);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            color: var(--neon-blue) !important;
            text-shadow: 0 0 10px rgba(34, 211, 238, 0.5);
            letter-spacing: 3px;
        }

        /* Hero Section */
        .hero-banner {
            padding: 120px 0 80px;
            text-align: center;
        }

        .hero-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            font-size: 4.5rem;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fff 30%, var(--neon-purple) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            letter-spacing: -2px;
        }

        .search-container {
            max-width: 700px;
            margin: 40px auto 0;
            position: relative;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 100px;
            padding: 18px 30px 18px 65px;
            color: white;
            font-size: 1.1rem;
            backdrop-filter: blur(10px);
            transition: 0.4s;
        }

        .search-box:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--neon-blue);
            box-shadow: 0 0 30px rgba(34, 211, 238, 0.2);
            outline: none;
            color: white;
        }

        .search-icon {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: var(--neon-blue);
        }

        /* Cat Cards */
        .cat-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
            transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(5px);
            position: relative;
        }

        .cat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(168, 85, 247, 0.1), transparent);
            transform: translateX(-100%);
            transition: 0.6s;
        }

        .cat-card:hover::before {
            transform: translateX(100%);
        }

        .cat-card:hover {
            transform: translateY(-15px) scale(1.02);
            border-color: var(--neon-purple);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(168, 85, 247, 0.2);
        }

        .img-container {
            height: 280px;
            position: relative;
            overflow: hidden;
        }

        .cat-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.8s;
            filter: saturate(0.8) contrast(1.1);
        }

        .cat-card:hover .cat-img {
            transform: scale(1.1);
            filter: saturate(1.2);
        }

        .card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px 20px 20px;
            background: linear-gradient(to top, var(--space-black) 20%, transparent);
            text-align: center;
        }

        .btn-neon-blue {
            background: transparent;
            color: var(--neon-blue);
            border: 1px solid var(--neon-blue);
            border-radius: 12px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.8rem;
            padding: 10px 20px;
            transition: 0.3s;
            letter-spacing: 1px;
            width: 100%;
        }

        .btn-neon-blue:hover {
            background: var(--neon-blue);
            color: var(--space-black);
            box-shadow: 0 0 20px var(--neon-blue);
        }

        /* Modal Ultra Modern */
        .modal-content {
            background: rgba(2, 6, 23, 0.9);
            border: 1px solid rgba(168, 85, 247, 0.5);
            border-radius: 40px;
            backdrop-filter: blur(30px);
            color: white;
            box-shadow: 0 0 50px rgba(168, 85, 247, 0.2);
        }

        .modal-body-custom {
            display: grid;
            grid-template-columns: 1.2fr 1.8fr;
            min-height: 500px;
        }

        .modal-img-side {
            background-size: cover;
            background-position: center;
            border-radius: 40px 0 0 40px;
            position: relative;
        }

        .modal-img-side::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(2, 6, 23, 0.9));
        }

        .info-panel {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 992px) {
            .modal-body-custom {
                grid-template-columns: 1fr;
            }

            .modal-img-side {
                height: 300px;
                border-radius: 40px 40px 0 0;
            }

            .modal-img-side::after {
                background: linear-gradient(to bottom, transparent, rgba(2, 6, 23, 0.9));
            }
        }
    </style>
</head>

<body>
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-rocket-takeoff-fill me-2"></i>CAT-A-VERSE
            </a>
            <div class="ms-auto">
                <button class="btn btn-outline-info rounded-pill px-4 me-2" onclick="location.href='catManage.php'">
                    <i class="bi bi-cpu me-2"></i>DASHBOARD
                </button>
            </div>
        </div>
    </nav>



    <div class="container pb-5 pt-4">
        <div class="row g-4" id="catGrid"></div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content text-center p-4">
                <div class="py-4">
                    <i class="bi bi-shield-lock-fill text-info" style="font-size: 4rem;"></i>
                    <h2 class="fw-bold mt-3" style="font-family: 'Orbitron';">IDENTITY</h2>
                </div>
                <form id="loginForm" class="p-3">
                    <input type="text" id="username" class="form-control mb-3 bg-dark border-secondary text-white rounded-pill px-4" placeholder="Username" required>
                    <input type="password" id="password" class="form-control mb-4 bg-dark border-secondary text-white rounded-pill px-4" placeholder="Password" required>
                    <button type="submit" class="btn btn-neon-blue py-2 rounded-pill fw-bold" id="btnLogin">AUTHORIZE</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content overflow-hidden">
                <div class="modal-body p-0">
                    <div class="modal-body-custom">
                        <div id="modalSideImg" class="modal-img-side"></div>
                        <div class="p-5 position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-4 shadow-none" data-bs-dismiss="modal"></button>

                            <h6 id="detailNameEn" class="text-info text-uppercase mb-2 fw-bold" style="font-family: 'Orbitron'; letter-spacing: 4px;"></h6>
                            <h2 id="detailNameTh" class="display-4 fw-bold mb-4 text-white"></h2>

                            <p id="detailDesc" class="fs-5 text-light opacity-75 mb-5"></p>

                            <div class="info-panel">
                                <h6 class="text-info fw-bold mb-2"><i class="bi bi-patch-check-fill me-2"></i>CHARACTERISTICS</h6>
                                <p id="detailChar" class="mb-0"></p>
                            </div>

                            <div class="info-panel" style="border-left: 4px solid var(--neon-blue);">
                                <h6 class="text-info fw-bold mb-2"><i class="bi bi-heart-pulse-fill me-2"></i>CARE PROTOCOL</h6>
                                <p id="detailCare" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = "http://localhost/IT137(2)/api/product.php";
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
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">Lost in space: No data found...</p></div>';
                return;
            }
            grid.innerHTML = cats.map(cat => `
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="cat-card">
                        <div class="img-container">
                            <img src="${cat.image_url}" class="cat-img" onerror="this.src='https://via.placeholder.com/400x500/020617/ffffff?text=Mystery+Cat'">
                        </div>
                        <div class="card-overlay">
                            <h4 class="fw-bold text-white mb-0">${cat.name_th}</h4>
                            <p class="text-info small opacity-75 mb-3">${cat.name_en}</p>
                            <button class="btn btn-neon-blue" onclick='showDetails(${JSON.stringify(cat)})'>SCAN PROFILE</button>
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
            document.getElementById('modalSideImg').style.backgroundImage = `url('${cat.image_url}')`;
            detailModal.show();
        }

        function searchCat() {
            const term = document.getElementById('searchInput').value.toLowerCase();
            const filtered = allCats.filter(cat =>
                cat.name_th.toLowerCase().includes(term) || cat.name_en.toLowerCase().includes(term)
            );
            displayCats(filtered);
        }

        // Login Logic
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btnLogin');
            btn.innerText = "LINKING...";
            try {
                const response = await fetch('login_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value
                    })
                });
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert("Access Denied: " + result.message);
                    btn.innerText = "AUTHORIZE";
                }
            } catch (error) {
                alert("Signal Lost!");
                btn.innerText = "AUTHORIZE";
            }
        });

        window.onload = fetchCats;
    </script>


</body>

</html>