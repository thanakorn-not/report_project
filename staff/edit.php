<?php
session_start();
require_once "../config/config.php"; // ตรวจสอบ Path ให้ถูกต้อง

// 1. รับค่าจาก URL
$id = $_GET['id'] ?? null;
$module_id = $_GET['module_id'] ?? null;

// 2. นิยาม $report_map (ต้องเหมือนกับหน้า staff_reports.php)
$report_map = [
    '1' => ['district_name' => 'อำเภอ', 'term' => 'ภาคเรียน', 'year' => 'ปีการศึกษา', 'cctv_status' => 'กล้องวงจรปิด', 'cctv_amount' => 'จำนวน', 'red_box_status' => 'ตู้แดง', 'reporter_name' => 'ผู้รายงาน', 'phone' => 'โทรศัพท์'],
    '2' => ['district_name' => 'อำเภอ', 'term' => 'ภาคเรียน', 'year' => 'ปีการศึกษา', 'prefix' => 'คำนำหน้า', 'firstname' => 'ชื่อ', 'lastname' => 'นามสกุล', 'education_level' => 'ระดับการศึกษา', 'school' => 'ศกร./ตำบล', 'employment_status' => 'สถานะการทำงาน', 'job' => 'งานที่ทำ', 'workplace' => 'สถานที่ทำงาน', 'other' => 'อื่นๆ'],
    '3' => ['district_name' => 'อำเภอ', 'term' => 'ภาคเรียน', 'year' => 'ปีการศึกษา', 'prefix' => 'คำนำหน้า', 'firstname' => 'ชื่อ', 'lastname' => 'นามสกุล', 'position' => 'ตำแหน่ง', 'scout_qualification' => 'วุฒิทางลูกเสือ', 'training_date' => 'วันเดือนปีที่ได้รับการอบรม', 'ability' => 'ความสามารถพิเศษ'],
    '4' => ['district_name' => 'อำเภอ', 'term' => 'ภาคเรียน', 'year' => 'ปีการศึกษา', 'school' => 'ศกร./ตำบล', 'total_student' => 'จำนวนนักเรียน', 'pri_total' => 'ป.ทั้งหมด', 'pri_very_good' => 'ป.ดีมาก'],
    '5' => ['district_name' => 'อำเภอ', 'term' => 'ภาคเรียน', 'year' => 'ปีการศึกษา', 'school' => 'สถานศึกษา'],
    '16' => ['district_name' => 'อำเภอ', 'term' => 'ภาคเรียน', 'year' => 'ปีการศึกษา', 'primary_total' => 'ป.ทั้งหมด', 'primary_pass' => 'ป.ผ่าน', 'junior_total' => 'ม.ต้นทั้งหมด', 'junior_pass' => 'ม.ต้นผ่าน', 'senior_total' => 'ม.ปลายทั้งหมด', 'senior_pass' => 'ม.ปลายผ่าน'],
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
    <title>แก้ไขข้อมูลโมดูล <?= $module_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-6 border-b pb-2">✏️ แก้ไขข้อมูล (แบบฟอร์มที่ <?= $module_id ?>)</h2>
        
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($fields as $col => $label): ?>
                    <?php if ($col === 'district_name') continue; // อำเภอปกติจะแก้ไม่ได้จากหน้านี้ ?>
                    
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