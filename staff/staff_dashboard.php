<?php
session_start();
require_once '../config/config.php';

// ** ตรวจสอบการเข้าสู่ระบบ/Session ที่นี่ก่อน (ถ้ามี)**
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php");
    exit;
}

// ดึงข้อมูลเรียงตาม id
// ในการออกแบบนี้ จะใช้ข้อมูลจาก DB (module_name)
// และเราจะเพิ่มไอคอนเข้าไปในแต่ละปุ่มโดยอิงจากชื่อ module_name (สมมติ)
$stmt = $pdo->query("SELECT id, module_name, is_active FROM modules ORDER BY id ASC");
$modules_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

$modules = array_filter($modules_all, function($m) {
    return $m['is_active'] == 1;
});

// รีเซ็ต Key ของ Array หลังการกรอง
$modules = array_values($modules);

// ฟังก์ชันสำหรับกำหนดไอคอน (จำเป็นต้องกำหนด Logic หรือข้อมูลเพิ่มเติมใน DB เพื่อระบุไอคอนที่ชัดเจนกว่านี้)
function get_module_icon($module_name) {
    // กำหนดไอคอนตามคำสำคัญในชื่อโมดูล (เพื่อความสวยงามตาม Mockup)
    if (strpos($module_name, 'G07') !== false) return 'fa-user-graduate';
    if (strpos($module_name, 'ทุจริต') !== false) return 'fa-shield-alt';
    if (strpos($module_name, 'ประวัติศาสตร์') !== false) return 'fa-book';
    if (strpos($module_name, 'ผู้ไม่รู้หนังสือ') !== false) return 'fa-book-open';
    if (strpos($module_name, 'คุณธรรม') !== false) return 'fa-star';
    if (strpos($module_name, 'ผลงาน') !== false) return 'fa-trophy';
    if (strpos($module_name, 'งานทำ') !== false) return 'fa-briefcase';
    if (strpos($module_name, 'ความปลอดภัย') !== false) return 'fa-bell';
    if (strpos($module_name, 'ทหาร') !== false) return 'fa-user-cog';
    if (strpos($module_name, 'N-NET') !== false) return 'fa-laptop-code';
    if (strpos($module_name, 'ปลายภาค') !== false) return 'fa-chart-line';
    if (strpos($module_name, 'ซ้ำซ้อน') !== false) return 'fa-exclamation-triangle';
    return 'fa-file-alt'; // ไอคอนเริ่มต้น
}

// แบ่งครึ่ง
$total = count($modules);
$half = ceil($total / 2);
$left = array_slice($modules, 0, $half);       // ครึ่งแรก
$right = array_slice($modules, $half);         // ครึ่งหลัง
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/php_records_project/img/logo.png">
    <title>รายการบันทึกข้อมูล | ระบบ สกร. นครปฐม</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* สไตล์ที่จำเป็นต้องอยู่ในไฟล์หลักเนื่องจากเป็นคลาสเฉพาะ */
        .sidebar-bg {
            background-color: #1a2a47; /* สีน้ำเงินเข้ม */
        }
        .main-blue {
            background-color: #3b82f6; /* สีน้ำเงินสว่าง */
        }
        .main-blue:hover {
            background-color: #2563eb;
        }
        /* Card Hover Effect */
        .report-card {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex">

        <?php 
        include '../includes/sidebar_staff.php'; 
        ?>
        <div class="flex-1 md:ml-64 p-8">
            
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-800">📊 ระบบสารสนเทศจัดการศึกษา</h1>
                    <p class="text-lg text-gray-500 mt-1">รายการบันทึกข้อมูลสำหรับเจ้าหน้าที่</p>
                </div>
                <a href="../logout.php"
                    class="md:hidden bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-4 rounded-lg font-medium shadow transition duration-150 ease-in-out">
                    🚪 ออกจากระบบ
                </a>
            </div>
            <hr class="mb-8">

            <h2 class="text-2xl font-bold text-gray-700 mb-6">📝 บันทึกข้อมูลและรายงานหลัก</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="grid grid-cols-1 gap-5">
                    <?php foreach ($left as $row): ?>
                        <a href="staff_form.php?module_id=<?= $row['id'] ?>"
                            class="report-card flex items-center p-5 bg-white border-l-4 border-blue-500 rounded-lg font-semibold text-gray-800 hover:text-blue-600">
                            <i class="fas <?= get_module_icon($row['module_name']) ?> fa-lg text-blue-500 mr-4"></i>
                            <span><?= htmlspecialchars($row['module_name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    <?php foreach ($right as $row): ?>
                        <a href="staff_form.php?module_id=<?= $row['id'] ?>"
                            class="report-card flex items-center p-5 bg-white border-l-4 border-blue-500 rounded-lg font-semibold text-gray-800 hover:text-blue-600">
                            <i class="fas <?= get_module_icon($row['module_name']) ?> fa-lg text-blue-500 mr-4"></i>
                            <span><?= htmlspecialchars($row['module_name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>


            <div class="flex flex-col md:flex-row justify-center items-center gap-6 mt-16">

                <a href="staff_reports.php"
                    class="flex items-center justify-center w-full md:w-auto main-blue hover:bg-blue-600 text-white px-10 py-4 rounded-xl shadow-lg font-bold text-lg transition duration-150 ease-in-out">
                    <i class="fas fa-file-invoice mr-3"></i>
                    ระบบรายงานผลข้อมูลสารสนเทศการศึกษา
                </a>

                

            </div>

        </div>
        </div>
<script>
function updateDistrictSession(districtId) {
    if (districtId) {
        // ใช้ XMLHttpRequest หรือ fetch API ในการส่งค่าไปให้ PHP Script
        fetch('update_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'district_id=' + districtId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // เมื่ออัปเดตสำเร็จ ให้รีโหลดหน้าจอเพื่อใช้การกรองข้อมูลใหม่
                window.location.reload(); 
            } else {
                alert('เกิดข้อผิดพลาดในการเลือกอำเภอ');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('การเชื่อมต่อมีปัญหา');
        });
    }
}
</script>
</body>
</html>