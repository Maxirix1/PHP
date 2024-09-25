<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ./login.php');
    exit();
}

require_once '../config.php';




// เช็คว่ามีการร้องขอจาก AJAX หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../time.php';

    $selectedDate = isset($_POST['selectedDate']) ? htmlspecialchars($_POST['selectedDate']) : date('Ymd'); 
    $selectedDepartment = isset($_POST['selectedDepartment']) ? htmlspecialchars($_POST['selectedDepartment']) : null; // รับค่าที่เลือกจากแผนก

    $sql = "SELECT reserve_time FROM reserve_time WHERE date = :selectedDate";

    echo $_SESSION['hn'];
    echo '<br>';
    echo $selectedDate;
    echo $selectedDepartment;

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':selectedDate', $selectedDate);
        $stmt->execute();
        $reservedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // แสดงปุ่มเวลา
        if (isset($_SESSION['timeSlots']) && !empty($_SESSION['timeSlots'])) {
            $timeSlots = $_SESSION['timeSlots'];
            $mergedTimeSlots = [];
            foreach ($timeSlots as $slots) {
                $mergedTimeSlots = array_merge($mergedTimeSlots, $slots);
            }
            

            $output = '<div class="contentButton" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 20px;">';
            foreach ($mergedTimeSlots as $slot) {
                $buttonColor = in_array($slot, $reservedTimes) ? '#0e6dc7' : '#ababab';
                $output .= '<button class="buttonDate" style="background-color: ' . $buttonColor . ';">' . htmlspecialchars($slot) . '</button>';
            }
            $output .= '</div>';
            echo $output;
            unset($_SESSION['timeSlots']);

        } else {
            echo 'ERROR: No time slots available.';
        }
        exit(); // หยุดการทำงานหลังจากส่งข้อมูล
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    };
    
    
} else {
    $selectedDate = date('Ymd');
}



// ดึงแผนกทั้งหมด
$sql = "SELECT [name],[code] FROM [smart_queue].[dbo].[department]";
try {
    $stmt = $conn->query($sql);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
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
    <link rel="icon" type="image/x-icon" href="./assets/logoHead.png">
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
        <div class="dropdown">
            <select class="department" id="department" style="background-color: #fff; " name="selectedDepartment">
                <option value="" disabled selected>เลือกแผนก</option>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option><?= htmlspecialchars($row['name']) ?></option>
                <?php } ?>
            </select>
        </div>

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

                <button id="nextDates"><svg height="48" viewBox="0 0 48 48" width="48"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 36l17-12-17-12v24zm20-24v24h4V12h-4z" />
                        <path d="M0 0h48v48H0z" fill="none" />
                    </svg></button>
            </div>
            <div id="dateContainer" class="container"></div>


            <div>
                <p class="text">เลือกเวลา</p>
            </div>

            <div class="containerDateSelect">
                <div class="selectTime">
                </div>
            </div>

        </div>
    </div>
    <div class="submit" style="margin: 0 20px;">
        <input type="hidden" name="hn" value="<?= htmlspecialchars($_SESSION['hn']) ?>">
        <input type="hidden" name="selectedDate" id="selectedDate" value="<?= htmlspecialchars($selectedDate) ?>">
        <input type="hidden" name="selectedTime" id="selectedTime">
        <select name="department" id="departmentSelect" style="display: none;">
            <?php
            // ดึงชื่อแผนกทั้งหมดที่แสดงใน dropdown
            $stmt->execute(); // reset the statement to fetch again
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php } ?>
        </select>
        <button class="rounded" id="confirmButton">ยืนยัน</button>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

</body>

</html>
