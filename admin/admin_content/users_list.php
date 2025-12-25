<?php
// ไฟล์: admin/user_list.php

// ✅ ต้องมีการเชื่อมต่อ $pdo มาจาก admin_layout.php

// 1. ดึงรายชื่อผู้ใช้งานทั้งหมด
$stmt = $pdo->prepare("
    SELECT 
        u.id, 
        u.username, 
        u.role, 
        u.name, 
        u.district_id,
        d.district_name 
    FROM 
        users u
    LEFT JOIN 
        districts d ON u.district_id = d.id
    ORDER BY 
        FIELD(u.role, 'admin', 'staff') ASC, u.id ASC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. ดึงรายชื่อศูนย์ฯ (สำหรับ Dropdown เพิ่ม/แก้ไข)
$districts_stmt = $pdo->query("SELECT id, district_name FROM districts ORDER BY district_name ASC");
$districts = $districts_stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. จัดการ Error/Success Message จากการ Redirect (Logic ที่แก้ไข)
$message = $_GET['msg'] ?? '';
$status = null;

if (isset($_GET['success'])) {
    // แปลงค่า success เป็นตัวเลข 1 (Success) หรือ 0 (Error)
    $status = intval($_GET['success']);
}
?>

<div class="p-6">
    <h2 class="text-2xl font-bold text-blue-800 mb-6">👤 จัดการผู้ใช้งาน</h2>

    <?php
    // 💡 Logic แสดงผลข้อความแจ้งเตือน (Success/Error)
    if ($status !== null && $message !== ''):
        $alert_class = ($status == 1)
            ? 'bg-green-100 border-green-400 text-green-700'
            : 'bg-red-100 border-red-400 text-red-700'; // ถ้า success=0 (Error) จะเป็นสีแดง
    ?>
        <div class="p-4 mb-4 rounded-md border <?= $alert_class; ?>">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-end mb-4">
        <button onclick="openModal('add', this)"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center shadow-md">
            ➕ เพิ่มผู้ใช้งาน
        </button>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ชื่อผู้ใช้งาน
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ชื่อ-สกุล
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        สิทธิ์
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ศูนย์/อำเภอ
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ดำเนินการ
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $i => $user):
                    // แปลงข้อมูลเป็น JSON เพื่อส่งไปให้ JavaScript ในการแก้ไข (ต้องทำในลูป)
                    $user_json = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                ?>
                    <tr data-user='<?= $user_json ?>' class="<?= ($user['role'] === 'admin') ? 'bg-indigo-50' : '' ?>">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($user['id']) ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?= htmlspecialchars($user['username']) ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?= htmlspecialchars($user['name']) ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                <?= ($user['role'] === 'admin') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' ?>">
                                <?= ($user['role'] === 'admin') ? 'Admin' : 'Staff' ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?= htmlspecialchars($user['district_name'] ?? '-') ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openModal('edit', this)"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                แก้ไข
                            </button>

                            <?php if ($user['id'] != 1 && $user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                                <a href="delete_user.php?id=<?= $user['id'] ?>"
                                    onclick="return confirm('ยืนยันการลบผู้ใช้งาน: <?= htmlspecialchars($user['name']) ?>?')"
                                    class="text-red-600 hover:text-red-900">
                                    ลบ
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">ลบไม่ได้</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="user-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="document.getElementById('user-modal').classList.add('hidden')">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="user-form" action="save_user.php" method="POST">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900 mb-4">เพิ่มผู้ใช้งานใหม่</h3>

                        <input type="hidden" id="user-id" name="id">

                        <div class="space-y-4">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้งาน (Username)</label>
                                <input type="text" id="username" name="username" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                                <input type="password" id="password" name="password"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p id="password-note" class="mt-1 text-xs text-gray-500">(ต้องระบุ)</p>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ-สกุล</label>
                                <input type="text" id="name" name="name" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">สิทธิ์ผู้ใช้งาน</label>
                                <select id="role" name="role" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="staff">Staff (เจ้าหน้าที่บันทึกข้อมูล)</option>
                                    <option value="admin">Admin (ผู้ดูแลระบบ)</option>
                                </select>
                            </div>

                            <div>
                                <label for="district-id" class="block text-sm font-medium text-gray-700">ศูนย์/อำเภอ</label>
                                <select id="district-id" name="district_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- ไม่ระบุศูนย์ (สำหรับ Admin/ส่วนกลาง) --</option>
                                    <?php foreach ($districts as $district): ?>
                                        <option value="<?= $district['id'] ?>"><?= htmlspecialchars($district['district_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            บันทึก
                        </button>
                        <button type="button" onclick="document.getElementById('user-modal').classList.add('hidden')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(mode, button) {
        const modal = document.getElementById('user-modal');
        const form = document.getElementById('user-form');
        const title = document.getElementById('modal-title');
        const passwordInput = document.getElementById('password');
        const passwordNote = document.getElementById('password-note');

        // Clear Form
        form.reset();
        document.getElementById('user-id').value = '';

        if (mode === 'add') {
            title.textContent = 'เพิ่มผู้ใช้งานใหม่';
            passwordInput.required = true;
            passwordNote.textContent = '(ต้องระบุ)';
        } else if (mode === 'edit') {
            title.textContent = 'แก้ไขผู้ใช้งาน';

            // Get data from table row
            const row = button.closest('tr');
            // ใช้ data-user ที่แปลงเป็น JSON
            const data = JSON.parse(row.dataset.user);

            // Populate form fields
            document.getElementById('user-id').value = data.id;
            document.getElementById('username').value = data.username;
            document.getElementById('name').value = data.name;
            document.getElementById('role').value = data.role;
            document.getElementById('district-id').value = data.district_id || '';

            // Password is optional during edit
            passwordInput.required = false;
            passwordNote.textContent = '(ว่างไว้ถ้าไม่ต้องการเปลี่ยน)';
        }

        modal.classList.remove('hidden');
    }
</script>