<?php
session_start();
require_once "../config/config.php";
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

/* ===============================
   1. SYSTEM DATA
================================ */

$module_id = intval($_POST['module_id'] ?? $_GET['module_id'] ?? 0);
$term      = $_POST['term'] ?? null;
$year      = $_POST['year'] ?? null;

$district_id      = $_SESSION['district_id'] ?? null;
$created_by       = $_SESSION['user_id'] ?? null;
$created_by_name  = $_SESSION['username'] ?? null;

if (!$module_id || !$term || !$year || !$district_id || !$created_by) {
    die("❌ ข้อมูลระบบไม่ครบ");
}

/* ===============================
   2. FILE
================================ */
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
    die("❌ ไม่พบไฟล์ Excel");
}

$filePath = $_FILES['file']['tmp_name'];

/* ===============================
   3. HEADER MAP
================================ */
$headerMap = [

    1 => [
        'school' => ['ศกร./ตำบล', 'school'],
        'cctv_status' => ['กล้องวงจรปิด', 'cctv_status'],
        'cctv_amount' => ['จำนวนกล้อง', 'cctv_amount'],
        'red_box_status' => ['ตู้แดง', 'red_box_status'],
        'reporter_name' => ['ผู้รายงาน', 'reporter_name'],
        'phone' => ['โทรศัพท์', 'phone'],
    ],

    2 => [
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'education_level' => ['ระดับการศึกษา', 'education_level'],
        'school' => ['ศกร./ตำบล', 'school'],
        'employment_status' => ['สถานะการทำงาน', 'employment_status'],
        'job' => ['งานที่ทำ', 'job'],
        'workplace' => ['สถานที่ทำงาน', 'workplace'],
        'other' => ['อื่นๆ', 'other'],
    ],
    3 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'position' => ['ตำแหน่ง', 'position'],
        'scout_qualification' => ['วุฒิทางลูกเสือ', 'scout_qualification'],
        'training_date' => ['วันเดือนปีที่ได้รับการอบรม', 'training_date'],
        'ability' => ['ความสามารถพิเศษ', 'ability'],
    ],

    4 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'school' => ['ศกร./ตำบล', 'school'],
        'total_student' => ['จำนวนนักเรียน', 'total_student'],
        'pri_total' => ['ป.ทั้งหมด', 'primary_total'],
        'pri_very_good' => ['ป.ดีมาก', 'primary_very_good'],
        'pri_good' => ['ป.ดี', 'primary_good'],
        'pri_fair' => ['ป.พอใช้', 'primary_fair'],
        'pri_improve' => ['ป.ปรับปรุง', 'primary_improve'],
        'sec_low_total' => ['ม.ต้นทั้งหมด', 'junior_total'],
        'sec_low_very_good' => ['ม.ต้นดีมาก', 'junior_very_good'],
        'sec_low_good' => ['ม.ต้นดี', 'junior_good'],
        'sec_low_fair' => ['ม.ต้นพอใช้', 'junior_fair'],
        'sec_low_improve' => ['ม.ต้นปรับปรุง', 'junior_improve'],
        'sec_up_total' => ['ม.ปลายทั้งหมด', 'senior_total'],
        'sec_up_very_good' => ['ม.ปลายดีมาก', 'senior_very_good'],
        'sec_up_good' => ['ม.ปลายดี', 'senior_good'],
        'sec_up_fair' => ['ม.ปลายพอใช้', 'senior_fair'],
        'sec_up_improve' => ['ม.ปลายปรับปรุง', 'senior_improve'],
    ],

    5 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'activities' => ['กิจกรรม', 'activities'],
    ],

    6 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'reg_primary' => ['ผู้ลงทะเบียนประถมศึกษา', 'registered_primary'],
        'reg_junior_high' => ['ผู้ลงทะเบียนมัธยมศึกษาตอนต้น', 'registered_junior_high'],
        'reg_senior_high' => ['ผู้ลงทะเบียนมัธยมศึกษาตอนปลาย', 'registered_senior_high'],
        'grad_primary' => ['ผู้จบการศึกษาประถมศึกษา', 'graduated_primary'],
        'grad_junior_high' => ['ผู้จบการศึกษามัธยมศึกษาตอนต้น', 'graduated_junior_high'],
        'grad_senior_high' => ['ผู้จบการศึกษามัธยมศึกษาตอนปลาย', 'graduated_senior_high'],
    ],

    8 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'student_code' => ['รหัสนักศึกษา', 'student_code'],
        'primary_type' => ['ความซ้ำซ้อนประถมศึกษา', 'primary_duplication'],
        'junior_type' => ['ความซ้ำซ้อนมัธยมศึกษาตอนต้น', 'junior_duplication'],
        'senior_type' => ['ความซ้ำซ้อนมัธยมศึกษาตอนปลาย', 'senior_duplication'],
    ],

    9 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'student_prefix' => ['คำนำหน้า', 'student_prefix'],
        'student_firstname' => ['ชื่อ', 'student_firstname'],
        'student_lastname' => ['นามสกุล', 'student_lastname'],
        'student_code' => ['รหัสนักศึกษา', 'student_code'],
        'student_school' => ['สถานศึกษา', 'student_school'],
        'student_level' => ['ระดับการศึกษา', 'student_level'],
        'teacher_prefix' => ['คำนำหน้าผู้สอน', 'teacher_prefix'],
        'teacher_firstname' => ['ชื่อผู้สอน', 'teacher_firstname'],
        'teacher_lastname' => ['นามสกุลผู้สอน', 'teacher_lastname'],
    ],

    10 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'student_code' => ['รหัสนักศึกษา', 'student_code'],
        'school' => ['ศกร./ตำบล', 'school'],
        'primary_code' => ['รหัสประถมศึกษา', 'primary_code'],
        'junior_code' => ['รหัสมัธยมศึกษาตอนต้น', 'junior_code'],
        'senior_code' => ['รหัสมัธยมศึกษาตอนปลาย', 'senior_code'],
    ],

    11 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'school' => ['ศกร./ตำบล', 'school'],
        'primary_status' => ['ผลการเรียนประถมศึกษา', 'primary_status'],
        'junior_status' => ['ผลการเรียนมัธยมศึกษาตอนต้น', 'junior_status'],
        'senior_status' => ['ผลการเรียนมัธยมศึกษาตอนปลาย', 'senior_status'],
    ],

    12 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'school' => ['ศกร./ตำบล', 'school'],
        'primary_status' => ['ผลการเรียนประถมศึกษา', 'primary_status'],
        'junior_status' => ['ผลการเรียนมัธยมศึกษาตอนต้น', 'junior_status'],
        'senior_status' => ['ผลการเรียนมัธยมศึกษาตอนปลาย', 'senior_status'],
    ],

    13 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'student_prefix' => ['คำนำหน้า', 'student_prefix'],
        'student_firstname' => ['ชื่อ', 'student_firstname'],
        'student_lastname' => ['นามสกุล', 'student_lastname'],
        'student_gender' => ['เพศ', 'student_gender'],
        'teacher_prefix' => ['คำนำหน้าผู้สอน', 'teacher_prefix'],
        'teacher_firstname' => ['ชื่อผู้สอน', 'teacher_firstname'],
        'teacher_lastname' => ['นามสกุลผู้สอน', 'teacher_lastname'],
    ],

    14 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'school' => ['ศกร./ตำบล', 'school'],
        'primary_total' => ['ป.ทั้งหมด', 'primary_total'],
        'primary_exam' => ['ป.เข้าสอบ', 'primary_exam'],
        'primary_absent' => ['ป.ขาดสอบ', 'primary_absent'],
        'junior_total' => ['ม.ต้นทั้งหมด', 'junior_total'],
        'junior_exam' => ['ม.ต้นเข้าสอบ', 'junior_exam'],
        'junior_absent' => ['ม.ต้นขาดสอบ', 'junior_absent'],
        'senior_total' => ['ม.ปลายทั้งหมด', 'senior_total'],
        'senior_exam' => ['ม.ปลายเข้าสอบ', 'senior_exam'],
        'senior_absent' => ['ม.ปลายขาดสอบ', 'senior_absent'],
    ],

    15 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'school' => ['ศกร./ตำบล', 'school'],
        'primary_total' => ['ป.ทั้งหมด', 'primary_total'],
        'primary_exam' => ['ป.เข้าสอบ', 'primary_exam'],
        'primary_absent' => ['ป.ขาดสอบ', 'primary_absent'],
        'junior_total' => ['ม.ต้นทั้งหมด', 'junior_total'],
        'junior_exam' => ['ม.ต้นเข้าสอบ', 'junior_exam'],
        'junior_absent' => ['ม.ต้นขาดสอบ', 'junior_absent'],
        'senior_total' => ['ม.ปลายทั้งหมด', 'senior_total'],
        'senior_exam' => ['ม.ปลายเข้าสอบ', 'senior_exam'],
        'senior_absent' => ['ม.ปลายขาดสอบ', 'senior_absent'],
    ],

    17 => [
        'term' => ['ภาคเรียน', 'term'],
        'year' => ['ปีการศึกษา', 'year'],
        'prefix' => ['คำนำหน้า', 'prefix'],
        'firstname' => ['ชื่อ', 'firstname'],
        'lastname' => ['นามสกุล', 'lastname'],
        'sex' => ['เพศ', 'sex'],
        'student_level' => ['ระดับการศึกษา', 'student_level'],
    ],

];

if (!isset($headerMap[$module_id])) {
    die("❌ ยังไม่ได้ตั้งค่า header สำหรับ module {$module_id}");
}

/* ===============================
   4. LOAD EXCEL
================================ */
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true);

$excelHeaders = array_shift($rows);
$table = "records_module{$module_id}";

/* ===============================
   5. IMPORT
================================ */
$pdo->beginTransaction();

try {

    foreach ($rows as $rowIndex => $row) {

        $data = [];

        foreach ($excelHeaders as $col => $excelHeader) {

            $excelHeader = trim(strtolower($excelHeader));

            foreach ($headerMap[$module_id] as $dbCol => $aliases) {

                foreach ($aliases as $alias) {

                    if ($excelHeader === strtolower($alias)) {
                        $data[$dbCol] = $row[$col] ?? null;
                        break 2; // ออก 2 loop
                    }
                }
            }
        }

        if (empty($data)) continue;

        // 🔥 system fields
        $data['module_id']        = $module_id;
        $data['district_id']      = $district_id;
        $data['term']             = $term;
        $data['year']             = $year;
        $data['created_by']       = $created_by;
        $data['created_by_name']  = $created_by_name;

        $columns = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));

        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ")
                VALUES ({$placeholders})";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
    }

    $pdo->commit();
    echo "✅ Import สำเร็จ";
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("❌ Import ผิดพลาด: " . $e->getMessage());
}
