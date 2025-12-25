<?php
// ไฟล์: admin/delete_user.php

session_start();
require_once '../config/config.php';

// 1. ตรวจสอบสิทธิ์และวิธีการ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$user_id_to_delete = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$current_user_id = $_SESSION['user_id'] ?? 0;

$message = '';
$status = 0;

if ($user_id_to_delete && $user_id_to_delete > 0) {
    if ($user_id_to_delete == 1 || $user_id_to_delete == $current_user_id) {
        $message = "ไม่สามารถลบผู้ใช้งาน ID: {$user_id_to_delete} ได้ (ป้องกันการลบ Admin หลักหรือบัญชีตัวเอง)";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
            $stmt->execute([$user_id_to_delete]);
            
            if ($stmt->rowCount() > 0) {
                $message = "ลบผู้ใช้งาน ID: {$user_id_to_delete} เรียบร้อยแล้ว 🗑️";
                $status = 1;
            } else {
                $message = "ไม่พบผู้ใช้งาน ID: {$user_id_to_delete} หรือไม่ได้รับอนุญาตให้ลบ Admin";
            }
        } catch (PDOException $e) {
            $message = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
        }
    }
} else {
    $message = "ไม่พบ ID ผู้ใช้งานที่ต้องการลบ";
}

// Redirect กลับไปหน้า users_list.php
header("Location: admin_layout.php?admin_content=users_list.php&success={$status}&msg=" . urlencode($message));
exit();