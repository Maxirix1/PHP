<?php
session_start();

if(!$_SESSION['hn']) {
    header('Location: ../../client/login.php');
}

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ตรวจสอบว่ามีค่า selectedDate ใน SESSION หรือไม่
    if (isset($_POST['date'])) {
        $date = $_POST['date'];
        $hn = $_SESSION['hn'];
        $department = $_POST['department'];
        $time = $_POST['time'];

        // แสดงค่าเวลาเพื่อตรวจสอบ
        // echo "ค่าเวลา: $date<br>";

        // ตรวจสอบรูปแบบเวลาที่ส่งมา
        if (preg_match('/^\s*\d{2}:\d{2}\s*-\s*\d{2}:\d{2}\s*$/', $time) === 0) {
            // echo "รูปแบบเวลาที่ส่งมาไม่ถูกต้อง";
            exit;
        }

        $sqlletter = 'SELECT letters FROM department';
        $stmtletter = $conn->prepare($sqlletter);
        $stmtletter->execute();
        $letters = $stmtletter->fetch(PDO::FETCH_ASSOC);
        $letterG = $letters['letters'];

        $sqlQueue = 'SELECT MAX(queue_no) AS maxQueue FROM reserve_time WHERE date = :date';
        $stmtQueue = $conn->prepare($sqlQueue);
        $stmtQueue->execute(['date'=> $date]);
        $queue = $stmtQueue->fetch(PDO::FETCH_ASSOC);
        
        $queueNo = $queue['maxQueue'];

        if($queueNo) {

            $maxQueueNum = intval(substr($queueNo, 1));
            $newQueue = $maxQueueNum + 1;
        }else {
            $newQueue = 1;
        }

        $newQueueNumber = $letterG . str_pad($newQueue, 3, '0', STR_PAD_LEFT);
        // echo $newQueueNumber;

        $sql_qty = 'SELECT qty_taking FROM setting_reserve WHERE range_time = :range_time';
        $stmt_qty = $conn->prepare($sql_qty);
        $stmt_qty->bindParam(':range_time', $time);
        $stmt_qty->execute();
        $qty_taking = $stmt_qty->fetch(PDO::FETCH_ASSOC)['qty_taking']; // ดึงค่า qty_taking ออกมา

        // แสดงจำนวนที่สามารถจองได้
        // if ($qty_taking) {
        //     echo "จำนวนที่สามารถจองได้: $qty_taking<br>";
        // } else {
        //     echo "ไม่พบจำนวนที่สามารถจองได้สำหรับช่วงเวลา: $time";
        //     exit;
        // }
        // ดึงเวลาย่อยทั้งหมดจากฐานข้อมูล
        $sql_dateReserve = 'SELECT * FROM reserve_time WHERE date = :date';
        $stmt_dateReserve = $conn->prepare($sql_dateReserve);
        $stmt_dateReserve->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt_dateReserve->execute();
        $reserve_times = $stmt_dateReserve->fetchAll(PDO::FETCH_ASSOC);

        // แยกค่าเวลาที่ต้องการตรวจสอบ
        list($startTime, $endTime) = explode('-', $time);
        $givenStart = DateTime::createFromFormat('H:i', trim($startTime));
        $givenEnd = DateTime::createFromFormat('H:i', trim($endTime));

        // ตัวแปรนับจำนวนเวลาที่อยู่ในช่วงที่กำหนด
        $countValidSlots = 0;

        // ตรวจสอบเวลาที่ถูกจองทั้งหมด
        foreach ($reserve_times as $reserve) {
            // แปลงเวลาที่เก็บในฐานข้อมูลเป็น DateTime
            $reservedTime = DateTime::createFromFormat('H:i', trim($reserve['reserve_time']));

            // Debug: แสดงค่าที่เปรียบเทียบ
            // echo "Reserved Time: " . $reservedTime->format('H:i') . " | Start: " . $givenStart->format('H:i') . " | End: " . $givenEnd->format('H:i') . "<br>";

            // ตรวจสอบว่ามีการชนกันระหว่างเวลาที่เก็บในฐานข้อมูลและเวลาที่กำหนด
            if ($reservedTime >= $givenStart && $reservedTime < $givenEnd) {
                $countValidSlots++;
            }
        }

        // แสดงจำนวนเวลาที่อยู่ในช่วงที่ต้องการ
        // echo "จำนวนเวลาย่อยในช่วง '$time' ที่มีในฐานข้อมูล: $countValidSlots";
        function splitTimeRange($time, $qty_taking)
        {
            list($start, $end) = explode('-', $time);

            $startTime = DateTime::createFromFormat('H:i', trim($start));
            $endTime = DateTime::createFromFormat('H:i', trim($end));

            $interval = $startTime->diff($endTime);
            $totalMinutes = $interval->h * 60 + $interval->i;

            // ตรวจสอบว่า qty_taking เป็นค่าบวกและไม่เท่ากับ 0
            if ($qty_taking <= 0) {
                echo "จำนวนที่สามารถจองได้ต้องมากกว่าศูนย์";
                return [];
            }

            // คำนวณเวลาย่อยต่อชิ้น
            $subIntervalMinutes = intval($totalMinutes / $qty_taking);

            $timeSlots = [];
            $currentTime = clone $startTime;

            for ($i = 0; $i < $qty_taking; $i++) {
                $nextTime = clone $currentTime;
                $nextTime->modify("+{$subIntervalMinutes} minutes");

                // ถ้าเป็นช่วงสุดท้าย ใช้เวลาสิ้นสุดเดิม
                if ($i == $qty_taking - 1) {
                    $nextTime = $endTime;
                }

                // เก็บช่วงเวลาในรูปแบบ H:i-H:i
                $timeSlots[] = $nextTime->format('H:i');

                // อัปเดตเวลาปัจจุบันเพื่อทำงานต่อในรอบถัดไป
                $currentTime = $nextTime;
            }
            return $timeSlots;
        }

        // เรียกใช้ฟังก์ชันเพื่อแบ่งช่วงเวลา
        $timeSlots = splitTimeRange($time, $qty_taking);

        if (empty($timeSlots)) {
            echo "ไม่สามารถแบ่งช่วงเวลาได้";
            exit;
        }


        $nextSlotTime = $countValidSlots;

        if (count($timeSlots) > $nextSlotTime) {
            $nextSlot = $timeSlots[$nextSlotTime];
            echo "เวลาย่อยอันถัดไปคือ: " . $nextSlot;
        } else {
            echo "ไม่มีเวลาย่อยอันถัดไป";
        }

        // // แสดงผลช่วงเวลาที่แบ่งได้
        // echo "ช่วงเวลาที่แบ่งได้: <br>";
        // foreach ($timeSlots as $slot) {
        //     echo $slot . "<br>"; // แสดงช่วงเวลาแต่ละช่วง
        // }

            $sql = "INSERT INTO reserve_time (date, hn, dept, reserve_time, queue_no) VALUES (:selectedDate, :hn, :department, :selectedTime, :queue_no)"; // แก้ไขชื่อ table และ column ให้ตรงกับฐานข้อมูลของคุณ

            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':selectedDate', $date);
                $stmt->bindParam(':hn', $hn);
                $stmt->bindParam(':department', $department);
                $stmt->bindParam(':selectedTime', $nextSlot);
                $stmt->bindParam(':queue_no', $newQueueNumber);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                exit; // หยุดการทำงานหากเกิดข้อผิดพลาด
            }


        echo "บันทึกเวลาสำเร็จ";
    } else {
        echo "ไม่มีวันที่ที่เลือก";
    }
}
?>