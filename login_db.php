<?php
session_start();
require_once './config.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $hn = $_POST['hn'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM reserve_time WHERE hn = :hn');
    $stmt->execute(['hn' => $hn]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $defaultPassword = $hn . '00';

    if ($user && $password === $defaultPassword) {
        $_SESSION['hn'] = $user['hn'];
        header('Location: ./client/home.php');
        exit();
    } else {
        $_SESSION['error'] = 'HN หรือ รหัสผ่านไม่ถูกต้อง';
        header('Location: ./client/login.php');
        exit();
    }
}
?>

