// const daysTH = ["อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส."];
// const monthsTH = [
//   "มกราคม",
//   "กุมภาพันธ์",
//   "มีนาคม",
//   "เมษายน",
//   "พฤษภาคม",
//   "มิถุนายน",
//   "กรกฎาคม",
//   "สิงหาคม",
//   "กันยายน",
//   "ตุลาคม",
//   "พฤศจิกายน",
//   "ธันวาคม",
// ];

// let currentDate = new Date();
// let currentIndex = 0;

// $(document).ready(function () {
//   const $container = $("#dateContainer");
//   const $monthDisplay = $("#monthDisplay");

//   function updateMonthDisplay() {
//     const month = monthsTH[currentDate.getMonth()];
//     const year = currentDate.getFullYear() + 543; // ปีพุทธศักราช
//     $monthDisplay.text(`${month} ${year}`);
//   }

//   function getDaysInMonth(year, month) {
//     return new Date(year, month + 1, 0).getDate();
//   }

//   function formatDateToDDMMYYYY(date) {
//     const day = String(date.getDate()).padStart(2, "0");
//     const month = String(date.getMonth() + 1).padStart(2, "0");
//     const year = date.getFullYear() + 543;
//     return `${day}${month}${year}`;
//   }

//   function updateDateBoxes() {
//     $container.empty();
//     const daysInMonth = getDaysInMonth(
//       currentDate.getFullYear(),
//       currentDate.getMonth()
//     );
//     let tempDate = new Date(
//       currentDate.getFullYear(),
//       currentDate.getMonth(),
//       currentIndex + 1
//     );

//     for (let i = 0; i < 4; i++) {
//       if (tempDate.getDate() > daysInMonth) {
//         currentDate.setMonth(currentDate.getMonth() + 1);
//         currentIndex = 0;
//         updateMonthDisplay();
//         updateDateBoxes();
//         return;
//       }

//       const dateForButton = new Date(tempDate);
//       const $box = $('<button class="date-box"></button>');

//       const dayOfWeek = daysTH[dateForButton.getDay()];
//       const date = dateForButton.getDate();

//       $box.html(
//         `<div class="dateWeek">${dayOfWeek}</div><br/><div class="dateNumber">${date}</div>`
//       );

//       $box.on("click", () => {
//         const formattedDate = formatDateToDDMMYYYY(dateForButton);
//         console.log("Selected Date: " + formattedDate);

//         const selectedDepartment = $("#department").val();

//         $.ajax({
//           url: "./home.php",
//           method: "POST",
//           data: {
//             selectedDate: formattedDate,
//             department: selectedDepartment,
//           },
//           success: function (response) {
//             console.log("Success:", response);
//             $(".selectTime").html(response);
//           },
//           error: function (xhr, status, error) {
//             console.log("Error:", error);
//           },
//         });
//       });

//       $("#departmentSelect").change(function () {
//         var selectedDepartment = $(this).val();
//         console.log("Selected Department: " + selectedDepartment);
//       });

//       $container.append($box);
//       tempDate.setDate(tempDate.getDate() + 1);
//     }
//   }

//   function updateDates() {
//     updateMonthDisplay();
//     updateDateBoxes();
//   }

//   $("#prevDates").on("click", () => {
//     currentIndex -= 4;
//     if (currentIndex < 0) {
//       currentDate.setMonth(currentDate.getMonth() - 1);
//       const daysInPreviousMonth = getDaysInMonth(
//         currentDate.getFullYear(),
//         currentDate.getMonth()
//       );
//       currentIndex = daysInPreviousMonth - (daysInPreviousMonth % 4);
//     }
//     updateDates();
//   });

//   $("#nextDates").on("click", () => {
//     const daysInMonth = getDaysInMonth(
//       currentDate.getFullYear(),
//       currentDate.getMonth()
//     );
//     currentIndex += 4;
//     if (currentIndex >= daysInMonth) {
//       currentDate.setMonth(currentDate.getMonth() + 1);
//       currentIndex = 0;
//     }
//     updateDates();
//   });

//   // เริ่มแสดงข้อมูล
//   updateDates();
// });

// function sendTime(time) {
//   // เก็บค่าที่ต้องการส่ง
//   const selectedDate = $("#dateContainer").data("selectedDate"); // หาค่าจาก data attribute
//   const selectedDepartment = $("#departmentSelect").val(); // เก็บแผนกที่เลือก

//   console.log("Selected time: " + time);
//   console.log("Selected date: " + selectedDate);
//   console.log("Selected department: " + selectedDepartment);

//   $.ajax({
//     url: "./home.php",
//     method: "POST",
//     data: {
//       selectedTime: time,
//       selectedDate: selectedDate, // ส่งวันที่ไปด้วย
//       department: selectedDepartment, // ส่งแผนกไปด้วย
//     },
//     success: function (response) {
//       console.log("Response:", response);
//       $(".selectTime").html(response);
//     },
//     error: function (xhr, status, error) {
//       console.error("Error:", error);
//     },
//   });
// }


const daysTH = ["Sun.", "Mon.", "Tue.", "Wed.", "Thu.", "Fri.", "Sat."];
const monthsTH = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
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

      // เมื่อคลิกวันที่
      $box.on("click", () => {
        const formattedDate = formatDateToDDMMYYYY(dateForButton);
        console.log("Selected Date: " + formattedDate);

        // ตั้งค่า selectedDate ใน data attribute
        $container.data("selectedDate", formattedDate);

        const selectedDepartment = $("#department").val();
        sendDateAndDepartment(formattedDate, selectedDepartment); // ส่งวันที่และแผนก

      });

      $container.append($box);
      tempDate.setDate(tempDate.getDate() + 1);
    }
  }

  function sendDateAndDepartment(selectedDate, selectedDepartment) {
    $.ajax({
      url: "./index.php",
      method: "POST",
      data: {
        selectedDate: selectedDate,
        department: selectedDepartment,
      },
      success: function (response) {
        console.log("Success:", response);
        $(".selectTime").html(response);
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  }

  $("#departmentSelect").change(function () {
    var selectedDepartment = $(this).val();
    console.log("Selected Department: " + selectedDepartment);
  });

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

// ฟังก์ชันส่งข้อมูล
function sendTime(slot, button) {
    const selectedDate = $("#dateContainer").data("selectedDate");
    const selectedDepartment = $("#department").val();

    if (!selectedDate || !selectedDepartment || !slot) {
      Swal.fire({
        title: "Select Department?",
        text: "You must select the department you want to book.",
        icon: "question"
      });
        return;
    }

    console.log("Selected time: " + slot);
    console.log("Selected date: " + selectedDate);
    console.log("Selected department: " + selectedDepartment);

    $.ajax({
        url: "./index.php",
        method: "POST",
        data: {
            selectedTime: slot, // ใช้ slot เป็นเวลา
            selectedDate: selectedDate,
            department: selectedDepartment,
        },
        success: function (response) {
            console.log("Response:", response);
            $(".selectTime").html(response);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });

    // Change the button color after sending data
    changeButtonColor(button);
}

// let lastClickedButton = null;
// const originalColors = {}; 

// function sendTime(slot, button) {
//     if (lastClickedButton && lastClickedButton !== button) {
//         lastClickedButton.style.backgroundColor = originalColors[lastClickedButton.innerHTML];
//     }

//     if (!originalColors[button.innerHTML]) {
//         originalColors[button.innerHTML] = button.style.backgroundColor;
//     }

//     button.style.backgroundColor = 'green';

//     lastClickedButton = button;
// }
function handleButtonClick(slot, isReserved) {
  if (isReserved) {
      // Show SweetAlert when button for reserved time is clicked
      Swal.fire({
          icon: 'warning',
          title: 'Unable to select time',
          text: 'This time is already booked. Please choose another time!',
          confirmButtonText: 'OK'
      });
  } else {
      sendTime(slot);
      changeButtonColor(button); // You may need to pass the button element if you want to change color
  }
}

