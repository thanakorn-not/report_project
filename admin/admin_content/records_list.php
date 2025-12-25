<?php
// ไฟล์: admin/records_list.php

// 1. ดึงโมดูล
$modules_stmt = $pdo->query("SELECT id, module_name, is_active FROM modules ORDER BY id ASC"); 
$modules = $modules_stmt->fetchAll(PDO::FETCH_ASSOC);

$selected_module_id = isset($_GET['module']) ? intval($_GET['module']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20; 
$offset = ($page - 1) * $limit;

$records = [];
$total_records = 0;
$display_cols = [];

if ($selected_module_id) {
    $table_name = "records_module" . $selected_module_id;

    try {
        // นับจำนวนและดึงข้อมูล
        $where = $search ? " WHERE id LIKE :s " : "";
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table_name} {$where}");
        if($search) $count_stmt->bindValue(':s', "%$search%");
        $count_stmt->execute();
        $total_records = $count_stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT * FROM {$table_name} {$where} ORDER BY id DESC LIMIT :l OFFSET :o");
        if($search) $stmt->bindValue(':s', "%$search%");
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($records)) {
            $all_cols = array_keys($records[0]);
            
            // ❌ สิ่งที่ห้ามโชว์ (ข้อมูลระบบ)
            $exclude = ['created_by', 'created_by_name', 'updated_at', 'user_id', 'created_at'];
            
            // ✅ สิ่งที่ "อยากโชว์" (Priority)
            $priority_keywords = ['school', 'prefix', 'first', 'name', 'last', 'student', 'status', 'total', 'year', 'term'];

            // เริ่มคัดเลือกคอลัมน์
            $selected_cols = ['id']; 
            if(in_array('district_id', $all_cols)) $selected_cols[] = 'district_id';

            foreach ($all_cols as $col) {
                if (in_array($col, $exclude) || in_array($col, $selected_cols)) continue;
                
                foreach ($priority_keywords as $key) {
                    if (stripos($col, $key) !== false) {
                        $selected_cols[] = $col;
                        break;
                    }
                }
                if (count($selected_cols) >= 7) break; 
            }
            $display_cols = $selected_cols;
        }
    } catch (Exception $e) { $records = []; }
}
?>

<div class="p-6 bg-gray-50">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold mb-4 text-blue-800 border-b pb-2">จัดการข้อมูลนักเรียน</h2>
        
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <input type="hidden" name="admin_content" value="records_list.php">
            <select name="module" onchange="this.form.submit()" class="border rounded-lg p-2">
                <option value="">-- เลือกโมดูล --</option>
                <?php foreach($modules as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $selected_module_id == $m['id'] ? 'selected' : '' ?>>
                        <?= $m['id'] ?>. <?= htmlspecialchars($m['module_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="search" value="<?= $search ?>" placeholder="ค้นหาจาก ID..." class="border rounded-lg p-2">
            <button class="bg-blue-600 text-white rounded-lg px-4">ค้นหา</button>
        </form>

        <?php if($selected_module_id && !empty($records)): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-blue-50">
                        <tr>
                            <?php foreach($display_cols as $c): ?>
                                <th class="p-3 text-blue-700 font-bold border-b text-sm uppercase"><?= $c ?></th>
                            <?php endforeach; ?>
                            <th class="p-3 text-right border-b">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($records as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <?php foreach($display_cols as $c): ?>
                                    <td class="p-3 border-b text-sm text-gray-600"><?= htmlspecialchars($row[$c]) ?></td>
                                <?php endforeach; ?>
                                <td class="p-3 border-b text-right">
                                    <a href="admin_layout.php?admin_content=record_form.php&module=<?= $selected_module_id ?>&id=<?= $row['id'] ?>" class="text-blue-600 mr-2">แก้ไข</a>
                                    <a href="delete_record.php?id=<?= $row['id'] ?>&module=<?= $selected_module_id ?>" class="text-red-500" onclick="return confirm('ลบข้อมูล?')">ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-400 py-10">กรุณาเลือกโมดูลเพื่อแสดงข้อมูล</p>
        <?php endif; ?>
    </div>
</div>