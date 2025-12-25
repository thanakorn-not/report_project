<?php
// save_record.php - ใช้บันทึกข้อมูล Module 1, 2, 3, ...
session_start();
require_once "../config/config.php";

// ----------------------------------------------------------------------------------
// ✅ 1. กำหนดคอลัมน์และค่าที่คาดว่าจะได้รับจากฟอร์มสำหรับแต่ละโมดูล (ปรับปรุงชื่อคอลัมน์)
// ----------------------------------------------------------------------------------

// KEY คือ Module ID (int), VALUE คือ Array ของชื่อคอลัมน์ในฐานข้อมูล (ต้องตรงเป๊ะ)
$module_columns_map = [

    '1' => [
        'district_id',
        'term',
        'year',
        'school',
        'cctv_status',
        'cctv_amount',
        'red_box_status',
        'reporter_name',
        'phone'
    ],


    '2' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'education_level',
        'school',
        'employment_status',
        'job',
        'workplace',
        'other'
    ],

    '3' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'position',
        'scout_qualification',
        'training_date',
        'ability',
    ],
    '4' => [

        'district_id',
        'term',
        'year',
        'school',
        'total_student',

        // ประถมศึกษา
        'pri_total',
        'pri_very_good',
        'pri_good',
        'pri_fair',
        'pri_improve',

        // มัธยมศึกษาตอนต้น
        'sec_low_total',
        'sec_low_very_good',
        'sec_low_good',
        'sec_low_fair',
        'sec_low_improve',

        // มัธยมศึกษาตอนปลาย
        'sec_up_total',
        'sec_up_very_good',
        'sec_up_good',
        'sec_up_fair',
        'sec_up_improve',
    ],
    '5' => [
        'district_id',
        'term',
        'year',
    ],
    '6' => [
        'district_id',
        'term',
        'year',

        // ข้อมูลผู้ลงทะเบียน
        'reg_primary',
        'reg_junior_high',
        'reg_senior_high',

        // ข้อมูลผู้จบการศึกษา
        'grad_primary',
        'grad_junior_high',
        'grad_senior_high'
    ],
    '7' => [
        'district_id',
        'term',
        'year',

        // จำนวนผู้เทียบระดับการศึกษา
        'primary_equiv',
        'junior_equiv',
        'senior_equiv',

        // จำนวนผู้ผ่านการประเมินมิติประสบการณ์
        'primary_exp',
        'junior_exp',
        'senior_exp',

        // จำนวนผู้ผ่านการประเมินมิติความรู้ความคิด
        'primary_think',
        'junior_think',
        'senior_think',

        // จำนวนผู้ผ่านการประเมินการเข้าร่วมสัมมนาวิชาการ
        'primary_seminar',
        'junior_seminar',
        'senior_seminar',

        // จำนวนผู้จบเทียบระดับการศึกษา
        'primary_grad_equiv',
        'junior_grad_equiv',
        'senior_grad_equiv',
    ],
    '8' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'student_code',
        'primary_type',
        'junior_type',
        'senior_type',
    ],
    '9' => [
        'district_id',
        'term',
        'year',
        'student_prefix',
        'student_firstname',
        'student_lastname',
        'student_code',
        'student_school',
        'student_level',
        'teacher_prefix',
        'teacher_firstname',
        'teacher_lastname'
    ],
    '10' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'student_code',
        'school',
        'primary_code',
        'junior_code',
        'senior_code'
    ],
    '11' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'school',
        'primary_status',
        'junior_status',
        'senior_status',
    ],
    '12' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'school',
        'primary_status',
        'junior_status',
        'senior_status',
    ],
    '13' => [
        'district_id',
        'term',
        'year',
        'student_prefix',
        'student_firstname',
        'student_lastname',
        'student_gender',
        'teacher_prefix',
        'teacher_firstname',
        'teacher_lastname',
        'result'
    ],
    '14' => [
        'district_id',
        'term',
        'year',
        'school',
        // ข้อมูลระดับประถมศึกษา
        'primary_total',
        'primary_exam',
        'primary_absent',
        // ข้อมูลระดับมัธยมศึกษาตอนต้น
        'junior_total',
        'junior_exam',
        'junior_absent',
        // ข้อมูลระดับมัธยมศึกษาตอนปลาย
        'senior_total',
        'senior_exam',
        'senior_absent',
    ],
    '15' => [
        'district_id',
        'term',
        'year',
        'school',
        // ข้อมูลระดับประถมศึกษา
        'primary_total',
        'primary_exam',
        'primary_absent',
        // ข้อมูลระดับมัธยมศึกษาตอนต้น
        'junior_total',
        'junior_exam',
        'junior_absent',
        // ข้อมูลระดับมัธยมศึกษาตอนปลาย
        'senior_total',
        'senior_exam',
        'senior_absent',
    ],
    '16' => [
        'district_id',
        'term',
        'year',
        // ข้อมูลระดับประถมศึกษา
        'primary_total',
        'primary_pass',
        'primary_path_academic',
        'primary_path_vocational',
        'primary_path_none',
        'primary_job_agriculture',
        'primary_job_company',
        'primary_job_sales',
        'primary_job_handicraft',
        'primary_job_general',
        'primary_job_other',
        'primary_job_none',

        // ข้อมูลระดับมัธยมศึกษาตอนต้น
        'junior_total',
        'junior_pass',
        'junior_path_academic',
        'junior_path_vocational',
        'junior_path_none',
        'junior_job_agriculture',
        'junior_job_company',
        'junior_job_sales',
        'junior_job_handicraft',
        'junior_job_general',
        'junior_job_other',
        'junior_job_none',

        // ข้อมูลระดับมัธยมศึกษาตอนปลาย
        'senior_total',
        'senior_pass',
        'senior_path_academic',
        'senior_path_vocational',
        'senior_path_none',
        'senior_job_agriculture',
        'senior_job_company',
        'senior_job_sales',
        'senior_job_handicraft',
        'senior_job_general',
        'senior_job_other',
        'senior_job_none',
    ],
    '17' => [
        'district_id',
        'term',
        'year',
        'prefix',
        'firstname',
        'lastname',
        'sex',
        'student_level',
    ],
];
// ----------------------------------------------------------------------------------
// 2. ตรวจสอบการส่งข้อมูลและ Module ID
// ----------------------------------------------------------------------------------
if ($_SESSION['role'] === 'staff') {
    $district_id = $_SESSION['district_id']; 
} else {
    // ถ้าเป็น admin ให้รับจากฟอร์ม (กรณี admin แก้ไขข้อมูลให้ศูนย์ต่างๆ)
    $district_id = $_POST['district_id'] ?? null;
}

// ตรวจสอบว่ามีค่า district_id หรือไม่ก่อนทำขั้นตอนต่อไป
if (!$district_id) {
    die("Error: ไม่พบข้อมูลรหัสศูนย์ (District ID)");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

$module_id = $_POST['module_id'] ?? null;

if (!is_numeric($module_id) || !isset($module_columns_map[$module_id])) {
    die("Invalid Module ID provided.");
}

// =====================================================================
// 🚨🚨🚨 LOGIC ป้องกัน: ตรวจสอบสถานะ is_active ก่อนบันทึก 🚨🚨🚨
// =====================================================================
try {
    $stmt_active = $pdo->prepare("SELECT module_name, is_active FROM modules WHERE id = ?");
    $stmt_active->execute([$module_id]);
    $module_status = $stmt_active->fetch(PDO::FETCH_ASSOC);

    if (!$module_status || $module_status['is_active'] == 0) {
        $module_name = $module_status['module_name'] ?? "ไม่ทราบโมดูล ({$module_id})";

        // บันทึกข้อความ Error เข้า Session เพื่อแจ้งเตือนที่หน้าฟอร์ม (ถ้ามีการ Redirect กลับไปที่เดิม)
        $_SESSION['error'] = "❌ ไม่สามารถบันทึกข้อมูลได้: โมดูล '{$module_name}' ถูกปิดใช้งานโดยผู้ดูแลระบบแล้ว";

        // Redirect กลับไปหน้าฟอร์ม โดยไม่มี success=1
        header("Location: staff_form.php?module_id={$module_id}");
        exit(); // 🛑 หยุดการทำงาน
    }
} catch (PDOException $e) {
    // จัดการข้อผิดพลาดฐานข้อมูลระหว่างการตรวจสอบ
    die("Database error during module status check: " . htmlspecialchars($e->getMessage()));
}
// =====================================================================

$columns_to_save = $module_columns_map[$module_id];
$tableName = "records_module" . $module_id;

// ----------------------------------------------------------------------------------
// 3. เตรียมข้อมูลพื้นฐาน (Metadata) และค่าที่ถูกส่ง
// ----------------------------------------------------------------------------------

// ข้อมูลสำหรับคอลัมน์ที่ใช้ร่วมกันในทุกตาราง
$metadata = [
    'created_by' => $_SESSION['user_id'] ?? null,
    'created_by_name' => $_SESSION['username'] ?? 'ไม่ระบุ',
    'created_at' => date('Y-m-d H:i:s'),
];

// เตรียมค่าและคอลัมน์ทั้งหมดสำหรับ SQL
$data_to_save = [];

// 1) รวบรวมค่าจาก $_POST ตามคอลัมน์ที่กำหนดไว้ใน $module_columns_map
foreach ($columns_to_save as $column) {
    // 💡 การแก้ไขที่สำคัญ: ต้องตรวจสอบชื่อที่ฟอร์มส่งมา
    // ถ้า $column เป็น 'district_id' หรือ 'sub_district' ให้ใช้ชื่อที่ฟอร์มส่งมา

    // ตรวจสอบชื่อ POST ที่คาดหวังจากฟอร์ม:
    $post_key = $column;
    if ($column === 'district_id') {
        $value = $district_id; // ใช้ค่าจาก Session ที่เราดึงมาด้านบน
    } else {
        $value = $_POST[$column] ?? null;
    }
    // สำหรับ Module 5, 'district_id' และ 'sub_district' จะอยู่ใน metadata ของตารางหลัก

    $value = $_POST[$post_key] ?? null;

    // ทำความสะอาด: ตัดช่องว่างหน้าหลังออก และแปลงสตริงว่างเป็น NULL
    if (is_string($value)) {
        $value = trim($value);
    }
    if ($value === '') {
        $value = null;
    }

    // ตรวจสอบพิเศษสำหรับ cctv_amount (รักษาโค้ดเดิมถ้าจำเป็น)
    if ($column === 'cctv_amount' && ($value === null || $value === 0)) {
        $value = 0;
    }

    $data_to_save[$column] = $value;
}

// เพิ่ม module_id และ Metadata เข้าไปในชุดข้อมูล
$data_to_save['module_id'] = $module_id;
$data_to_save = array_merge($data_to_save, $metadata);

$record_id = $_POST['record_id'] ?? null;
$is_editing = ($record_id > 0);
// ----------------------------------------------------------------------------------
// 4. จัดการและ Execute คำสั่ง SQL
// ----------------------------------------------------------------------------------
// 💡 กำหนด URL Redirect ให้ถูกต้อง (Staff กลับไป Form, Admin กลับไป List)
$redirect_url = "staff_form.php?module_id={$module_id}&success=1";
if ($is_editing && !empty($_POST['is_admin_edit'])) { // ใช้ธง is_admin_edit ถ้าเป็นหน้า Admin
    $redirect_url = "../admin_layout.php?admin_content=records_list.php&module={$module_id}&success=1";
} elseif ($is_editing) {
    // ถ้า Staff แก้ไข ให้กลับไปหน้า Form โดยส่ง record_id เดิมไปด้วย
    $redirect_url = "staff_form.php?module_id={$module_id}&record_id={$record_id}&success=1";
}


try {
    $tableName = "records_module" . $module_id;
    $all_columns = array_keys($data_to_save);

    // =======================================================
    // A. Logic สำหรับ Module 5 (Header + Activities)
    // =======================================================
    if ($module_id == 5) {

        // ... (ส่วนบันทึก Header เหมือนเดิม) ...

        // 🔑 ดึง ID, User ID และ DISTRICT ID มาใช้ในส่วนกิจกรรม
        $user_id = $data_to_save['created_by'] ?? null;
        $district_id = isset($data_to_save['district_id'])
            ? (int)$data_to_save['district_id']
            : null;


        // ถ้าเป็นการเพิ่มใหม่สำหรับ Module 5 ต้องบันทึก Header (records_module5) ก่อน
        if ($is_editing) {
            // ถ้าเป็นการแก้ไข ใช้ record_id ที่ส่งมา
            $report_id = $record_id;
        } else {

            // บันทึก header ในตาราง records_module5
            $header_sql = "INSERT INTO records_module5 (module_id, created_by, term, year, created_by_name, created_at)
            VALUES (:module_id, :created_by, :term, :year, :created_by_name, :created_at)";

            $stmt_header = $pdo->prepare($header_sql);
            $stmt_header->execute([
                ':module_id' => $module_id,
                ':created_by' => $user_id,
                ':term' => $data_to_save['term'] ?? null,
                ':year' => $data_to_save['year'] ?? null,
                ':created_by_name' => $data_to_save['created_by_name'] ?? '',
                ':created_at' => $data_to_save['created_at'] ?? date('Y-m-d H:i:s')
            ]);

            $report_id = $pdo->lastInsertId();
        }

        // --- 2. จัดการรายละเอียดกิจกรรม (module5_activities) ---
        $activityTableName = "module5_activities";

        // ถ้าเป็นการแก้ไข: ลบกิจกรรมเดิมทั้งหมดก่อน
        if ($is_editing) {
            $pdo->prepare("DELETE FROM {$activityTableName} WHERE report_id = ?")->execute([$report_id]);
        }

        // SQL สำหรับรายละเอียดกิจกรรม
        $activity_sql = "INSERT INTO {$activityTableName} (
        report_id, created_by, activity_type_no, activity_name, 
        count_camp, count_classroom, count_study_trip, count_online, count_offline, count_other, 
        num_students, remark, 
        district_id 
    ) VALUES (
        :report_id, :created_by, :activity_type_no, :activity_name, 
        :count_camp, :count_classroom, :count_study_trip, :count_online, :count_offline, :count_other, 
        :num_students, :remark, 
        :district_id 
    )";

        $stmt_activity = $pdo->prepare($activity_sql);

        // ✅ Array ชื่อกิจกรรม (ต้องมีเพื่อแก้ปัญหา Undefined variable)
        $activity_names = [
            1 => 'กิจกรรมการเรียนรู้เพื่อพัฒนาวิชาการ',
            2 => 'กิจกรรมการเรียนรู้เพื่อพัฒนาทักษะชีวิต',
            3 => 'กิจกรรมที่แสดงออกถึงความจงรักภักดี ต่อสถาบันชาติ ศาสนาพระมหากษัตริย์',
            4 => 'กิจกรรมส่งเสริมการเรียนรู้ตามหลักปรัชญาของเศรษฐกิจพอเพียง',
            5 => 'กิจกรรมลูกเสือและกิจกรรมอาสายุวกาชาด',
            6 => 'กิจกรรมส่งเสริมกีฬา และสุขภาพ',
            7 => 'กิจกรรมเพี่อพัฒนาความรู้ความสามารถด้านเทคโนโลยีสารสนเทศ (ICT)',
            8 => 'กิจกรรมเพื่อพัฒนาความรู้สู่ประชาคมโลก',
            9 => 'กิจกรรมจิตอาสา "เราทำความดีด้วยหัวใจ"',
            10 => 'กิจกรรมส่งเสริมการอ่านและพัฒนาทักษะการเรียนรู้',
            11 => 'กิจกรรมส่งเสริมการเรียนรู้เพื่อพัฒนาทักษะอาชีพ',
            12 => 'กิจกรรมส่งเสริมคุณธรรม จริยธรรม',
            13 => 'กิจกรรมการเรียนรู้การปกครองระบอบประชาธิปไตยอันมีพระมหากษัตริย์ทรงเป็นประมุข',
            14 => 'กิจกรรมเสริมสร้างความสามารถพิเศษ',
        ];

        if (empty($district_id)) {
            throw new Exception('❌ Module 5: district_id is missing, cannot save activities');
        }
        // 🔥 วนลูปบันทึกข้อมูลสำหรับแต่ละกิจกรรม
        for ($i = 1; $i <= 14; $i++) {

            $activity_name = $activity_names[$i] ?? "กิจกรรมที่ {$i}";

            // --- 1. ดึงค่าจาก POST (แก้ไขบั๊กที่ค่าเป็น 0) ---
            // ใช้ intval(trim(...)) เพื่อแปลงสตริงว่าง/ช่องว่างให้เป็น 0 อย่างชัดเจน
            $count_camp         = intval(trim($_POST["activity_{$i}_camp"] ?? 0));
            $count_classroom    = intval(trim($_POST["activity_{$i}_classroom"] ?? 0));
            $count_study_trip   = intval(trim($_POST["activity_{$i}_study_trip"] ?? 0));
            $count_online       = intval(trim($_POST["activity_{$i}_online"] ?? 0));
            $count_offline      = intval(trim($_POST["activity_{$i}_offline"] ?? 0));
            $count_other        = intval(trim($_POST["activity_{$i}_other"] ?? 0));
            $num_students       = intval(trim($_POST["activity_{$i}_students"] ?? 0));
            $remark             = trim($_POST["activity_{$i}_remark"] ?? null);

            // --- 2. การตรวจสอบตามคำขอของผู้ใช้: บันทึกเฉพาะที่มีข้อมูล ---
            if (
                $count_camp === 0 &&
                $count_classroom === 0 &&
                $count_study_trip === 0 &&
                $count_online === 0 &&
                $count_offline === 0 &&
                $count_other === 0 &&
                $num_students === 0 &&
                empty($remark) // ตรวจสอบว่าหมายเหตุว่างเปล่าด้วย
            ) {
                // หากทุกช่องนับเป็น 0 และไม่มีหมายเหตุ ให้ข้ามการบันทึกแถวนี้ไปเลย
                continue;
            }

            // --- 3. เตรียมและบันทึกข้อมูล (เฉพาะแถวที่ไม่ถูกข้าม) ---
            $data_activity = [
                'report_id'          => $report_id,
                'created_by'         => $user_id,
                'activity_type_no'   => $i,
                'activity_name'      => $activity_name,
                'count_camp'         => $count_camp,
                'count_classroom'    => $count_classroom,
                'count_study_trip'   => $count_study_trip,
                'count_online'       => $count_online,
                'count_offline'      => $count_offline,
                'count_other'        => $count_other,
                'num_students'       => $num_students,
                'remark'             => $remark,
                'district_id'        => $district_id
            ];

            $stmt_activity->bindValue(':district_id', (int)$district_id, PDO::PARAM_INT);
            $stmt_activity->execute($data_activity);
        }
    } elseif ($module_id == 16) {

        $tableName = "records_module16";

        // -------------------------
        // 1) metadata ที่ต้องมี
        // -------------------------
        $base_columns = [
            'module_id',
            'district_id',
            'term',
            'year',
            'created_by',
            'created_by_name',
            'created_at'
        ];

        // -------------------------
        // 2) column เฉพาะ module 16
        // -------------------------
        $module16_columns = [
            'primary_total',
            'primary_pass',
            'junior_total',
            'junior_pass',
            'senior_total',
            'senior_pass',

            'primary_path_academic',
            'primary_path_vocational',
            'primary_path_none',
            'junior_path_academic',
            'junior_path_vocational',
            'junior_path_none',
            'senior_path_academic',
            'senior_path_vocational',
            'senior_path_none',

            'primary_job_agriculture',
            'primary_job_company',
            'primary_job_sales',
            'primary_job_handicraft',
            'primary_job_general',
            'primary_job_other',
            'primary_job_none',

            'junior_job_agriculture',
            'junior_job_company',
            'junior_job_sales',
            'junior_job_handicraft',
            'junior_job_general',
            'junior_job_other',
            'junior_job_none',

            'senior_job_agriculture',
            'senior_job_company',
            'senior_job_sales',
            'senior_job_handicraft',
            'senior_job_general',
            'senior_job_other',
            'senior_job_none'
        ];

        $allowed_columns = array_merge($base_columns, $module16_columns);

        // -------------------------
        // 3) กรองข้อมูล
        // -------------------------
        $filtered_data = array_intersect_key(
            $data_to_save,
            array_flip($allowed_columns)
        );

        // ❗ district_id ต้องมี
        if (empty($filtered_data['district_id'])) {
            throw new Exception('district_id is required for module 16');
        }

        // -------------------------
        // 4) ค่า default
        // -------------------------
        foreach ($module16_columns as $col) {
            $filtered_data[$col] = (int)($filtered_data[$col] ?? 0);
        }

        // -------------------------
        // 5) INSERT
        // -------------------------
        $columns = implode(',', array_keys($filtered_data));
        $placeholders = ':' . implode(',:', array_keys($filtered_data));

        $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
        $stmt = $pdo->prepare($sql);

        foreach ($filtered_data as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$key", $value, $type);
        }

        $stmt->execute();
    } else {

        // -------------------------------------------------------
        // C. โค้ดเดิมสำหรับ Module 1, 2, 3, 4 (ที่มีตารางเดียว records_moduleX)
        // -------------------------------------------------------
        $tableName = "records_module" . $module_id;

        $all_columns = array_keys($data_to_save); // คอลัมน์สำหรับ Header
        $column_list = implode(', ', $all_columns);
        $placeholders = ':' . implode(', :', $all_columns);
        $sql = "INSERT INTO {$tableName} ({$column_list}) VALUES ({$placeholders})";

        $stmt = $pdo->prepare($sql);

        // Bind ค่าตัวแปรทั้งหมด
        foreach ($data_to_save as $key => $value) {

            // 💡 แก้ไข: ถ้าเป็น district_id ต้อง bind เป็น INT เสมอ
            // และแก้ตรรกะการตรวจสอบ is_numeric:
            $is_int = (
                is_numeric($value) &&
                $key !== 'phone' &&
                $key !== 'reporter_tel' &&
                $key !== 'sub_district' &&
                $key !== 'primary_status' &&
                $key !== 'junior_status' &&
                $key !== 'senior_status'
            );
            $type = ($is_int) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$key", $value, $type);
        }

        $stmt->execute();
    }

    // -------------------------------------------------------
    // D. ส่วน Redirect (ใช้ร่วมกันได้)
    // -------------------------------------------------------

    // ✅ บันทึกค่าที่ต้องการล็อกเข้า Session ก่อน Redirect (ใช้ค่าจาก POST ที่ส่งมาจากฟอร์ม)
    // 💡 แก้ไข: ใช้ 'district_id' (ID) และ 'sub_district' (ตำบล) แทน 'school' (ชื่อเต็ม) และ 'district' (ตำบล)
    $_SESSION['form_district_id'] = $data_to_save['district_id'] ?? null; // ใช้ ID เพื่อให้ Form ดึงชื่อมาแสดงได้
    $_SESSION['form_term'] = $data_to_save['term'] ?? '';
    $_SESSION['form_year'] = $data_to_save['year'] ?? '';


    // บันทึกสำเร็จ: Redirect
    header("Location: {$redirect_url}");
    exit();
} catch (PDOException $e) {
    // ตรวจสอบและแก้ไขข้อความ Error ที่อาจมีคำว่า 'school' หรือ 'district'
    $error_msg = $e->getMessage();

    // ตัวอย่างการแจ้ง Error ที่ชัดเจน
    $redirect_url = "staff_form.php?module_id={$module_id}&error=1";
    $_SESSION['error'] = "❌ Error while saving data to database: SQLSTATE[" . $e->getCode() . "]: " . $error_msg;
    // ❌ แสดงข้อผิดพลาด: ถ้าเป็น Module 5 จะแจ้ง Error ลำดับกิจกรรมให้
    if ($module_id == 5) {
        // เพิ่มโค้ด Debug ของคุณ
        die("❌ Error while saving data to database: " . htmlspecialchars($e->getMessage()) . " (Check activity data and column names in module5_activities)");
    } else {
        die("❌ Error while saving data to database: " . htmlspecialchars($e->getMessage()));
    }
}
