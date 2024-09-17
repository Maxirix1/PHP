<?php
session_start();
require_once './config.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        header('Location:  ./client/home.php');
        exit();

    } else {
        $_SESSION['error'] = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        header('Location: ./client/login.php');
        exit();
    }
}

?>