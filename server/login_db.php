<?php
session_start();
require_once './config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $hn = $_POST['hn'];
        $password = $_POST['password'];

        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare('SELECT * FROM users_signup WHERE userHN = :hn');
        $stmt->execute(['hn' => $hn]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // สร้าง token ใหม่
            $newToken = bin2hex(random_bytes(32));

            // อัพเดท token ในฐานข้อมูล
            $updateStmt = $conn->prepare('UPDATE users_signup SET token = :token WHERE userId = :userId');
            $updateStmt->execute(['token'=> $newToken, 'userId' => $user['userId']]);

            // ตั้งค่า session
            $_SESSION['token'] = $newToken;
            $_SESSION['userID'] = $user['userId'];
            $_SESSION['hn'] = $user['userHn'];
            $_SESSION['username'] = $user['userName'];

            // Redirect ไปยังหน้า home
            header('Location: ../client/homeTH');
            exit();
        } else {
            // ถ้าเลข HN หรือ รหัสผ่านไม่ถูกต้อง
            $_SESSION['error'] = 'เลข HN หรือ รหัสผ่านไม่ถูกต้อง';
            header("Location: ../client/login.php");
            exit();
        }
    } catch (Exception $e) {
        // จัดการข้อผิดพลาดที่เกิดขึ้น
        $_SESSION['error'] = 'เกิดข้อผิดพลาด: โปรดลองใหม่อีกครั้ง';
        header("Location: ../client/login.php");
        exit();
    }
}
?>
