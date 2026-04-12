<?php
require_once '../../config/database.php';
require_once '../../config/session_check.php';
 
header('Content-Type: application/json');
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}
 
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
 
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    exit;
}
 
try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);
 
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Employee deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Employee not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}