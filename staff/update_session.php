<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['district_id'])) {
    $districtId = filter_var($_POST['district_id'], FILTER_VALIDATE_INT);

    if ($districtId !== false && $districtId > 0) {
        // อัปเดต Session
        $_SESSION['active_district_id'] = $districtId;
        
        // (Optional: ดึงชื่ออำเภอมาเก็บใน Session ด้วย)
        // require_once 'config/config.php';
        // $stmt = $pdo->prepare("SELECT district_name FROM districts WHERE id = ?");
        // $stmt->execute([$districtId]);
        // $district = $stmt->fetchColumn();
        // $_SESSION['active_district_name'] = $district;
        
        echo json_encode(['status' => 'success', 'message' => 'Session updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>