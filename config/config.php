<?php
date_default_timezone_set('Asia/Bangkok');
$DB_HOST = '127.0.0.1';
$DB_NAME = 'edu_center';
$DB_USER = 'root';
$DB_PASS = '';
$DSN = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
try {
    $pdo = new PDO($DSN, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die('DB Connection failed: ' . htmlspecialchars($e->getMessage()));
}
if (!session_id()) session_start();
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
