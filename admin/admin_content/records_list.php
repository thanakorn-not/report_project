<?php
// ========== LOAD MODULE LIST ==========
$modules_stmt = $pdo->query("SELECT id, module_name FROM modules ORDER BY id ASC");
$modules = $modules_stmt->fetchAll(PDO::FETCH_ASSOC);

$module_id = isset($_GET['module']) ? intval($_GET['module']) : 0;
$search_field = $_GET['field'] ?? '';
$search_text = trim($_GET['keyword'] ?? '');
$records = [];
$columns = [];

// ถ้าเลือกโมดูลแล้ว
if ($module_id > 0) {
    $table = "records_module" . $module_id;

    try {
        // ===== ดึง Column ของตาราง =====
        $col_stmt = $pdo->query("SHOW COLUMNS FROM $table");
        $all_cols = $col_stmt->fetchAll(PDO::FETCH_COLUMN);

        // ตัด column ระบบออก
        $exclude = ['created_at', 'updated_at', 'user_id', 'created_by', 'created_by_name'];
        $columns = array_values(array_diff($all_cols, $exclude));

        // ===== Query หลัก =====
        $sql = "SELECT * FROM $table";
        $params = [];


        // ถ้ามีค้นหา
        if ($search_field && $search_text && in_array($search_field, $columns)) {

            // คำค้นที่ต้อง match ตรงตัว
            $exactWords = ["มี", "ไม่มี", "ชาย", "หญิง", "ใช้", "ไม่ใช้", "เปิด", "ปิด"];

            if (in_array($search_text, $exactWords)) {
                // ค้นแบบตรงตัว
                $sql .= " WHERE $search_field = :kw";
                $params[':kw'] = $search_text;
            } else {
                // ค้นหาแบบ LIKE ปกติ
                $sql .= " WHERE $search_field LIKE :kw";
                $params[':kw'] = "%$search_text%";
            }
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $records = [];
    }
}
?>

<div class="p-4 md:p-6 bg-gray-50">

    <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg">

        <h2 class="text-lg md:text-xl font-bold text-blue-800 mb-4 border-b pb-2">จัดการข้อมูล</h2>

        <form method="get" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
            <input type="hidden" name="admin_content" value="records_list.php">

            <!-- เลือกโมดูล -->
            <select name="module" onchange="this.form.submit()" class="border rounded-lg p-2 w-full text-sm md:text-base">
                <option value="">-- เลือกโมดูล --</option>
                <?php foreach ($modules as $m): ?>
                    <option value="<?= $m['id']; ?>" <?= $module_id == $m['id'] ? 'selected' : '' ?>>
                        <?= $m['id'] . ". " . htmlspecialchars($m['module_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- เลือกคอลัมน์ -->
            <select name="field" class="border rounded-lg p-2 w-full text-sm md:text-base">
                <option value="">-- เลือกช่องข้อมูล --</option>
                <?php foreach ($columns as $c): ?>
                    <option value="<?= $c ?>" <?= $search_field == $c ? 'selected' : '' ?>>
                        <?= $c ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- ค่าที่ต้องการค้นหา -->
            <input type="text" name="keyword" value="<?= htmlspecialchars($search_text); ?>"
                placeholder="พิมพ์คำที่ต้องการค้นหา"
                class="border rounded-lg p-2 w-full text-sm md:text-base">

            <button class="bg-blue-700 text-white rounded-lg px-4 py-2 w-full text-sm md:text-base hover:bg-blue-800">
                ค้นหา
            </button>
        </form>

        <?php if ($module_id): ?>
            <div class="flex flex-wrap gap-2 md:gap-3 mb-4 justify-end">

                <a target="_blank"
                    href="report_pdf.php?module_id=<?= $module_id ?>
   &field=<?= urlencode($search_field) ?>
   &keyword=<?= urlencode($search_text) ?>"
                    class="bg-red-500 text-white px-3 md:px-4 py-2 rounded-lg text-sm flex items-center hover:bg-red-600 transition">
                    📄 <span class="hidden sm:inline ml-1">รายงาน</span> PDF
                </a>

                <a target="_blank"
                    href="report_excel.php?module_id=<?= $module_id ?>
   &field=<?= urlencode($search_field) ?>
   &keyword=<?= urlencode($search_text) ?>"
                    class="bg-green-600 text-white px-3 md:px-4 py-2 rounded-lg text-sm flex items-center hover:bg-green-700 transition">
                    📊 <span class="hidden sm:inline ml-1">รายงาน</span> Excel
                </a>

            </div>
        <?php endif; ?>

        <?php if ($module_id && !empty($records)): ?>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead class="bg-blue-50">
                        <tr>
                            <?php foreach ($columns as $c): ?>
                                <th class="p-3 border-b text-xs md:text-sm font-bold text-blue-700 uppercase whitespace-nowrap"><?= $c ?></th>
                            <?php endforeach; ?>
                            <th class="p-3 border-b text-right whitespace-nowrap sticky right-0 bg-blue-50 shadow-sm">จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($records as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <?php foreach ($columns as $c): ?>
                                    <td class="p-3 border-b text-sm text-gray-700 whitespace-nowrap max-w-xs overflow-hidden text-ellipsis">
                                        <?= htmlspecialchars($row[$c]) ?>
                                    </td>
                                <?php endforeach; ?>

                                <td class="p-3 border-b text-right whitespace-nowrap sticky right-0 bg-white hover:bg-gray-50 shadow-sm">
                                    <a href="admin_layout.php?admin_content=record_form.php&module=<?= $module_id ?>&id=<?= $row['id'] ?>" class="text-blue-600 mr-3 hover:text-blue-800 font-medium">
                                        แก้ไข
                                    </a>

                                    <a href="delete_record.php?module=<?= $module_id ?>&id=<?= $row['id'] ?>"
                                        onclick="return confirm('ยืนยันลบข้อมูล?')"
                                        class="text-red-500 hover:text-red-700 font-medium">
                                        ลบ
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($module_id): ?>
            <p class="text-center text-gray-400 py-10 bg-gray-50 rounded-lg">
                ยังไม่มีข้อมูลในโมดูลนี้
            </p>

        <?php else: ?>
            <p class="text-center text-gray-400 py-10 bg-gray-50 rounded-lg">
                กรุณาเลือกโมดูลก่อน
            </p>
        <?php endif; ?>

    </div>
</div>