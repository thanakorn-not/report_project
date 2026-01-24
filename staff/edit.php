<?php
session_start();
require_once "../config/config.php"; // ตรวจสอบ Path ให้ถูกต้อง

// 1. รับค่าจาก URL
$id = $_GET['id'] ?? null;
$module_id = $_GET['module_id'] ?? null;

// 2. นิยาม $report_map (ต้องเหมือนกับหน้า staff_reports.php)
$report_map = [
    '1' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'cctv_status' => 'กล้องวงจรปิด',
        'cctv_amount' => 'จำนวน',
        'red_box_status' => 'ตู้แดง',
        'reporter_name' => 'ผู้รายงาน',
        'phone' => 'โทรศัพท์',
    ],
    '2' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'education_level' => 'ระดับการศึกษา',
        'school' => 'ศกร./ตำบล',
        'employment_status' => 'สถานะการทำงาน',
        'job' => 'งานที่ทำ',
        'workplace' => 'สถานที่ทำงาน',
        'other' => 'อื่นๆ',
    ],
    '3' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'position' => 'ตำแหน่ง',
        'scout_qualification' => 'วุฒิทางลูกเสือ',
        'training_date' => 'วันเดือนปีที่ได้รับการอบรม',
        'ability' => 'ความสามารถพิเศษ',
    ],
    '4' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'total_student' => 'จำนวนนักเรียน',
        'pri_total' => 'ป.ทั้งหมด',
        'pri_very_good' => 'ป.ดีมาก',
        'pri_good' => 'ป.ดี',
        'pri_fair' => 'ป.พอใช้',
        'pri_improve' => 'ป.ปรับปรุง',
        'sec_low_total' => 'ม.ต้นทั้งหมด',
        'sec_low_very_good' => 'ม.ต้นดีมาก',
        'sec_low_good' => 'ม.ต้นดี',
        'sec_low_fair' => 'ม.ต้นพอใช้',
        'sec_low_improve' => 'ม.ต้นปรับปรุง',
        'sec_up_total' => 'ม.ปลายทั้งหมด',
        'sec_up_very_good' => 'ม.ปลายดีมาก',
        'sec_up_good' => 'ม.ปลายดี',
        'sec_up_fair' => 'ม.ปลายพอใช้',
        'sec_up_improve' => 'ม.ปลายปรับปรุง',
    ],


    '5' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'activities' => 'กิจกรรม',
    ],
    '6' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'reg_primary' => 'ผู้ลงทะเบียนประถมศึกษา',
        'reg_junior_high' => 'ผู้ลงทะเบียนมัธยมศึกษาตอนต้น',
        'reg_senior_high' => 'ผู้ลงทะเบียนมัธยมศึกษาตอนปลาย',
        'grad_primary' => 'ผู้จบการศึกษาประถมศึกษา',
        'grad_junior_high' => 'ผู้จบการศึกษามัธยมศึกษาตอนต้น',
        'grad_senior_high' => 'ผู้จบการศึกษามัธยมศึกษาตอนปลาย',
    ],
    '8' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'student_code' => 'รหัสนักศึกษา',
        'primary_type' => 'ความซ้ำซ้อนประถมศึกษา',
        'junior_type' => 'ความซ้ำซ้อนมัธยมศึกษาตอนต้น',
        'senior_type' => 'ความซ้ำซ้อนมัธยมศึกษาตอนปลาย',
    ],
    '9' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'student_prefix' => 'คำนำหน้า',
        'student_firstname' => 'ชื่อ',
        'student_lastname' => 'นามสกุล',
        'student_code' => 'รหัสนักศึกษา',
        'student_school' => 'สถานศึกษา',
        'student_level' => 'ระดับการศึกษา',
        'teacher_prefix' => 'คำนำหน้าผู้สอน',
        'teacher_firstname' => 'ชื่อผู้สอน',
        'teacher_lastname' => 'นามสกุลผู้สอน',
    ],
    '10' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'student_code' => 'รหัสนักศึกษา',
        'school' => 'ศกร./ตำบล',
        'primary_code' => 'รหัสประถมศึกษา',
        'junior_code' => 'รหัสมัธยมศึกษาตอนต้น',
        'senior_code' => 'รหัสมัธยมศึกษาตอนปลาย',
    ],
    '11' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'school' => 'ศกร./ตำบล',
        'primary_status' => 'ผลการเรียนประถมศึกษา',
        'junior_status' => 'ผลการเรียนมัธยมศึกษาตอนต้น',
        'senior_status' => 'ผลการเรียนมัธยมศึกษาตอนปลาย',
    ],
    '12' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'school' => 'ศกร./ตำบล',
        'primary_status' => 'ผลการเรียนประถมศึกษา',
        'junior_status' => 'ผลการเรียนมัธยมศึกษาตอนต้น',
        'senior_status' => 'ผลการเรียนมัธยมศึกษาตอนปลาย',
    ],
    '13' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'student_prefix' => 'คำนำหน้า',
        'student_firstname' => 'ชื่อ',
        'student_lastname' => 'นามสกุล',
        'student_gender' => 'เพศ',
        'teacher_prefix' => 'คำนำหน้าผู้สอน',
        'teacher_firstname' => 'ชื่อผู้สอน',
        'teacher_lastname' => 'นามสกุลผู้สอน',
    ],
    '14' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'primary_total' => 'ป.ทั้งหมด',
        'primary_exam' => 'ป.เข้าสอบ',
        'primary_absent' => 'ป.ขาดสอบ',
        'junior_total' => 'ม.ต้นทั้งหมด',
        'junior_exam' => 'ม.ต้นเข้าสอบ',
        'junior_absent' => 'ม.ต้นขาดสอบ',
        'senior_total' => 'ม.ปลายทั้งหมด',
        'senior_exam' => 'ม.ปลายเข้าสอบ',
        'senior_absent' => 'ม.ปลายขาดสอบ',
    ],
    '15' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'primary_total' => 'ป.ทั้งหมด',
        'primary_exam' => 'ป.เข้าสอบ',
        'primary_absent' => 'ป.ขาดสอบ',
        'junior_total' => 'ม.ต้นทั้งหมด',
        'junior_exam' => 'ม.ต้นเข้าสอบ',
        'junior_absent' => 'ม.ต้นขาดสอบ',
        'senior_total' => 'ม.ปลายทั้งหมด',
        'senior_exam' => 'ม.ปลายเข้าสอบ',
        'senior_absent' => 'ม.ปลายขาดสอบ',
    ],
    '17' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'sex' => 'เพศ',
        'student_level' => 'ระดับการศึกษา',
    ],
];

if (!$id || !$module_id || !isset($report_map[$module_id])) {
    die("ข้อมูลไม่ครบถ้วน หรือไม่พบโมดูลที่ระบุ");
}

$table = "records_module{$module_id}";
$fields = $report_map[$module_id];

// 3. ดึงข้อมูลเดิมมาแสดง
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die("ไม่พบข้อมูลที่ต้องการแก้ไข");
}

// 4. บันทึกข้อมูลเมื่อมีการ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $set_parts = [];
    $params = [];

    foreach (array_keys($fields) as $col) {
        if ($col === 'district_name') continue; // ข้ามฟิลด์ที่ Join มาจากตารางอื่น

        $set_parts[] = "{$col} = ?";
        $params[] = $_POST[$col] ?? '';
    }

    $params[] = $id;
    $sql = "UPDATE $table SET " . implode(', ', $set_parts) . " WHERE id = ?";
    $update_stmt = $pdo->prepare($sql);

    if ($update_stmt->execute($params)) {
        echo "<script>alert('บันทึกสำเร็จ'); window.location='staff_reports.php?module_id=$module_id';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลโมดูล <?= $module_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-6 border-b pb-2">✏️ แก้ไขข้อมูล (แบบฟอร์มที่ <?= $module_id ?>)</h2>

        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($fields as $col => $label): ?>
                    <?php if ($col === 'district_name') continue; // อำเภอปกติจะแก้ไม่ได้จากหน้านี้ 
                    ?>

                    <div class="flex flex-col">
                        <label class="font-semibold text-gray-700 mb-1"><?= $label ?></label>
                        <input type="text" name="<?= $col ?>"
                            value="<?= htmlspecialchars($record[$col] ?? '') ?>"
                            class="border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="staff_reports.php?module_id=<?= $module_id ?>" class="bg-gray-500 text-white px-6 py-2 rounded">ยกเลิก</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</body>

</html>