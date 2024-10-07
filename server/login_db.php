<?php
session_start();



require_once './config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hn = $_POST['hn'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users_signup WHERE userHN = :hn');
    $stmt->execute(['hn' => $hn]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
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
}

ini_set('display_errors', 0);
error_reporting(0);

?>