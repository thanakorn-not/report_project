<?php echo "<!-- SIDEBAR LOADED -->"; ?>
<div class="w-64 h-full fixed top-0 left-0 bg-blue-900 text-white shadow-lg flex flex-col z-30">
    <div class="p-6 border-b border-blue-800 text-center">
        <img src="../img/logo.png" class="w-16 mx-auto mb-2" alt="logo">
        <h2 class="text-xl font-semibold">ระบบสารสนเทศ</h2>
        <p class="text-blue-200 text-sm">สกร.จังหวัดนครปฐม</p>
    </div>

    <ul class="mt-4 space-y-1 flex-1">

        <li>
            <a href="admin_layout.php?admin_content=dashboard_content.php" class="block px-5 py-3 hover:bg-blue-800 transition">
                🏠 หน้าแดชบอร์ด
            </a>
        </li>

        <li>
            <a href="admin_layout.php?admin_content=users_list.php" class="block px-5 py-3 hover:bg-blue-800 transition">
                👥 จัดการผู้ใช้งาน
            </a>
        </li>

        <li>
            <a href="admin_layout.php?admin_content=manage_modules.php" class="block px-5 py-3 hover:bg-blue-800 transition">
                📁 จัดการโมดูล
            </a>
        </li>

        <li>
            <a href="admin_layout.php?admin_content=records_list.php" class="block px-5 py-3 hover:bg-blue-800 transition">
                🧾 จัดการข้อมูลนักเรียน
            </a>
        </li>

        <li>
            <a href="admin_layout.php?admin_content=reports.php" class="block px-5 py-3 hover:bg-blue-800 transition">
                📊 สร้างรายงาน (PDF / Excel)
            </a>
        </li>

    </ul>

    <div class="border-t border-blue-800 p-4">
        <a href="../logout.php"
            class="block px-5 py-2 bg-red-600 hover:bg-red-700 text-center rounded">
            🚪 ออกจากระบบ
        </a>
    </div>
</div>