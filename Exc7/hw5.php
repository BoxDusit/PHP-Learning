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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>โปรแกรมแยกชื่อ-สกุล</title>
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #fff;
            --accent: #3b6ef6;
            --muted: #6b7280;
        }

        body {
            margin: 0;
            font-family: "Noto Sans Thai", "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg);
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        .container {
            width: 100%;
            max-width: 720px;
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
        }

        h1 {
            margin: 0 0 6px;
            color: var(--accent);
            font-size: 20px;
        }

        .lead {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 12px;
        }

        label {
            min-width: 110px;
            color: var(--muted);
            font-size: 14px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #e6e9ef;
            border-radius: 8px;
            font-size: 15px;
        }

        input[readonly] {
            background: #f8fafc;
        }

        button {
            background: var(--accent);
            color: #fff;
            border: 0;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .result {
            margin-top: 16px;
            border-top: 1px solid #eef2ff;
            padding-top: 16px;
        }

        .small {
            font-size: 13px;
            color: var(--muted);
        }

        @media (max-width:480px) {
            .form-row {
                flex-direction: column;
                align-items: stretch;
            }

            label {
                min-width: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>โปรแกรมแยกชื่อ-สกุล</h1>
            <p class="lead small">กรอกชื่อ-สกุลแบบเต็ม (รวมคำนำหน้า ถ้ามี) แล้วกดปุ่มเพื่อแยก</p>

            <form method="post" novalidate>
                <div class="form-row">
                    <label for="fullname">ชื่อ-สกุล</label>
                    <input id="fullname" type="text" name="fullname" placeholder="เช่น นายสมชาย ใจดี" value="<?= isset(
                                                                                                                    $_POST['fullname']
                                                                                                                ) ? htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8') : '' ?>">
                    <button type="submit">แยก</button>
                </div>
            </form>

            <?php if (!empty($data)) : ?>
                <div class="result">
                    <div class="form-row"><label>คำนำหน้า</label><input type="text" readonly value="<?= htmlspecialchars($data['prefix'], ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div class="form-row"><label>ชื่อ</label><input type="text" readonly value="<?= htmlspecialchars($data['firstname'], ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div class="form-row"><label>สกุล</label><input type="text" readonly value="<?= htmlspecialchars($data['lastname'], ENT_QUOTES, 'UTF-8') ?>"></div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>