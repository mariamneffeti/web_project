<?php

session_start();
require_once __DIR__ . '/database.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");


$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$docRoot     = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);


$basePath = str_replace($docRoot, '', $projectRoot) . '/web pages';


$loginUrl = $basePath . '/login/login.php';

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
    define('BASE_URL', $basePath . '/');
}

function requireRole($roles) {
    global $currentUser, $basePath;
    $roles = (array)$roles;

    if (!in_array($currentUser['role'], $roles)) {
        header("Location: " . $basePath . "/home/home.php");
        exit();
    }
}
?>