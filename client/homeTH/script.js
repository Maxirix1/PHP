
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
    const year = currentDate.getFullYear() + 543;
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
        const formattedDate = formatDateToDDMMYYYY(dateForButton);
        console.log("Selected Date: " + formattedDate);

        $container.data("selectedDate", formattedDate);

        const selectedDepartment = $("#department").val();
        sendDateAndDepartment(formattedDate, selectedDepartment);

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

  updateDates();
});

function sendTime(slot, button) {
    const selectedDate = $("#dateContainer").data("selectedDate");
    const selectedDepartment = $("#department").val();

    if (!selectedDate || !selectedDepartment || !slot) {
      Swal.fire({
        title: "เลือกแผนก?",
        text: "คุณต้องเลือกแผนกที่คุณต้องการจอง",
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
            selectedTime: slot,
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

    changeButtonColor(button);
}

function handleButtonClick(slot, isReserved) {
  if (isReserved) {
      Swal.fire({
          icon: 'warning',
          title: 'ไม่สามารถเลือกเวลา',
          text: 'เวลานี้ถูกจองแล้ว กรุณาเลือกเวลาอื่น!',
          confirmButtonText: 'ตกลง'
      });
  } else {
      sendTime(slot);
      changeButtonColor(button);
  }
}


