<?php
function splitThaiName($fullName)
{
    $prefixes = [
        "นาย",
        "นาง",
        "นางสาว",
        "เด็กชาย",
        "เด็กหญิง",
        "น.ส.",
        "ด.ช.",
        "ด.ญ.",
        "ร.ต.ต.",
        "ด.ต.",
        "มรว.",
        "ผศ.",
        "ดร."
    ];

    $result = [
        "prefix" => "",
        "firstname" => "",
        "lastname" => ""
    ];

    $fullName = trim(preg_replace('/\s+/', ' ', $fullName));

    // ตรวจหาคำนำหน้าที่อยู่ด้านหน้า แม้จะไม่มีช่องว่างระหว่างคำนำหน้าและชื่อ
    // เรียงคำนำหน้าตามความยาวเพื่อจับคำนำหน้าที่ยาวที่สุดก่อน
    usort($prefixes, function ($a, $b) {
        return mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8');
    });

    foreach ($prefixes as $p) {
        $len = mb_strlen($p, 'UTF-8');
        if (mb_substr($fullName, 0, $len, 'UTF-8') === $p) {
            $result['prefix'] = $p;
            $fullName = ltrim(mb_substr($fullName, $len, null, 'UTF-8'));
            break;
        }
    }

    $parts = explode(' ', $fullName);

    // ถ้ามีเพียงคำเดียว ให้ถือเป็นชื่อ (ไม่มีสกุล)
    if (count($parts) === 1) {
        $result['firstname'] = $parts[0];
        return $result;
    }

    $result['firstname'] = $parts[0];
    $result['lastname'] = implode(' ', array_slice($parts, 1));

    return $result;
}

// ค่าเริ่มต้นสำหรับการแสดงผล (ป้องกันตัวแปรไม่ถูกกำหนด)
$data = [
    "prefix" => "",
    "firstname" => "",
    "lastname" => ""
];

if (isset($_POST['fullname'])) {
    $data = splitThaiName($_POST['fullname']);
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>โปรแกรมแยกชื่อ-สกุล</title>
    <style>
        body {
            margin: 0;
            background: #f5f7fb;
            font-family: "Noto Sans Thai", Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            width: 720px;
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        h1 {
            margin: 0 0 6px;
            color: #3b6ef6;
            font-size: 22px;
        }

        .desc {
            margin-bottom: 22px;
            color: #6b7280;
            font-size: 14px;
        }

        .row {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #6b7280;
            font-size: 14px;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #dbe1f1;
            font-size: 15px;
        }

        input[readonly] {
            background: #f8fafc;
        }

        button {
            padding: 0 18px;
            background: #3b6ef6;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .result {
            margin-top: 24px;
            border-top: 1px solid #eef2ff;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h1>โปรแกรมแยกชื่อ-สกุล</h1>
        <div class="desc">กรอกชื่อ-สกุลแบบเต็ม (รวมคำนำหน้า ถ้ามี) แล้วกดปุ่มเพื่อแยก</div>

        <form method="post">
            <div class="row">
                <label>ชื่อ-สกุล</label>
                <div class="input-group">
                    <input type="text" name="fullname" placeholder="เช่น นายสมชาย ใจดี"
                        value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8') : '' ?>">
                    <button type="submit">แยก</button>
                </div>
            </div>
        </form>

        <div class="result">
            <div class="row">
                <label>คำนำหน้า</label>
                <input type="text" readonly value="<?= htmlspecialchars($data['prefix'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="row">
                <label>ชื่อ</label>
                <input type="text" readonly value="<?= htmlspecialchars($data['firstname'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="row">
                <label>สกุล</label>
                <input type="text" readonly value="<?= htmlspecialchars($data['lastname'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </div>
    </div>

</body>

</html>
