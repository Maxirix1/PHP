<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['date'] = $_POST['selectedDate'];
}


$date = $_SESSION['date'];

require_once 'config.php'; // ตรวจสอบให้แน่ใจว่าคุณเชื่อมต่อฐานข้อมูลอย่างถูกต้อง

try {
    // ดึงข้อมูล reserve_time จาก setting_reserve ตามวันที่
    $sqlReserve = "SELECT reserve_time FROM reserve_time WHERE date = :date";
    $stmtReserve = $conn->prepare($sqlReserve);
    $stmtReserve->bindParam(':date', $date, PDO::PARAM_STR);
    $stmtReserve->execute();
    $reserveTimes = $stmtReserve->fetchAll(PDO::FETCH_ASSOC);

    // ดึงช่วงเวลาจาก setting_time
    $sqlSetting = "SELECT range_time FROM setting_reserve";
    $stmtSetting = $conn->query($sqlSetting);
    $rangeTimes = $stmtSetting->fetchAll(PDO::FETCH_ASSOC);

    // แสดงค่าที่ได้จาก range_time
    echo "<h3>range_time:</h3>";
    foreach ($rangeTimes as $row) {
        echo $row['range_time'] . "<br>";
    }

    // สร้าง array สำหรับเก็บจำนวนของ reserve_time ในแต่ละช่วง
    $timeCount = [];

    // Initialize array count for each range_time
    foreach ($rangeTimes as $rangeRow) {
        $rangeTime = $rangeRow['range_time'];
        $timeCount[$rangeTime] = 0; // ตั้งค่าเริ่มต้นให้เท่ากับ 0
    }

    // Loop เช็คว่า reserve_time อยู่ในช่วงเวลาใดบ้าง
    foreach ($reserveTimes as $reserveRow) {
        $reserveTime = $reserveRow['reserve_time'];

        foreach ($rangeTimes as $rangeRow) {
            $rangeTime = $rangeRow['range_time'];

            // แยกช่วงเวลาเริ่มและสิ้นสุดจาก range_time
            list($startTime, $endTime) = explode('-', $rangeTime);

            // แปลง reserve_time, startTime, และ endTime ให้อยู่ในรูปแบบ DateTime
            $reserveDateTime = DateTime::createFromFormat('H:i', $reserveTime);
            $startDateTime = DateTime::createFromFormat('H:i', trim($startTime));
            $endDateTime = DateTime::createFromFormat('H:i', trim($endTime));

            // ตรวจสอบว่า reserve_time อยู่ในช่วงเวลา
            if ($reserveDateTime >= $startDateTime && $reserveDateTime <= $endDateTime) {
                // ถ้าอยู่ในช่วง ให้เพิ่มจำนวนใน array
                $timeCount[$rangeTime]++;
                break; // หยุด loop เมื่อเจอช่วงเวลาที่ตรงแล้ว เพื่อหลีกเลี่ยงการนับซ้ำ
            }
        }
    }

    // แสดงผลจำนวน reserve_time ที่อยู่ในแต่ละช่วงเวลา
    echo "<h3>จำนวน reserve_time ในแต่ละช่วงเวลา (วันที่ $date):</h3>";
    foreach ($timeCount as $rangeTime => $count) {
        echo "ช่วงเวลา $rangeTime: $count รายการ<br>";
    }

} catch (PDOException $e) {
    // แสดงข้อผิดพลาดหากเกิดขึ้น
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>Flatpickr Example</title>
</head>
<body>
    <input type="text" id="flatpickr-input" />

    <div id="result"></div>

    <script>
        // ฟังก์ชันเพื่อสร้าง minDate
        const today = new Date();
        const minDate = today.toISOString().split('T')[0]; // วันที่ปัจจุบันในรูปแบบ YYYY-MM-DD

        flatpickr("#flatpickr-input", {
            minDate: minDate, // กำหนด minDate เป็นวันนี้
            dateFormat: "dmY", // กำหนดรูปแบบวันที่เป็น dmY
            onValueUpdate: function(selectedDates, dateStr, instance) {
                // ปรับปีให้เป็นปี พ.ศ. ตอนที่มีการอัปเดตค่าใน input
                let dateObject = selectedDates[0];
                let thaiYear = dateObject.getFullYear() + 543; // แปลงเป็นปีไทย
                let formattedDate = 
                    ("0" + dateObject.getDate()).slice(-2) +  // วัน
                    ("0" + (dateObject.getMonth() + 1)).slice(-2) + // เดือน
                    thaiYear.toString(); // ปี พ.ศ.
                instance.input.value = formattedDate; // ตั้งค่าลงใน input
            },
            onOpen: function (selectedDates, dateStr, instance) {
                const year = today.getFullYear() + 543; // แปลงปีเป็นปีไทย
                instance.currentYear = year;
                instance.jumpToDate(today); // ไปยังวันที่ปัจจุบัน
            },
            onChange: function(selectedDates, dateStr, instance) {
                // ส่งค่าที่เลือกไปยัง PHP ผ่าน AJAX
                $.ajax({
                    type: "POST",
                    url: "", // เปลี่ยนเป็นชื่อไฟล์ PHP ของคุณ
                    data: { selectedDate: dateStr },
                    success: function(response) {
                        // แสดงผลลัพธ์ที่ตอบกลับจาก PHP
                        $("#result").html(response);
                        console.log(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error: " + textStatus);
                    }
                });
            }
        });
    </script>
</body>
</html>


