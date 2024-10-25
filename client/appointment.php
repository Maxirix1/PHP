<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../server/config.php';
include './phpqrcode/qrlib.php'; // เรียกใช้ phpqrcode

if (isset($_GET['uuid'])) {
    $uuid = $_GET['uuid'];
    $sql = "SELECT * FROM reserve_time WHERE appointment_id = :uuid";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $hnData = $row['hn'];
            $sql_data = "SELECT * FROM users_signup WHERE userHn = :hn";
            $stmt = $conn->prepare($sql_data);
            $stmt->bindParam(':hn', $hnData);
            $stmt->execute();
            $hn = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $hn['userName'];
            $profile = $hn['profile'];

            // สร้างลิงก์ที่ต้องการใช้ใน QR code
            $link = "http://maxirix.thddns.net:7374/PHP/client/profile.php?name=$profile";
            // กำหนดที่อยู่ไฟล์ PNG ที่จะเก็บ QR code
            $tempDir = 'tempDir/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir);
            }
            $fileName = $tempDir . $profile . '.png';


            if (!file_exists($fileName)) {
                $fileName = $tempDir . $profile . '.png';
                $link = "http://maxirix.thddns.net:7374/PHP/client/profile.php?name=$profile";

                // สร้าง QR Code และบันทึกในไฟล์
                QRcode::png($link, $fileName, QR_ECLEVEL_L, 2);
            }

        } else {
            echo "Error: No record found.";
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
        exit;
    }
} else {
    echo "ไม่พบ UUID ที่ต้องการ";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/logoHead.png">
    <title>AZTEC | ใบนัด</title>
    <link rel="stylesheet" href="./style/appointment.css">
</head>

<body>
    <header>
        <div class="head">
            <img src="./assets/logo_hospitol.png" alt="">
            <h2>บัตรนัดผู้ป่วยโรงพยาบาลบางบ่อ</h2>
        </div>
        <div class="name">
            <p>คุณ <?= htmlspecialchars($name) ?></p>
        </div>
        <div class="time">
            <p>เวลานัดหมาย เพื่อพบเจ้าหน้าที่ซักประวัติ <?= htmlspecialchars($row['reserve_time']) ?></p>
            <span class="textDis">คลินิกโรคจากการทำงาน</span>
        </div>
        <h1>คิวที่ : <?= htmlspecialchars($row['queue_no']) ?></h1>
        <p class="reason">เหตุที่นัด : <?= htmlspecialchars($row['reason']) ?></p>
    </header>

    <!-- แสดง QR code -->
    <div class="qrcode">
        <!-- <h3>สแกน QR Code เพื่อตรวจสอบรายละเอียด:</h3> -->
        <img src="<?= $fileName ?>" alt="QR Code">
    </div>

    <footer>
        <p>ถ้าต้องการเลื่อนนัดกรุณาโทรก่อนถึงวันนัดอย่างน้อย 7 วันในเวลาราชการ</p>

        <button id="goback">ย้อนกลับ</button>
        <script>
            document.getElementById('goback').onclick = function() {
                window.history.back();
            }
        </script>
    </footer>
</body>

</html>