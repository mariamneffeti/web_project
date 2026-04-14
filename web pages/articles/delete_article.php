<?php
require_once '../../config/database.php';
require_once '../../config/session_check.php';
 
header('Content-Type: application/json');
 if (!isset($_GET['id'])) {
    die("ID missing");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$_GET['id']]);
 
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
             'message' => 'Article deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Article not found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()]);
}
}