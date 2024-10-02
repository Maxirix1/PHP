<?php 
if (!isset($_SESSION['hn'])) {
    header('Location: ../client/login.php');
    exit();
}

    // session_start();
    // require_once ("./config.php");

    // if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    //     $username = $_POST['username'];
    //     $email = $_POST['email'];
    //     $password = $_POST['password'];
    //     $confirmPassword = $_POST['confirmPassword'];


    //     if ($password != $confirmPassword) {
    //         $_SESSION['error'] = 'Password do not match!';
    //         header('Location: ./client/signup.php');
    //         exit();
    //     }


    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    //     $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
    //     $stmt->execute(['email' => $email, 'username' => $username]);
    //     $user = $stmt->fetch(PDO::FETCH_ASSOC);


    //     if ($user) {
    //         $_SESSION['error'] = 'มีชื่อผู้ใช้นี้ในระบบแล้ว';
    //         header('Location: ./client/signup.php');
    //         exit();
    //     }


    //     $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    //     $result = $stmt->execute(["email"=> $email, "username"=> $username, 'password' => $hashedPassword]);


    //     if($result) {
    //         $_SESSION['success'] = 'Signup successful!';
    //         header('Location: ./client/login.php'); 
    //         exit();
    //     } else {
    //         $_SESSION['error'] = 'สมัครสมาชิกไม่สำเร็จ โปรดลองอีกครั้ง';
    //         header('Location: ./client/signup.php');  // แ
    //         exit();
    //     }
    // }
?>
