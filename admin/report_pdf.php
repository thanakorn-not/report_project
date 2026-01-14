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
$field       = $_GET['field'] ?? '';
$keyword     = trim($_GET['keyword'] ?? '');

if (!$module_id || !is_numeric($module_id)) {
    die('Invalid module');
}

/* =====================================================
   2. Mapping คอลัมน์
===================================================== */
$columns_map = [
    '1' => [
        'district_name' => 'อำเภอ',
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
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

$table = "records_module{$module_id}";

$col_stmt = $pdo->query("SHOW COLUMNS FROM $table");
$all_cols = $col_stmt->fetchAll(PDO::FETCH_COLUMN);

// ตัด column ระบบ
$exclude = ['id','created_at','updated_at','user_id','created_by','created_by_name'];
$columns = array_values(array_diff($all_cols, $exclude));

/* =====================================================
   3. SQL
===================================================== */
$select = [];
foreach ($columns as $c) {
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

// filter ปกติ
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

// 🔍 filter ค้นหา
if ($field && $keyword && in_array($field, $columns)) {

    $exactWords = ['มี', 'ไม่มี', 'เปิด', 'ปิด', 'ชาย', 'หญิง'];

    if (in_array($keyword, $exactWords)) {
        $sql .= " AND r.$field = :kw";
        $params[':kw'] = $keyword;
    } else {
        $sql .= " AND r.$field LIKE :kw";
        $params[':kw'] = "%$keyword%";
    }
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================================================
   4. HTML
===================================================== */
$html = '
<h2 style="text-align:center;">รายงานข้อมูล</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<thead><tr>';

foreach ($columns as $header) {
    $html .= "<th>{$header}</th>";
}

$html .= '</tr></thead><tbody>';

foreach ($data as $row) {
    $html .= '<tr>';
    foreach ($columns as $key) {
        $html .= '<td>' . ($row[$key] ?? '-') . '</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody></table>';

/* =====================================================
   5. mPDF + ฟอนต์
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
            'R' => 'THSarabunNew.ttf',
            'B' => 'THSarabunNew Bold.ttf',
        ]
    ],
    'default_font' => 'thsarabunnew'
]);

$mpdf->WriteHTML('
<style>
body { font-size:16pt; }
table { border-collapse: collapse; }
th { background:#f0f0f0; font-weight:bold; }
td, th { text-align:center; }
</style>
');

$mpdf->WriteHTML($html);
$mpdf->Output("module_{$module_id}_report.pdf", 'D');
exit;
