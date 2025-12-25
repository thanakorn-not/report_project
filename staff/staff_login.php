<?php
session_start();
require '../config/config.php';

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'staff') {
    header("Location: staff_dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 🔐 ดึงเฉพาะ staff + active
    $stmt = $pdo->prepare("
    SELECT 
        u.id,
        u.username,
        u.password,
        u.role,
        u.district_id,
        d.district_name
    FROM users u
    LEFT JOIN districts d ON u.district_id = d.id
    WHERE u.username = ?
      AND u.role = 'staff'
      AND u.is_active = 1
    LIMIT 1
");

    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['username']   = $user['username'];
        $_SESSION['role']       = $user['role'];
        $_SESSION['district_id'] = $user['district_id'];
        $_SESSION['district_name'] = $user['district_name'];

        header("Location: staff_dashboard.php");
        exit;
    } else {
        $error = "บัญชีนี้ไม่ใช่เจ้าหน้าที่ หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>เข้าสู่ระบบเจ้าหน้าที่</title>
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

                <?php if ($error): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="space-y-4">
                    <input name="username" placeholder="ชื่อผู้ใช้" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                    <input name="password" type="password" placeholder="รหัสผ่าน" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                    <div class="flex justify-end">
                        <button class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition">
                            เข้าสู่ระบบเจ้าหน้าที่
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>