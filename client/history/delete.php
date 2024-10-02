<?php
require_once '../../server/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $hn = $_POST['hn'];
    $date = $_POST['date'];
    $reserve_time = $_POST['reserve_time'];
    $dept = $_POST['dept'];

    try {
        $sqlCheck = "SELECT * FROM reserve_time WHERE hn = :hn AND date = :date AND reserve_time = :reserve_time AND dept = :dept";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':hn', $hn, PDO::PARAM_STR);
        $stmtCheck->bindParam(':date', $date, PDO::PARAM_STR);
        $stmtCheck->bindParam(':reserve_time', $reserve_time, PDO::PARAM_STR);
        $stmtCheck->bindParam(':dept', $dept, PDO::PARAM_STR);
        $stmtCheck->execute();
        $data = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $sqlDelete = "DELETE FROM reserve_time WHERE hn = :hn AND date = :date AND reserve_time = :reserve_time AND dept = :dept";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':hn', $hn, PDO::PARAM_STR);
            $stmtDelete->bindParam(':date', $date, PDO::PARAM_STR);
            $stmtDelete->bindParam(':reserve_time', $reserve_time, PDO::PARAM_STR);
            $stmtDelete->bindParam(':dept', $dept, PDO::PARAM_STR);
            $stmtDelete->execute();

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการลบ']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
