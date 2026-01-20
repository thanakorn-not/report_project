<!-- Mobile Header & Hamburger -->
<div class="md:hidden flex items-center justify-between p-4 bg-blue-900 text-white shadow-md w-full fixed top-0 left-0 z-50">
    <div class="flex items-center gap-3">
        <button onclick="toggleStaffSidebar()" class="focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <span class="text-lg font-semibold">ระบบสารสนเทศ</span>
    </div>
</div>

<!-- Overlay Background -->
<div id="staff-sidebar-overlay" onclick="closeStaffSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

<!-- Sidebar Container -->
<div id="staff-sidebar" class="hidden md:flex flex-col sidebar-bg w-64 min-h-screen text-white p-6 shadow-xl fixed z-50 top-0 left-0 transition-transform transform md:translate-x-0 bg-blue-900">

    <div class="p-6 border-b border-blue-800 text-center">
        <img src="../img/logo.png" class="w-16 mx-auto mb-2" alt="logo">
        <p class="text-lg font-bold">ระบบสารสนเทศ</p>
        <p class="text-sm text-gray-400">สกร. จังหวัดนครปฐม</p>
    </div>

    <nav class="space-y-4 pt-4 flex-1">
        <a href="staff_dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg main-blue font-semibold">
            <i class="fas fa-list-alt"></i> <span>รายการบันทึกข้อมูล</span>
        </a>
        <a href="#" onclick="openContactModal()" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out">
            <i class="fas fa-headset"></i> <span>ติดต่อผู้ดูแลระบบ</span>
        </a>
    </nav>

    <div class="border-t border-blue-800 p-4 absolute bottom-0 w-full pr-12">
        <a href="../logout.php" class="w-full text-center bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition duration-150 ease-in-out">
            🚪 ออกจากระบบ
        </a>
    </div>
</div>

<div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fas fa-headset mr-2"></i>ติดต่อผู้ดูแลระบบ</h3>
            <button onclick="closeContactModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-start space-x-4">
                <div class="bg-blue-100 p-3 rounded-full text-blue-600"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <p class="text-sm text-gray-500">เบอร์โทรศัพท์</p>
                    <p class="font-bold">02-XXX-XXXX (ฝ่าย IT)</p>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <div class="bg-green-100 p-3 rounded-full text-green-600"><i class="fab fa-line text-xl"></i></div>
                <div>
                    <p class="text-sm text-gray-500">Line Official</p>
                    <p class="font-bold text-green-600">@admin_system</p>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <div class="bg-red-100 p-3 rounded-full text-red-600"><i class="fas fa-envelope"></i></div>
                <div>
                    <p class="text-sm text-gray-500">อีเมล</p>
                    <p class="font-bold text-gray-800">support@nfe.go.th</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 p-4 text-right">
            <button onclick="closeContactModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">ปิดหน้าต่าง</button>
        </div>
    </div>
</div>


<script>
function openContactModal() {
    document.getElementById('contactModal').classList.remove('hidden');
}
function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
}

function toggleStaffSidebar() {
    const sidebar = document.getElementById('staff-sidebar');
    const overlay = document.getElementById('staff-sidebar-overlay');
    
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

function closeStaffSidebar() {
    const sidebar = document.getElementById('staff-sidebar');
    const overlay = document.getElementById('staff-sidebar-overlay');
    
    sidebar.classList.add('hidden');
    sidebar.classList.remove('flex');
    overlay.classList.add('hidden');
}

// ปิด modal เมื่อคลิกพื้นหลัง
window.onclick = function(event) {
    let modal = document.getElementById('contactModal');
    if (event.target == modal) { closeContactModal(); }
}
</script>