<?php

include './config.php';

if (!isset($_SESSION['hn'])) {
    header('Location: ../client/HomeTH');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['date'] = $_POST['date'];
}

$date = $_SESSION['date'];



try {
    $sqlReserve = "SELECT reserve_time FROM reserve_time WHERE date = :date";
    $stmtReserve = $conn->prepare($sqlReserve);

    $stmtReserve->bindParam(':date', $date, PDO::PARAM_STR);
    $stmtReserve->execute();
    $reserveTimes = $stmtReserve->fetchAll(PDO::FETCH_ASSOC);

    $sqlSetting = "SELECT range_time, qty_reserve FROM setting_reserve";
    $stmtSetting = $conn->query($sqlSetting);
    $rangeTimes = $stmtSetting->fetchAll(PDO::FETCH_ASSOC);

    $timeCount = [];

    foreach ($rangeTimes as $rangeRow) {
        $rangeTime = $rangeRow['range_time'];
        $timeCount[$rangeTime] = 0;
    }

    foreach ($reserveTimes as $reserveRow) {
        $reserveTime = $reserveRow['reserve_time'];

        foreach ($rangeTimes as $rangeRow) {
            $rangeTime = $rangeRow['range_time'];

            list($startTime, $endTime) = explode('-', $rangeTime);

            $reserveDateTime = DateTime::createFromFormat('H:i', $reserveTime);
            $startDateTime = DateTime::createFromFormat('H:i', trim($startTime));
            $endDateTime = DateTime::createFromFormat('H:i', trim($endTime));

            if ($reserveDateTime >= $startDateTime && $reserveDateTime <= $endDateTime) {

                $timeCount[$rangeTime]++;
                break;
            }
        }
    }

    // echo "<h3>จำนวน reserve_time ในแต่ละช่วงเวลา (วันที่ $date):</h3>";
    // foreach ($timeCount as $rangeTime => $count) {
    //     echo "ช่วงเวลา $rangeTime: $count รายการ<br>";
    // }

    $beforeNoon = [];
    $afterNoon = [];

    foreach ($rangeTimes as $rangeRow) {
        $rangeTime = $rangeRow['range_time'];

        list($startTime, $endTime) = explode('-', $rangeTime);

        if (strtotime($startTime) < strtotime("12:00")) {
            $beforeNoon[] = $rangeRow;
        } else {
            $afterNoon[] = $rangeRow;
        }
    }

    $output = '';

    foreach ($beforeNoon as $rangeRow) {
        $rangeTime = $rangeRow['range_time'];
        $qtyReserve = $rangeRow['qty_reserve'];

        if ($timeCount[$rangeTime] >= $qtyReserve) {
            $outputBefore .= '';
        } else {
            $outputBefore .= '<button class="Btn" id="timeBtn" data-time="' . $rangeTime . '">' . $rangeTime . '</button>';
        }
    }

    foreach ($afterNoon as $rangeRow) {
        $rangeTime = $rangeRow['range_time'];
        $qtyReserve = $rangeRow['qty_reserve'];

        if ($timeCount[$rangeTime] >= $qtyReserve) {
            $outputAfter .= '';
        } else {
            $outputAfter .= '<button class="Btn" id="timeBtn" data-time="' . $rangeTime . '">' . $rangeTime . '</button>';
        }
    }

    echo $outputBefore . '|||' . $outputAfter;
    // exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>