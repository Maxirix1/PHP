<?php
$serverName = "49.0.65.19";
$database = "smart_queue";
$username = "test";
$password = "admin1234";

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ทดสอบการดึงข้อมูล
    $stmt = $conn->query("SELECT * FROM setting_reserve");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo"SUCCESS";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
