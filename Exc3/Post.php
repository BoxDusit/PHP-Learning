<?php
// 1. สร้าง Function สำหรับคำนวณ BMI
function calculateBMI($weight, $height_cm) {
    $height_m = $height_cm / 100;
    $bmi = $weight / ($height_m * $height_m);
    return round($bmi, 2);
}

// 2. รับค่าจาก Method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $weight = isset($_POST['weight']) ? (float)$_POST['weight'] : 0;
    $height = isset($_POST['height']) ? (float)$_POST['height'] : 0;

    $errors = [];
    if ($name === '') {
        $errors[] = 'กรุณากรอกชื่อ';
    }
    if (empty($dob)) {
        $errors[] = 'กรุณาเลือกวันเกิด';
    } else {
        try {
            $dob_dt = new DateTime($dob);
            $today_dt = new DateTime();
            if ($dob_dt > $today_dt) {
                $errors[] = 'วันเกิดต้องไม่เป็นวันในอนาคต';
            } else {
                $age = $today_dt->diff($dob_dt)->y;
            }
        } catch (Exception $e) {
            $errors[] = 'รูปแบบวันเกิดไม่ถูกต้อง';
        }
    }

    if ($weight <= 0 || $height <= 0) {
        $errors[] = 'กรุณากรอกน้ำหนักและส่วนสูงที่ถูกต้อง';
    }

    if (empty($errors)) {
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

        // บันทึกข้อมูลการกรอก (ต่อท้ายไฟล์ CSV ภายในโฟลเดอร์นี้)
        $csvFile = __DIR__ . '/submissions.csv';
        $csvRow = [
            $name,
            isset($dob_dt) ? $dob_dt->format('Y-m-d') : $dob,
            isset($age) ? (int)$age : '',
            $weight,
            $height,
            $bmi_result,
            date('Y-m-d H:i:s')
        ];
        $csvError = '';
        $needHeader = !file_exists($csvFile);
        if ($fp = @fopen($csvFile, 'a')) {
            if ($needHeader) {
                fputcsv($fp, ['name', 'dob', 'age', 'weight', 'height', 'bmi', 'created_at']);
            }
            fputcsv($fp, $csvRow);
            fclose($fp);
        } else {
            $csvError = 'ไม่สามารถบันทึกไฟล์ submissions.csv ได้ โปรดตรวจสอบสิทธิ์การเขียนของโฟลเดอร์';
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
        .result-bmi { font-size:3rem; line-height:1; }
        .small-note { font-size:0.9rem; color:#6b7280; }
        .kv th { width:36%; text-align:right; padding-right:1rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-0">ผลการคำนวณ</h4>
                            <div class="small-note">ผลจากข้อมูลที่คุณกรอก</div>
                        </div>
                        <div class="text-end">
                            <a href="Post.html" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i>คำนวณใหม่</a>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-auto text-center pe-4">
                            <div class="result-bmi fw-bold"><?php echo $bmi_result; ?></div>
                            <div class="mt-2"><span class="badge <?php echo $badgeClass; ?> py-2 px-3 fs-6"><?php echo $label; ?></span></div>
                        </div>
                        <div class="col">
                            <table class="table table-borderless mb-0 kv">
                                <tbody>
                                    <tr><th scope="row">ชื่อ</th><td><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                    <tr><th scope="row">วันเกิด</th><td><?php echo htmlspecialchars(isset($dob_dt) ? $dob_dt->format('Y-m-d') : $dob, ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                    <tr><th scope="row">อายุ</th><td><?php echo isset($age) ? (int)$age . ' ปี' : '-'; ?></td></tr>
                                    <tr><th scope="row">น้ำหนัก</th><td><?php echo htmlspecialchars($weight, ENT_QUOTES, 'UTF-8'); ?> กก.</td></tr>
                                    <tr><th scope="row">ส่วนสูง</th><td><?php echo htmlspecialchars($height, ENT_QUOTES, 'UTF-8'); ?> ซม.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="alert alert-light border"><strong>คำแนะนำ:</strong> <?php echo htmlspecialchars($advice, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>

                    <?php if (!empty($csvError)): ?>
                        <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($csvError, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php else: ?>
                        <div class="text-muted small mt-2">ข้อมูลถูกบันทึกแล้ว</div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    } else {
        // แสดงหน้าข้อความแจ้งข้อผิดพลาดแบบสวยงาม
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>พบข้อผิดพลาด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card p-4">
                    <h4 class="mb-3">พบข้อผิดพลาดในการส่งข้อมูล</h4>
                    <div class="alert alert-danger">
                        กรุณาแก้ไขข้อมูลต่อไปนี้ก่อนส่ง:
                        <ul class="mb-0">
                            <?php foreach ($errors as $err) { echo '<li>' . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . '</li>'; } ?>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="Post.html" class="btn btn-outline-secondary">กลับไปแก้ไข</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    }
}  
?>
