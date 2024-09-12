<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงวันที่ตามปฏิทิน</title>
    <style>
        .date-box {
            display: inline-block;
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            margin: 5px;
            text-align: center;
            line-height: 100px;
            font-size: 1.5em;
        }
        .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    
    <p id="monthDisplay"></p>
    <div id="dateContainer" class="container">
        <!-- Date boxes will be inserted here -->
    </div>
    <button id="prevDates">วันที่ก่อนหน้า</button>
    <button id="nextDates">วันที่ถัดไป</button>

    <script src="script.js"></script>
</body>
</html>
