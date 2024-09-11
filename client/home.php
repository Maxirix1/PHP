<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style/style.css?v=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <header>
        <div class="dataMain">
            <h2>HN xxxxxx</h2>
            <h2>คุณ xxx xxx</h2>
        </div>
        <div class="language">
            <select class="dropdownLang">
                <option value="th">Thailand</option>
                <option value="en">English</option>
            </select>
            <div class="textLang">
                <p>Thailand</p>
            </div>
        </div>
    </header>

    <div class="container">
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
            <select class="department" id="department">
                <option value="volvo">อายุรกรรม</option>
                <option value="saab">Saab</option>
                <option value="mercedes">Mercedes</option>
                <option value="audi">Audi</option>
            </select>
        </div>

        <div class="dataReserve">
            <p>ระบุวันนัดหมาย</p>
            <div class="dataSelect">
                <img src="./assets/calendar.png" alt="Calendar Icon">
                <p id="selectedDate">วันที่ที่เลือก: ยังไม่มีการเลือก</p>
                <button id="openDatePicker" aria-label="เปิดปฏิทิน">
                    เลือกวันที่
                </button>
                <!-- Hidden input used only for Flatpickr -->
                <input type="text" id="dateInput" class="hidden">
            </div>

            <div class="dateList">
                <div class="dayOne">
                    <p>อ.</p>
                    <h2>9</h2>
                </div>
                <div class="dayTwo">
                    <p>พ.</p>
                    <h2>10</h2>
                </div>
                <div class="dayThree">
                    <p>พฤ.</p>
                    <h2>11</h2>
                </div>
                <div class="dayFour">
                    <p>ศ.</p>
                    <h2>12</h2>
                </div>
            </div>
            <h2>เลือกเวลา</h2>

            <div class="selectTime">
                <div class="time">08:00</div>
                <div class="time">09:00</div>
                <div class="time">10:00</div>
                <div class="time">11:00</div>
                <div class="time">13:00</div>
                <div class="time">14:00</div>
            </div>

            <div class="submit">
                <button>ยืนยัน</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Flatpickr on the hidden input element
            flatpickr("#dateInput", {
                dateFormat: "Y-m-d", // Format of the date
                onChange: function (selectedDates, dateStr, instance) {
                    // Update the <p> tag with the selected date
                    document.getElementById('selectedDate').textContent = 'วันที่เลือก: ' + dateStr;
                }
            });

            // Open the date picker when the button is clicked
            document.getElementById('openDatePicker').addEventListener('click', function () {
                document.querySelector('#dateInput')._flatpickr.open();
            });
        });
    </script>
</body>

</html>
