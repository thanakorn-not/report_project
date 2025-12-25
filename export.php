<?php
// Simple CSV export respecting district ACL
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$cfg = include __DIR__ . '/../config.php';
$pdo = new PDO($cfg['db_dsn'], $cfg['db_user'], $cfg['db_pass']);
$user = $_SESSION['user'];

$params = [];
$sql = 'SELECT r.*, m.name as module_name FROM records r JOIN modules m ON m.id = r.module_id WHERE 1=1 ';
if ($user['role'] !== 'admin') {
    $sql .= ' AND r.district = ? ';
    $params[] = $user['district'];
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=records_export.csv');
$out = fopen('php://output', 'w');
fputcsv($out, ['id','module','district','term','student','school','level','created_at']);

foreach ($rows as $r) {
    fputcsv($out, [
        $r['id'],
        $r['module_name'],
        $r['district'],
        $r['term'],
        $r['student_prefix'].' '.$r['student_firstname'].' '.$r['student_lastname'],
        $r['school'],
        $r['level'],
        $r['created_at']
    ]);
}
fclose($out);
exit;
