<?php
session_start();
require_once './config.php';

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $sessionToken = $_SESSION['token'];

    $stmt = $conn->prepare('SELECT token FROM users_signup WHERE userId = :userID');
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $dbToken = $user['token'];

        if ($sessionToken !== $dbToken) {
            echo json_encode(['status' => 'logout']);
        } else {
            echo json_encode(['status' => 'active']);
        }
    } else {
        echo json_encode(['status' => 'logout']);
    }
} else {
    echo json_encode(['status' => 'logout']);
}
?>
