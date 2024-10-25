<?php
session_start();

require_once '../server/config.php';

if (isset($_GET['name'])) {
    $uuid = $_GET['name'];


    $sql = "SELECT * FROM users_signup WHERE profile = :name";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $uuid);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "ไม่พบ UUID ที่ต้องการ";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/logoHead.png">
    <title>AZTEC | ใบนัด</title>
    <link rel="stylesheet" href="./style/profile.css">
</head>

<body>
    <header>
        <div class="head">
            <img src="./assets/logo_hospitol.png" alt="">
        </div>
        <div class="name">
            <p>คุณ <span><?= htmlspecialchars($row['userName']) ?></span></p>
            <p>เลขบัตร <span><?= htmlspecialchars($row['userHn']) ?></span></p>
        </div>
    
</body>

</html>