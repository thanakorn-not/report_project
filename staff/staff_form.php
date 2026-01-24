<?php
session_start();
// ตรวจสอบและเริ่มต้นการเชื่อมต่อฐานข้อมูล
require_once "../config/config.php";

$staffDistrictId   = $_SESSION['district_id'] ?? null;
$staffDistrictName = $_SESSION['district_name'] ?? null;

// ถ้ายังไม่มี district_id → ผิดพลาด
if (!$staffDistrictId) {
    die('ไม่พบข้อมูลศูนย์ของผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่');
}
if (!isset($_GET['module_id'])) {
    die("ไม่พบโมดูลที่ต้องการบันทึกข้อมูล");
}

$module_id = intval($_GET['module_id']);

// 🔹 ดึงชื่อโมดูลและสถานะ is_active จากฐานข้อมูล (แก้ไข: เพิ่ม is_active)
$stmt = $pdo->prepare("SELECT module_name, is_active FROM modules WHERE id = ?");
$stmt->execute([$module_id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    die("โมดูลไม่ถูกต้อง");
}

// =====================================================================
// 🚨🚨🚨 LOGIC ป้องกัน: ตรวจสอบสถานะ is_active ก่อนแสดงฟอร์ม 🚨🚨🚨
// =====================================================================
if (isset($module['is_active']) && $module['is_active'] == 0) {
    // แสดงข้อความแจ้งเตือนและหยุดการทำงาน (แทนที่จะแสดงฟอร์ม)
?>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>บันทึกข้อมูล - <?= htmlspecialchars($module['module_name']) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'IBM Plex Sans Thai', sans-serif;
            }
        </style>
    </head>

    <body class="bg-gray-100 py-10">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow border-l-4 border-red-500">
            <h2 class="text-2xl font-bold text-red-700 mb-4">
                🔒 โมดูลถูกปิดใช้งาน
            </h2>
            <p class="text-gray-600">
                โมดูล **<?= htmlspecialchars($module['module_name']) ?>** ถูกปิดใช้งานโดยผู้ดูแลระบบแล้ว
                คุณไม่สามารถเข้าถึงหน้าฟอร์มบันทึกข้อมูลนี้ได้ในขณะนี้
            </p>
            <p class="mt-4 text-sm text-red-500">กรุณาติดต่อผู้ดูแลระบบหากมีข้อสงสัย</p>
            <a href="staff_dashboard.php" class="mt-6 inline-block bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                กลับสู่หน้าหลัก
            </a>
        </div>
    </body>

    </html>
<?php
    exit; // 🛑 หยุดการทำงาน
}

// =====================================================================
// ✅ การปรับปรุงใหม่ 1: ดึงค่า DISTRICT ID และ NAME จาก SESSION
// =====================================================================

$active_district_id = $_SESSION['active_district_id'] ?? null;

// ถ้ามี district_id ใน Session ให้ดึงชื่อเต็มมาแสดง
if ($active_district_id) {
    try {
        $stmt_dist = $pdo->prepare("SELECT district_name FROM districts WHERE id = ?");
        $stmt_dist->execute([$active_district_id]);
        $name_from_db = $stmt_dist->fetchColumn();
        if ($name_from_db) {
            $active_district_name = $name_from_db;
        }
    } catch (PDOException $e) {
        // จัดการข้อผิดพลาดถ้าดึงจาก DB ไม่ได้
    }
}

// 🔹 โหลดค่าที่เคยเลือกไว้จาก Session สำหรับฟิลด์หลัก (Term, Year, District/Tambon)
$selected_term      = $_SESSION['form_term'] ?? '';
$selected_year      = $_SESSION['form_year'] ?? '';
$selected_sub_district = $_SESSION['form_sub_district'] ?? '';

// =====================================================================
// 💡 การปรับปรุงใหม่ 2: ดึงข้อมูลล่าสุดจาก DB หากมีการบันทึกสำเร็จ
// =====================================================================
$old_data = [];
$success_status = $_GET['success'] ?? null;

if ($success_status === '1' && $active_district_id) {
    // 🔥🔥 ถ้าบันทึกสำเร็จ ให้ดึงข้อมูลที่บันทึกไปล่าสุด (จาก Session ที่ save_record.php ล็อกไว้)
    // สำหรับ Module 1 เราจะดึงข้อมูล CCTV และอื่นๆ มาแสดงผลต่อ 
    // (ใช้ $active_district_id และ $selected_term/year จาก Session)

    // NOTE: เนื่องจากเราไม่มี PK ของแถวที่บันทึก, เราจะดึงแถวล่าสุดที่ตรงกับ district_id, term, year

    $tableName = "records_module" . $module_id;

    // ดึงค่าล่าสุด (LIMIT 1) ที่ตรงกับเงื่อนไขหลัก
    $sql_fetch = "SELECT * FROM {$tableName} WHERE district_id = :district_id AND term = :term AND year = :year ORDER BY id DESC LIMIT 1";

    try {
        $stmt_fetch = $pdo->prepare($sql_fetch);
        $stmt_fetch->execute([
            ':district_id' => $active_district_id,
            ':term' => $selected_term,
            ':year' => $selected_year
        ]);
        $old_data = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

        // ถ้าดึงข้อมูลได้สำเร็จ ให้อัปเดตค่า Session ที่ใช้ในฟอร์มโมดูลด้วย
        if ($old_data) {
            $selected_sub_district = $old_data['sub_district'] ?? $selected_sub_district;
        }
    } catch (PDOException $e) {
        // ใน Dev Mode สามารถ echo Error ได้: echo "Error fetching old data: " . $e->getMessage();
    }
}
// =====================================================================
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูล - <?= htmlspecialchars($module['module_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 py-10">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-2xl font-bold text-center mb-4">
            <?= htmlspecialchars($module['module_name']) ?>
        </h2>

        <form action="save_record.php" method="POST">
            <input type="hidden" name="module_id" value="<?= htmlspecialchars($module_id) ?>">

            <div class="mb-4">
                <label class="block font-semibold mb-1">สถานศึกษา / ศูนย์ฯ</label>

                <!-- แสดงชื่อศูนย์ (อ่านอย่างเดียว) -->
                <p class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md sm:text-sm font-semibold text-blue-700">
                    <?= htmlspecialchars($staffDistrictName ?? 'ไม่ทราบชื่อศูนย์') ?>
                </p>

                <!-- ส่ง district_id ไปบันทึก -->
                <input type="hidden" name="district_id" value="<?= htmlspecialchars($staffDistrictId) ?>">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <label class="block font-semibold mb-1">ภาคเรียนที่</label>
                    <div class="flex items-center space-x-4">
                        <label><input type="radio" name="term" value="1" <?= ($selected_term == "1") ? 'checked' : '' ?> required class="mr-2">1</label>
                        <label><input type="radio" name="term" value="2" <?= ($selected_term == "2") ? 'checked' : '' ?> required class="mr-2">2</label>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1">ปีการศึกษา</label>
                    <select name="year" required class="border p-2 w-full rounded">
                        <option value="">-- เลือกปีการศึกษา --</option>
                        <?php for ($y = 2568; $y <= 2575; $y++): ?>
                            <option value="<?= $y ?>" <?= ($selected_year == $y) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <?php
            $form_file = "../modules/module_{$module_id}.php";
            if (file_exists($form_file)) {
                // ส่งค่าเก่าที่ดึงจาก DB เข้าไปในไฟล์โมดูล (สำหรับ Module 1)
                $old_cctv_status = $old_data['cctv_status'] ?? '';
                $old_cctv_amount = $old_data['cctv_amount'] ?? 0;
                $old_red_box_status = $old_data['red_box_status'] ?? '';
                $old_reporter_name = $old_data['reporter_name'] ?? '';
                $old_phone = $old_data['phone'] ?? '';

                include $form_file;
            } else {
                echo "<p class='text-red-600'>⚠ ฟอร์มของโมดูลนี้ยังไม่ถูกสร้าง</p>";
            }
            ?>

            <hr class="my-6">

            <div class="flex gap-4 mt-6">
                <a href="staff_dashboard.php"
                    class="w-1/3 bg-gray-500 text-white text-center py-3 rounded-md hover:bg-gray-600 transition shadow">
                    ⬅ กลับหน้าหลัก
                </a>

                <button type="submit"
                    class="w-2/3 bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition shadow">
                    ✅ บันทึกข้อมูล
                </button>
            </div>

        </form>
        <div class="max-w-4xl mx-auto mt-4 bg-white p-4 rounded-xl shadow">
    <h3 class="font-semibold mb-2 text-green-700">📥 Import ข้อมูลจาก Excel</h3>

    <form action="import_excel.php" method="post" enctype="multipart/form-data">
        
        <input type="hidden" name="module_id" value="<?= htmlspecialchars($module_id ?? '') ?>">

        <select name="term" required class="border p-2 rounded">
            <option value="">-- เลือกภาคเรียน --</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>

        <select name="year" required class="border p-2 rounded">
            <option value="">-- เลือกปี --</option>
            <option value="2568">2568</option>
            <option value="2569">2569</option>
        </select>

        <!-- 🔴 ชื่อต้องตรงกับ import.php -->
        <input type="file" name="file" required>

        <button type="submit" name="import"
            class="bg-green-600 text-white px-4 py-2 rounded">
            Import
        </button>
    </form>
</div>

        

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            Swal.fire({
                title: '✅ บันทึกข้อมูลสำเร็จ!',
                text: 'ระบบได้บันทึกข้อมูลของคุณเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#2563eb'
            });
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <script>
        // ฟังก์ชันสำหรับซ่อน/แสดงช่องกรอกจำนวน
        function toggleCCTVAmount(show) {
            const container = document.getElementById('cctv_amount_container');
            const input = document.getElementById('cctv_amount');

            if (container && input) {
                if (show) {
                    container.classList.remove('hidden'); // แสดงช่องกรอก
                    input.disabled = false; // เปิดใช้งาน input
                    input.setAttribute('required', 'required'); // กำหนดให้ต้องกรอก
                } else {
                    container.classList.add('hidden'); // ซ่อนช่องกรอก
                    input.disabled = true; // ปิดใช้งาน input
                    input.removeAttribute('required'); // ลบคุณสมบัติ required
                    input.value = 0; // ตั้งค่าเป็น 0
                }
            }
        }

        // กำหนดค่าเริ่มต้นเมื่อโหลดหน้า
        document.addEventListener('DOMContentLoaded', function() {
            // ตรวจสอบค่าที่ถูกเลือกไว้ (ถ้ามีการโหลดหน้าซ้ำ)
            const hasCCTV = document.getElementById('cctv_status_has');
            if (hasCCTV && hasCCTV.checked) {
                // เรียกใช้ฟังก์ชันเพื่อให้ช่องกรอกจำนวนกล้องแสดงผลถูกต้องตามสถานะ radio button
                toggleCCTVAmount(true);
            }
        });
    </script>

</body>

</html>