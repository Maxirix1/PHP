<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../../server/config.php';

try {
    $hn = $_SESSION['hn'];

    $sql = "SELECT * FROM reserve_time WHERE hn = :hn";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':hn', $hn, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows) {
        echo '<!DOCTYPE html>
        <html lang="th">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../style/main.css" />
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script src="./delete.js"></script>
            <title>ข้อมูลการจอง</title>
        </head>
        <style>
        body {
            background: rgb(42, 189, 255);
            background: radial-gradient(circle, rgba(42, 189, 255, 1) 0%, rgba(0, 75, 143, 1) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
            padding-top: 10px;
            margin: 0;
            height: 100vh;
            background-color: #ffffff;
            backdrop-filter: blur(20px);
            border: 1px solid #00000022;
            border-radius: 20px;
        }
        </style>
        <body>
            <div class="container mt-4">
                <h2>ข้อมูลการจอง HN: ' . htmlspecialchars($hn) . '</h2>
                <button class="btn btn-primary mb-4" onclick="goBack()">หน้าแรก</button>

                <script>
                    function goBack() {
                        window.history.back();
                    }
                </script>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>HN</th>
                                <th>วันที่</th>
                                <th>เวลาที่จอง</th>
                                <th>แผนก</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($rows as $row) {
            echo '<tr id="row-' . htmlspecialchars($row['hn']) . htmlspecialchars($row['date']) . htmlspecialchars($row['reserve_time']) . '">';
            echo '<td>' . htmlspecialchars($row['hn']) . '</td>';
            echo '<td>' . htmlspecialchars($row['date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['reserve_time']) . '</td>';
            echo '<td>' . htmlspecialchars($row['dept']) . '</td>';
            echo '<td>
                    <button type="button" class="btn btn-danger" onclick="confirmCancel(\'' . htmlspecialchars($row['hn']) . '\', \'' . htmlspecialchars($row['date']) . '\', \'' . htmlspecialchars($row['reserve_time']) . '\', \'' . htmlspecialchars($row['dept']) . '\')">ยกเลิกการจอง</button>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody>
                </table>
                </div>
            </div>

           
        </body>
        </html>';
    } else {
        echo '<div class="alert alert-warning" role="alert">ไม่พบข้อมูลสำหรับ HN ที่ระบุ</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">เกิดข้อผิดพลาด: ' . $e->getMessage() . '</div>';
}

?>