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
    '4' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'total_student' => 'จำนวนนักเรียน',
        'pri_total' => 'ป.ทั้งหมด',
        'pri_very_good' => 'ป.ดีมาก',
        'pri_good' => 'ป.ดี',
        'pri_fair' => 'ป.พอใช้',
        'pri_improve' => 'ป.ปรับปรุง',
        'sec_low_total' => 'ม.ต้นทั้งหมด',
        'sec_low_very_good' => 'ม.ต้นดีมาก',
        'sec_low_good' => 'ม.ต้นดี',
        'sec_low_fair' => 'ม.ต้นพอใช้',
        'sec_low_improve' => 'ม.ต้นปรับปรุง',
        'sec_up_total' => 'ม.ปลายทั้งหมด',
        'sec_up_very_good' => 'ม.ปลายดีมาก',
        'sec_up_good' => 'ม.ปลายดี',
        'sec_up_fair' => 'ม.ปลายพอใช้',
        'sec_up_improve' => 'ม.ปลายปรับปรุง',
    ],


    '5' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'activities' => 'กิจกรรม',
    ],
    '6' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'reg_primary' => 'ผู้ลงทะเบียนประถมศึกษา',
        'reg_junior_high' => 'ผู้ลงทะเบียนมัธยมศึกษาตอนต้น',
        'reg_senior_high' => 'ผู้ลงทะเบียนมัธยมศึกษาตอนปลาย',
        'grad_primary' => 'ผู้จบการศึกษาประถมศึกษา',
        'grad_junior_high' => 'ผู้จบการศึกษามัธยมศึกษาตอนต้น',
        'grad_senior_high' => 'ผู้จบการศึกษามัธยมศึกษาตอนปลาย',
    ],
    '8' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'student_code' => 'รหัสนักศึกษา',
        'primary_type' => 'ความซ้ำซ้อนประถมศึกษา',
        'junior_type' => 'ความซ้ำซ้อนมัธยมศึกษาตอนต้น',
        'senior_type' => 'ความซ้ำซ้อนมัธยมศึกษาตอนปลาย',
    ],
    '9' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'student_prefix' => 'คำนำหน้า',
        'student_firstname' => 'ชื่อ',
        'student_lastname' => 'นามสกุล',
        'student_code' => 'รหัสนักศึกษา',
        'student_school' => 'สถานศึกษา',
        'student_level' => 'ระดับการศึกษา',
        'teacher_prefix' => 'คำนำหน้าผู้สอน',
        'teacher_firstname' => 'ชื่อผู้สอน',
        'teacher_lastname' => 'นามสกุลผู้สอน',
    ],
    '10' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'student_code' => 'รหัสนักศึกษา',
        'school' => 'ศกร./ตำบล',
        'primary_code' => 'รหัสประถมศึกษา',
        'junior_code' => 'รหัสมัธยมศึกษาตอนต้น',
        'senior_code' => 'รหัสมัธยมศึกษาตอนปลาย',
    ],
    '11' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'school' => 'ศกร./ตำบล',
        'primary_status' => 'ผลการเรียนประถมศึกษา',
        'junior_status' => 'ผลการเรียนมัธยมศึกษาตอนต้น',
        'senior_status' => 'ผลการเรียนมัธยมศึกษาตอนปลาย',
    ],
    '12' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'school' => 'ศกร./ตำบล',
        'primary_status' => 'ผลการเรียนประถมศึกษา',
        'junior_status' => 'ผลการเรียนมัธยมศึกษาตอนต้น',
        'senior_status' => 'ผลการเรียนมัธยมศึกษาตอนปลาย',
    ],
    '13' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'student_prefix' => 'คำนำหน้า',
        'student_firstname' => 'ชื่อ',
        'student_lastname' => 'นามสกุล',
        'student_gender' => 'เพศ',
        'teacher_prefix' => 'คำนำหน้าผู้สอน',
        'teacher_firstname' => 'ชื่อผู้สอน',
        'teacher_lastname' => 'นามสกุลผู้สอน',
    ],
    '14' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'primary_total' => 'ป.ทั้งหมด',
        'primary_exam' => 'ป.เข้าสอบ',
        'primary_absent' => 'ป.ขาดสอบ',
        'junior_total' => 'ม.ต้นทั้งหมด',
        'junior_exam' => 'ม.ต้นเข้าสอบ',
        'junior_absent' => 'ม.ต้นขาดสอบ',
        'senior_total' => 'ม.ปลายทั้งหมด',
        'senior_exam' => 'ม.ปลายเข้าสอบ',
        'senior_absent' => 'ม.ปลายขาดสอบ',
    ],
    '15' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'primary_total' => 'ป.ทั้งหมด',
        'primary_exam' => 'ป.เข้าสอบ',
        'primary_absent' => 'ป.ขาดสอบ',
        'junior_total' => 'ม.ต้นทั้งหมด',
        'junior_exam' => 'ม.ต้นเข้าสอบ',
        'junior_absent' => 'ม.ต้นขาดสอบ',
        'senior_total' => 'ม.ปลายทั้งหมด',
        'senior_exam' => 'ม.ปลายเข้าสอบ',
        'senior_absent' => 'ม.ปลายขาดสอบ',
    ],
    '17' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'prefix' => 'คำนำหน้า',
        'firstname' => 'ชื่อ',
        'lastname' => 'นามสกุล',
        'sex' => 'เพศ',
        'student_level' => 'ระดับการศึกษา',
    ],
];
/* ---------- module แบบ group ---------- */
$grouped_modules = ['7', '16'];
$is_grouped = in_array($module_id, $grouped_modules);
$module_group_map = [

    '7' => [
        'base' => [
            'term' => 'ภาคเรียน',
            'year' => 'ปีการศึกษา',
        ],
        'groups' => [
            'equiv' => [
                'title' => 'จำนวนผู้เทียบระดับการศึกษา',
                'fields' => [
                    'primary_equiv' => 'ประถม',
                    'junior_equiv'  => 'ม.ต้น',
                    'senior_equiv'  => 'ม.ปลาย',
                ],
            ],
            'exp' => [
                'title' => 'จำนวนผู้ผ่านการประเมินมิติประสบการณ์',
                'fields' => [
                    'primary_exp' => 'ประถม',
                    'junior_exp'  => 'ม.ต้น',
                    'senior_exp'  => 'ม.ปลาย',
                ],
            ],
            'think' => [
                'title' => 'จำนวนผู้ผ่านการประเมิน มิติความรู้ความคิด',
                'fields' => [
                    'primary_think' => 'ประถม',
                    'junior_think'  => 'ม.ต้น',
                    'senior_think'  => 'ม.ปลาย',
                ],
            ],
            'seminar' => [
                'title' => 'จำนวนผู้ผ่านการประเมิน มิติการนำเสนอผลงาน',
                'fields' => [
                    'primary_seminar' => 'ประถม',
                    'junior_seminar'  => 'ม.ต้น',
                    'senior_seminar'  => 'ม.ปลาย',
                ],
            ],
            'graduate' => [
                'title' => 'จำนวนผู้จบเทียบระดับการศึกษา',
                'fields' => [
                    'primary_grad_equiv' => 'ประถม',
                    'junior_grad_equiv'  => 'ม.ต้น',
                    'senior_grad_equiv'  => 'ม.ปลาย',
                ],
            ],
        ],
    ],

    '16' => [
        'base' => [
            'term' => 'ภาคเรียน',
            'year' => 'ปีการศึกษา',
        ],
        'groups' => [
            'primary' => [
                'title' => 'ประถมศึกษา',
                'fields' => [
                    'primary_total' => 'ทั้งหมด',
                    'primary_pass'  => 'ผ่าน',
                    'primary_fail'  => 'ไม่ผ่าน',
                ],
            ],
            'junior' => [
                'title' => 'มัธยมศึกษาตอนต้น',
                'fields' => [
                    'junior_total' => 'ทั้งหมด',
                    'junior_pass'  => 'ผ่าน',
                    'junior_fail'  => 'ไม่ผ่าน',
                ],
            ],
        ],
    ],
];

/* =====================================================
   3. SQL
===================================================== */
$table   = "records_module{$module_id}";
if ($is_grouped) {

    /* ---------- รายงานสรุป ---------- */
    $select = [];

    foreach ($module_group_map[$module_id]['groups'] as $group) {
        foreach ($group['fields'] as $col => $label) {
            $select[] = "SUM(r.$col) AS $col";
        }
    }

    $sql = "
        SELECT 
            MAX(r.term) AS term,
            MAX(r.year) AS year,
            " . implode(',', $select) . "
        FROM {$table} r
        WHERE 1
    ";

} else {

    /* ---------- module ปกติ ---------- */
    require_once "../config/display_columns.php";
    $select = [];

    foreach (array_keys($columns_map[$module_id]) as $col) {
        $select[] = "r.$col";
    }

    $sql = "
        SELECT " . implode(',', $select) . "
        FROM {$table} r
        WHERE 1
    ";
}

/* filter */
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

$data = $is_grouped
    ? $stmt->fetch(PDO::FETCH_ASSOC)
    : $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================================================
   4. HTML สำหรับ PDF
===================================================== */
$html = '<h2 style="text-align:center;">รายงานข้อมูล</h2>';

if ($is_grouped && $data) {

    /* base */
    $html .= '<table width="100%" border="1" cellpadding="8">';
    foreach ($module_group_map[$module_id]['base'] as $col => $label) {
        $html .= "<tr><th width='30%'>{$label}</th><td>{$data[$col]}</td></tr>";
    }
    $html .= '</table><br>';

    /* groups */
    foreach ($module_group_map[$module_id]['groups'] as $group) {

        $html .= "<h3>{$group['title']}</h3>";
        $html .= '<table width="100%" border="1" cellpadding="8"><tr>';

        foreach ($group['fields'] as $label) {
            $html .= "<th>{$label}</th>";
        }

        $html .= '</tr><tr>';

        foreach (array_keys($group['fields']) as $field) {
            $html .= "<td>" . ($data[$field] ?? 0) . "</td>";
        }

        $html .= '</tr></table><br>';
    }

} else {

    /* module ปกติ */
    $columns = $columns_map[$module_id];

    $html .= '<table width="100%" border="1" cellpadding="6"><tr>';
    foreach ($columns as $header) {
        $html .= "<th>{$header}</th>";
    }
    $html .= '</tr>';

    foreach ($data as $row) {
        $html .= '<tr>';
        foreach (array_keys($columns) as $col) {
            $html .= "<td>{$row[$col]}</td>";
        }
        $html .= '</tr>';
    }

    $html .= '</table>';
}


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
