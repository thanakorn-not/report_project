<?php
require_once "../config/config.php";
require_once "../vendor/autoload.php";

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

/* =====================================================
   1. รับค่า filter
===================================================== */
$module_id   = $_GET['module_id'] ?? '';
$year        = $_GET['year'] ?? '';
$term        = $_GET['term'] ?? '';
$district_id = $_GET['district_id'] ?? '';

if (!$module_id || !is_numeric($module_id)) {
    die('Invalid module');
}

/* =====================================================
   2. Mapping คอลัมน์รายงาน
===================================================== */
$columns_map = [

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

if (!isset($columns_map[$module_id])) {
    die('Module not supported');
}

$table   = "records_module{$module_id}";
$columns = $columns_map[$module_id];

/* =====================================================
   3. SQL
===================================================== */
$select = [];
foreach (array_keys($columns) as $col) {
    $select[] = ($col === 'district_name')
        ? "d.district_name"
        : "r.$col";
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

/* =====================================================
   4. HTML สำหรับ PDF
===================================================== */
$html = '
<h2 style="text-align:center;">รายงานข้อมูล</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
<thead>
<tr>';

foreach ($columns as $header) {
    $html .= "<th>{$header}</th>";
}

$html .= '</tr>
</thead>
<tbody>';

foreach ($data as $row) {
    $html .= '<tr>';
    foreach (array_keys($columns) as $key) {
        $value = $row[$key] ?? '-';
        $html .= "<td>{$value}</td>";
    }
    $html .= '</tr>';
}

$html .= '</tbody></table>';

/* =====================================================
   5. ตั้งค่า mPDF + ฟอนต์ไทย
===================================================== */
$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../vendor/mpdf/mpdf/ttfonts'
    ]),
    'fontdata' => $fontData + [
        'thsarabunnew' => [
            'R'  => 'THSarabunNew.ttf',
            'B'  => 'THSarabunNew Bold.ttf',
            'I'  => 'THSarabunNew Italic.ttf',
            'BI' => 'THSarabunNew BoldItalic.ttf',
        ]
    ],
    'default_font' => 'thsarabunnew'
]);

/* ================= CSS (สำคัญมาก) ================= */
$mpdf->WriteHTML('
<style>
body {
    font-family: "thsarabunnew";
    font-size: 16pt;
}
table {
    border-collapse: collapse;
}
th {
    background: #f0f0f0;
    font-weight: bold;
}
td, th {
    text-align: center;
}
</style>
');

$mpdf->WriteHTML($html);

/* =====================================================
   6. ส่งออก PDF
===================================================== */
$mpdf->Output("module_{$module_id}_report.pdf", 'D');
exit;
