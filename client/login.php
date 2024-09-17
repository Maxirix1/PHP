<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | AZTEC</title>
    <link rel="stylesheet" href="./style/login.css" />
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
                <h1 class="text-4xl font-medium text-[#fff]">LOGIN</h1>

                <form action="../login_db.php" method="POST">
                    <p class="text-white">HN Number :</p>
                    <input type="number" name="hn" id="hn"
                        class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="HN Number" required />
                    <p class="text-white">Password :</p>
                    <input type="password" name="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Password" required />
                    <button type="submit"
                        class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                        LOGIN
                    </button>
                    <!-- <p class="text-[#e8e6e6] font-light mt-6">คุณยังไม่มีบัญชีใช่หรือไม่ ? 
                        <a href="signup.php" class="text-[#002b4d] font-semibold">สมัครสมาชิก</a> ตอนนี้!
                    </p> -->
                </form>
            </div>
        </div>
    </section>
</body>

</html>