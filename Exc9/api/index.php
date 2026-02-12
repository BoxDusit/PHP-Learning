<!DOCTYPE html>
<html>

<head>
    <title>Product API Test</title>
</head>

<body>
    <h2>เพิ่มสินค้า</h2>
    <input type="text" id="name" placeholder="ชื่อสินค้า">
    <input type="number" id="price" placeholder="ราคา">
    <button onclick="addProduct()">เพิ่มสินค้า</button>
    <script>
        function addProduct() {
            fetch("https://hosting.udru.ac.th/~it67040233115/PHP-Learning/Exc9/api/product.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        product_name: document.getElementById("name").value,
                        price: document.getElementById("price").value
                    })
                })
                .then(res => res.json())
                .then(data => console.log(data));
        }
    </script>
</body>

</html>