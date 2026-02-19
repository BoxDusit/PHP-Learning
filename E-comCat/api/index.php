<!DOCTYPE html>
<html>

<head>
    <title>Product API Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h1 class="text-center mb-4">RESTFULLAPI Test</h1>

    <div class="text-end mb-3">
        <button class="btn btn-success" onclick="openAddModal()">+ เพิ่มสินค้า</button>
    </div>

    <!-- ตาราง -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคา</th>
                <th width="180">จัดการ</th>
            </tr>
        </thead>
        <tbody id="productTable"></tbody>
    </table>
</div>

<!-- ================= Modal เพิ่ม/แก้ไข ================= -->
<div class="modal fade" id="productModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">เพิ่มสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="id">
                <div class="mb-3">
                    <label class="form-label">ชื่อสินค้า</label>
                    <input type="text" id="name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">ราคา</label>
                    <input type="number" id="price" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button class="btn btn-primary" onclick="saveProduct()">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<!-- ================= Modal ลบ ================= -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p>ต้องการลบสินค้านี้หรือไม่?</p>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete()">ลบ</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const API_URL = "https://hosting.udru.ac.th/~it67040233115/PHP-Learning/Exc9/api/product.php";

let productModal = new bootstrap.Modal(document.getElementById('productModal'));
let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
let deleteId = null;


// ================= โหลดตาราง =================
function loadTable() {
    fetch(API_URL)
        .then(res => res.json())
        .then(data => {
            let rows = "";
            data.forEach(product => {
                rows += `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.product_name}</td>
                        <td>${product.price}</td>
                        <td>
                            <button class="btn btn-sm btn-primary"
                                onclick="openEditModal(${product.id}, '${product.product_name}', ${product.price})">
                                แก้ไข
                            </button>
                            <button class="btn btn-sm btn-danger"
                                onclick="openDeleteModal(${product.id})">
                                ลบ
                            </button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById("productTable").innerHTML = rows;
        });
}


// ================= เปิด Modal เพิ่ม =================
function openAddModal() {
    document.getElementById("modalTitle").innerText = "เพิ่มสินค้า";
    clearForm();
    productModal.show();
}


// ================= เปิด Modal แก้ไข =================
function openEditModal(id, name, price) {
    document.getElementById("modalTitle").innerText = "แก้ไขสินค้า";
    document.getElementById("id").value = id;
    document.getElementById("name").value = name;
    document.getElementById("price").value = price;
    productModal.show();
}


// ================= บันทึก (เพิ่ม/แก้ไข) =================
function saveProduct() {

    const id = document.getElementById("id").value;
    const method = id ? "PUT" : "POST";

    fetch(API_URL, {
        method: method,
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            id: id,
            product_name: document.getElementById("name").value,
            price: document.getElementById("price").value
        })
    })
    .then(res => res.json())
    .then(data => {
        productModal.hide();
        loadTable();
    });
}


// ================= เปิด Modal ลบ =================
function openDeleteModal(id) {
    deleteId = id;
    deleteModal.show();
}


// ================= ยืนยันลบ =================
function confirmDelete() {
    fetch(API_URL, {
        method: "DELETE",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ id: deleteId })
    })
    .then(res => res.json())
    .then(data => {
        deleteModal.hide();
        loadTable();
    });
}


// ================= ล้างฟอร์ม =================
function clearForm() {
    document.getElementById("id").value = "";
    document.getElementById("name").value = "";
    document.getElementById("price").value = "";
}

window.onload = loadTable;

</script>

</body>
</html>
