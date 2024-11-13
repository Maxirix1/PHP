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
            $newToken = bin2hex(random_bytes(32));

            $updateStmt = $conn->prepare('UPDATE users_signup SET token = :token WHERE userId = :userId');
            $updateStmt->execute(['token'=> $newToken, 'userId' => $user['userId']]);

            $_SESSION['token'] = $newToken;
            $_SESSION['userID'] = $user['userId'];
            $_SESSION['hn'] = $user['userHn'];
            $_SESSION['username'] = $user['userName'];

            header('Location: ../client/homeTH');
            exit();
        } else {
            $_SESSION['error'] = 'เลข HN หรือ รหัสผ่านไม่ถูกต้อง';
            header("Location: ../client/login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'เกิดข้อผิดพลาด: โปรดลองใหม่อีกครั้ง';
        header("Location: ../client/login.php");
        exit();
    }
}
?>
