const daysTH = ["อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส."];
const monthsTH = [
  "มกราคม",
  "กุมภาพันธ์",
  "มีนาคม",
  "เมษายน",
  "พฤษภาคม",
  "มิถุนายน",
  "กรกฎาคม",
  "สิงหาคม",
  "กันยายน",
  "ตุลาคม",
  "พฤศจิกายน",
  "ธันวาคม",
];

let currentDate = new Date();
let currentIndex = 0;

$(document).ready(function () {
  const $container = $("#dateContainer");
  const $monthDisplay = $("#monthDisplay");

  function updateMonthDisplay() {
    const month = monthsTH[currentDate.getMonth()];
    const year = currentDate.getFullYear() + 543; // ปีพุทธศักราช
    $monthDisplay.text(`${month} ${year}`);
  }

  function getDaysInMonth(year, month) {
    return new Date(year, month + 1, 0).getDate();
  }

  function formatDateToDDMMYYYY(date) {
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear() + 543;
    return `${day}${month}${year}`;
  }

  function updateDateBoxes() {
    $container.empty();
    const daysInMonth = getDaysInMonth(
      currentDate.getFullYear(),
      currentDate.getMonth()
    );
    let tempDate = new Date(
      currentDate.getFullYear(),
      currentDate.getMonth(),
      currentIndex + 1
    );

    for (let i = 0; i < 4; i++) {
      if (tempDate.getDate() > daysInMonth) {
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentIndex = 0;
        updateMonthDisplay();
        updateDateBoxes();
        return;
      }

      const dateForButton = new Date(tempDate);
      const $box = $('<button class="date-box"></button>');

      const dayOfWeek = daysTH[dateForButton.getDay()];
      const date = dateForButton.getDate();

      $box.html(
        `<div class="dateWeek">${dayOfWeek}</div><br/><div class="dateNumber">${date}</div>`
      );

$box.on("click", () => {
    const formattedDate = formatDateToDDMMYYYY(dateForButton); // จัดรูปแบบวันที่

    $.ajax({
        url: "./home.php", // ไฟล์ PHP ที่จะรับค่า
        method: "POST",
        data: { selectedDate: formattedDate }, // ส่งวันที่ที่เลือกไปยัง PHP
        success: function (response) {
            console.log("Success:", response);
            // อัปเดตเนื้อหาใน HTML ด้วย response ที่ได้รับมา
            $(".selectTime").html(response); // อัปเดตเนื้อหาใน container
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
});


      $container.append($box);
      tempDate.setDate(tempDate.getDate() + 1);
    }
  }

  function updateDates() {
    updateMonthDisplay();
    updateDateBoxes();
  }

  $("#prevDates").on("click", () => {
    currentIndex -= 4;
    if (currentIndex < 0) {
      currentDate.setMonth(currentDate.getMonth() - 1);
      const daysInPreviousMonth = getDaysInMonth(
        currentDate.getFullYear(),
        currentDate.getMonth()
      );
      currentIndex = daysInPreviousMonth - (daysInPreviousMonth % 4);
    }
    updateDates();
  });

  $("#nextDates").on("click", () => {
    const daysInMonth = getDaysInMonth(
      currentDate.getFullYear(),
      currentDate.getMonth()
    );
    currentIndex += 4;
    if (currentIndex >= daysInMonth) {
      currentDate.setMonth(currentDate.getMonth() + 1);
      currentIndex = 0;
    }
    updateDates();
  });

  // เริ่มแสดงข้อมูล
  updateDates();
});

// อัปเดต AJAX function
function sendTime(time) {
    // แสดงค่าที่คุณคลิกใน console
    console.log("Selected time: " + time);

    // ส่งค่าที่เลือกไปยังเซิร์ฟเวอร์
    fetch('./home.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ selectedTime: time }),
    })
    .then(response => response.json())
    .then(data => {
        console.log("Response from server:", data);
        // ทำสิ่งที่คุณต้องการกับ response ที่ได้รับที่นี่
    })
    .catch((error) => {
        console.error("Error:", error);
    });
}
