<?php
session_start();
require '../config/config.php';

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_layout.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 🔐 ดึงเฉพาะ admin + active
    $stmt = $pdo->prepare("
        SELECT id, username, password, role 
        FROM users
        WHERE username = ?
          AND role = 'admin'
          AND is_active = 1
        LIMIT 1
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        header("Location: admin_layout.php");
        exit;
    } else {
        $error = "บัญชีนี้ไม่ใช่ผู้ดูแลระบบ หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>


<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>เข้าสู่ระบบแอดมิน</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<div class="min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-3xl bg-white rounded-xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <!-- ✅ ส่วนหัวสีกรมท่า -->
    <div class="p-8 bg-gradient-to-b from-blue-700 to-blue-600 text-white flex flex-col justify-center">
      <img src="../img/logo.png" class="w-16 mx-auto mb-8" alt="logo">
      <h1 class="text-2xl font-bold">ระบบสารสนเทศเพื่อการศึกษา</h1>
      <p class="mt-2 text-sm">สำนักงานส่งเสริมการเรียนรู้จังหวัดนครปฐม</p>
    </div>

    <!-- ✅ แบบฟอร์ม Login -->
    <div class="p-8">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">เข้าสู่ระบบ</h2>

      <?php if($error): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
          <?= htmlspecialchars($error); ?>
      </div>
      <?php endif; ?>

      <form method="post" class="space-y-4">
        <input name="username" placeholder="ชื่อผู้ใช้" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>

        <input name="password" type="password" placeholder="รหัสผ่าน" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>

        <div class="flex justify-end">
          <button class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition">
            เข้าสู่ระบบแอดมิน
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
</body>
</html>
