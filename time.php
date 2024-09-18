<?php
session_start();
require_once './config.php';


$sql = "SELECT range_time, qty_taking FROM setting_reserve";
$stmt = $conn->query($sql);

$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$timeSlots = [];

foreach ($settings as $setting) {

    $rangeTime = $setting['range_time'];
    list($startTime, $endTime) = explode('-', $rangeTime);

    $startTimestamp = strtotime($startTime);
    $endTimestamp = strtotime($endTime);

    $qtyTaking = (int) $setting['qty_taking'];

    $interval = round(($endTimestamp - $startTimestamp) / $qtyTaking);

    for ($i = 0; $i < $qtyTaking; $i++) {
        $newTime = $startTimestamp + ($i * $interval);
        $slot[] = date('H:i', $newTime);
    }

    $timeSlots[] = $slots;
}

$_SESSION['timeSlots'] = $timeSlots;

header('Location: ./client/home.php');
exit();


?>