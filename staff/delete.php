<?php
session_start();
require_once "../config/config.php";

// 1. ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบ']));
}

// 2. รับค่าที่ส่งมาจากหน้า Report (แนะนำให้ส่งแบบ GET หรือ POST ก็ได้)
$module_id = $_GET['module_id'] ?? null;
$record_id = $_GET['id'] ?? null;

if (!$module_id || !$record_id) {
    die(json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']));
}

try {
    // 3. กำหนดชื่อตารางตาม Module ID
    $tableName = "records_module" . intval($module_id);

    // 4. ตรวจสอบความปลอดภัย (Staff ลบได้เฉพาะข้อมูลในศูนย์ของตัวเองเท่านั้น)
    if ($_SESSION['role'] === 'staff') {
        $staff_district_id = $_SESSION['district_id'];
        
        // เช็คก่อนว่า record นี้เป็นของศูนย์นี้จริงไหม
        $check_sql = "SELECT id FROM {$tableName} WHERE id = :record_id AND district_id = :dist_id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([
            ':record_id' => $record_id,
            ':dist_id' => $staff_district_id
        ]);
        
        if (!$check_stmt->fetch()) {
            die(json_encode(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ลบข้อมูลนี้']));
        }
    }

    // 5. เริ่มการลบข้อมูล
    $pdo->beginTransaction();

    // กรณีพิเศษ: ถ้าเป็น Module 5 ต้องลบข้อมูลในตาราง activities ด้วย
    if ($module_id == 5) {
        $del_act = $pdo->prepare("DELETE FROM module5_activities WHERE report_id = ?");
        $del_act->execute([$record_id]);
    }

    // ลบข้อมูลหลัก
    $sql = "DELETE FROM {$tableName} WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $record_id]);

    $pdo->commit();

    // 6. ส่งกลับไปยังหน้าเดิมพร้อมสถานะ success
    header("Location: staff_reports.php?module_id={$module_id}&delete_success=1");
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage());
}