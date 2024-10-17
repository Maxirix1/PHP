<?php
session_start();

require_once "./config.php";

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = $_POST['username'];
    $userHn = $_POST['hn'];
    $birthDate = $_POST['birthDate'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $fatherName = $_POST['fatherName'];
    $motherName = $_POST['motherName'];
    $urgentName = $_POST['urgentName'];
    $urgentNumber = $_POST['urgentNumber'];
    $password = $_POST['phoneNumber'];
    // $confirmPassword = $_POST['confirmPassword'];

    if (empty($userHn) || strlen($userHn) !== 13) {
        $_SESSION['error'] = 'เลขประจำตัวผู้ป่วยต้องมี 13 หลัก';
        header('Location: ../client/signup.php');
        exit();
    }

    $stmt = $conn->prepare('SELECT * FROM users_signup WHERE userHn = :userHn OR userName = :userName');
    $stmt->execute(['userHn' => $userHn, 'userName' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
    }
    
    if ($user) {
        $_SESSION['error'] = 'มีผู้ใช้นี้ในระบบแล้ว';
        header('Location: ../client/signup.php');
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users_signup (userName, userHn, birthDate, phoneNumber, address, fatherName, motherName, urgentName, urgentNumber, password) VALUES (:userName, :userHn, :birthDate, :phoneNumber, :address, :fatherName, :motherName, :urgentName, :urgentNumber, :password)");
    $result = $stmt->execute([
        "userName" => $username,
        "userHn" => $userHn,
        "birthDate" => $birthDate,
        "phoneNumber" => $phoneNumber,
        "address" => $address,
        "fatherName" => $fatherName,
        "motherName" => $motherName,
        "urgentName" => $urgentName,
        "urgentNumber" => $urgentNumber,
        "password" => $password
    ]);

    if ($result) {
        $_SESSION['success'] = 'สมัครสมาชิกสำเร็จ!';
        header('Location: ../client/login.php');
        exit();
    } else {
        $_SESSION['error'] = 'สมัครสมาชิกไม่สำเร็จ โปรดลองอีกครั้ง';
        header('Location: ../client/signup.php');
        exit();
    }
}
?>
