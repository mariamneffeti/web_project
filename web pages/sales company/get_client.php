<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    
    if (isset($_GET['name'])) {
        try {
            
            $stmt = $pdo->prepare("SELECT email, client_type, last_purchase_date, total_spent 
                                FROM clients 
                                WHERE client_name = ? AND company_id = ? LIMIT 1");
            $stmt->execute([$_GET['name'], $company_id]);
            $client = $stmt->fetch();

            if ($client) {
                echo json_encode(['status' => 'success', 'data' => $client]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Client not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }