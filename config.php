<?php
$serverName = "49.0.65.19";
$database = "smart_queue";
$username = "test";
$password = "admin1234";

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // echo"404 ERROR | Connect SUCCESS";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
