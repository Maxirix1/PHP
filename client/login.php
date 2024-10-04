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
    <link rel="icon" type="image/x-icon" href="./assets/logoHead.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <section>
        <div class="container w-full">
            <img src="./assets/logoFull.png" alt="">
            <div class="content">

                <?php
                if (isset($_SESSION["error"])) {
                    echo '<div class="bg-red-500 px-10 py-2 rounded-md"><p class="text-white" >' . $_SESSION["error"] . '</p></div>';
                    unset($_SESSION['error']);
                } if (isset($_SESSION["success"])) {
                    echo '<div class="bg-green-500 px-10 py-2 rounded-md"><p class="text-white" >' . $_SESSION["success"] . '</p></div>';
                    unset($_SESSION['success']);
                }
                ?>
                <h1 class="text-4xl font-medium text-[#fff]">เข้าสู่ระบบ</h1>

                <form action="../server/login_db.php" method="POST" class="px-4 xl:px-80 md:px-40">


                    <div class="name">
                        <div class="relative flex items-center mt-8 w-full">
                            <span class="absolute">
                                <svg class="w-6 h-6 text-[#05356b] mx-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4Zm10 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-8-5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm1.942 4a3 3 0 0 0-2.847 2.051l-.044.133-.004.012c-.042.126-.055.167-.042.195.006.013.02.023.038.039.032.025.08.064.146.155A1 1 0 0 0 6 17h6a1 1 0 0 0 .811-.415.713.713 0 0 1 .146-.155c.019-.016.031-.026.038-.04.014-.027 0-.068-.042-.194l-.004-.012-.044-.133A3 3 0 0 0 10.059 14H7.942Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>

                            <input type="text" id="hn" name="hn"
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="เลขประจำตัวผู้ป่วย (HN)">
                        </div>


                        <div class="relative flex items-center mt-2 w-full">
                            <span class="absolute">
                                <svg class="w-6 h-6 text-[#05356b] mx-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z" />
                                </svg>

                            </span>

                            <input id="password" type="password" name="password"
                                class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 pr-0 md:px-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
                                placeholder="รหัสผ่าน">
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600">
                                <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path class="hs-password-active:hidden"
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                    </path>
                                    <path class="hs-password-active:hidden"
                                        d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61">
                                    </path>
                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                    <path class="hidden hs-password-active:block"
                                        d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <script>
                        function togglePasswordVisibility() {
                            var passwordField = document.getElementById("password");
                            var eyeShow = document.getElementById("eye-show");
                            var eyeHide = document.getElementById("eye-hide");

                            if (passwordField.type === "password") {
                                passwordField.type = "text";
                                eyeShow.classList.remove("hidden");
                                eyeHide.classList.add("hidden");
                            } else {
                                passwordField.type = "password";
                                eyeShow.classList.add("hidden");
                                eyeHide.classList.remove("hidden");
                            }
                        };
                    </script>


                    <!-- <p class="text-white">HN Number :</p>
                    <input type="number" name="hn" id="hn"
                        class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="HN Number" required />
                    <p class="text-white">Password :</p>
                    <input type="password" name="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Password" required /> -->


                    <button type="submit" name="submit" id="submit"
                        class="mt-8 text-white bg-[#05356b] hover:bg-[#041b36] focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2focus:outline-none">ยืนยัน</button>
                    <p class="text-[#e8e6e6] font-light mt-2 mb-8 text-center">คุณยังไม่มีบัญชีใช่หรือไม่ ? <a
                            href="signup.php" class="text-[#fff] font-semibold underline">สมัครสมาชิก </a>ที่นี่!</p>
                </form>
            </div>
        </div>
    </section>
</body>

</html>