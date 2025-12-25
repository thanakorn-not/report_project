<?php
session_start();

// ✅ โหลด config + เชื่อมฐานข้อมูล
require_once __DIR__ . "/config/config.php";

// ✅ ดึงจำนวนข้อมูล
$userCount    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$moduleCount  = $pdo->query("SELECT COUNT(*) FROM modules")->fetchColumn();
$studentCount = 0;
$totalModules = 17; 

for ($i = 1; $i <= $totalModules; $i++) {
    $tableName = "records_module" . $i;
    
    // ตรวจสอบว่าตารางมีอยู่จริงหรือไม่ก่อน query (ป้องกัน Error)
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM {$tableName}")->fetchColumn();
        $studentCount += (int)$count; 
    } catch (PDOException $e) {
        // ถ้าตารางไม่มีอยู่ (เช่น records_module5 ยังไม่ได้สร้าง) จะข้ามไป
        continue; 
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ระบบสารสนเทศเพื่อการศึกษา | สกร.นครปฐม</title>
  <link rel="icon" type="image/png" href="/php_records_project/img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>

<body class="bg-gray-100 flex min-h-screen">

  <!-- ✅ Sidebar -->
  <?php include "includes/sidebar_public.php"; ?>

  <!-- ✅ เนื้อหาหลัก -->
  <main class="flex-1 flex flex-col">
    
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-700 to-blue-900 text-white text-center py-10 shadow-md">
      <h1 class="text-3xl font-bold">ระบบสารสนเทศเพื่อการศึกษา</h1>
      <p class="text-blue-100 mt-2">สำนักงานส่งเสริมการเรียนรู้ จังหวัดนครปฐม</p>
    </header>

    <!-- Content -->
    <section class="flex-grow max-w-5xl mx-auto mt-10 px-6">

      <!-- ✅ กล่องข้อมูลระบบ -->
      <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-800">
        <h2 class="text-2xl font-semibold text-blue-800 mb-4">ℹ️ เกี่ยวกับระบบ</h2>
        <p class="text-gray-600 leading-relaxed">
          ✅ ระบบสำหรับจัดเก็บข้อมูลนักเรียนในจังหวัดนครปฐม <br>
          ✅ รองรับการบันทึกข้อมูลมากกว่า 17 โมดูล <br>
          ✅ สามารถออกรายงานในรูปแบบ PDF และ Excel <br>
          ✅ ผู้ดูแลระบบและเจ้าหน้าที่สามารถจัดการข้อมูลได้ในระบบนี้
        </p>
      </div>

      <!-- ✅ ปุ่มต่างๆ -->
      <div class="mt-8 bg-white rounded-2xl shadow-lg p-8 border-t-4 border-green-700 text-center">
        <h2 class="text-xl font-semibold text-green-700 mb-6">เริ่มต้นใช้งานระบบ</h2>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="admin/admin_login.php" class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-3 rounded-lg font-medium shadow-md transition">
            🔑 เข้าสู่ระบบแอดมิน
          </a>

          <a href="staff/staff_login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium shadow-md transition">
            📌 เข้าสู่ระบบเจ้าหน้าที่
          </a>

          
        </div>
      </div>

      <!-- ✅ สถิติข้อมูล -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-10">
        <div class="bg-white shadow p-6 rounded-xl border-t-4 border-blue-700 text-center">
          <h3 class="text-lg font-bold text-blue-700">ผู้ใช้งานทั้งหมด</h3>
          <p class="text-3xl font-semibold mt-2"><?= $userCount ?></p>
        </div>

        <div class="bg-white shadow p-6 rounded-xl border-t-4 border-green-700 text-center">
          <h3 class="text-lg font-bold text-green-700">ข้อมูลนักเรียน</h3>
          <p class="text-3xl font-semibold mt-2"><?= $studentCount ?></p>
        </div>

        <div class="bg-white shadow p-6 rounded-xl border-t-4 border-purple-700 text-center">
          <h3 class="text-lg font-bold text-purple-700">โมดูลทั้งหมด</h3>
          <p class="text-3xl font-semibold mt-2"><?= $moduleCount ?></p>
        </div>
      </div>

    </section>

    <!-- ✅ Footer -->
    <?php include "includes/footer.php"; ?>

  </main>
</body>
</html>
