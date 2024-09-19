const daysTH = ["อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส."];
const monthsTH = [
    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
];

const container = document.getElementById('dateContainer');
const monthDisplay = document.getElementById('monthDisplay');
let currentDate = new Date();  // วันที่ปัจจุบัน
let currentIndex = 0; // ตำแหน่งเริ่มต้นสำหรับ 4 วันแรก

function updateMonthDisplay() {
    const month = monthsTH[currentDate.getMonth()];
    const year = currentDate.getFullYear() + 543; // ปีพุทธศักราช
    monthDisplay.textContent = `${month} ${year}`;
}

function getDaysInMonth(year, month) {
    return new Date(year, month + 1, 0).getDate(); // คำนวณจำนวนวันในเดือนที่เลือก
}

function formatDateToDDMMYYYY(date) {
    const day = String(date.getDate()).padStart(2, '0'); //มันจะเติม 0 ถ้าเลขหลักเดียวอะ
    const month = String(date.getMonth() + 1).padStart(2, '0'); //เหมือนกัน
    const year = date.getFullYear() + 543;
    return `${day}${month}${year}`;
}

function updateDateBoxes() {
    container.innerHTML = ''; // ลบข้อมูลเดิม
    const daysInMonth = getDaysInMonth(currentDate.getFullYear(), currentDate.getMonth());

    let tempDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentIndex + 1);

    for (let i = 0; i < 4; i++) {
        if (tempDate.getDate() > daysInMonth) {
            
            // หากเกินจำนวนวันในเดือน ก็ข้ามไปเดือนถัดไป
            currentDate.setMonth(currentDate.getMonth() + 1);
            currentIndex = 0;
            updateMonthDisplay();
            updateDateBoxes();
            return;
        }

        const dateForButton = new Date(tempDate);

        const box = document.createElement('button');
        box.className = 'date-box';
        
        const dayOfWeek = daysTH[dateForButton.getDay()];
        const date = dateForButton.getDate();
        
        box.innerHTML = `<div class="dateWeek">${dayOfWeek}<div/><br/><div class="dateNumber">${date}<div/>`;

        box.addEventListener('click', () => {
            const formattedDate = formatDateToDDMMYYYY(dateForButton);
            console.log(formattedDate);
            // alert(`${formattedDate}`);
        });
        
        container.appendChild(box);
        
        tempDate.setDate(tempDate.getDate() + 1);
    }
}


function updateDates() {
    updateMonthDisplay();
    updateDateBoxes();
}

document.getElementById('prevDates').addEventListener('click', () => {
    currentIndex -= 4;
    if (currentIndex < 0) {
        currentDate.setMonth(currentDate.getMonth() - 1);
        const daysInPreviousMonth = getDaysInMonth(currentDate.getFullYear(), currentDate.getMonth());
        currentIndex = daysInPreviousMonth - (daysInPreviousMonth % 4);
    }
    updateDates();
});

document.getElementById('nextDates').addEventListener('click', () => {
    const daysInMonth = getDaysInMonth(currentDate.getFullYear(), currentDate.getMonth());
    currentIndex += 4;
    if (currentIndex >= daysInMonth) {
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentIndex = 0;
    }
    updateDates();
});

// เริ่มแสดงข้อมูล
updateDates();
