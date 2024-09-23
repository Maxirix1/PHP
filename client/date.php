<?php
// Array สำหรับวันและเดือนในภาษาไทย
$daysTH = ["อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส."];
$monthsTH = [
    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
];

// กำหนดวันที่ปัจจุบัน
$currentDate = new DateTime();
$currentIndex = 0;

// ฟังก์ชันสำหรับแสดงเดือนและปี
function updateMonthDisplay($currentDate, $monthsTH) {
    $month = $monthsTH[$currentDate->format('n') - 1];
    $year = $currentDate->format('Y') + 543; // ปีพุทธศักราช
    echo "<script>document.getElementById('monthDisplay').innerHTML = '$month $year';</script>";
}

// ฟังก์ชันหาจำนวนวันในเดือน
function getDaysInMonth($year, $month) {
    return cal_days_in_month(CAL_GREGORIAN, $month + 1, $year);
}

// ฟังก์ชันฟอร์แมตวันที่เป็น DDMMYYYY
function formatDateToDDMMYYYY($date) {
    $day = str_pad($date->format('d'), 2, '0', STR_PAD_LEFT);
    $month = str_pad($date->format('m'), 2, '0', STR_PAD_LEFT);
    $year = $date->format('Y') + 543;
    return "$day$month$year";
}

// ฟังก์ชันสำหรับอัปเดตวันที่ในกล่องแสดงผล
function updateDateBoxes($currentDate, $currentIndex, $daysTH, $monthsTH) {
    echo "<script>document.getElementById('dateContainer').innerHTML = '';</script>";
    $daysInMonth = getDaysInMonth($currentDate->format('Y'), $currentDate->format('n') - 1);

    $tempDate = clone $currentDate;
    $tempDate->modify('first day of this month')->setDate($tempDate->format('Y'), $tempDate->format('n'), $currentIndex + 1);

    for ($i = 0; $i < 4; $i++) {
        if ($tempDate->format('d') > $daysInMonth) {
            $currentDate->modify('+1 month');
            $currentIndex = 0;
            updateMonthDisplay($currentDate, $monthsTH);
            updateDateBoxes($currentDate, $currentIndex, $daysTH, $monthsTH);
            return;
        }

        $dateForButton = clone $tempDate;
        $dayOfWeek = $daysTH[$dateForButton->format('w')];
        $date = $dateForButton->format('d');
        $formattedDate = formatDateToDDMMYYYY($dateForButton);

        echo "<script>
            var button = document.createElement('button');
            button.classList.add('date-box');
            button.innerHTML = '<div class=\"dateWeek\">$dayOfWeek</div><br/><div class=\"dateNumber\">$date</div>';
            button.addEventListener('click', function() {
                console.log('$formattedDate');
            });
            document.getElementById('dateContainer').appendChild(button);
        </script>";

        $tempDate->modify('+1 day');
    }
}

// ฟังก์ชันอัปเดตวันที่
function updateDates($currentDate, $currentIndex, $daysTH, $monthsTH) {
    updateMonthDisplay($currentDate, $monthsTH);
    updateDateBoxes($currentDate, $currentIndex, $daysTH, $monthsTH);
}

// เริ่มการแสดงผล
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ปฏิทิน</title>
    <style>
        .date-box { margin: 10px; padding: 10px; }
    </style>
</head>
<body>

<div id="monthDisplay"></div>
<div id="dateContainer"></div>
<button id="prevDates">ก่อนหน้า</button>
<button id="nextDates">ถัดไป</button>

<script>
    // ส่งข้อมูล array PHP มายัง JavaScript
    const daysTH = <?php echo json_encode($daysTH); ?>;
    const monthsTH = <?php echo json_encode($monthsTH); ?>;

    let currentIndex = 0;
    let currentDate = new Date(<?php echo $currentDate->getTimestamp() * 1000; ?>);

    function updateMonthDisplay() {
        const month = monthsTH[currentDate.getMonth()];
        const year = currentDate.getFullYear() + 543; // ปีพุทธศักราช
        document.getElementById('monthDisplay').innerHTML = `${month} ${year}`;
    }

    function getDaysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    }

    function formatDateToDDMMYYYY(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear() + 543;
        return `${day}${month}${year}`;
    }

    function updateDateBoxes() {
        document.getElementById('dateContainer').innerHTML = '';
        const daysInMonth = getDaysInMonth(currentDate.getFullYear(), currentDate.getMonth());

        let tempDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentIndex + 1);

        for (let i = 0; i < 4; i++) {
            if (tempDate.getDate() > daysInMonth) {
                currentDate.setMonth(currentDate.getMonth() + 1);
                currentIndex = 0;
                updateMonthDisplay();
                updateDateBoxes();
                return;
            }

            const dateForButton = new Date(tempDate);
            const dayOfWeek = daysTH[dateForButton.getDay()];
            const date = dateForButton.getDate();
            const formattedDate = formatDateToDDMMYYYY(dateForButton);

            const button = document.createElement('button');
            button.classList.add('date-box');
            button.innerHTML = `<div class="dateWeek">${dayOfWeek}</div><br/><div class="dateNumber">${date}</div>`;
            button.addEventListener('click', () => {
                console.log(formattedDate);
            });

            document.getElementById('dateContainer').appendChild(button);
            tempDate.setDate(tempDate.getDate() + 1);
        }
    }

    document.getElementById('prevDates').addEventListener('click', function() {
        currentIndex -= 4;
        if (currentIndex < 0) {
            currentDate.setMonth(currentDate.getMonth() - 1);
            const daysInPreviousMonth = getDaysInMonth(currentDate.getFullYear(), currentDate.getMonth());
            currentIndex = daysInPreviousMonth - (daysInPreviousMonth % 4);
        }
        updateMonthDisplay();
        updateDateBoxes();
    });

    document.getElementById('nextDates').addEventListener('click', function() {
        const daysInMonth = getDaysInMonth(currentDate.getFullYear(), currentDate.getMonth());
        currentIndex += 4;
        if (currentIndex >= daysInMonth) {
            currentDate.setMonth(currentDate.getMonth() + 1);
            currentIndex = 0;
        }
        updateMonthDisplay();
        updateDateBoxes();
    });

    // เริ่มแสดงข้อมูล
    updateMonthDisplay();
    updateDateBoxes();
</script>

</body>
</html>
