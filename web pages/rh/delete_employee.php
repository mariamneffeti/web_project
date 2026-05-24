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
    $id  = $_GET['id'];
    $empStmt = $pdo->prepare("SELECT email FROM employees WHERE id = ?");
    $empStmt->execute([$id]);
    $employee = $empStmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Employee not found'
            ]);
            exit;
        }
        
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $deleteUser = $pdo->prepare("DELETE FROM users WHERE email = ?");
    $deleteUser->execute([$employee['email']]);
 
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
             'message' => 'Employee deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Employee not found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()]);
}
}