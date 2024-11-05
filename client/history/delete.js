function confirmCancel(hn, date, reserve_time, dept) {
    Swal.fire({
        title: 'ยืนยันการยกเลิก',
        text: "คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการจองนี้?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ยกเลิกการจอง',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                type: "POST",
                url: "./delete.php",
                data: {
                    action: "delete",
                    hn: hn,
                    date: date,
                    reserve_time: reserve_time,
                    dept: dept
                },
                success: function(response) {
                    console.log(response);
                    try {
                        response = JSON.parse(response);
                        if (response.status === 'success') {
                            Swal.fire(
                                'สำเร็จ!',
                                'การจองถูกยกเลิกเรียบร้อยแล้ว!',
                                'success'
                            ).then(() => {
                                setTimeout(function() {
                                    location.reload();
                                });
                            });
                        } else {
                            Swal.fire(
                                'ข้อผิดพลาด!',
                                response.message,
                                'error'
                            );
                        }
                    } catch (e) {
                        Swal.fire(
                            'ข้อผิดพลาด!',
                            "ไม่สามารถแปลงข้อมูลเป็น JSON ได้: " + response,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error("เกิดข้อผิดพลาด: " + error);
                    Swal.fire(
                        'เกิดข้อผิดพลาด!',
                        "เกิดข้อผิดพลาดในการส่งคำขอ: " + xhr.responseText,
                        'error'
                    );
                }
            });
        } else {
            Swal.fire(
                'ยกเลิก',
                'การยกเลิกถูกยกเลิก',
                'info'
            );
        }

    });
}
