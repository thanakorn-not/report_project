<!-- Mobile Header & Hamburger -->
<div class="lg:hidden flex items-center justify-between p-4 bg-blue-900 text-white shadow-md w-full fixed top-0 left-0 z-50">
    <div class="flex items-center gap-3">
        <button onclick="togglePublicSidebar()" class="focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <span class="text-lg font-semibold">ระบบสารสนเทศ</span>
    </div>
</div>

<!-- Overlay Background -->
<div id="public-sidebar-overlay" onclick="closePublicSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

<!-- Sidebar Container -->
<div id="public-sidebar" class="w-64 min-h-screen bg-blue-900 text-white shadow-lg flex-col fixed top-0 left-0 z-50 hidden lg:flex transition-transform transform lg:translate-x-0">
    
    <!-- โลโก้ + ชื่อระบบ -->
    <div class="p-6 border-b border-blue-800 text-center">
        <img src="img/logo.png" class="w-16 mx-auto mb-2" alt="logo">
        <h2 class="text-xl font-semibold">ระบบสารสนเทศ</h2>
        <p class="text-blue-200 text-sm">สกร.จังหวัดนครปฐม</p>
    </div>

    <!-- เมนู -->
    <ul class="mt-4 space-y-1 flex-1">
        <li>
            <a href="index.php" class="block px-5 py-3 hover:bg-blue-800 transition">
                🏠 หน้าแรก
            </a>
        </li>

        
    </ul>

    
</div>

<script>
    function togglePublicSidebar() {
        const sidebar = document.getElementById('public-sidebar');
        const overlay = document.getElementById('public-sidebar-overlay');
        
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

    function closePublicSidebar() {
        const sidebar = document.getElementById('public-sidebar');
        const overlay = document.getElementById('public-sidebar-overlay');
        
        sidebar.classList.add('hidden');
        sidebar.classList.remove('flex');
        overlay.classList.add('hidden');
    }
</script>
