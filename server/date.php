<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['date'] = $_POST['selectedDate'];
}

if (!isset($_SESSION['hn'])) {
    header('Location: ../client/HomeTH');
    exit();
}
$date = $_SESSION['date'];

require_once 'config.php';

try {
    $sqlReserve = "SELECT reserve_time FROM reserve_time WHERE date = :date";
    $stmtReserve = $conn->prepare($sqlReserve);
    $stmtReserve->bindParam(':date', $date, PDO::PARAM_STR);
    $stmtReserve->execute();
    $reserveTimes = $stmtReserve->fetchAll(PDO::FETCH_ASSOC);

    $sqlSetting = "SELECT range_time FROM setting_reserve";
    $stmtSetting = $conn->query($sqlSetting);
    $rangeTimes = $stmtSetting->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>range_time:</h3>";
    foreach ($rangeTimes as $row) {
        echo $row['range_time'] . "<br>";
    }

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

    echo "<h3>จำนวน reserve_time ในแต่ละช่วงเวลา (วันที่ $date):</h3>";
    foreach ($timeCount as $rangeTime => $count) {
        echo "ช่วงเวลา $rangeTime: $count รายการ<br>";
    }

} catch (PDOException $e) {
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
        const today = new Date();
        const minDate = today.toISOString().split('T')[0];

        flatpickr("#flatpickr-input", {
            minDate: minDate,
            dateFormat: "dmY",
            onValueUpdate: function(selectedDates, dateStr, instance) {

                let dateObject = selectedDates[0];
                let thaiYear = dateObject.getFullYear() + 543;
                let formattedDate = 
                    ("0" + dateObject.getDate()).slice(-2) + 
                    ("0" + (dateObject.getMonth() + 1)).slice(-2) + 
                    thaiYear.toString(); //พ.ศ.
                instance.input.value = formattedDate; 
            },
            onOpen: function (selectedDates, dateStr, instance) {
                const year = today.getFullYear() + 543;
                instance.currentYear = year;
                instance.jumpToDate(today);
            },
            onChange: function(selectedDates, dateStr, instance) {
                $.ajax({
                    type: "POST",
                    url: "", 
                    data: { selectedDate: dateStr },
                    success: function(response) {
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


