const daysTH = ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"];
const monthsTH = [
    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
];

const container = document.getElementById('dateContainer');
const monthDisplay = document.getElementById('monthDisplay');
let currentDate = new Date();

function updateMonthDisplay() {
    const month = monthsTH[currentDate.getMonth()];
    const year = currentDate.getFullYear() + 543; // Thai Buddhist year
    monthDisplay.textContent = `${month} ${year}`;
}

function updateDateBoxes() {
    container.innerHTML = ''; // Clear existing content

    const startOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    let startDay = new Date(startOfMonth);
    startDay.setDate(startOfMonth.getDate() - startOfMonth.getDay()); // Start from the beginning of the week

    for (let i = 0; i < 4; i++) {
        const box = document.createElement('div');
        box.className = 'date-box';

        const dayOfWeek = daysTH[startDay.getDay()];
        const date = startDay.getDate();

        // Ensure the date is within the current month
        if (startDay.getMonth() === currentDate.getMonth()) {
            box.textContent = `${dayOfWeek} ${date}`;
        } else {
            box.textContent = '';
        }
        container.appendChild(box);

        startDay.setDate(startDay.getDate() + 1); // Move to next day
    }
}

function updateDates() {
    updateMonthDisplay();
    updateDateBoxes();
}

document.getElementById('prevDates').addEventListener('click', () => {
    currentDate.setDate(currentDate.getDate() - 4);
    if (currentDate.getDate() < 1) {
        currentDate.setMonth(currentDate.getMonth() - 1);
        currentDate.setDate(new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate());
    }
    updateDates();
});

document.getElementById('nextDates').addEventListener('click', () => {
    currentDate.setDate(currentDate.getDate() + 4);
    const daysInCurrentMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
    if (currentDate.getDate() > daysInCurrentMonth) {
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentDate.setDate(1);
    }
    updateDates();
});

// Initial display update
updateDates();
