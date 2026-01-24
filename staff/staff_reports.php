<?php
session_start();
require_once "../config/config.php";

$staff_district_id = $_SESSION['district_id'] ?? null;

if (!$staff_district_id) {
    die('ไม่พบข้อมูลศูนย์ของผู้ใช้งาน');
}
/* ======================================================
   1. โหลดรายการโมดูล
====================================================== */
$modules = $pdo->query("
    SELECT id, module_name 
    FROM modules 
    WHERE is_active = 1
    ORDER BY id ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ======================================================
   2. รับค่า Filter
====================================================== */
$module_id   = $_GET['module_id']   ?? '';
$year        = $_GET['year']        ?? '';
$term        = $_GET['term']        ?? '';
$district_id = $staff_district_id; // จำกัดแค่เขตของ staff เท่านั้น

$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

/* ======================================================
   3. Mapping คอลัมน์รายงาน
====================================================== */
$report_map = [

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
$report_map['7'] = [
    'base' => [
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
    ],

    'groups' => [
        'equiv' => [
            'title' => 'จำนวนผู้เทียบระดับการศึกษา',
            'fields' => [
                'primary' => 'primary_equiv',
                'junior'  => 'junior_equiv',
                'senior'  => 'senior_equiv',
            ],
        ],

        'experience' => [
            'title' => 'จำนวนผู้ผ่านการประเมินมิติประสบการณ์',
            'fields' => [
                'primary' => 'primary_exp',
                'junior'  => 'junior_exp',
                'senior'  => 'senior_exp',
            ],
        ],

        'thinking' => [
            'title' => 'จำนวนผู้ผ่านการประเมินมิติความรู้ความคิด',
            'fields' => [
                'primary' => 'primary_think',
                'junior'  => 'junior_think',
                'senior'  => 'senior_think',
            ],
        ],

        'seminar' => [
            'title' => 'จำนวนผู้ผ่านการประเมินการเข้าร่วมสัมมนาวิชาการ',
            'fields' => [
                'primary' => 'primary_seminar',
                'junior'  => 'junior_seminar',
                'senior'  => 'senior_seminar',
            ],
        ],

        'graduate' => [
            'title' => 'จำนวนผู้จบเทียบระดับการศึกษา',
            'fields' => [
                'primary' => 'primary_grad_equiv',
                'junior'  => 'junior_grad_equiv',
                'senior'  => 'senior_grad_equiv',
            ],
        ],
    ],
];
$report_map['16'] = [
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
];


function getReportColumns($module_id, $report_map)
{
    $columns = [];
    $headers = [];

    // module ปกติ
    if (!in_array($module_id, ['7', '16'])) {
        foreach ($report_map[$module_id] as $col => $label) {
            $columns[] = $col;
            $headers[] = $label;
        }
        return [$columns, $headers];
    }

    // ===== module 7 & 16 (grouped) =====
    foreach ($report_map[$module_id]['base'] as $col => $label) {
        $columns[] = $col;
        $headers[] = $label;
    }

    foreach ($report_map[$module_id]['groups'] as $group) {
        foreach ($group['fields'] as $label => $dbField) {
            $columns[] = $dbField;
            $headers[] = $group['title'] . ' - ' . $label;
        }
    }

    return [$columns, $headers];
}
function getModule5Activities($pdo, $record_id)
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM module5_activities
        WHERE record_id = ?
        ORDER BY activity_no ASC
    ");
    $stmt->execute([$record_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$activity_titles = [
    1  => 'กิจกรรมการเรียนรู้เพื่อพัฒนาวิชาการ',
    2  => 'กิจกรรมการเรียนรู้เพื่อพัฒนาทักษะชีวิต',
    3  => 'กิจกรรมแสดงออกถึงความจงรักภักดี',
    4  => 'กิจกรรมตามหลักปรัชญาเศรษฐกิจพอเพียง',
    5  => 'กิจกรรมลูกเสือ / ยุวกาชาด',
    6  => 'กิจกรรมกีฬาและสุขภาพ',
    7  => 'กิจกรรมพัฒนา ICT',
    8  => 'กิจกรรมสู่ประชาคมโลก',
    9  => 'กิจกรรมจิตอาสา',
    10 => 'กิจกรรมส่งเสริมการอ่าน',
    11 => 'กิจกรรมพัฒนาทักษะอาชีพ',
    12 => 'กิจกรรมคุณธรรม จริยธรรม',
    13 => 'กิจกรรมประชาธิปไตยและกฎหมาย',
    14 => 'กิจกรรมเสริมสร้างความสามารถพิเศษ',
];


/* ======================================================
   4. ดึงข้อมูล
====================================================== */
$records = [];
$total_pages = 0;
$error = '';

if ($module_id && isset($report_map[$module_id])) {

    list($column_keys, $headers) = getReportColumns($module_id, $report_map);

    /* ================= MODULE 5 ================= */
    if ($module_id == '5') {

        // count
        $count_stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM records_module5
            WHERE district_id = :district_id
        ");
        $count_stmt->execute([':district_id' => $staff_district_id]);
        $total_rows = (int)$count_stmt->fetchColumn();
        $total_pages = ceil($total_rows / $limit);

        // data
        $stmt = $pdo->prepare("
            SELECT
                r.id,
                r.term,
                r.year,
                GROUP_CONCAT(
  CONCAT(
    a.activity_name, ' (',
    CONCAT_WS(', ',
      IF(a.count_camp > 0, CONCAT('ค่าย:', a.count_camp), NULL),
      IF(a.count_classroom > 0, CONCAT('ห้องเรียน:', a.count_classroom), NULL),
      IF(a.count_study_trip > 0, CONCAT('ทัศนศึกษา:', a.count_study_trip), NULL),
      IF(a.count_online > 0, CONCAT('ออนไลน์:', a.count_online), NULL),
      IF(a.count_offline > 0, CONCAT('ออฟไลน์:', a.count_offline), NULL)
    ),
    ')'
  )
  SEPARATOR '<br>'
) AS activities
            FROM records_module5 r
            LEFT JOIN module5_activities a
                ON r.id = a.report_id
            WHERE r.district_id = :district_id
            GROUP BY r.id
            ORDER BY r.id DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':district_id', $staff_district_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= MODULE อื่น ================= */ else {

        $table = "records_module{$module_id}";
        $select = [];

        foreach ($column_keys as $col) {
            $select[] = "r.$col";
        }
        $select[] = "r.id";

        $sql_base = "
            FROM {$table} r
            WHERE r.district_id = :district_id
        ";

        $params = [':district_id' => $staff_district_id];

        if ($year !== '') {
            $sql_base .= " AND r.year = :year";
            $params[':year'] = $year;
        }

        if ($term !== '') {
            $sql_base .= " AND r.term = :term";
            $params[':term'] = $term;
        }

        // count
        $count_stmt = $pdo->prepare("SELECT COUNT(*) {$sql_base}");
        $count_stmt->execute($params);
        $total_rows = (int)$count_stmt->fetchColumn();
        $total_pages = ceil($total_rows / $limit);

        // data
        $stmt = $pdo->prepare("
            SELECT " . implode(', ', $select) . "
            {$sql_base}
            ORDER BY r.id DESC
            LIMIT :limit OFFSET :offset
        ");

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานข้อมูล</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">
    <div class=" mx-auto bg-white p-6 rounded-xl shadow">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">📊 รายงานข้อมูล</h2>

            <a href="staff_dashboard.php"
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-home mr-2"></i> กลับหน้าหลัก
            </a>
        </div>

        <!-- ================= FILTER ================= -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

            <select name="module_id" class="border p-2 rounded">
                <option value="">เลือกแบบฟอร์ม</option>
                <?php foreach ($modules as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $module_id == $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['module_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="year" class="border p-2 rounded">
                <option value="">ปี</option>
                <?php for ($y = 2566; $y <= 2575; $y++): ?>
                    <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>

            <select name="term" class="border p-2 rounded">
                <option value="">ภาคเรียน</option>
                <option value="1" <?= $term == '1' ? 'selected' : '' ?>>1</option>
                <option value="2" <?= $term == '2' ? 'selected' : '' ?>>2</option>
            </select>

            <select name="district_id" class="border p-2 rounded bg-gray-100" readonly>
                <?php
                $stmt = $pdo->prepare("
                    SELECT id, district_name 
                    FROM districts 
                    WHERE id = ?
                    LIMIT 1
                ");
                $stmt->execute([$staff_district_id]);
                $district = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <option value="<?= $district['id'] ?>" selected>
                    <?= htmlspecialchars($district['district_name']) ?>
                </option>
            </select>


            <button class="bg-blue-600 text-white rounded">ค้นหา</button>
        </form>

        <?php if ($records): ?>
            <div class="flex justify-end gap-3 mb-4">
                <a href="export_excel.php?<?= http_build_query($_GET) ?>" class="bg-green-600 text-white px-4 py-2 rounded">📥 Excel</a>
                <a href="export_pdf.php?<?= http_build_query($_GET) ?>" class="bg-red-600 text-white px-4 py-2 rounded">📄 PDF</a>
            </div>
        <?php endif; ?>

        <?php if (!$records): ?>
            <p class="text-center text-gray-500">ไม่พบข้อมูล</p>
        <?php else: ?>
            <table class="min-w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <?php foreach ($headers as $label): ?>
                            <th class="border p-2"><?= htmlspecialchars($label) ?></th>
                        <?php endforeach; ?>

                        <th class="border p-2">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                        <tr>
                            <?php foreach ($column_keys as $col): ?>
                                <td class="border p-2 align-top text-left">
                                    <?php if ($module_id == '5' && $col == 'activities'): ?>
                                        <?= $row[$col] ?: '<span class="text-gray-400">ไม่มีการจัดกิจกรรม</span>' ?>
                                    <?php else: ?>
                                        <?= htmlspecialchars($row[$col] ?? '-') ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>



                            <td class="border p-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="edit.php?id=<?= $row['id'] ?>&module_id=<?= $module_id ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                        แก้ไข
                                    </a>

                                    <a href="delete.php?id=<?= $row['id'] ?>&module_id=<?= $module_id ?>"
                                        onclick="return confirm('ยืนยันการลบข้อมูล?')"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">
                                        ลบ
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="flex justify-center gap-4 mt-6">
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="px-4 py-2 border">⬅ ก่อนหน้า</a>
                <span>หน้า <?= $page ?> / <?= $total_pages ?></span>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="px-4 py-2 border">ถัดไป ➡</a>
            </div>

        <?php endif; ?>

    </div>

</body>

</html>