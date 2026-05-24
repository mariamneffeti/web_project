<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$loginUrl = '/WEB_PROJECT/web%20pages/login/login.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . $loginUrl);
    exit();
}

$pdo  = getDB();

$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role, image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser) {
    session_destroy();
    header("Location: " . $loginUrl);
    exit();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WEB_PROJECT/web%20pages/');
}

function requireRole($roles) {
    global $currentUser;
    $roles = (array)$roles;
    if (!in_array($currentUser['role'], $roles)) {
        header("Location: /WEB_PROJECT/web%20pages/home/home.php");
        exit();
    }
}
?>