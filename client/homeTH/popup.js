const openDepartBtn = document.getElementById('openDepartmentBtn');
const popup = document.getElementById('department');
const closeBtn = document.querySelector(".close");
const cancleBtn = document.getElementById('cancle');
const exitBtn = document.getElementById('exit');

openDepartBtn.addEventListener('click', function() {
    popup.style.display = 'flex';
});

closeBtn.addEventListener('click' ,function() {
    popup.style.display = 'none';
});

cancleBtn.addEventListener('click' ,function() {
    popup.style.display = 'none';
});
exitBtn.addEventListener('click' ,function() {
    popup.style.display = 'none';
});


window.addEventListener('click', function(event) {
  if (event.target === popup) {
      popup.style.display = 'none';
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
