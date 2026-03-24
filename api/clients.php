<?php
/**
 * Clients API
 * 
 * Handles client management operations
 */

require_once '../config/database.php';
require_once '../config/session.php';

header('Content-Type: application/json');

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
        
        // GET ALL CLIENTS
        case 'list':
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            
            $query = "SELECT * FROM clients WHERE company_id = :company_id";
            $params = ['company_id' => $user['company_id']];
            
            if ($search) {
                $query .= " AND (client_name LIKE :search OR email LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            if ($status) {
                $query .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $query .= " ORDER BY client_name ASC";
            
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $clients = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'data' => $clients]);
            break;
        
        // GET SINGLE CLIENT
        case 'get':
            $clientId = $_GET['id'] ?? 0;
            
            $stmt = $db->prepare("SELECT * FROM clients WHERE id = :id AND company_id = :company_id");
            $stmt->execute(['id' => $clientId, 'company_id' => $user['company_id']]);
            $client = $stmt->fetch();
            
            if (!$client) {
                http_response_code(404);
                echo json_encode(['error' => 'Client not found']);
                exit();
            }
            
            echo json_encode(['success' => true, 'data' => $client]);
            break;
        
        // CREATE CLIENT
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit();
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['client_name']) || empty($data['client_name'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Client name is required']);
                exit();
            }
            
            $stmt = $db->prepare("
                INSERT INTO clients (company_id, client_name, email, phone, address, client_type, status)
                VALUES (:company_id, :client_name, :email, :phone, :address, :client_type, :status)
            ");
            
            $stmt->execute([
                'company_id' => $user['company_id'],
                'client_name' => $data['client_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'client_type' => $data['client_type'] ?? 'B2C',
                'status' => $data['status'] ?? 'Active'
            ]);
            
            $clientId = $db->lastInsertId();
            
            echo json_encode([
                'success' => true,
                'message' => 'Client created successfully',
                'client_id' => $clientId
            ]);
            break;
        
        // UPDATE CLIENT
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit();
            }
            
            $clientId = $_GET['id'] ?? 0;
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Verify ownership
            $stmt = $db->prepare("SELECT * FROM clients WHERE id = :id AND company_id = :company_id");
            $stmt->execute(['id' => $clientId, 'company_id' => $user['company_id']]);
            if (!$stmt->fetch()) {
                http_response_code(404);
                echo json_encode(['error' => 'Client not found']);
                exit();
            }
            
            $stmt = $db->prepare("
                UPDATE clients 
                SET client_name = :client_name, email = :email, phone = :phone,
                    address = :address, client_type = :client_type, status = :status
                WHERE id = :id
            ");
            
            $stmt->execute([
                'id' => $clientId,
                'client_name' => $data['client_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'client_type' => $data['client_type'] ?? 'B2C',
                'status' => $data['status'] ?? 'Active'
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
            break;
        
        // DELETE CLIENT
        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit();
            }
            
            $clientId = $_GET['id'] ?? 0;
            
            // Verify ownership
            $stmt = $db->prepare("SELECT * FROM clients WHERE id = :id AND company_id = :company_id");
            $stmt->execute(['id' => $clientId, 'company_id' => $user['company_id']]);
            if (!$stmt->fetch()) {
                http_response_code(404);
                echo json_encode(['error' => 'Client not found']);
                exit();
            }
            
            // Check if client has sales
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM sales WHERE client_id = :id");
            $stmt->execute(['id' => $clientId]);
            if ($stmt->fetch()['count'] > 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Cannot delete client with existing sales']);
                exit();
            }
            
            $stmt = $db->prepare("DELETE FROM clients WHERE id = :id");
            $stmt->execute(['id' => $clientId]);
            
            echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
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
