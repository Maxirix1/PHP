<?php
session_start();

// session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../../server/config.php';
require_once '../../server/time.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['department'])) {
        $departmentName = htmlspecialchars($_POST['department']);

        $_SESSION['departments'] = $departmentName;

    }
}

// แผนก 
$sql = "SELECT [name],[code],[svg_icon] FROM [smart_queue].[dbo].[department]";
try {
    $stmt = $conn->query($sql);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AZTEC | Home</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/responsive.css">
    <link rel="icon" type="image/x-icon" href="../assets/logoHead.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>


</head>

<body>
    <div id="time"></div>
    <header>
        <div class="dataMain">
            <div class="data">
                <h3 class="name">คุณ <?= htmlspecialchars($_SESSION['username']) ?></h3>
                <h3 class="hn"><?= htmlspecialchars($_SESSION['hn']) ?></h3>
            </div>
            <!-- <h2>คุณ </h2>  -->

        </div>
        <div class="language">

            <select class="dropdownLang" onchange="changeLanguage()">
                <option value="th">ไทย</option>
                <option value="en">English</option>
            </select>

            <a href="../../server/logout.php" style="
            background-color: #fff;
            font-weight: 600;
            color: #0a5491;
            padding: 1px 15px;
            border-radius: 5px;
            margin-top: 10px;
            z-index: 999;
            " class="logout">ออกจากระบบ</a>


            <script>
                function changeLanguage() {
                    const dropdown = document.querySelector('.dropdownLang');
                    const selectedValue = dropdown.value;

                    if (selectedValue === 'en') {
                        window.location.href = '../HomeEN'; // เปลี่ยนเป็น URL ของหน้า homeEN ที่ต้องการ
                    } else if (selectedValue === 'th') {
                        window.location.href = '../HomeTH'; // เปลี่ยนเป็น URL ของหน้าไทยที่ต้องการ
                    }
                }
            </script>
        </div>
    </header>

    <!-- -------------------------------------------------------------------------------------------- -->

    <div class="containerMain">
        <div class="contenthead">
            <div class="logo">
                <img src="../assets/logoSmall.png" alt="Logo">
            </div>
            <h1 class="textHead">จองคิวนัดหมาย</h1>
        </div>
        <div class="dropdown">
            <p>ระบุแผนก</p>

            <button class="selectDepartment" id="openDepartmentBtn">
                <h1 id="beforeSelected">เลือกแผนก</h1>
                <h1 id="departmentDisplay"></h1>
            </button>
        </div>

        <section class="containerDepartment" id="department">
            <div class="department">
                <div class="headDepart">
                    <p>เลือกแผนก</p>
                    <span class="close">&times;</span>
                </div>
                <div class="contentList">
                    <div class="listDepartment">

                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                            <button class="departmentItem" id="departmentItem"
                                value="<?= htmlspecialchars($row['name']) ?>">
                                <?= $row['svg_icon'] ?>
                                <span id="nameDepartment"><?= htmlspecialchars($row['name']) ?></span>
                            </button>
                        <?php } ?>


                    </div>
                </div>

                <div class="listSubmit">
                    <button id="exit">ยืนยัน</button>
                    <button id="cancle">ยกเลิก</button>
                </div>

            </div>
        </section>

        <div class="dataReserve">
            <!-- <div id="selectedTime" style="margin-top: 20px; font-size: 18px; color: #333;"></div> -->

            <p class="text">ระบุวันนัดหมาย</p>
            <div class="dataSelect">

                <div class="dataMonthHead">
                    <button id="dateClick">
                        <img src="../assets/calendar-2.png" alt="Calendar Icon">
                        <p id="beforeSelectDate">กรุณาเลือกวันที่</p>
                        <p id="dateDisplay"></p>
                    </button>
                    <!-- <input type="text" id="calendar" autocomplete="off" readonly /> -->

                </div>

                <div class="containerCalendar">
                    <div class="calendar" id="calendar">
                        <div class="calendar-header">
                            <button id="prevMonth">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m15 19-7-7 7-7" />
                                </svg>

                            </button>
                            <span id="monthYear"></span>
                            <button id="nextMonth">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m9 5 7 7-7 7" />
                                </svg>

                            </button>
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

            </div>


            <!-- <div id="dateContainer" class="container"></div> -->


            <div class="headText">
                <h2 class="text" style="padding:8px 25px;">เลือกเวลา</h2>
                <a href="../history" class="history">ประวัติการจองทั้งหมดของคุณ</a>
            </div>
            <div class="timeSelect">


                <h3 class="textTime">ก่อนเที่ยง</h3>

                <div class="beforeContent">
                    <button id="slideLeftBefore">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="beforeNoon" id="beforeNoon">
                        <button id="noDisplay" data-time="08:00 - 08:29">08:00 - 08:29</button>
                        <button id="noDisplay" data-time="08:30 - 08:59">08:30 - 08:59</button>
                        <button id="noDisplay" data-time="09:00 - 09:29">09:00 - 09:29</button>
                        <button id="noDisplay" data-time="09:30 - 09:59">09:30 - 09:59</button>
                        <button id="noDisplay" data-time="10:00 - 10:29">10:00 - 10:29</button>
                        <button id="noDisplay" data-time="10:30 - 10:59">10:30 - 10:59</button>
                        <button id="noDisplay" data-time="11:00 - 11:29">11:00 - 11:29</button>
                        <button id="noDisplay" data-time="11:30 - 11:59">11:30 - 11:59</button>
                    </div>
                    <button id="slideRightBefore">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                    </button>
                </div>


                <h3 class="textTime">หลังเที่ยง</h3>

                <div class="afterContent">
                    <button id="slideLeftAfter">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="afterNoon" id="afterNoon">

                        <button id="noDisplay" data-time="12:00 - 12:29">12:00 - 12:29</button>
                        <button id="noDisplay" data-time="12:30 - 12:59">12:30 - 12:59</button>
                        <button id="noDisplay" data-time="13:00 - 13:29">13:00 - 13:29</button>
                        <button id="noDisplay" data-time="13:30 - 13:59">13:30 - 13:59</button>
                        <button id="noDisplay" data-time="14:00 - 14:29">14:00 - 14:29</button>
                        <button id="noDisplay" data-time="14:30 - 14:59">14:30 - 14:59</button>
                        <button id="noDisplay" data-time="15:00 - 15:29">15:00 - 15:29</button>
                    </div>
                    <button id="slideRightAfter">

                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>

                    </button>
                </div>

            </div>

        </div>

        <div class="submitBtn">
            <button class="submit" id="submit">ยืนยัน</button>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>


        <!-- <script src="script.js"></script> -->

        <script src="./popup.js"></script>
        <!-- <script>
            document.addEventListener('DOMContentLoaded', function () {
                const Field = document.getElementById('calendar');
                const dateDisplay = document.getElementById('dateDisplay');
                const beforeDate = document.getElementById('beforeSelectDate');
                const today = new Date();
                const minDate = today;
                // const minDate = today.toISOString().split('T')[0];

                const datepicker = flatpickr(Field, {
                    locale: "th",
                    minDate: minDate,
                    dateFormat: "dmY",
                    position: "auto bottom",
                    allowInput: true,
                    disableMobile: "true",
                    onValueUpdate: function (selectedDates, dateStr, instance) {
                        // ปรับปีให้เป็นปี พ.ศ. ตอนที่มีการอัปเดตค่าใน input
                        let dateObject = selectedDates[0];
                        let thaiYear = dateObject.getFullYear() + 543; // แปลงเป็นปีไทย
                        let formattedDate =
                            ("0" + dateObject.getDate()).slice(-2) +  // วัน
                            ("0" + (dateObject.getMonth() + 1)).slice(-2) + // เดือน
                            thaiYear.toString(); // ปี พ.ศ.
                        instance.input.value = formattedDate; // ตั้งค่าลงใน input
                    },
                    onChange: function (selectedDates, dateStr, instance) {
                        selectedDate = dateStr;
                        const thaiDate = convertToThaiDate(selectedDates[0]);

                        $.ajax({
                            type: "POST",
                            url: "../../server/timeList.php",
                            data: {
                                date: selectedDate
                            },
                            success: function (response) {
                                // console.log(response);
                                const outputs = response.split('|||');
                                const before = outputs[0];
                                const after = outputs[1];


                                document.querySelector('#beforeNoon').innerHTML = before;
                                document.querySelector('#afterNoon').innerHTML = after;


                                const buttons = document.querySelectorAll('.Btn');
                                buttons.forEach(button => {
                                    button.addEventListener('click', function () {
                                        // รีเซ็ตสีของทุกปุ่มให้เป็นค่าเดิม
                                        buttons.forEach(btn => {
                                            btn.style.backgroundColor = '';
                                            btn.style.color = '';
                                        });

                                        // เปลี่ยนสีของปุ่มที่ถูกคลิก
                                        button.style.backgroundColor = '#00CCA7';
                                        button.style.color = 'white';

                                        selectedTime = button.textContent;
                                        department = document.getElementById('departmentDisplay').textContent;

                                        console.log("Selected time: " + selectedTime);
                                    });
                                });
                            },
                            error: function (error, xhr, status) {
                                console.error("Error:", error);
                            }
                        });

                        dateDisplay.textContent = thaiDate;
                        beforeDate.style.display = 'none';
                    },
                    onOpen: function (selectedDates, dateStr, instance) {
                        const thaiYear = new Date().getFullYear() + 543;
                        instance.currentYear = thaiYear;
                        instance.jumpToDate(new Date());
                        instance.redraw();
                    },
                    onReady: function (selectedDates, dateStr, instance) {
                        const currentYear = new Date().getFullYear() + 543;
                        instance.currentYear = currentYear;
                        instance.redraw();
                    }
                });

                function convertToThaiDate(date) {
                    const monthsThai = [
                        "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                        "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                    ];
                    const day = date.getDate();
                    const month = monthsThai[date.getMonth()];
                    const year = date.getFullYear() + 543;

                    return `${day} ${month} ${year}`;
                }

                document.getElementById('submit').addEventListener('click', function () {
                    thaiDate = dateDisplay.textContent

                    if (!department) {
                        Swal.fire({
                            title: 'เลือกข้อมูลให้ครบถ้วน',
                            text: 'กรุณาเลือกแผนก',
                            icon: 'warning',
                            confirmButtonText: 'ตกลง'
                        });
                        // return;
                    }

                    Swal.fire({
                        title: 'ยืนยันการจอง',
                        html: ` <div style=" color: #162e71; display: flex; align-items:start; justify-content:start; flex-direction:column;">
                                <p>วันที่:<h4>${thaiDate}</h4></p>
                                <br>
                                <p>แผนก:<h4>${department}</h4></p>
                                <br>
                                <p>ช่วงเวลา:<h4>${selectedTime}</h4></p>
                            </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก',
                        customClass: {
                            icon: 'custom-swal-icon' // ใช้ custom class สำหรับไอคอน
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '../../server/success/index.php',
                                data: {
                                    time: selectedTime,
                                    date: selectedDate,
                                    department: departmentDisplay.textContent
                                },
                                success: function (response) {
                                    // console.log(response);
                                    Swal.fire({
                                        title: 'การจองเสร็จสิ้น!',
                                        icon: 'success',
                                    }).then(() => {
                                        setTimeout(function () {
                                            location.reload();
                                        });
                                    });
                                },
                                error: function (error, xhr, status) {
                                    console.error('Error', error);
                                }
                            })
                        }
                    });
                })
            });



            document.getElementById('dateClick').addEventListener('click', function () {
                const datePicker = document.getElementById('calendar');

                if (!datePicker._flatpickr) {
                    flatpickr(datePicker, {
                        dateFormat: "dmY",
                        defaultDate: new Date()
                    });
                }

                datePicker._flatpickr.open();
            });

        </script> -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // โค้ดการเริ่มต้นอื่น ๆ...

                document.getElementById('submit').addEventListener('click', function () {

                    // แปลง selectedDate เป็นวัตถุ Date
                    const dateObject = new Date(selectedDate);

                    // ฟังก์ชันสำหรับแปลงวันที่เป็นรูปแบบ YYYY-MM-DD
                    function formatDateToSQL(date) {
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0'); // เดือนเริ่มต้นที่ 0
                        const day = String(date.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    }

                    formattedDate = formatDateToSQL(dateObject);
                    console.log(formattedDate);

                    const dateDisplayElement = document.getElementById('dateDisplay');
                    const departmentElement = document.getElementById('departmentDisplay');

                    // ตรวจสอบว่ามีองค์ประกอบอยู่หรือไม่
                    const thaiDate = dateDisplayElement ? dateDisplayElement.textContent : ''; // ใช้ค่าที่แสดงใน input
                    const department = departmentElement ? departmentElement.textContent : ''; // หาข้อมูลแผนก

                    if (!department) {
                        Swal.fire({
                            title: 'เลือกข้อมูลให้ครบถ้วน',
                            text: 'กรุณาเลือกแผนก',
                            icon: 'warning',
                            confirmButtonText: 'ตกลง'
                        });
                        return; // ย้อนกลับถ้าไม่เลือกแผนก
                    }

                    console.log(selectedDate);


                    Swal.fire({
                        title: 'ยืนยันการจอง',
                        html: `<div style="color: #162e71; display: flex; align-items:start; justify-content:start; flex-direction:column;">
                    <p>วันที่:<h4>${thaiDate}</h4></p>
                    <br>
                    <p>แผนก:<h4>${department}</h4></p>
                    <br>
                    <p>ช่วงเวลา:<h4>${selectedTime}</h4></p>
                </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก',
                        customClass: {
                            icon: 'custom-swal-icon' // ใช้ custom class สำหรับไอคอน
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '../../server/success/index.php',
                                data: {
                                    time: selectedTime,
                                    date: selectedDate,
                                    department: department // ส่งข้อมูลแผนก
                                },
                                success: function (response) {
                                    Swal.fire({
                                        title: 'การจองเสร็จสิ้น!',
                                        icon: 'success',
                                    }).then(() => {
                                        setTimeout(function () {
                                            location.reload();
                                        }, 1000); // รอ 1 วินาทีก่อนรีเฟรชหน้า
                                    });
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.error('Error', jqXHR);
                                    let errorMessage = 'คุณมีการจองแผนกนี้แล้ว ไม่สามารถจองได้!'; // ข้อความเริ่มต้น

                                    // ตรวจสอบว่า responseText มีค่าหรือไม่ และแปลงเป็น JSON
                                    if (jqXHR.responseText) {
                                        try {
                                            const response = JSON.parse(jqXHR.responseText);
                                            if (response.error) {
                                                errorMessage = response.error; // ดึงข้อความ error จาก PHP
                                            }
                                        } catch (e) {
                                            console.error('Error parsing response', e);
                                        }
                                    }
                                    Swal.fire({
                                        title: 'จองไม่สำเร็จ!',
                                        text: errorMessage,
                                        icon: 'error',
                                    }).then(() => {
                                        setTimeout(function () {
                                            location.reload();
                                        }, 1000); // รอ 1 วินาทีก่อนรีเฟรชหน้า
                                    });
                                }
                            });
                        }
                    });
                });
            });

        </script>

        <script>
            $(document).ready(function () {
                $('.departmentItem').click(function () {

                    const cancleBtn = document.getElementById('cancle');
                    var departmentName = $(this).val();
                    cancleBtn.addEventListener('click', function () {
                        departmentName = '';

                        // ลบข้อความแสดงผลใน element #departmentDisplay
                        $('#departmentDisplay').text('');

                        // เอา class 'clicked' ออกจากปุ่มทั้งหมด
                        $('.departmentItem').removeClass('clicked');

                        // อาจซ่อนปุ่มหรือทำการกระทำอื่นๆ
                        $('#beforeSelected').show();
                    });

                    $.ajax({
                        type: "POST",
                        url: "index.php",
                        data: {
                            department: departmentName
                        },
                        success: function (response) {
                            $('#departmentDisplay').text(departmentName);

                            $('#beforeSelected').hide();


                            $('.departmentItem').removeClass('clicked');
                            $(this).addClass('clicked');

                        }.bind(this),
                        error: function (xhr, status, error) {
                            console.error("เกิดข้อผิดพลาด: " + error);
                        }
                    });
                });
            });


        </script>

        <!-- -------------slide Time---------------------- -->

        <script>
            const beforeCon = document.getElementById('beforeNoon');
            const afterCon = document.getElementById('afterNoon');

            const leftBefore = document.getElementById('slideLeftBefore');
            const rightBefore = document.getElementById('slideRightBefore');
            const leftAfter = document.getElementById('slideLeftAfter');
            const rightAfter = document.getElementById('slideRightAfter');

            // ฟังก์ชันสำหรับเลื่อนไปทางซ้ายและขวาของ beforeCon
            leftBefore.addEventListener('click', function () {
                beforeCon.scrollBy({
                    left: -200,
                    behavior: "smooth"
                });
            });

            rightBefore.addEventListener('click', function () {
                beforeCon.scrollBy({
                    left: 200,
                    behavior: "smooth"
                });
            });

            // ฟังก์ชันสำหรับเลื่อนไปทางซ้ายและขวาของ afterCon
            leftAfter.addEventListener('click', function () {
                afterCon.scrollBy({
                    left: -200,
                    behavior: "smooth"
                });
            });

            rightAfter.addEventListener('click', function () {
                afterCon.scrollBy({
                    left: 200,
                    behavior: "smooth"
                });
            });

            function checkSession() {
                $.ajax({
                    url: '../../server/checkToken.php',
                    method: 'GET',
                    success: function (response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.status === 'logout') {
                                Swal.fire({
                                    title: 'เซสชันหมดอายุ',
                                    text: 'คุณถูกออกจากระบบเนื่องจากมีการเข้าสู่ระบบจากอุปกรณ์อื่น',
                                    icon: 'warning',
                                    confirmButtonText: 'ตกลง'
                                }).then(() => {
                                    window.location.href = '../login.php'; // ล็อกเอาท์และไปหน้า login
                                });
                            }
                        } catch (e) {
                            console.error("ไม่สามารถแปลงข้อมูล JSON ได้: " + response);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("เกิดข้อผิดพลาดในการตรวจสอบเซสชัน: " + error);
                    }
                });
            }

            // เรียกใช้ฟังก์ชัน checkSession ทุกๆ 10 วินาที (10000 milliseconds)
            setInterval(checkSession, 2500);







        </script>

</body>

</html>