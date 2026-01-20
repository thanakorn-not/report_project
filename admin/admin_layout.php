<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once '../config/config.php';

// ✅ ตรวจสอบสิทธิ์ผู้ใช้
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ระบบผู้ดูแล | สำนักงานส่งเสริมการเรียนรู้</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'IBM Plex Sans Thai', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 text-gray-800 flex min-h-screen">

  <!-- ✅ Sidebar -->
  <?php include '../includes/admin_sidebar.php'; ?>

  <!-- ✅ ส่วนเนื้อหาหลัก -->
  <main class="flex-1 flex flex-col lg:ml-64 pt-16 lg:pt-0">

    <header class="bg-gradient-to-r from-blue-700 to-blue-900 text-white py-4 md:py-6 px-4 md:px-8 shadow">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl md:text-2xl font-semibold">ผู้ดูแลระบบ</h1>
          <p class="text-blue-200 text-xs md:text-sm mt-1">สำนักงานส่งเสริมการเรียนรู้ จังหวัดนครปฐม</p>
        </div>
        <div class="text-xs md:text-sm text-blue-100">
          👤 <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
        </div>
      </div>
    </header>

    <section class="p-4 md:p-8 flex-1">
      <?php
      $page = isset($_GET['admin_content']) ? $_GET['admin_content'] : 'dashboard_content.php';
      $file = __DIR__ . "/admin_content/" . basename($page);

      if (file_exists($file)) {
        include $file;
      } else {
        echo "<p class='text-red-600'>❌ ไม่พบหน้า: " . htmlspecialchars($page) . "</p>";
      }
      ?>
    </section>

    <?php include '../includes/footer.php'; ?>
  </main>
</body>

</html>