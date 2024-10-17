<?php
session_start();
require_once './config.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $sessionToken = $_SESSION['token'];

    // ดึง token ปัจจุบันจากฐานข้อมูล
    $stmt = $conn->prepare('SELECT token FROM users_signup WHERE userId = :userID');
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $dbToken = $user['token'];

        // เปรียบเทียบ token ระหว่างเซสชันปัจจุบันและฐานข้อมูล
        if ($sessionToken !== $dbToken) {
            // ถ้า token ไม่ตรงกัน ให้ส่งสถานะ logout
            echo json_encode(['status' => 'logout']);
        } else {
            // ถ้า token ตรงกัน ให้ส่งสถานะ success
            echo json_encode(['status' => 'active']);
        }
    } else {
        // ถ้าผู้ใช้ไม่พบในฐานข้อมูล ให้ส่งสถานะ logout
        echo json_encode(['status' => 'logout']);
    }
} else {
    // ถ้าไม่มีการล็อกอิน ให้ส่งสถานะ logout
    echo json_encode(['status' => 'logout']);
}
?>
