const openDepartBtn = document.getElementById("openDepartmentBtn");
const popup = document.getElementById("department");
const closeBtn = document.querySelector(".close");
const cancleBtn = document.getElementById("cancle");
const exitBtn = document.getElementById("exit");

openDepartBtn.addEventListener("click", function () {
  popup.style.display = "flex";
});

closeBtn.addEventListener("click", function () {
  popup.style.display = "none";
});

cancleBtn.addEventListener("click", function () {
  popup.style.display = "none";
  departmentButtons.forEach(function (button) {
    button.value = "";
  });
});
exitBtn.addEventListener("click", function () {
  popup.style.display = "none";
});

window.addEventListener("click", function (event) {
  if (event.target === popup) {
    popup.style.display = "none";
  }
});

// $('button.departmentItem').on('click', function() {
//     var selectedValue = $(this).val();

//     $.ajax({
//         method: "POST",
//         url: "index.php",
//         data: { selectedValue: selectedValue },
//         success: function(response) {
//             console.log("Response from PHP: ", selectedValue); // Debug response จาก PHP
//         },
//         error: function(xhr, status, error) {
//             console.error('Error:', error);
//         }
//     });
// });

const thaiMonths = [
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

const calendar = document.getElementById("calendar");
const monthYear = document.getElementById("monthYear");
const days = document.getElementById("days");

let currentDate = new Date();
let selectedDate = new Date();
let displayDate = new Date();

function initializeDatepicker() {
  currentDate.setHours(0, 0, 0, 0);
  displayDate = new Date(currentDate);
  renderCalendar();
}

function renderCalendar() {
  monthYear.textContent = `${thaiMonths[displayDate.getMonth()]} ${
    displayDate.getFullYear() + 543
  }`;
  const year = displayDate.getFullYear();
  const month = displayDate.getMonth();

  days.innerHTML = "";

  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const startDay = firstDay.getDay();
  const totalDays = lastDay.getDate();

  let html = "<tr>";
  for (let i = 0; i < startDay; i++) {
    html += "<td></td>";
  }
  for (let day = 1; day <= totalDays; day++) {
    const dayDate = new Date(year, month, day);
    const isDisabled = dayDate < currentDate;
    html += `<td class="${
      isDisabled ? "disabled" : ""
    }" data-date="${dayDate}">${day}</td>`;

    if ((day + startDay) % 7 === 0) {
      html += "</tr><tr>";
    }
  }
  html += "</tr>";
  days.innerHTML = html;
}

document.getElementById("dateClick").addEventListener("click", () => {
  if (calendar.style.display === "block") {
    calendar.style.display = "none";
  } else {
    calendar.style.display = "block";
    renderCalendar();
  }
});

days.addEventListener("click", (e) => {
  if (e.target.dataset.date) {
    const selectDate = new Date(e.target.dataset.date);
    const day = selectDate.getDate().toString().padStart(2, "0");
    const month = (selectDate.getMonth() + 1).toString().padStart(2, "0");
    const year = selectDate.getFullYear() + 543;

    document.getElementById("dateDisplay").textContent = `${day} ${
      thaiMonths[selectDate.getMonth()]
    } ${year}`;

    selectedDate = `${day}${month}${year}`;

    console.log(selectedDate);

    $.ajax({
      type: "POST",
      url: "../../server/timeList.php",
      data: {
        date: selectedDate,
      },
      success: function (response) {
        // console.log(response);
        const outputs = response.split("|||");
        const before = outputs[0];
        const after = outputs[1];

        document.querySelector("#beforeNoon").innerHTML = before;
        document.querySelector("#afterNoon").innerHTML = after;

        const buttons = document.querySelectorAll(".Btn");
        buttons.forEach((button) => {
          button.addEventListener("click", function () {
            buttons.forEach((btn) => {
              btn.style.backgroundColor = "";
              btn.style.color = "";
            });

            button.style.backgroundColor = "#00CCA7";
            button.style.color = "white";

            selectedTime = button.textContent;
            department = document.getElementById("departmentDisplay").textContent;

            console.log("Selected time: " + selectedTime);
          });
        });
      },
      error: function (error, xhr, status) {
        console.error("Error:", error);
      },
    });

    calendar.style.display = "none";
    document.getElementById("beforeSelectDate").style.display = "none";
  }
});

document.getElementById("prevMonth").addEventListener("click", () => {
  displayDate.setMonth(displayDate.getMonth() - 1);
  renderCalendar();
});

document.getElementById("nextMonth").addEventListener("click", () => {
  displayDate.setMonth(displayDate.getMonth() + 1);
  renderCalendar();
});
document.addEventListener('click', (event) => {
    const isClickInside = dateClick.contains(event.target) || calendar.contains(event.target);
    
    if (!isClickInside) {
        calendar.style.display = 'none';
    }
});

document.addEventListener('click', (event) => {
    const isClickInside = dateClick.contains(event.target) || calendar.contains(event.target);
    if (!isClickInside) {
        calendar.style.display = 'none';
    }
});



initializeDatepicker();

