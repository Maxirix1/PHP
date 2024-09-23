<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ./login.php');
    exit();
}

require_once '../config.php';

$sql = "SELECT [name] FROM [smart_queue].[dbo].[department]";

try {
    $stmt = $conn->query($sql);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าวันที่ที่ส่งมาจาก JavaScript
    $data = json_decode(file_get_contents("php://input"), true);
    $selectedDate = $data['selectedDate'] ?? null;

    if ($selectedDate) {
        // เตรียมคำสั่ง SQL
        $sql = "SELECT reserve_time FROM reserve_time WHERE date = :selectedDate";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':selectedDate', $selectedDate);
            $stmt->execute();

            // ดึงข้อมูล reserve_time
            $reserveTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ส่งค่ากลับเป็น JSON
            header('Content-Type: application/json');
            echo json_encode(['selectedDate' => $selectedDate, 'reserve_times' => $reserveTimes]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style/style.css?v=1.0">
    <link rel="stylesheet" href="./style/responsive.css">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
</head>

<body>
    <header>
        <div class="dataMain">
            <h2 style="font-size: 2rem; font-weight: 600;">HN <?= htmlspecialchars($_SESSION['hn']) ?></h2>
            <!-- <h2>คุณ </h2> -->
            <a href="../logout.php" style="
            background-color: #fff;
            font-weight: 600;
            color: #0a5491;
            padding: 1px 15px;
            border-radius: 5px;
            margin-top: 10px;
            z-index: 999;
            ">ออกจากระบบ</a>
        </div>
        <div class="language">


            <select class="dropdownLang">
                <option value="th">Thailand</option>
                <option value="en">English</option>
            </select>
        </div>

    </header>
    <h1 id="time-list"></h1>


    <div class="containerMain">
        <div class="contenthead">
            <div class="logo">
                <img src="./assets/logoSmall.png" alt="Logo">
            </div>
            <h1 class="textHead">จองคิวนัดหมาย</h1>
        </div>

        <div class="textSelect">
            <p>ระบุแผนก</p>
        </div>
        <?php
        echo '<div class="dropdown">';
        echo '<select class="department" id="department" style="background-color: #fff;">';
        echo '<option>เลือกแผนก</option>';
        // วนลูปแสดงข้อมูลในรูปแบบ option
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option>' . htmlspecialchars($row['name']) . '</option>';
        }

        echo '</select>';
        echo '</div>';
        ?>

        <div class="dataReserve">
            <p class="text">ระบุวันนัดหมาย</p>
            <div class="dataSelect">


                <button id="prevDates">
                    <svg height="48" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12h4v24h-4zm7 12l17 12V12z" />
                        <path d="M0 0h48v48H0z" fill="none" />
                    </svg>
                </button>

                <div class="dataMonthHead">
                    <img src="./assets/calendar.png" alt="Calendar Icon">
                    <p id="monthDisplay"></p>
                </div>

                <button id="nextDates">
                    <svg height="48" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 36l17-12-17-12v24zm20-24v24h4V12h-4z" />
                        <path d="M0 0h48v48H0z" fill="none" />
                    </svg>
                </button>

            </div>

            <p id="monthDisplay"></p>
            <div id="dateContainer" class="container">

            </div>

            <div class="headText">
                <h2 class="textSelectTime" style="padding:8px 25px;">เลือกเวลา</h2>
                <a href="history">ประวัจิการจองทั้งหมดของคุณ</a>
            </div>

            <div class="selectTime" style="padding:8px 25px;">


            <?php
include '../time.php';

// ตรวจสอบว่ามีการส่ง POST request หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่า selectedDate จาก JavaScript ผ่าน POST
    $selectedDate = $_POST['selectedDate'] ?? ''; // ใช้ค่าใน POST หากมี

    // ตรวจสอบว่าค่ามีอยู่หรือไม่
    if (!empty($selectedDate)) {
        $sql = "SELECT reserve_time FROM reserve_time WHERE date = :selectedDate";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':selectedDate', $selectedDate);
            $stmt->execute();
            $reservedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN); // ดึงค่า reserve_time เป็น array
            
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['error' => 'ไม่พบวันที่']);
        exit;
    }
} else {
    // ค่าหมายเลขวันที่เริ่มต้น// ค่านี้สามารถเปลี่ยนได้ตามที่ต้องการ
    $sql = "SELECT reserve_time FROM reserve_time WHERE date = :selectedDate";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':selectedDate', $selectedDate);
        $stmt->execute();
        $reservedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN); // ดึงค่า reserve_time เป็น array
        
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// แสดงปุ่มเวลา
if (isset($_SESSION['timeSlots']) && !empty($_SESSION['timeSlots'])) {
    $timeSlots = $_SESSION['timeSlots'];

    $mergedTimeSlots = [];
    foreach ($timeSlots as $slots) {
        $mergedTimeSlots = array_merge($mergedTimeSlots, $slots);
    }

    echo '<div class="contentButton" style="
        display: flex; 
        flex-wrap: wrap; 
        gap: 10px; 
        align-item: start; 
        justify-content: center;
        margin-top: 20px;
        ">';

    foreach ($mergedTimeSlots as $slot) {
        // เช็คว่าเวลาใน $slot มีอยู่ใน $reservedTimes หรือไม่
        $buttonColor = in_array($slot, $reservedTimes) ? 'red' : 'green'; // เปลี่ยนสีตามเงื่อนไข
    
        echo '<button class="buttonDate" style="background-color: ' . $buttonColor . ';">
            ' . htmlspecialchars($slot) . '
            </button>';
    }

    echo '</div>';
    unset($_SESSION['timeSlots']);
} else {
    echo 'ERROR: No time slots available.';
}
?>


                <!-- <div class="time">08:00</div>
                <div class="time">09:00</div>
                <div class="time">10:00</div>   
                <div class="time">14:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div> -->
            </div>

            <div class="submit" style="margin-top: 20px; margin-bottom: 30px;">
                <button class="rounded">ยืนยัน</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

</body>

</html>