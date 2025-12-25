<?php
session_start();
session_unset(); // ล้างตัวแปร Session ทั้งหมด
session_destroy(); // ทำลาย Session
header("Location: index.php"); // Redirect กลับไปหน้าหลัก (index.php)
exit();
?>