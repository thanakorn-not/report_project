<?php
// 1. รับค่าพารามิเตอร์
$selected_module_id = isset($_GET['module']) ? intval($_GET['module']) : (isset($_POST['module_id']) ? intval($_POST['module_id']) : null);
$record_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['record_id']) ? intval($_POST['record_id']) : null);

if (!$selected_module_id || !$record_id) {
    die("<div class='p-6 text-red-500 font-bold'>❌ Error: ข้อมูลไม่ครบถ้วน</div>");
}

$table_name = "records_module" . $selected_module_id;
$status_message = "";

// ---------------------------------------------------------
// 2. ส่วนการประมวลผลการบันทึก (แก้ไขจุดที่มีปัญหา)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_record'])) {
    $fields = $_POST['field'] ?? []; 
    $update_parts = [];
    $params = [':id' => $record_id];

    // รายการฟิลด์ที่ "ห้าม" ยุ่งเด็ดขาด
    $protected_fields = ['id', 'module_id', 'district_id', 'year', 'term', 'created_at', 'created_by', 'updated_at'];

    foreach ($fields as $column => $value) {
        // ตรวจสอบชื่อคอลัมน์ (ป้องกัน SQL Injection และการแก้ฟิลด์ระบบ)
        if (in_array(strtolower($column), $protected_fields)) continue; 
        
        $update_parts[] = "`$column` = :$column";
        $params[":$column"] = $value;
    }

    if (!empty($update_parts)) {
        // ลบ `updated_at` = NOW() ออกจากคำสั่ง SQL เพราะฐานข้อมูลจะจัดการเองอัตโนมัติจากที่เรารัน SQL ไป
        $sql = "UPDATE `{$table_name}` SET " . implode(', ', $update_parts) . " WHERE id = :id";
        
        try {
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                $status_message = "<div class='bg-green-500 text-white p-4 rounded-xl mb-6 shadow-md'>✅ บันทึกการแก้ไขเรียบร้อยแล้ว</div>";
                
                // โหลดข้อมูลใหม่หลังจากบันทึกทันที เพื่อให้ฟอร์มแสดงค่าล่าสุด
                $stmt_reload = $pdo->prepare("SELECT * FROM `{$table_name}` WHERE id = :id");
                $stmt_reload->execute([':id' => $record_id]);
                $record = $stmt_reload->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            // แสดง Error แบบละเอียดเพื่อใช้แก้ปัญหา
            $status_message = "<div class='bg-red-500 text-white p-4 rounded-xl mb-6 shadow-md'>
                <b>❌ บันทึกไม่สำเร็จ:</b> " . htmlspecialchars($e->getMessage()) . "
            </div>";
        }
    }
}

// ---------------------------------------------------------
// 3. ดึงข้อมูลมาแสดง (ถ้ายังไม่ได้โหลดจากการบันทึกด้านบน)
// ---------------------------------------------------------
if (!isset($record) || !$record) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `{$table_name}` WHERE id = :id");
        $stmt->execute([':id' => $record_id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            die("<div class='p-6 text-red-500 font-bold'>❌ ไม่พบข้อมูลในระบบ</div>");
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

// รายการคอลัมน์ที่ไม่แสดงเป็น Input
$exclude_from_form = [
    'id', 'module_id', 'district_id', 'year', 'term', 
    'created_at', 'updated_at', 'created_by', 'created_by_name', 'user_id'
];
?>

<div class="p-4 md:p-6 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">จัดการข้อมูลนักเรียน</h1>
                <p class="text-blue-600 font-semibold uppercase tracking-wider text-xs md:text-sm">การแก้ไขข้อมูลรายการ #<?= $record_id ?></p>
            </div>
            <a href="admin_layout.php?admin_content=records_list.php&module=<?= $selected_module_id ?>" class="text-gray-500 hover:text-gray-800 text-sm font-medium flex items-center">
                <span class="mr-1">⬅</span> กลับหน้ารายการ
            </a>
        </div>

        <?= $status_message ?>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
            <div class="bg-white p-3 md:p-4 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase">โมดูลที่</p>
                <p class="text-lg md:text-xl font-black text-gray-700"><?= $selected_module_id ?></p>
            </div>
            <div class="bg-white p-3 md:p-4 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase">รหัสอำเภอ</p>
                <p class="text-lg md:text-xl font-black text-gray-700"><?= htmlspecialchars($record['district_id'] ?? 'N/A') ?></p>
            </div>
            <div class="bg-white p-3 md:p-4 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase">ปีการศึกษา</p>
                <p class="text-lg md:text-xl font-black text-gray-700"><?= htmlspecialchars($record['year'] ?? '-') ?></p>
            </div>
            <div class="bg-white p-3 md:p-4 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase">ภาคเรียน</p>
                <p class="text-lg md:text-xl font-black text-gray-700"><?= htmlspecialchars($record['term'] ?? '-') ?></p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gray-800 p-4 text-white text-sm font-bold flex items-center">
                <span class="mr-2">📝</span> ส่วนแก้ไขข้อมูลเฉพาะคอลัมน์
            </div>
            
            <form method="POST" action="" class="p-6 md:p-10">
                <input type="hidden" name="module_id" value="<?= $selected_module_id ?>">
                <input type="hidden" name="record_id" value="<?= $record_id ?>">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    foreach ($record as $column => $value): 
                        if (in_array(strtolower($column), $exclude_from_form)) continue; 
                    ?>
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-400 uppercase mb-2 ml-1 break-words">
                                <?= str_replace('_', ' ', $column) ?>
                            </label>
                            
                            <?php if (strlen($value ?? '') > 150): ?>
                                <textarea name="field[<?= $column ?>]" rows="3" 
                                    class="w-full border-2 border-gray-100 rounded-xl p-3 focus:border-blue-400 focus:bg-white transition outline-none bg-gray-50 text-sm md:text-base"><?= htmlspecialchars($value ?? '') ?></textarea>
                            <?php else: ?>
                                <input type="text" name="field[<?= $column ?>]" value="<?= htmlspecialchars($value ?? '') ?>" 
                                    class="w-full border-2 border-gray-100 rounded-xl p-3 focus:border-blue-400 focus:bg-white transition outline-none bg-gray-50 text-sm md:text-base">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 md:mt-10 pt-6 md:pt-8 border-t border-gray-100 flex justify-end">
                    <button type="submit" name="update_record" 
                        class="w-full md:w-auto bg-blue-600 text-white px-8 md:px-10 py-3 md:py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition active:scale-95 text-sm md:text-base">
                        💾 บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>