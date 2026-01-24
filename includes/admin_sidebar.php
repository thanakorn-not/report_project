<!-- Mobile Header & Hamburger -->
<div class="lg:hidden flex items-center justify-between p-4 bg-blue-900 text-white shadow-md w-full fixed top-0 left-0 z-50">
    <div class="flex items-center gap-3">
        <button onclick="toggleAdminSidebar()" class="focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <span class="text-lg font-semibold">ระบบสารสนเทศ</span>
    </div>
</div>

<!-- Overlay Background -->
<div id="admin-sidebar-overlay" onclick="closeAdminSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

<!-- Sidebar Container -->
<div id="admin-sidebar" class="w-64 h-full fixed top-0 left-0 bg-blue-900 text-white shadow-lg flex-col z-50 hidden lg:flex transition-transform transform lg:translate-x-0">
    <div class="p-6 border-b border-blue-800 text-center">
        <img src="../img/logo.png" class="w-16 mx-auto mb-2" alt="logo">
        <h2 class="text-xl font-semibold">ระบบสารสนเทศ</h2>
        <p class="text-blue-200 text-sm">สกร.จังหวัดนครปฐม</p>
    </div>

    <ul class="mt-4 space-y-1 flex-1 overflow-y-auto">

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

<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');
        
        if (sidebar.classList.contains('hidden')) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('flex');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('flex');
            overlay.classList.add('hidden');
        }
    }

    function closeAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');
        
        sidebar.classList.add('hidden');
        sidebar.classList.remove('flex');
        overlay.classList.add('hidden');
    }
</script>