<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ../client/HomeTH');
    exit();
}


require_once 'config.php';

try {

    // $sql_reserve = "SELECT * FROM reserve_time";
    // $stmt_reserve = $conn->query($sql_reserve);
    // $reserve = $stmt_reserve->fetchAll(PDO::FETCH_ASSOC);

    $sql_time = "SELECT range_time, qty_taking FROM setting_reserve";
    $stmt_time = $conn->query($sql_time);
    $settings = $stmt_time->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>
