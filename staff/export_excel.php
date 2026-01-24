<?php
require_once "../config/config.php";
require_once "../vendor/autoload.php";
function excelCol(int $index): string
{
    $col = '';
    while ($index >= 0) {
        $col = chr($index % 26 + 65) . $col;
        $index = intdiv($index, 26) - 1;
    }
    return $col;
}
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
/* ===============================
   REPORT MAP (MODULE 7,16)
================================ */
$report_map = [

    '7' => [
        'base' => [
            'term' => 'ภาคเรียน',
            'year' => 'ปีการศึกษา',
        ],
        'groups' => [
            'equiv' => [
                'title' => 'จำนวนผู้เทียบระดับการศึกษา',
                'fields' => [
                    'ประถม' => 'primary_equiv',
                    'ม.ต้น' => 'junior_equiv',
                    'ม.ปลาย' => 'senior_equiv',
                ],
            ],
            'experience' => [
                'title' => 'จำนวนผู้ผ่านการประเมินมิติประสบการณ์',
                'fields' => [
                    'ประถม' => 'primary_exp',
                    'ม.ต้น' => 'junior_exp',
                    'ม.ปลาย' => 'senior_exp',
                ],
            ],
            'think' => [
                'title' => 'จำนวนผู้ผ่านการประเมิน มิติความรู้ความคิด',
                'fields' => [
                    'ประถม' => 'primary_think',
                    'ม.ต้น' => 'junior_think',
                    'ม.ปลาย' => 'senior_think',
                ],
            ],
            'seminar' => [
                'title' => 'จำนวนผู้ผ่านการประเมิน การเข้าร่วมสัมมนาวิชาการ',
                'fields' => [
                    'ประถม' => 'primary_seminar',
                    'ม.ต้น' => 'junior_seminar',
                    'ม.ปลาย' => 'senior_seminar',
                ],
            ],
            'graduate' => [
                'title' => 'จำนวนผู้จบเทียบระดับการศึกษา',
                'fields' => [
                    'ประถม' => 'primary_grad_equiv',
                    'ม.ต้น' => 'junior_grad_equiv',
                    'ม.ปลาย' => 'senior_grad_equiv',
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
                'ทั้งหมด' => 'primary_total',
                'ผ่าน' => 'primary_pass',
                'ศึกษาต่อสายสามัญ' => 'primary_path_academic',
                'ศึกษาต่อสายอาชีพ' => 'primary_path_vocational',
                'ไม่ศึกษาต่อ' => 'primary_path_none',
                'เกษตรกรรม' => 'primary_job_agriculture',
                'พนักงานบริษัท/โรงงาน' => 'primary_job_company',
                'ค้าขาย' => 'primary_job_sales',
                'หัตถกรรม' => 'primary_job_handicraft',
                'รับจ้างทั่วไป' => 'primary_job_general',
                'อื่น ๆ' => 'primary_job_other',
                'ไม่ประกอบอาชีพ' => 'primary_job_none',
            ],
        ],

        'junior' => [
            'title' => 'มัธยมศึกษาตอนต้น',
            'fields' => [
                'ทั้งหมด' => 'junior_total',
                'ผ่าน' => 'junior_pass',
                'ศึกษาต่อสายสามัญ' => 'junior_path_academic',
                'ศึกษาต่อสายอาชีพ' => 'junior_path_vocational',
                'ไม่ศึกษาต่อ' => 'junior_path_none',
                'เกษตรกรรม' => 'junior_job_agriculture',
                'พนักงานบริษัท/โรงงาน' => 'junior_job_company',
                'ค้าขาย' => 'junior_job_sales',
                'หัตถกรรม/เย็บปักถักร้อย' => 'junior_job_handicraft',
                'รับจ้างทั่วไป' => 'junior_job_general',
                'อื่น ๆ' => 'junior_job_other',
                'ไม่ประกอบอาชีพ' => 'junior_job_none',
            ],
        ],

        'senior' => [
            'title' => 'มัธยมศึกษาตอนปลาย',
            'fields' => [
                'ทั้งหมด' => 'senior_total',
                'ผ่าน' => 'senior_pass',
                'ศึกษาต่อสายสามัญ' => 'senior_path_academic',
                'ศึกษาต่อสายอาชีพ' => 'senior_path_vocational',
                'ไม่ศึกษาต่อ' => 'senior_path_none',
                'เกษตรกรรม' => 'senior_job_agriculture',
                'พนักงานบริษัท/โรงงาน' => 'senior_job_company',
                'ค้าขาย' => 'senior_job_sales',
                'หัตถกรรม/เย็บปักถักร้อย' => 'senior_job_handicraft',
                'รับจ้างทั่วไป' => 'senior_job_general',
                'อื่น ๆ' => 'senior_job_other',
                'ไม่ประกอบอาชีพ' => 'senior_job_none',
            ],
        ],
        ],
    ],
];


if (
    !isset($display_columns_map[$module_id]) &&
    !isset($report_map[$module_id])
) {
    die("Module {$module_id} not supported");
}


$table = "records_module{$module_id}";

/* ===============================
   SQL
================================ */
$select = [];

if (isset($report_map[$module_id])) {
    // ===== MODULE 7 / 16 =====
    $map = $report_map[$module_id];

    foreach (array_keys($map['base']) as $c) {
        $select[] = "r.$c";
    }

    foreach ($map['groups'] as $group) {
        foreach ($group['fields'] as $db) {
            $select[] = "r.$db";
        }
    }

} else {
    // ===== MODULE ปกติ =====
    $columns = $display_columns_map[$module_id];
    foreach (array_keys($columns) as $c) {
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
$sql .= " ORDER BY r.id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   สร้าง Excel
================================ */
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

/* ===============================
   MODULE 7 / 16 (GROUPED)
================================ */
if (isset($report_map[$module_id])) {

    $map = $report_map[$module_id];
    $colIndex = 0;

    /* ===== HEADER ROW 1 ===== */
    foreach ($map['base'] as $label) {
        $col = excelCol($colIndex);
        $sheet->setCellValue($col.'1', $label);
        $sheet->mergeCells($col.'1:'.$col.'2');
        $colIndex++;
    }

    foreach ($map['groups'] as $group) {
        $startIndex = $colIndex;
        foreach ($group['fields'] as $f) {
            $colIndex++;
        }
        $startCol = excelCol($startIndex);
        $endCol   = excelCol($colIndex - 1);

        $sheet->setCellValue($startCol.'1', $group['title']);
        $sheet->mergeCells($startCol.'1:'.$endCol.'1');
    }

    /* ===== HEADER ROW 2 ===== */
    $colIndex = count($map['base']);
    foreach ($map['groups'] as $group) {
        foreach ($group['fields'] as $label => $db) {
            $sheet->setCellValue(excelCol($colIndex).'2', $label);
            $colIndex++;
        }
    }

    /* ===== DATA ===== */
    $row = 3;
    foreach ($data as $record) {
        $colIndex = 0;

        foreach (array_keys($map['base']) as $f) {
            $sheet->setCellValue(excelCol($colIndex).$row, $record[$f] ?? '');
            $colIndex++;
        }

        foreach ($map['groups'] as $group) {
            foreach ($group['fields'] as $db) {
                $sheet->setCellValue(excelCol($colIndex).$row, $record[$db] ?? 0);
                $colIndex++;
            }
        }
        $row++;
    }
}

/* ===============================
   MODULE ปกติ
================================ */
else {

    if (!isset($display_columns_map[$module_id])) {
        die('Module not supported');
    }

    $columns = $display_columns_map[$module_id];

    // HEADER
    $col = 'A';
    foreach ($columns as $key => $label) {
        $sheet->setCellValue($col.'1', is_numeric($key) ? $key : $label);
        $col++;
    }

    // DATA
    $row = 2;
    foreach ($data as $record) {
        $col = 'A';
        foreach ($columns as $key => $label) {
            $db = is_numeric($key) ? $label : $key;
            $sheet->setCellValue($col.$row, $record[$db] ?? '');
            $col++;
        }
        $row++;
    }
}

$filename = "module_{$module_id}_report.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename={$filename}");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
