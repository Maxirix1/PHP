<!-- <?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก | AZTEC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./style/signup.css">
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
                <h1 class="text-4xl font-medium text-[#fff] textshadow-xl">SIGNUP</h1>
                <h1 class="textAlert text-red-500"></h1>
                <form action="../signup_db.php" method="POST">
                    <input type="text" id="username" name="username"
                        class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder=" username" required />
                    <input type="email" id="email" name="email"
                        class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="email" required />
                    <input type="password" id="password" name="password"
                        class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder=" password" required />
                    <input type="password" id="confirmPassword" name="confirmPassword"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="confirm password" required />


                    <button type="submit" name="signupSubmit"
                        class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2focus:outline-none">SIGNUP</button>
                    <p class="text-[#e8e6e6] font-light mt-6">คุณมีบัญชีแล้วใช่หรือไม่ ? <a href="login.php"
                            class="text-[#002b4d] font-semibold">เข้าสู่ระบบ</a> ตอนนี้!</p>
                </form>
            </div>

        </div>
    </section>
</body>

</html> -->