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
    '4' => [
        'district_name' => 'อำเภอ',
        'term' => 'ภาคเรียน',
        'year' => 'ปีการศึกษา',
        'school' => 'ศกร./ตำบล',
        'total_student' => 'จำนวนนักเรียน',
        'pri_total' => 'ป.ทั้งหมด',
        'pri_very_good' => 'ป.ดีมาก',
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
        'primary_total' => 'ป.ทั้งหมด',
        'primary_pass' => 'ป.ผ่าน',
        'junior_total' => 'ม.ต้นทั้งหมด',
        'junior_pass' => 'ม.ต้นผ่าน',
        'senior_total' => 'ม.ปลายทั้งหมด',
        'senior_pass' => 'ม.ปลายผ่าน',
    ],
];

/* ======================================================
   4. ดึงข้อมูล
====================================================== */
$records = [];
$total_pages = 0;
$error = '';

if ($module_id && isset($report_map[$module_id])) {

    $table = "records_module{$module_id}";
    $columns = $report_map[$module_id];

    $select = [];
    foreach (array_keys($columns) as $col) {
        if ($col === 'district_name') {
            $select[] = "d.district_name";
        } else {
            $select[] = "r.$col";
        }
    }
    $select[] = "r.id";

    $sql_base = "
        FROM {$table} r
        LEFT JOIN districts d ON r.district_id = d.id
        WHERE 1
    ";

    $params = [];

    if ($year !== '') {
        $sql_base .= " AND r.year = :year";
        $params[':year'] = $year;
    }

    if ($term !== '') {
        $sql_base .= " AND r.term = :term";
        $params[':term'] = $term;
    }

    if ($district_id !== '') {
        $sql_base .= " AND r.district_id = :staff_district_id";
        $params[':staff_district_id'] = $staff_district_id;
    }

    // count
    $count_stmt = $pdo->prepare("SELECT COUNT(*) {$sql_base}");
    $count_stmt->execute($params);
    $total_rows = (int)$count_stmt->fetchColumn();
    $total_pages = ceil($total_rows / $limit);

    // data
    $sql = "
        SELECT " . implode(', ', $select) . "
        {$sql_base}
        ORDER BY r.id DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายงานข้อมูล</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-xl shadow">

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
                        <?php foreach ($report_map[$module_id] as $label): ?>
                            <th class="border p-2"><?= $label ?></th>
                        <?php endforeach; ?>
                        <th class="border p-2">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                        <tr>
                            <?php foreach (array_keys($report_map[$module_id]) as $col): ?>
                                <td class="border p-2 text-center"><?= htmlspecialchars($row[$col] ?? '-') ?></td>
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