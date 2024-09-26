<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ./login.php');
    exit();
}

require_once './config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีค่า selectedDate ใน SESSION หรือไม่
    if (isset($_SESSION['selectedDate'])) {
        $selectedDate = $_SESSION['selectedDate'];
        $hn = $_SESSION['hn'];
        $department = $_SESSION['selected_department'];

        // สร้างคำสั่ง SQL เพื่อบันทึกข้อมูล
        $sql = "INSERT INTO reserve_time (date, hn, dept) VALUES (:selectedDate, :hn, :department)"; // แก้ไขชื่อ table และ column ให้ตรงกับฐานข้อมูลของคุณ

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':selectedDate', $selectedDate);
            $stmt->bindParam(':hn', $hn);
            $stmt->bindParam(':department', $department);
            $stmt->execute(); 

            echo "บันทึกวันที่สำเร็จ: " . htmlspecialchars($selectedDate);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "ไม่มีวันที่ที่เลือก";
    }
}
?>
