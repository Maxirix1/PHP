<!-- <!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanilla JavaScript Datepicker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        .datepicker {
            position: relative;
            width: 320px;
        }
        
        .calendar {
            display: none; /* เริ่มต้นให้ปิดปฏิทิน */
            position: absolute;
            width: 600px; /* กำหนดขนาดของปฏิทิน */
            height: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        
        .calendar-header {
            background: #062075;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .calendar table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th,
        .calendar td {
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }

        .calendar th {
            background: #f0f0f0;
        }

        .calendar td:hover {
            background: #e0e0e0;
        }

        .calendar .disabled {
            color: #ccc;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="datepicker">
    <input type="text" id="dateInput" placeholder="เลือกวันที่" readonly style="width: 100%; padding: 10px; font-size: 18px;">
    
    <div class="calendar" id="calendar">
        <div class="calendar-header">
            <span id="monthYear"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>อา</th>
                    <th>จ</th>
                    <th>อ</th>
                    <th>พ</th>
                    <th>พฤ</th>
                    <th>ศ</th>
                    <th>ส</th>
                </tr>
            </thead>
            <tbody id="days"></tbody>
        </table>
    </div>
</div>

<script>
    const thaiMonths = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    
    const dateInput = document.getElementById('dateInput');
    const calendar = document.getElementById('calendar');
    const monthYear = document.getElementById('monthYear');
    const days = document.getElementById('days');

    let currentDate = new Date();
    let displayDate = new Date(currentDate);

    // Initialize the datepicker
    function initializeDatepicker() {
        renderCalendar();
    }

    // Update the calendar display
    function renderCalendar() {
        monthYear.textContent = `${thaiMonths[displayDate.getMonth()]} ${displayDate.getFullYear() + 543}`;
        const year = displayDate.getFullYear();
        const month = displayDate.getMonth();

        days.innerHTML = '';

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDay = firstDay.getDay();
        const totalDays = lastDay.getDate();

        let html = '<tr>';
        for (let i = 0; i < startDay; i++) {
            html += '<td></td>';
        }

        for (let day = 1; day <= totalDays; day++) {
            const dayDate = new Date(year, month, day);
            const isDisabled = dayDate < currentDate; // Disable past dates
            html += `<td class="${isDisabled ? 'disabled' : ''}" data-date="${dayDate}">${day}</td>`;

            if ((day + startDay) % 7 === 0) {
                html += '</tr><tr>';
            }
        }
        html += '</tr>';
        days.innerHTML = html;
    }

    // Show/Hide calendar
    dateInput.addEventListener('focus', () => {
        calendar.style.display = 'block';
        renderCalendar();
    });

    // Select a date
    days.addEventListener('click', (e) => {
        if (e.target.dataset.date) {
            const selectedDate = new Date(e.target.dataset.date);
            dateInput.value = `${selectedDate.getDate().toString().padStart(2, '0')}${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}${selectedDate.getFullYear() + 543}`;
            calendar.style.display = 'none';
        }
    });

    // Close calendar when clicking outside
    document.addEventListener('click', (event) => {
        const isClickInside = dateInput.contains(event.target) || calendar.contains(event.target);
        if (!isClickInside) {
            calendar.style.display = 'none'; // ปิดปฏิทินเมื่อคลิกนอก
        }
    });

    initializeDatepicker(); // Initialize the datepicker
</script>

</body>
</html> -->
