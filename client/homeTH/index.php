<?php
session_start();

if (!isset($_SESSION['hn'])) {
    header('Location: ../login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีค่าที่ส่งมา
    if (isset($_POST['department'])) {
        $departmentName = htmlspecialchars($_POST['department']);
        // เพิ่มค่าลงใน array
        $_SESSION['departments'] = $departmentName;

        // ส่งค่าตอบกลับ
        // echo $_SESSION['departments'];
    }
}


require_once '../../server/config.php';


// ดึงแผนกทั้งหมด
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
    <link rel="stylesheet" href="../style/style.css?v=1.0">
    <link rel="stylesheet" href="../style/responsive.css">
    <link rel="icon" type="image/x-icon" href="../assets/logoHead.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>

<body>
    <header>

        <div class="dataMain">
            <div class="data">
                <h3 class="name">คุณ <?= htmlspecialchars($_SESSION['username']) ?></h3>
                <h3 class="hn">HN <?= htmlspecialchars($_SESSION['hn']) ?></h3>
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
                    <img src="../assets/calendar.png" alt="Calendar Icon">
                    <p>กรุณาเลือกวันที่</p>
                </div>

            </div>
            <!-- <div id="dateContainer" class="container"></div> -->


            <div class="headText">
                <h2 class="text" style="padding:8px 25px;">เลือกเวลา</h2>
                <a href="../history" class="history">ประวัติการจองทั้งหมดของคุณ</a>
            </div>

            <div class="containerDateSelect">
                <div class="selectTime">
                </div>

            </div>

        </div>

        <!-- <form action="../../server/success/" method="POST" id="confirmForm" style="margin-top:20px;">
            <input type="hidden" id="selectedDepartment" name="department">
            <div class="submit">
                <button type="submit" id="submitBtn">ยืนยัน</button>
            </div>


        </form> -->

        <!-- <script>

            const submitBtn = document.getElementById('submitBtn');

            function toggleSubmitButton() {
                if (departmentSelect.value) {
                    selectedDepartmentInput.value = departmentSelect.value;
                    submitBtn.disabled = false;
                } else {
                    selectedDepartmentInput.value = "";
                    submitBtn.disabled = true;
                }
            }

            departmentSelect.addEventListener('change', toggleSubmitButton);

            toggleSubmitButton();
            document.getElementById('submitBtn').addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการยืนยันการจองนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ยืนยันเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form
                        document.getElementById('confirmForm').submit();

                        Swal.fire({
                            title: "Success!",
                            text: "จองสำเร็จ",
                            icon: "success",
                        });
                    }
                });
            });

        </script> -->

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="script.js"></script> -->
    <script src="./popup.js"></script>
    <script>
        $(document).ready(function () {
            $('.departmentItem').click(function () {
                var departmentName = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: { department: departmentName },
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

</body>

</html>