<?php
require_once '../config.php'; // เชื่อมต่อฐานข้อมูล

try {
    $sql = "SELECT range_time, qty_taking FROM setting_reserve";
    $stmt = $conn->query($sql);
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$timeSlots = [];

// $mergedTimeSlots = [];

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

if (!empty($timeSlots)) {
    $_SESSION['timeSlots'] = $timeSlots;
} else {
    echo 'No time slots were generated.';
}
// header('Location: ./home.php');
// exit();
?>
