<?php

if (!isset($_SESSION['hn'])) {
    header('Location: ../client/HomeTH');
    exit();
}

require_once 'config.php';

try {
    $sql = "SELECT range_time, qty_taking FROM setting_reserve";
    $stmt = $conn->query($sql);
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$timeSlots = [];

// สร้างช่วงเวลาจากการตั้งค่า
foreach ($settings as $setting) {
    $rangeTime = $setting['range_time'];
    list($startTime, $endTime) = explode('-', $rangeTime);

    $startTimestamp = strtotime($startTime);
    $endTimestamp = strtotime($endTime);

    $qtyTaking = (int) $setting['qty_taking'];

    $interval = round(($endTimestamp - $startTimestamp) / $qtyTaking);
    
    $slot = [];
    for ($i = 0; $i < $qtyTaking; $i++) {
        $newTime = $startTimestamp + ($i * $interval);
        $slot[] = date('H:i', $newTime);
    }

    $timeSlots[] = $slot;
}

function createTimeRanges() {
    $rangeTimes = [];
    for ($i = 8; $i <= 15; $i++) {
        $start = sprintf("%02d:00", $i);
        $end = sprintf("%02d:29", $i);
        $rangeTimes[] = "$start - $end";
        
        // เพิ่มช่วงเวลาสำหรับ 30 นาที
        $start = sprintf("%02d:30", $i);
        $end = sprintf("%02d:59", $i);
        $rangeTimes[] = "$start - $end";
    }
    return $rangeTimes;
}

// ฟังก์ชันตรวจสอบว่า selectedTime อยู่ในช่วงเวลาไหน
function checkSelectedTime($selectedTime, $rangeTimes) {
    foreach ($rangeTimes as $range) {
        list($start, $end) = explode(' - ', $range);
        if ($selectedTime >= $start && $selectedTime <= $end) {
            return $range; // คืนค่าช่วงเวลาที่ตรงกัน
        }
    }
    return null; // ถ้าไม่ตรงกัน
}

if (!empty($timeSlots)) {
    $_SESSION['timeSlots'] = $timeSlots;
} else {
    echo 'No time slots were generated.';
}
?>
