<?php
if (!isset($_SESSION['hn'])) {
  header('Location: ../../client/HomeTH');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ERROR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./error.css" />
  </head>
  <body>
    <div class="flex text-center p-20 mt-40 flex-col center bg-[#ffffffce] justify-center text-white w-full h-auto rounded-xl">

      <div class="font-bold">
        <h1 class=" text-5xl text-blue-900 mb-4 font-medium">500</h1>
        <h1 class=" text-2xl text-blue-900 mb-4"><span class="text-red-500">ERROR</span> | เกิดข้อผิดพลาด</h1>
        <p class="text-xl text-blue-900 font-medium">โปรดลองใหม่ในภายหลัง</p>
      </div>
    </div>
  </body>
</html>
