<?php
// ไฟล์: delete_record.php
session_start();
require_once '../config/config.php'; // เชื่อมต่อฐานข้อมูล

// 1. ตรวจสอบสิทธิ์ (ถ้ามีระบบสิทธิ์)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header('Location: ../login.php');
    exit;
}

// 2. รับ Module ID และ Record ID
$module_id = isset($_GET['module']) ? intval($_GET['module']) : null;
$record_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$module_id || !$record_id) {
    $_SESSION['error'] = "❌ ข้อมูลไม่ครบถ้วนสำหรับการลบ";
    header('Location: admin_layout.php?admin_content=records_list.php');
    exit;
}

$table_name = "records_module" . $module_id;

// 3. (Optional) ตรวจสอบสถานะโมดูล (เป็นมาตรการความปลอดภัยซ้ำซ้อน)
try {
    $stmt_active = $pdo->prepare("SELECT is_active FROM modules WHERE id = ?");
    $stmt_active->execute([$module_id]);
    $is_active = $stmt_active->fetchColumn();

    if ($is_active === false || $is_active == 0) {
        $_SESSION['error'] = "❌ โมดูลนี้ถูกปิดใช้งาน ไม่สามารถลบข้อมูลได้";
        header("Location: admin_layout.php?admin_content=records_list.php&module={$module_id}");
        exit;
    }
} catch (PDOException $e) {
    // จัดการข้อผิดพลาดฐานข้อมูล
    $_SESSION['error'] = "❌ เกิดข้อผิดพลาดในการตรวจสอบโมดูล";
    header("Location: admin_layout.php?admin_content=records_list.php&module={$module_id}");
    exit;
}


// 4. ดำเนินการลบข้อมูล
try {
    $stmt = $pdo->prepare("DELETE FROM {$table_name} WHERE id = :id");
    $stmt->bindParam(':id', $record_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success'] = "🗑️ ลบข้อมูล ID: {$record_id} ในโมดูล {$module_id} เรียบร้อยแล้ว";

} catch (PDOException $e) {
    // จัดการข้อผิดพลาดฐานข้อมูล (เช่น ตารางไม่มีอยู่)
    $_SESSION['error'] = "❌ เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
}

// 5. Redirect กลับไปยังหน้าแสดงรายการ
header("Location: admin_layout.php?admin_content=records_list.php&module={$module_id}");
exit;
?>