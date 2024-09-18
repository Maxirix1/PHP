<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ./login.php');

    exit();
}

include("../config.php");

$sql = "SELECT [name] FROM [smart_queue].[dbo].[department]";

try {

    $stmt = $conn->query($sql);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<?php
include '../time.php';

if (isset($_SESSION['timeSlots'])) {
    $timeSlots = $_SESSION['timeSlots'];

    echo '<h1>Time</h1>';
    foreach ($timeSlots as $slots) {
        echo '<ul>';
        foreach ($slot as $slot) {
            echo '<li>' . htmlspecialchars($slot) . '</li>';
        }
        echo '</ul>';
    }
    unset($_SESSION['timeSlots']);
} else {
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
        echo '<select class="department" id="department">';
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


            <h2 class="textSelectTime">เลือกเวลา</h2>

            <div class="selectTime">
                <div class="time">08:00</div>
                <div class="time">09:00</div>
                <div class="time">10:00</div>
                <div class="time">11:00</div>
                <div class="time">12:00</div>
                <div class="time">13:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
                <div class="time">14:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
                <div class="time">14:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
                <div class="time">14:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
            </div>

            <div class="submit" style="margin-top: 20px;">
                <button class="rounded">ยืนยัน</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>