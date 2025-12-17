<?php
// 1. สร้าง Function สำหรับคำนวณ BMI
function calculateBMI($weight, $height_cm) {
    // แปลงส่วนสูงจาก เซนติเมตร เป็น เมตร
    $height_m = $height_cm / 100;
    // สูตร BMI = น้ำหนัก (kg) / (ส่วนสูง (m) * ส่วนสูง (m))
    $bmi = $weight / ($height_m * $height_m);
    return round($bmi, 2);
}

// 2. รับค่าจาก Method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    if ($weight > 0 && $height > 0) {
        $bmi_result = calculateBMI($weight, $height);
        
        // 3. แปลผลและให้คำแนะนำ
        $label = "";
        $advice = "";

        if ($bmi_result < 18.5) {
            $label = "น้ำหนักน้อยกว่ามาตรฐาน (ผอม)";
            $advice = "ควรรับประทานอาหารที่มีสารอาหารครบ 5 หมู่ และเพิ่มปริมาณแคลอรี่ต่อวัน";
            $badgeClass = 'bg-info text-dark';
        } elseif ($bmi_result >= 18.5 && $bmi_result <= 22.9) {
            $label = "น้ำหนักปกติ (สุขภาพดี)";
            $advice = "รักษาสุขภาพให้ดีแบบนี้ต่อไป ออกกำลังกายสม่ำเสมอครับ";
            $badgeClass = 'bg-success';
        } elseif ($bmi_result >= 23.0 && $bmi_result <= 24.9) {
            $label = "น้ำหนักเกิน (ท้วม)";
            $advice = "เริ่มมีความเสี่ยง ควรควบคุมปริมาณน้ำตาลและแป้งในอาหาร";
            $badgeClass = 'bg-warning text-dark';
        } elseif ($bmi_result >= 25.0 && $bmi_result <= 29.9) {
            $label = "อ้วน (ระดับ 1)";
            $advice = "ควรปรับเปลี่ยนพฤติกรรมการกิน และหาเวลาออกกำลังกายอย่างจริงจัง";
            $badgeClass = 'bg-danger';
        } else {
            $label = "อ้วนมาก (ระดับ 2)";
            $advice = "มีความเสี่ยงต่อโรคแทรกซ้อนสูง ควรปรึกษาแพทย์หรือนักโภชนาการ";
            $badgeClass = 'bg-danger';
        }
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ผลการคำนวณ BMI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg,#f5f7fa,#e4eef8); min-height:100vh; }
        .card { border-radius:12px; box-shadow:0 8px 24px rgba(32,45,80,0.06); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card p-4 text-center">
                    <h4 class="mb-3">ผลการคำนวณของคุณ</h4>
                    <div class="mb-3">
                        <div class="h1 fw-bold"><?php echo $bmi_result; ?></div>
                        <div class="mb-2"><span class="badge <?php echo $badgeClass; ?> py-2 px-3 fs-6"><?php echo $label; ?></span></div>
                    </div>
                    <p class="mb-4"><strong>คำแนะนำ:</strong> <?php echo $advice; ?></p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="Post.html" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i>คำนวณใหม่</a>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    } else {
        echo "กรุณากรอกข้อมูลที่ถูกต้อง";
    }
}
?>
