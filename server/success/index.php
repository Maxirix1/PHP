<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!$_SESSION['hn']) {
    header('Location: ../../client/login.php');
}

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['date'])) {
        $date = $_POST['date'];
        $hn = $_SESSION['hn'];
        $department = $_POST['department'];
        $time = $_POST['time'];
        $reason = $_POST['reason'];

        // echo "ค่าเวลา: $date<br>";

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
        $stmtQueue->execute(['date' => $date]);
        $queue = $stmtQueue->fetch(PDO::FETCH_ASSOC);

        $queueNo = $queue['maxQueue'];

        if ($queueNo) {

            $maxQueueNum = intval(substr($queueNo, 1));
            $newQueue = $maxQueueNum + 1;
        } else {
            $newQueue = 1;
        }

        $newQueueNumber = $letterG . str_pad($newQueue, 3, '0', STR_PAD_LEFT);
        // echo $newQueueNumber;

        $sql_qty = 'SELECT qty_taking FROM setting_reserve WHERE range_time = :range_time';
        $stmt_qty = $conn->prepare($sql_qty);
        $stmt_qty->bindParam(':range_time', $time);
        $stmt_qty->execute();
        $qty_taking = $stmt_qty->fetch(PDO::FETCH_ASSOC)['qty_taking'];

        // if ($qty_taking) {
        //     echo "จำนวนที่สามารถจองได้: $qty_taking<br>";
        // } else {
        //     echo "ไม่พบจำนวนที่สามารถจองได้สำหรับช่วงเวลา: $time";
        //     exit;
        // }
        $sql_dateReserve = 'SELECT * FROM reserve_time WHERE date = :date';
        $stmt_dateReserve = $conn->prepare($sql_dateReserve);
        $stmt_dateReserve->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt_dateReserve->execute();
        $reserve_times = $stmt_dateReserve->fetchAll(PDO::FETCH_ASSOC);

        list($startTime, $endTime) = explode('-', $time);
        $givenStart = DateTime::createFromFormat('H:i', trim($startTime));
        $givenEnd = DateTime::createFromFormat('H:i', trim($endTime));

        $countValidSlots = 0;

        foreach ($reserve_times as $reserve) {
            $reservedTime = DateTime::createFromFormat('H:i', trim($reserve['reserve_time']));

            // echo "Reserved Time: " . $reservedTime->format('H:i') . " | Start: " . $givenStart->format('H:i') . " | End: " . $givenEnd->format('H:i') . "<br>";

            if ($reservedTime >= $givenStart && $reservedTime < $givenEnd) {
                $countValidSlots++;
            }
        }

        // echo "จำนวนเวลาย่อยในช่วง '$time' ที่มีในฐานข้อมูล: $countValidSlots";
        function splitTimeRange($time, $qty_taking)
        {
            list($start, $end) = explode('-', $time);

            $startTime = DateTime::createFromFormat('H:i', trim($start));
            $endTime = DateTime::createFromFormat('H:i', trim($end));

            $interval = $startTime->diff($endTime);
            $totalMinutes = $interval->h * 60 + $interval->i;

            if ($qty_taking <= 0) {
                echo "จำนวนที่สามารถจองได้ต้องมากกว่าศูนย์";
                return [];
            }

            $subIntervalMinutes = intval($totalMinutes / $qty_taking);

            $timeSlots = [];
            $currentTime = clone $startTime;

            for ($i = 0; $i < $qty_taking; $i++) {
                $nextTime = clone $currentTime;
                $nextTime->modify("+{$subIntervalMinutes} minutes");

                if ($i == $qty_taking - 1) {
                    $nextTime = $endTime;
                }

                $timeSlots[] = $nextTime->format('H:i');

                $currentTime = $nextTime;
            }
            return $timeSlots;
        }

        $timeSlots = splitTimeRange($time, $qty_taking);

        if (empty($timeSlots)) {
            echo "ไม่สามารถแบ่งช่วงเวลาได้";
            exit;
        }


        $nextSlotTime = $countValidSlots;

        if (count($timeSlots) > $nextSlotTime) {
            $nextSlot = $timeSlots[$nextSlotTime];
            // echo "เวลาย่อยอันถัดไปคือ: " . $nextSlot;
        } else {
            echo "ไม่มีเวลาย่อยอันถัดไป";
        }

        // echo "ช่วงเวลาที่แบ่งได้: <br>";
        // foreach ($timeSlots as $slot) {
        //     echo $slot . "<br>"; // แสดงช่วงเวลาแต่ละช่วง
        // }

        $sql_check = 'SELECT COUNT(*) AS count FROM reserve_time WHERE date = :date AND hn = :hn AND dept = :department';
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':date', $date);
        $stmt_check->bindParam(':hn', $hn);
        $stmt_check->bindParam(':department', $department);
        $stmt_check->execute();
        $reserveCount = $stmt_check->fetch(PDO::FETCH_ASSOC)['count'];

        if ($reserveCount > 0) {
            echo json_encode(['error' => 'คุณได้ทำการจองในแผนกนี้แล้วในวันนี้']);
            http_response_code(400);
            exit;
        }

        function generateUUID() {
            return sprintf(
                '%s-%s-%s-%s-%s',
                bin2hex(random_bytes(4)),
                bin2hex(random_bytes(2)),
                bin2hex(random_bytes(2)),
                bin2hex(random_bytes(2)),
                bin2hex(random_bytes(6))
            );
        };

        $appointment_id = generateUUID();

        $sql = "INSERT INTO reserve_time (date, hn, dept, reserve_time, queue_no, appointment_id, reason) VALUES (:selectedDate, :hn, :department, :selectedTime, :queue_no, :appointment_id, :reason)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':selectedDate', $date);
            $stmt->bindParam(':hn', $hn);
            $stmt->bindParam(':department', $department);
            $stmt->bindParam(':selectedTime', $nextSlot);
            $stmt->bindParam(':queue_no', $newQueueNumber);
            $stmt->bindParam(':appointment_id', $appointment_id);
            $stmt->bindParam(':reason', $reason);
            $stmt->execute();

            header('Content-Type: application/json');
            // header("Location: ../../client/appointment.php?uuid=$appointment_id");
            echo json_encode(['success' => true, 'uuid' => $appointment_id]);
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }

    } else {
        echo "ไม่มีวันที่ที่เลือก";
    }
}
?>