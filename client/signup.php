<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก | AZTEC</title>
    <link rel="icon" type="image/x-icon" href="./assets/logoHead.png">
    <link rel="stylesheet" href="./style/signup.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <section>
        <div class="container">
            <img src="./assets/logoFull.png" alt="">
            <div class="content">
                <?php

                if (isset($_SESSION["error"])) {
                    echo '<div class="bg-red-500 px-10 py-2 rounded-md"><p class="text-white" >' . $_SESSION["error"] . '</p></div>';
                    unset($_SESSION['error']);
                }
                ?>
                <h1 class="text-4xl font-medium text-[#fff] mt-4">สมัครสมาชิก</h1>
                <!-- <h1 class="textAlert text-red-500"></h1> -->
                <form action="../server/signup_db.php" method="POST" class="px-4 xl:px-80 md:px-40 gap-2">


                    <!-- <p class="text-white">ชื่อ-นามสกุล :</p> -->
                    <div class="name flex-row w-full gap-2 sm:flex-row gap-2 md:flex">
                        <div class="relative flex items-center mt-8 w-full">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-[#05356b]" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>

                            <!-- ----------User Name---------- -->
                            <input type="text" id="username" name="username" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="ชื่อ - นามสกุล">
                        </div>
                        <div class="relative flex items-center mt-2 w-full sm:mt-8">
                            <span class="absolute">
                                <svg class="w-6 h-6 text-[#05356b] mx-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4Zm10 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-8-5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm1.942 4a3 3 0 0 0-2.847 2.051l-.044.133-.004.012c-.042.126-.055.167-.042.195.006.013.02.023.038.039.032.025.08.064.146.155A1 1 0 0 0 6 17h6a1 1 0 0 0 .811-.415.713.713 0 0 1 .146-.155c.019-.016.031-.026.038-.04.014-.027 0-.068-.042-.194l-.004-.012-.044-.133A3 3 0 0 0 10.059 14H7.942Z"
                                        clip-rule="evenodd" />
                                </svg>

                            </span>

                            <!-- -------------HN------------- -->
                            <input type="number" id="hn" name="hn" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="เลขบัตรประชาชน">
                        </div>
                    </div>


                    <div class="flex-col w-full gap-2 sm:flex-row md:flex">
                        <div class="relative flex items-center w-full mb-2 sm:mb-2 md:mb-0">
                            <span class="absolute">
                                <svg class="w-6 h-6 text-[#05356b] mx-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                                </svg>

                            </span>

                            <!-- ------------------Birth Date------------- -->
                            <input
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                readonly type="text" id="dob" name="birthDate" placeholder="วัน เดือน ปี เกิด">

                            <!-- <input type="text" id="dob" placeholder="เลือกวัน" readonly> -->

                            <div class="containerCalendar">
                                <div class="calendar" id="calendar">
                                    <div class="calendar-header">
                                        <a id="prevMonth">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                                            </svg>
                                        </a>
                                        <select id="monthSelect"></select>
                                        <select id="yearSelect"></select>
                                        <a id="nextMonth">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                                            </svg>
                                        </a>
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
                        <div class="relative flex items-center w-full">
                            <span class="absolute">
                                <svg class="w-6 h-6 text-[#05356b] mx-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M7.978 4a2.553 2.553 0 0 0-1.926.877C4.233 6.7 3.699 8.751 4.153 10.814c.44 1.995 1.778 3.893 3.456 5.572 1.68 1.679 3.577 3.018 5.57 3.459 2.062.456 4.115-.073 5.94-1.885a2.556 2.556 0 0 0 .001-3.861l-1.21-1.21a2.689 2.689 0 0 0-3.802 0l-.617.618a.806.806 0 0 1-1.14 0l-1.854-1.855a.807.807 0 0 1 0-1.14l.618-.62a2.692 2.692 0 0 0 0-3.803l-1.21-1.211A2.555 2.555 0 0 0 7.978 4Z" />
                                </svg>


                            </span>

                            <!-- ----------------Phone Number------------ -->
                            <input type="number" id="phoneNumber" name="phoneNumber" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="เบอร์โทรติดต่อ">
                        </div>
                    </div>
                    <div class="relative flex items-center w-full">
                        <span class="absolute">
                            <svg class="w-6 h-6 text-[#05356b] mx-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z" />
                            </svg>
                        </span>

                        <!-- ---------------------address-------------------- -->
                        <input type="text" id="address" name="address" required
                            class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                            placeholder="ที่อยู่ปัจจุบัน">
                    </div>

                    <div class="flex-row w-full gap-2 sm:flex-row gap-2 md:flex">
                        <div class="relative flex items-center w-full mb-2 sm:md-2 md:mb-0">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-[#05356b]" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>

                            <!-- ------------Father Name-------------- -->
                            <input type="text" id="fatherName" name="fatherName" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="ชื่อ - นามสกุล บิดา">
                        </div>
                        <div class="relative flex items-center w-full">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-[#05356b]" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>

                            <!-- -----------------Mother Name----------------- -->
                            <input type="text" id="motherName" name="motherName" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="ชื่อ - นามสกุล มารดา">
                        </div>
                    </div>
                    <div class="flex-row w-full gap-2 sm:flex-row gap-2 md:flex">
                        <div class="relative flex items-center w-full mb-2 md:mb-0">
                            <span class="absolute">
                                <svg class="w-6 h-6 mx-3 text-[#05356b]" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 20a16.405 16.405 0 0 1-5.092-5.804A16.694 16.694 0 0 1 5 6.666L12 4l7 2.667a16.695 16.695 0 0 1-1.908 7.529A16.406 16.406 0 0 1 12 20Z" />
                                </svg>

                            </span>


                            <!-- ------------------urgent Name------------------- -->
                            <input type="text" id="urgentName" name="urgentName" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="ชื่อ - นามสกุล ผู้ติดต่อกรณีฉุกเฉิน">
                        </div>
                        <div class="relative flex items-center w-full">
                            <span class="absolute">
                                <svg class="w-6 h-6 mx-3 text-[#05356b]" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                </svg>

                            </span>

                            <!-- ---------------------urgent Number---------------------- -->
                            <input type="number" id="urgentNumber" name="urgentNumber" required
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="เบอร์ผู้ติดต่อกรณีฉุกเฉิน">
                        </div>
                    </div>


                

                    <!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
                    <!-- <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script> -->


                    <!-- JavaScript to control the date picker functionality -->
                    <script type="text/javascript">
                        const thaiMonths = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
                        const dateInput = document.getElementById('dob');
                        const calendar = document.getElementById('calendar');
                        const monthSelect = document.getElementById('monthSelect');
                        const yearSelect = document.getElementById('yearSelect');
                        const days = document.getElementById('days');
                        const prevMonthBtn = document.getElementById('prevMonth');
                        const nextMonthBtn = document.getElementById('nextMonth');

                        let currentDate = new Date();
                        let displayDate = new Date(currentDate);
                        let maxDate = new Date();
                        maxDate.setDate(maxDate.getDate() + 0);

                        // สร้างรายการเดือนและปีให้ผู้ใช้เลือก
                        function populateMonthYearSelectors() {
                            // เติมข้อมูลใน monthSelect
                            thaiMonths.forEach((month, index) => {
                                const option = document.createElement('option');
                                option.value = index;
                                option.textContent = month;
                                monthSelect.appendChild(option);
                            });

                            // เติมข้อมูลใน yearSelect (ตั้งค่าให้เลือกปีปัจจุบันย้อนหลังหรืออนาคต 10 ปี)
                            const currentYear = currentDate.getFullYear();
                            for (let i = currentYear - 80; i <= currentYear + 0; i++) {
                                const option = document.createElement('option');
                                option.value = i;
                                option.textContent = i + 543;
                                yearSelect.appendChild(option);
                            }

                            // ตั้งค่าเริ่มต้นของ monthSelect และ yearSelect
                            monthSelect.value = displayDate.getMonth();
                            yearSelect.value = displayDate.getFullYear();
                        }

                        // อัปเดตการแสดงปฏิทิน
                        function renderCalendar() {
                            const year = displayDate.getFullYear();
                            const month = displayDate.getMonth();

                            const firstDay = new Date(year, month, 1);
                            const lastDay = new Date(year, month + 1, 0);
                            const startDay = firstDay.getDay();
                            const totalDays = lastDay.getDate();

                            days.innerHTML = '';

                            let html = '<tr>';
                            for (let i = 0; i < startDay; i++) {
                                html += '<td></td>';
                            }

                            for (let day = 1; day <= totalDays; day++) {
                                const dayDate = new Date(year, month, day);
                                // html += `<td data-date="${dayDate}">${day}</td>`;
                                const isDisabled = dayDate > maxDate;  // Disable dates after maxDate
                                html += `<td class="${isDisabled ? 'disabled' : ''}" data-date="${isDisabled ? '' : dayDate}">${day}</td>`;

                                if ((day + startDay) % 7 === 0) {
                                    html += '</tr><tr>';
                                }
                            }
                            html += '</tr>';
                            days.innerHTML = html;

                            monthSelect.value = displayDate.getMonth();
                            yearSelect.value = displayDate.getFullYear();
                        }

                        // แสดงปฏิทินเมื่อคลิก input
                        dateInput.addEventListener('focus', () => {
                            calendar.style.display = 'block';
                            renderCalendar();
                        });

                        // เลือกวันที่
                        days.addEventListener('click', (e) => {
                            if (e.target.dataset.date) {
                                const selectedDate = new Date(e.target.dataset.date);
                                if (selectedDate <= maxDate) {
                                    dateInput.value = `${selectedDate.getDate().toString().padStart(2, '0')}/${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}/${selectedDate.getFullYear() + 543}`;
                                    calendar.style.display = 'none';
                                }
                            }
                        });
                        nextMonthBtn.addEventListener('click', () => {
                            const currentMonth = currentDate.getMonth();
                            const currentYear = currentDate.getFullYear();

                            if (displayDate.getFullYear() === currentYear && displayDate.getMonth() === currentMonth) {
                                // ถ้าเป็นเดือนและปีปัจจุบัน ไม่ให้เลื่อน
                                return;
                            }

                            displayDate.setMonth(displayDate.getMonth() + 1);
                            renderCalendar();
                        });

                        // เลื่อนเดือนก่อนหน้า
                        prevMonthBtn.addEventListener('click', () => {
                            displayDate.setMonth(displayDate.getMonth() - 1);
                            renderCalendar();
                        });

                        // อัปเดตเมื่อผู้ใช้เปลี่ยนเดือน
                        monthSelect.addEventListener('change', (e) => {
                            displayDate.setMonth(e.target.value);
                            renderCalendar();
                        });

                        // อัปเดตเมื่อผู้ใช้เปลี่ยนปี
                        yearSelect.addEventListener('change', (e) => {
                            displayDate.setFullYear(e.target.value);
                            renderCalendar();
                        });

                        // ฟังก์ชันเริ่มต้น
                        populateMonthYearSelectors();
                        renderCalendar();

                    </script>
                    <script>
                        function togglePasswordVisibility() {
                            var passwordField = document.getElementById("password");
                            // var eyeHide = document.getElementById("eye-hide");

                            if (passwordField.type === "password") {
                                passwordField.type = "text";
                                // eyeHide.classList.add("hidden");
                            } else {
                                passwordField.type = "password";
                                // eyeHide.classList.remove("hidden");
                            }
                        }
                    </script>


                    <!-- <p class="text-white">Email :</p> -->
                    <button type="submit" name="signupSubmit" id="submit"
                        class="mt-4 text-white bg-[#05356b] hover:bg-[#041b36] focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2focus:outline-none">
                        ลงทะเบียนผู้ป่วยใหม่
                    </button>
                    <p class="text-[#e8e6e6] font-light mt-2 mb-8 text-center">คุณมีบัญชีแล้วใช่หรือไม่ ? <a
                            href="login.php" class="text-[#fff] font-semibold underline">เข้าสู่ระบบ </a>ตอนนี้!</p>
                </form>
            </div>

        </div>
    </section>

</body>

</html>