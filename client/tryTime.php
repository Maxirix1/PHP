<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกวันที่</title>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        #dateInput {
            display: none; /* Hide the date input initially */
        }
    </style>
</head>
<body>
    <button id="openDatePicker">เลือกวันที่</button>
    <input type="text" id="dateInput" placeholder="เลือกวันที่" readonly>
    <p id="selectedDate">วันที่เลือก: </p>

    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr on the input element
        flatpickr("#dateInput", {
            dateFormat: "Y-m-d", // Format of the date
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('selectedDate').textContent = 'วันที่เลือก: ' + dateStr;
            }
        });

        // Open the date picker when the button is clicked
        document.getElementById('openDatePicker').addEventListener('click', function() {
            document.querySelector('#dateInput')._flatpickr.open();
        });
    </script>
</body>
</html>
