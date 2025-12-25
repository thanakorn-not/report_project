<?php
require_once "../config/config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* ===============================
   รับค่า filter
================================ */
$module_id   = $_GET['module_id'] ?? '';
$year        = $_GET['year'] ?? '';
$term        = $_GET['term'] ?? '';
$district_id = $_GET['district_id'] ?? '';

if (!$module_id || !is_numeric($module_id)) {
    die('Invalid module');
}

/* ===============================
   Mapping คอลัมน์ (ต้องตรงกับ report)
================================ */
$display_columns_map = [

    '1' => [
        'district_name' => 'อำเภอ',
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'cctv_status' => 'กล้องวงจรปิด',
        'cctv_amount' => 'จำนวน',
        'red_box_status' => 'ตู้แดง',
        'reporter_name' => 'ผู้รายงาน',
        'phone' => 'โทรศัพท์',
    ],
    '2' => [
        'district_name' => 'อำเภอ',
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
        'district_name' => 'อำเภอ',
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

    '5' => [
        'district_name' => 'อำเภอ',
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'สถานศึกษา',
        'created_by_name' => 'ผู้บันทึก',
        'created_at' => 'วันที่บันทึก',
    ],

    '16' => [
        'district_name' => 'อำเภอ',
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'primary_total' => 'ประถมทั้งหมด',
        'primary_pass' => 'ประถมผ่าน',
        'junior_total' => 'ม.ต้นทั้งหมด',
        'junior_pass' => 'ม.ต้นผ่าน',
        'senior_total' => 'ม.ปลายทั้งหมด',
        'senior_pass' => 'ม.ปลายผ่าน',
    ],
];

if (!isset($display_columns_map[$module_id])) {
    die('Module not supported');
}

$table = "records_module{$module_id}";
$columns = $display_columns_map[$module_id];

/* ===============================
   SQL
================================ */
$select = [];
foreach (array_keys($columns) as $c) {
    if ($c === 'district_name') {
        $select[] = "d.district_name";
    } else {
        $select[] = "r.$c";
    }
}

$sql = "
    SELECT " . implode(',', $select) . "
    FROM {$table} r
    LEFT JOIN districts d ON r.district_id = d.id
    WHERE 1
";

$params = [];

if ($year !== '') {
    $sql .= " AND r.year = :year";
    $params[':year'] = $year;
}
if ($term !== '') {
    $sql .= " AND r.term = :term";
    $params[':term'] = $term;
}
if ($district_id !== '') {
    $sql .= " AND r.district_id = :district_id";
    $params[':district_id'] = (int)$district_id;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   สร้าง Excel
================================ */
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

/* Header */
$col = 'A';
foreach ($columns as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

/* Data */
$row = 2;
foreach ($data as $record) {
    $col = 'A';
    foreach (array_keys($columns) as $key) {
        $sheet->setCellValue($col . $row, $record[$key] ?? '');
        $col++;
    }
    $row++;
}

$filename = "module_{$module_id}_report.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename={$filename}");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
