<?php
session_start();
require_once "../config/config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

$module_id   = intval($_POST['module_id'] ?? 0);
$district_id = $_SESSION['district_id'] ?? null;
$term        = $_SESSION['form_term'] ?? null;
$year        = $_SESSION['form_year'] ?? null;

if (!$module_id || !$district_id || !$term || !$year) {
    die("ข้อมูลระบบไม่ครบ ไม่สามารถ Import ได้");
}

if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    die("ไม่พบไฟล์ Excel");
}

$table = "records_module{$module_id}";

$filePath = $_FILES['excel_file']['tmp_name'];
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true);

/**
 * =============================
 * 🔑 แถวที่ 1 = Header
 * =============================
 */
$headerRow = array_shift($rows);
$headers = array_values($headerRow);

/**
 * =============================
 * ✅ Field ที่บังคับ (ปรับตาม module)
 * =============================
 */
$requiredFields = ['term', 'year']; // เพิ่ม field อื่นได้ เช่น 'firstname', 'school'

$pdo->beginTransaction();

try {

    foreach ($rows as $rowIndex => $row) {

        // แปลง A,B,C → array ธรรมดา
        $rowData = array_values($row);

        // ข้ามแถวว่าง
        if (count(array_filter($rowData)) === 0) {
            continue;
        }

        if (count($headers) !== count($rowData)) {
            throw new Exception("จำนวนคอลัมน์ไม่ตรงกัน แถวที่ " . ($rowIndex + 2));
        }

        $data = array_combine($headers, $rowData);

        if ($data === false) {
            throw new Exception("Header ไม่ตรงกับข้อมูล แถวที่ " . ($rowIndex + 2));
        }

        // เพิ่มค่าจากระบบ
        $data['district_id'] = $district_id;
        $data['term']        = $term;
        $data['year']        = $year;

        // ตรวจข้อมูลบังคับ
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                throw new Exception("ข้อมูลไม่ครบ ({$field}) แถวที่ " . ($rowIndex + 2));
            }
        }

        // เตรียม SQL
        $columns = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));

        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ")
                VALUES ({$placeholders})";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
    }

    $pdo->commit();

    header("Location: staff_dashboard.php?import=success");
    exit;

} catch (Exception $e) {

    $pdo->rollBack();
    die("❌ Import ผิดพลาด: " . $e->getMessage());
}
