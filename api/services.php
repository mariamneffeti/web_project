<?php
/**
 * Services API
 */

require_once '../config/database.php';
require_once '../config/session.php';

header('Content-Type: application/json');

// Check if user is authenticated
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$db = getDB();
$user = getCurrentUser();
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        // GET ALL SERVICES FOR THE COMPANY
        case 'list':
            $stmt = $db->prepare("
                SELECT id, service_name, base_price 
                FROM services 
                WHERE company_id = :company_id 
                ORDER BY service_name ASC
            ");
            $stmt->execute(['company_id' => $user['company_id']]);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $services
            ]);
            break;

        // GET SINGLE SERVICE
        case 'get':
            $id = $_GET['id'] ?? 0;
            $stmt = $db->prepare("SELECT * FROM services WHERE id = :id AND company_id = :company_id");
            $stmt->execute(['id' => $id, 'company_id' => $user['company_id']]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$service) {
                http_response_code(404);
                echo json_encode(['error' => 'Service not found']);
            } else {
                echo json_encode(['success' => true, 'data' => $service]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>