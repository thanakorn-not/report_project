<?php
// ไฟล์: admin/save_user.php

// 1. เริ่มต้น Session และดึง Config
session_start();
require_once '../config/config.php'; // Path ต้องถูกต้อง: ขึ้นไป 1 ขั้นจาก /admin/ ไป /

// 2. ตรวจสอบสิทธิ์ (ต้องเป็น Admin เท่านั้น)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // หรือหน้า Login
    exit();
}

// 3. รับและตรวจสอบข้อมูลที่ส่งมา
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_layout.php?admin_content=users_list.php');
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$name = trim($_POST['name'] ?? '');
$role = $_POST['role'] ?? 'staff';
$district_id = filter_input(INPUT_POST, 'district_id', FILTER_VALIDATE_INT) ?: null;

$message = '';
$status = 0; // 0=Error, 1=Success

if (empty($username) || empty($name) || empty($role)) {
    $message = "ข้อมูลไม่ครบถ้วน กรุณากรอกชื่อผู้ใช้งาน, ชื่อ-สกุล และสิทธิ์";
} else {
    try {
        $pdo->beginTransaction();

        // 3.1 ตรวจสอบ Username ซ้ำ
        $check_username_sql = "SELECT id FROM users WHERE username = ? AND id != ?";
        $check_stmt = $pdo->prepare($check_username_sql);
        $check_stmt->execute([$username, $id ?? 0]);

        if ($check_stmt->fetchColumn()) {
            $message = "ชื่อผู้ใช้งานนี้ ({$username}) มีผู้ใช้แล้ว กรุณาเปลี่ยนชื่ออื่น";
            $status = 0;
            $pdo->rollBack();
        } else {
            // 3.2 ประมวลผลตามโหมด (Add/Edit)
            if (empty($id)) {
                // ADD MODE: ต้องมีรหัสผ่าน
                if (empty($password)) {
                    $message = "ต้องระบุรหัสผ่านสำหรับการเพิ่มผู้ใช้งานใหม่";
                    $pdo->rollBack();
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "INSERT INTO users (username, password, role, name, district_id) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $hashed_password, $role, $name, $district_id]);
                    $message = "เพิ่มผู้ใช้งาน {$username} เรียบร้อยแล้ว";
                    $status = 1;
                    $pdo->commit();
                }
            } else {
                // EDIT MODE: รหัสผ่านเป็นทางเลือก
                if (!empty($password)) {
                    // อัปเดตทั้งรหัสผ่านและข้อมูล
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET username = ?, password = ?, role = ?, name = ?, district_id = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $hashed_password, $role, $name, $district_id, $id]);
                    $message = "แก้ไขผู้ใช้งาน ID: {$id} และเปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
                } else {
                    // อัปเดตเฉพาะข้อมูล
                    $sql = "UPDATE users SET username = ?, role = ?, name = ?, district_id = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $role, $name, $district_id, $id]);
                    $message = "แก้ไขผู้ใช้งาน ID: {$id} เรียบร้อยแล้ว";
                }
                $status = 1;
                $pdo->commit();
            }
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $message = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage();
        $status = 0;
    }
}

// 4. Redirect กลับไปหน้า users_list.php พร้อมข้อความแจ้งเตือน
header("Location: admin_layout.php?admin_content=users_list.php&success={$status}&msg=" . urlencode($message));
exit();