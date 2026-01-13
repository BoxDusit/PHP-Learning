<?php
// รับค่าจากผู้ใช้ (ปลอดภัยและเก็บค่าดั้งเดิมไว้ในฟอร์ม)
$amountRaw = isset($_POST['amount']) ? $_POST['amount'] : '';
$amount = is_numeric($amountRaw) ? floatval($amountRaw) : 0;
$isMember = isset($_POST['member']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_numeric($amountRaw) || $amount < 0) {
        $error = 'ยอดซื้อไม่สามารถเป็นค่าที่ไม่ถูกต้องหรือติดลบได้';
    }
}

// กำหนดค่าพื้นฐานสำหรับการคำนวณ
$discountRate = 0;
$level = 'ไม่ได้รับส่วนลด';
$nextTarget = null;
$nextRate = null;

if ($amount >= 5000) {
    $discountRate = 20;
    $level = 'Platinum';
} elseif ($amount >= 3000) {
    $discountRate = 15;
    $level = 'Gold';
    $nextTarget = 5000;
    $nextRate = 20;
} elseif ($amount >= 1000) {
    $discountRate = 10;
    $level = 'Silver';
    $nextTarget = 3000;
    $nextRate = 15;
} elseif ($amount >= 500) {
    $discountRate = 5;
    $level = 'Bronze';
    $nextTarget = 1000;
    $nextRate = 10;
} else {
    $nextTarget = 500;
    $nextRate = 5;
}

$memberRate = 0;
if ($isMember && $amount >= 500) {
    $memberRate = 5;
}

$totalDiscountRate = $discountRate + $memberRate;
$discountMoney = $amount * ($totalDiscountRate / 100);
$netPrice = $amount - $discountMoney;
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>คำนวณส่วนลด</title>
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --accent: #2b6cb0;
            --muted: #6b7280
        }

        body {
            font-family: Segoe UI, Roboto, "Noto Sans Thai", sans-serif;
            background: var(--bg);
            margin: 0;
            padding: 32px;
            display: flex;
            align-items: flex-start;
            justify-content: center
        }

        .container {
            width: 100%;
            max-width: 720px
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(11, 22, 39, 0.06);
            padding: 24px
        }

        h1 {
            margin: 0 0 12px;
            font-size: 20px;
            color: #0f172a
        }

        form .row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 12px
        }

        input[type="number"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #e6e9ef;
            border-radius: 8px;
            font-size: 15px
        }

        label {
            font-size: 14px;
            color: var(--muted)
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center
        }

        button {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer
        }

        .result {
            margin-top: 18px;
            padding: 16px;
            border-radius: 10px;
            background: #fbfdff;
            border: 1px solid #eef2ff
        }

        .muted {
            color: var(--muted);
            font-size: 13px
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: var(--accent);
            font-weight: 600;
            margin-left: 8px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px
        }

        td {
            padding: 6px 4px;
            border-bottom: 1px dashed #eef2ff
        }

        .right {
            text-align: right
        }

        .error {
            color: #b91c1c;
            background: #fff1f2;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 12px
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>โปรแกรมคำนวณส่วนลด</h1>
            <p class="muted">กรอกยอดซื้อและเลือกหากเป็นสมาชิกเพื่อดูส่วนลดที่ได้รับ</p>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row">
                    <input type="number" name="amount" step="0.01" placeholder="ยอดซื้อ (บาท)" value="<?= htmlspecialchars($amountRaw) ?>" required>
                    <label><input type="checkbox" name="member" <?= $isMember ? 'checked' : '' ?>> เป็นสมาชิก</label>
                </div>
                <div class="actions">
                    <button type="submit">คำนวณ</button>
                    <span class="muted">หมายเหตุ: สมาชิกรับ +5% เมื่อยอดไม่ต่ำกว่า 500 บาท</span>
                </div>
            </form>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error): ?>
                <div class="result">
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div>
                            <strong>สรุปผล</strong>
                            <span class="badge"><?= $level ?></span>
                        </div>
                        <div class="muted">ยอดซื้อ: <strong><?= number_format($amount, 2) ?> บาท</strong></div>
                    </div>

                    <table>
                        <tr>
                            <td>ระดับส่วนลด</td>
                            <td class="right"><?php if ($discountRate > 0) echo $discountRate . '%';
                                                else echo '–'; ?></td>
                        </tr>
                        <tr>
                            <td>ส่วนลดสมาชิก</td>
                            <td class="right"><?php if ($memberRate > 0) echo '+' . $memberRate . '%';
                                                else echo '–'; ?></td>
                        </tr>
                        <tr>
                            <td>รวมส่วนลด</td>
                            <td class="right"><?= $totalDiscountRate ?>%</td>
                        </tr>
                        <tr>
                            <td>จำนวนส่วนลด</td>
                            <td class="right"><?= number_format($discountMoney, 2) ?> บาท</td>
                        </tr>
                        <tr>
                            <td>ราคาที่ต้องจ่าย</td>
                            <td class="right"><strong><?= number_format($netPrice, 2) ?> บาท</strong></td>
                        </tr>
                    </table>

                    <?php if ($nextTarget !== null && $amount < $nextTarget):
                        $needMore = $nextTarget - $amount; ?>
                        <p class="muted">แนะนำ: ซื้อเพิ่มอีก <strong><?= number_format($needMore, 2) ?> บาท</strong> เพื่อรับส่วนลด <?= $nextRate ?>%.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>