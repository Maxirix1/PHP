<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../../server/config.php';



// ส่วนนี้แยกการแสดงผลปุ่มออกจากการส่ง session กลับ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../server/time.php';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['selectedDate'] = isset($_POST['selectedDate']) ? htmlspecialchars($_POST['selectedDate']) : NULL;
        $_SESSION['selected_department'] = isset($_POST['department']) ? htmlspecialchars($_POST['department']) : 'ห้องตรวจโรคทั่วไป';
        $_SESSION['selectedTime'] = isset($_POST['selectedTime']) ? htmlspecialchars($_POST['selectedTime']) : NULL;

        // echo "Selected Date: " . $_SESSION['selectedDate'] . "<br>";
        // echo "Selected Department: " . $_SESSION['selected_department'] . "<br>";

        if ($_SESSION['selectedTime'] == NULL) {
            echo '<div class="timeSelected">
            <div><h3>ไม่มีการเลือกเวลา</h3>
            </div>
            </div>';
        } else {
            echo '<div class="timeSelected">
            <div><h3>' . $_SESSION['selectedTime'] . '</h3>
            </div>
            </div>';
        }
        ;
    }

    $rangeTimes = createTimeRanges();
    $matchedRange = checkSelectedTime($_SESSION['selectedTime'], $rangeTimes);

    $sql_setting = "SELECT qty_reserve FROM setting_reserve WHERE range_time = :range_time";
    $stmt_setting = $conn->prepare($sql_setting);

    // คำนวณจำนวนการจองในช่วงเวลานั้น
    $sql_reserve = "SELECT COUNT(*) FROM reserve_time WHERE date = :selectedDate AND reserve_time BETWEEN :start_time AND :end_time";
    $stmt_reserve = $conn->prepare($sql_reserve);

    foreach ($rangeTimes as $range) {
        // echo $range;
        if ($range === $matchedRange) {
            // echo " (Selected Time)";

            // แยกช่วงเวลา
            list($startTime, $endTime) = explode(' - ', $range);

            // คำนวณจำนวนการจองในช่วงเวลา
            $stmt_reserve->bindParam(':selectedDate', $_SESSION['selectedDate']);
            $stmt_reserve->bindParam(':start_time', $startTime);
            $stmt_reserve->bindParam(':end_time', $endTime);
            $stmt_reserve->execute();

            $reserveCount = $stmt_reserve->fetchColumn();

            // ดึงค่า qty_reserve
            $stmt_setting->bindParam(':range_time', $range);
            $stmt_setting->execute();
            $qty_reserve = $stmt_setting->fetchColumn();

            // echo "<br>จำนวนการจองในช่วงเวลานี้: " . $reserveCount . " ช่วง<br>";
            // echo "จำนวนการจองที่อนุญาต: " . $qty_reserve . " ช่วง<br>";

            // ตรวจสอบว่าผู้ใช้สามารถจองได้หรือไม่
            if ($reserveCount >= $qty_reserve) {
                echo '<script>
                Swal.fire({
                    title: "การจองถูกจำกัด",
                    text: "ไม่สามารถจองช่วงเวลานี้ได้ โปรดเลือกช่วงเวลาใหม่ หรือทำการ Walk-In",
                    icon: "error",
                    confirmButtonText: "ตกลง"
                }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
            </script>';
            } else {

            }
        }
        // echo "<br>"; 
    }


    $sql = "SELECT reserve_time FROM reserve_time WHERE date = :selectedDate";


    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':selectedDate', $_SESSION['selectedDate']);
        $stmt->execute();
        $reservedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        // ตรวจสอบว่า selectedTime มีค่าและมีรูปแบบที่ถูกต้อง
        if (isset($_SESSION['selectedTime'])) {
            // แสดงค่าของ selectedTime
            // echo 'Selected Time: ' . htmlspecialchars($_SESSION['selectedTime']) . '<br>';

            // ค่าที่ได้จาก selectedTime
            $selectedTime = $_SESSION['selectedTime'];

            // สร้างช่วงเวลา (แนะนำให้ทำการเชื่อมโยงช่วงเวลา)
            $rangeTimes = createTimeRanges(); // ฟังก์ชันที่คุณได้สร้างขึ้น

            // ค้นหาช่วงเวลาที่ selectedTime อยู่ในนั้น
            $matchedRange = null;
            foreach ($rangeTimes as $range) {
                list($startTime, $endTime) = explode(' - ', $range);

                // ตรวจสอบว่า selectedTime อยู่ในช่วงเวลาหรือไม่
                if ($selectedTime >= $startTime && $selectedTime <= $endTime) {
                    $matchedRange = $range;
                    break;
                }
            }

            // ตรวจสอบว่าได้ matchedRange หรือไม่
            if ($matchedRange) {
                // แยกช่วงเวลา
                list($startTime, $endTime) = explode(' - ', $matchedRange);

                // นับจำนวนการจองในช่วงเวลาที่เลือก
                $countQuery = "SELECT COUNT(DISTINCT reserve_time) FROM reserve_time 
                       WHERE date = :selectedDate 
                       AND reserve_time BETWEEN :startTime AND :endTime";

                $countStmt = $conn->prepare($countQuery);
                $countStmt->bindParam(':selectedDate', $_SESSION['selectedDate']);
                $countStmt->bindParam(':startTime', $startTime);
                $countStmt->bindParam(':endTime', $endTime);
                $countStmt->execute();
                $countReserved = $countStmt->fetchColumn();

            } else {
                echo '<div>เกิดข้อผิดพลาด: ช่วงเวลาที่เลือกไม่ถูกต้อง</div>';
            }
        } else {
        }

        // ปุ่ม
        if (isset($_SESSION['timeSlots']) && !empty($_SESSION['timeSlots'])) {
            $timeSlots = $_SESSION['timeSlots'];

            $mergedTimeSlots = [];
            foreach ($timeSlots as $slots) {
                $mergedTimeSlots = array_merge($mergedTimeSlots, $slots);
            }

            $output = '<div class="contentButton" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 1px;">';
            foreach ($mergedTimeSlots as $slot) {
                // echo $slot;
                $buttonColor = in_array($slot, $reservedTimes) ? '#0e6dc7' : '';
                $output .= '<button class="buttonDate" style="background-color: ' . $buttonColor . '; cursor: pointer;" 
                             onclick="handleButtonClick(\'' . $slot . '\', ' . (in_array($slot, $reservedTimes) ? 'true' : 'false') . ');">'
                    . htmlspecialchars($slot) . '</button>';
            }
            $output .= '</div>';

            echo $output;

            unset($_SESSION['timeSlots']);
        } else {
            echo 'ERROR: No time slots available.';
        }

        exit();

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
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
    <title>AZTEC | Home</title>
    <link rel="stylesheet" href="../style/style.css?v=1.0">
    <link rel="stylesheet" href="../style/responsive.css">
    <link rel="icon" type="image/x-icon" href="../assets/logoHead.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <header>
        <div class="dataMain">
            <h2 style="font-size: 2rem; font-weight: 600;">HN <?= htmlspecialchars($_SESSION['hn']) ?></h2>
            <!-- <h2>คุณ </h2>  -->
            <a href="../../server/logout.php" style="
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

            <select class="dropdownLang" onchange="changeLanguage()">
                <option value="th">ไทย</option>
                <option value="en">English</option>
            </select>


            <script>
                function changeLanguage() {
                    const dropdown = document.querySelector('.dropdownLang');
                    const selectedValue = dropdown.value;

                    if (selectedValue === 'en') {
                        window.location.href = '../HomeEN'; // เปลี่ยนเป็น URL ของหน้า homeEN ที่ต้องการ
                    } else if (selectedValue === 'th') {
                        window.location.href = '../HomeTH'; // เปลี่ยนเป็น URL ของหน้าไทยที่ต้องการ
                    }
                }
            </script>

        </div>

    </header>

    <div class="containerMain">
        <div class="contenthead">
            <div class="logo">
                <img src="../assets/logoSmall.png" alt="Logo">
            </div>
            <h1 class="textHead">จองคิวนัดหมาย</h1>
        </div>

        <div class="textSelect">
            <p>ระบุแผนก</p>
        </div>
        <div class="dropdown">
            <select class="department" id="department" style="background-color: #fff; " name="department" required>
                <option value="" disabled selected>เลือกแผนก</option>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="dataReserve">
            <!-- <div id="selectedTime" style="margin-top: 20px; font-size: 18px; color: #333;"></div> -->

            <p class="text">ระบุวันนัดหมาย</p>
            <div class="dataSelect">
                <button id="prevDates">
                    <svg height="48" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12h4v24h-4zm7 12l17 12V12z" />
                        <path d="M0 0h48v48H0z" fill="none" />
                    </svg>
                </button>

                <div class="dataMonthHead">
                    <img src="../assets/calendar.png" alt="Calendar Icon">
                    <p id="monthDisplay"></p>
                </div>

                <button id="nextDates"><svg height="48" viewBox="0 0 48 48" width="48"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 36l17-12-17-12v24zm20-24v24h4V12h-4z" />
                        <path d="M0 0h48v48H0z" fill="none" />
                    </svg></button>
            </div>
            <div id="dateContainer" class="container"></div>


            <div class="headText">
                <h2 class="textSelectTime" style="padding:8px 25px;">เลือกเวลา</h2>
                <a href="../history">ประวัติการจองทั้งหมดของคุณ</a>
            </div>

            <div class="containerDateSelect">
                <div class="selectTime">
                </div>

            </div>

        </div>

        <form action="../../server/success/" method="POST" id="confirmForm" style="margin-top:20px;">
            <input type="hidden" id="selectedDepartment" name="department">
            <div class="submit">
                <button type="submit" id="submitBtn">ยืนยัน</button>
            </div>


        </form>

        <script>


            const departmentSelect = document.getElementById('department');
            const selectedDepartmentInput = document.getElementById('selectedDepartment');
            const submitBtn = document.getElementById('submitBtn');

            function toggleSubmitButton() {
                if (departmentSelect.value) {
                    selectedDepartmentInput.value = departmentSelect.value;
                    submitBtn.disabled = false;
                } else {
                    selectedDepartmentInput.value = "";
                    submitBtn.disabled = true;
                }
            }

            departmentSelect.addEventListener('change', toggleSubmitButton);

            toggleSubmitButton();
            document.getElementById('submitBtn').addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการยืนยันการจองนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ยืนยันเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form
                        document.getElementById('confirmForm').submit();

                        Swal.fire({
                            title: "Success!",
                            text: "จองสำเร็จ",
                            icon: "success",
                        });
                    }
                });
            });
        </script>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

</body>

</html>