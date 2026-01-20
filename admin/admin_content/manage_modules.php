<?php
// ไฟล์: admin/admin_content/manage_modules.php (ฉบับปรับปรุง)

// ✅ ตรวจสอบสิทธิ์การเข้าถึง (ใช้ $_SESSION ที่ถูกตั้งค่าโดย admin_layout.php)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php'); // หรือหน้า Login
  exit;
}

// ✅ 1. จัดการการบันทึก/แก้ไขโมดูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_success = false;
    $action_error = false;

    if (isset($_POST['add_module'])) {
        $module_name = trim($_POST['module_name']);
        if (!empty($module_name)) {
            // เพิ่มโมดูลใหม่: is_active = 1
            $stmt = $pdo->prepare("INSERT INTO modules (module_name, is_active) VALUES (?, 1)");
            $stmt->execute([$module_name]);
            $action_success = "เพิ่มโมดูลใหม่ '{$module_name}' เรียบร้อยแล้ว ✅";
        } else {
            $action_error = "กรุณากรอกชื่อโมดูลก่อนบันทึก ❗";
        }
    } elseif (isset($_POST['edit_module'])) {
        $id = (int) $_POST['id'];
        $module_name = trim($_POST['module_name']);
        if (!empty($module_name)) {
            $stmt = $pdo->prepare("UPDATE modules SET module_name = ? WHERE id = ?");
            $stmt->execute([$module_name, $id]);
            $action_success = "แก้ไขชื่อโมดูล ID: {$id} เป็น '{$module_name}' เรียบร้อยแล้ว ✅";
        } else {
            $action_error = "ชื่อโมดูลห้ามว่าง ❗";
        }
    } elseif (isset($_POST['toggle_active'])) {
        $id = (int) $_POST['id'];
        $current_status = (int) $_POST['current_status'];
        $new_status = 1 - $current_status; // สลับสถานะ (1->0, 0->1)
        
        $stmt = $pdo->prepare("UPDATE modules SET is_active = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
        
        $status_text = $new_status ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        $action_success = "โมดูล ID: {$id} ถูก {$status_text} เรียบร้อยแล้ว ✅";
    }

    // กำหนดข้อความแจ้งเตือนลงใน Session
    if ($action_success) {
        $_SESSION['success'] = $action_success;
    } elseif ($action_error) {
        $_SESSION['error'] = $action_error;
    }
    
    // Redirect เพื่อป้องกันการส่งซ้ำ
    header('Location: admin_layout.php?admin_content=manage_modules.php');
    exit;
}

// ✅ 2. จัดการการลบโมดูล (Hard Delete)
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  // **คำเตือน:** การลบโมดูลจริง (Hard Delete) ควรลบตาราง records_moduleX ด้วย
  // แต่เพื่อความปลอดภัย เราควรเปลี่ยนเป็น Soft Delete (is_active=0) เท่านั้น
  // แต่ถ้าต้องการลบจริง ให้ลบทั้งตารางและแถวใน modules
  
  // สมมติว่ายังคงใช้ Hard Delete ตามโค้ดเดิม แต่ควรมีคำเตือน
  try {
    // 1. ลบตารางข้อมูลที่เกี่ยวข้อง
    $pdo->exec("DROP TABLE IF EXISTS records_module{$id}");

    // 2. ลบแถวโมดูล
    $stmt = $pdo->prepare("DELETE FROM modules WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "ลบโมดูล ID: {$id} และตารางข้อมูลที่เกี่ยวข้องเรียบร้อยแล้ว 🗑️";
  } catch (PDOException $e) {
    $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบโมดูล: " . $e->getMessage();
  }
  header('Location: admin_layout.php?admin_content=manage_modules.php');
  exit;
}

// ✅ 3. ดึงข้อมูลโมดูลทั้งหมด (ดึงทั้ง active และ inactive เพื่อให้จัดการได้)
// **💡 แก้ไข:** ลบ WHERE is_active = 1 ออกจาก Query เดิม
$modules_stmt = $pdo->query("SELECT id, module_name, is_active FROM modules ORDER BY id ASC");
$modules = $modules_stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. จัดการ Error/Success Message จาก Session
$success_msg = $_SESSION['success'] ?? null;
$error_msg = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="p-4 md:p-6">
  <h2 class="text-xl md:text-2xl font-bold text-blue-800 mb-6">📁 จัดการโมดูล</h2>

  <?php if ($success_msg): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      <?= htmlspecialchars($success_msg); ?>
    </div>
  <?php elseif ($error_msg): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <?= htmlspecialchars($error_msg); ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="bg-white p-4 md:p-6 rounded-xl shadow-md border border-gray-200 mb-8">
    <input type="hidden" name="add_module" value="1">
    <label for="module_name" class="block text-gray-700 font-medium mb-2">ชื่อโมดูลใหม่:</label>
    <div class="flex flex-col md:flex-row gap-3">
      <input type="text" id="module_name" name="module_name"
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none w-full"
        placeholder="เช่น ข้อมูลนักเรียน, ผลการเรียน ฯลฯ" required>
      <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-medium px-5 py-2 rounded-lg w-full md:w-auto">
        ➕ เพิ่มโมดูล
      </button>
    </div>
  </form>

  <div class="bg-white p-4 md:p-6 rounded-xl shadow-md border border-gray-200">
    <h3 class="text-lg font-semibold text-blue-900 mb-4">📋 รายการโมดูลทั้งหมด (<?= count($modules) ?> รายการ)</h3>

    <?php if (count($modules) > 0): ?>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[600px]">
          <thead class="bg-blue-800 text-white">
            <tr>
              <th class="py-3 px-4 border-b w-16">ID</th>
              <th class="py-3 px-4 border-b">ชื่อโมดูล</th>
              <th class="py-3 px-4 border-b text-center w-32">สถานะ</th>
              <th class="py-3 px-4 border-b text-center w-24">ดำเนินการ</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($modules as $m): ?>
              <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 border-b"><?= $m['id']; ?></td>
                <td class="py-3 px-4 border-b">
                    <form method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <input type="hidden" name="edit_module" value="1">
                        <input type="hidden" name="id" value="<?= $m['id']; ?>">
                        <input type="text" name="module_name" value="<?= htmlspecialchars($m['module_name']); ?>"
                            required class="border border-gray-300 rounded-lg px-2 py-1 flex-1 w-full sm:w-auto min-w-[200px]">
                        <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm whitespace-nowrap mt-1 sm:mt-0">💾 บันทึก</button>
                    </form>
                </td>
                <td class="py-3 px-4 border-b text-center">
                    <form method="POST" class="inline-block">
                        <input type="hidden" name="toggle_active" value="1">
                        <input type="hidden" name="id" value="<?= $m['id']; ?>">
                        <input type="hidden" name="current_status" value="<?= $m['is_active']; ?>">
                        <button type="submit" 
                            class="px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap
                            <?= $m['is_active'] ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' ?>">
                            <?= $m['is_active'] ? '✅ เปิดใช้งาน' : '❌ ปิดใช้งาน' ?>
                        </button>
                    </form>
                </td>
                <td class="py-3 px-4 border-b text-center">
                  <a href="?admin_content=manage_modules.php&delete=<?= $m['id']; ?>"
                    onclick="return confirm('คำเตือน: การลบโมดูลนี้จะลบตาราง records_module<?= $m['id']; ?> ด้วย! คุณแน่ใจหรือไม่ที่จะลบโมดูลนี้?');"
                    class="text-red-600 hover:text-red-800 font-medium whitespace-nowrap">🗑️ ลบ</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-gray-500">ยังไม่มีโมดูลในระบบ</p>
    <?php endif; ?>
  </div>
</div>