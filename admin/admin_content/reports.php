<?php
if (!isset($pdo)) {
    echo "<p class='text-red-600'>❌ ไม่สามารถเชื่อมต่อฐานข้อมูลได้</p>";
    exit();
}

try {
    $modules_stmt = $pdo->query("SELECT id, module_name FROM modules WHERE is_active = 1 ORDER BY id ASC");
    $modules = $modules_stmt->fetchAll(PDO::FETCH_ASSOC);

    $districts_stmt = $pdo->query("SELECT id, district_name FROM districts ORDER BY id ASC");
    $districts = $districts_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $current_year = date('Y') + 543;
    $academic_years = range($current_year, $current_year - 5);
} catch (PDOException $e) {
    $error_db = "Error: " . $e->getMessage();
}
?>

<div class="p-4 md:p-6">
    <h2 class="text-xl md:text-2xl font-bold text-blue-800 mb-6 border-b pb-2">📊 สร้างรายงาน</h2>
    
    <div class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100">
        <form id="reportForm" method="GET" target="_blank" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="md:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">เลือกโมดูลข้อมูล: <span class="text-red-500">*</span></label>
                    <select name="module_id" required class="w-full border-2 border-gray-50 rounded-xl md:rounded-2xl p-3 md:p-4 focus:border-blue-500 outline-none bg-gray-50 text-sm md:text-base">
                        <option value="">-- กรุณาเลือกโมดูล --</option>
                        <?php foreach ($modules as $module): ?>
                            <option value="<?= $module['id'] ?>">[<?= $module['id'] ?>] <?= $module['module_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ปีการศึกษา:</label>
                    <select name="year" class="w-full border-2 border-gray-50 rounded-xl md:rounded-2xl p-3 md:p-4 focus:border-blue-500 outline-none bg-gray-50 text-sm md:text-base">
                        <option value="">ทั้งหมด</option>
                        <?php foreach ($academic_years as $y): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ภาคเรียน:</label>
                    <select name="term" class="w-full border-2 border-gray-50 rounded-xl md:rounded-2xl p-3 md:p-4 focus:border-blue-500 outline-none bg-gray-50 text-sm md:text-base">
                        <option value="">ทั้งหมด</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">อำเภอ:</label>
                    <select name="district_id" class="w-full border-2 border-gray-50 rounded-xl md:rounded-2xl p-3 md:p-4 focus:border-blue-500 outline-none bg-gray-50 text-sm md:text-base">
                        <option value="">ทุกอำเภอ</option>
                        <?php foreach ($districts as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['district_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 md:gap-4 pt-6 border-t border-gray-100 justify-end">
                <button type="button" onclick="submitForm('pdf')" class="w-full md:w-auto bg-red-500 text-white px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl font-bold hover:bg-red-600 shadow-lg shadow-red-200 transition-all flex items-center justify-center text-sm md:text-base">
                    <span class="mr-2">📄</span> ออกรายงาน PDF
                </button>
                
                <button type="button" onclick="submitForm('excel')" class="w-full md:w-auto bg-green-600 text-white px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl font-bold hover:bg-green-700 shadow-lg shadow-green-200 transition-all flex items-center justify-center text-sm md:text-base">
                    <span class="mr-2">📥</span> ออกรายงาน Excel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function submitForm(type) {
    const form = document.getElementById('reportForm');
    if (form.module_id.value === "") {
        alert("กรุณาเลือกโมดูลก่อนออกรายงาน");
        return;
    }
    
    // เปลี่ยน Action ตามประเภทที่กด
    if (type === 'pdf') {
        form.action = 'report_pdf.php';
    } else {
        form.action = 'report_excel.php';
    }
    
    form.submit();
}
</script>