<?php
// ไฟล์: admin/admin_content/dashboard_content.php
// ✅ ต้องมี $pdo มาก่อน (ดึงจาก admin_layout.php)

try {
    // 1. นับจำนวนผู้ใช้งานทั้งหมด
    $users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // 2. ดึงรายการ ID โมดูลที่เปิดใช้งาน
    $modules_stmt = $pdo->query("SELECT id FROM modules WHERE is_active = 1 ORDER BY id ASC");
    $active_module_ids = $modules_stmt->fetchAll(PDO::FETCH_COLUMN);

    // นับจำนวนโมดูลที่เปิดใช้งาน
    $modules = count($active_module_ids);

    // 3. นับจำนวนข้อมูลนักเรียนทั้งหมดจากตารางโมดูลที่เปิดใช้งาน
    $total_students_records = 0;

    foreach ($active_module_ids as $module_id) {
        $table_name = "records_module" . intval($module_id);
    
        try {
            $count = $pdo->query("SELECT COUNT(*) FROM `{$table_name}`")->fetchColumn();
            $total_students_records += $count;
        } catch (PDOException $e) {
            // ข้ามตารางที่ยังไม่มีอยู่
        }
    }
    
    $students = $total_students_records;

} catch (PDOException $e) {
    // จัดการข้อผิดพลาดฐานข้อมูลหลัก (เช่น ตาราง modules ไม่มี is_active)
    $error_msg = "เกิดข้อผิดพลาดในการดึงข้อมูลสถิติ: " . $e->getMessage();
    echo "<div class='bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-6'>❌ {$error_msg}</div>";
    // กำหนดค่าเริ่มต้นเป็น 0 เพื่อป้องกัน Fatal Error ใน HTML
    $users = 0;
    $modules = 0;
    $students = 0;
}
?>

<div class="p-6">

    <h2 class="text-2xl font-bold text-blue-800 mb-6">📊 แดชบอร์ดผู้ดูแลระบบ</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">

        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-800">
            <p class="text-gray-500">จำนวนผู้ใช้งาน</p>
            <h3 class="text-3xl font-bold text-blue-900 mt-2"><?= number_format($users) ?></h3>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-green-600">
            <p class="text-gray-500">ข้อมูลนักเรียนทั้งหมด</p>
            <h3 class="text-3xl font-bold text-green-800 mt-2"><?= number_format($students) ?></h3>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-500">
            <p class="text-gray-500">โมดูลข้อมูลที่เปิดใช้งาน</p>
            <h3 class="text-3xl font-bold text-yellow-600 mt-2"><?= number_format($modules) ?></h3>
        </div>

    </div>

    <h2 class="text-xl font-semibold mt-8 mb-4 border-b pb-2 text-gray-700">📌 เมนูทางลัด</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <a href="admin_layout.php?admin_content=users_list.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 block border-t-4 border-blue-500">
            <div class="flex items-center space-x-3">
                <span class="text-3xl text-blue-600">👥</span>
                <div>
                    <h3 class="font-bold text-lg text-blue-800">จัดการผู้ใช้งาน</h3>
                    <p class="text-sm text-gray-500">เพิ่ม/แก้ไข/ลบบัญชี Staff และ Admin</p>
                </div>
            </div>
        </a>

        <a href="admin_layout.php?admin_content=manage_modules.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 block border-t-4 border-green-500">
            <div class="flex items-center space-x-3">
                <span class="text-3xl text-green-600">📁</span>
                <div>
                    <h3 class="font-bold text-lg text-green-800">จัดการโมดูล</h3>
                    <p class="text-sm text-gray-500">แก้ไขรายชื่อและสถานะของ 17 โมดูล</p>
                </div>
            </div>
        </a>

        <a href="admin_layout.php?admin_content=records_list.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 block border-t-4 border-red-500">
            <div class="flex items-center space-x-3">
                <span class="text-3xl text-red-600">🧾</span>
                <div>
                    <h3 class="font-bold text-lg text-red-800">จัดการข้อมูลนักเรียน</h3>
                    <p class="text-sm text-gray-500">ตรวจสอบ/แก้ไข/ลบข้อมูลที่บันทึก</p>
                </div>
            </div>
        </a>

        <a href="admin_layout.php?admin_content=reports.php" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 transform hover:-translate-y-1 block border-t-4 border-yellow-500">
            <div class="flex items-center space-x-3">
                <span class="text-3xl text-yellow-600">📊</span>
                <div>
                    <h3 class="font-bold text-lg text-yellow-800">สร้างรายงาน</h3>
                    <p class="text-sm text-gray-500">ส่งออกข้อมูลเป็น PDF / Excel</p>
                </div>
            </div>
        </a>

    </div>
</div>