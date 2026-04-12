<?php

session_start();
require_once __DIR__ . '/database.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['user_id'])) {
    header("Location: /../web pages/login/login.php");
    exit();
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role, image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser) {
    session_destroy();
    header("Location: /../web pages/login/login.php");
    exit();
}
?>