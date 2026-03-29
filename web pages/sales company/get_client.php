<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    if (isset($_GET['name'])) {
        try {
            $pdo = Database::getInstance()->getConnection();
            
            $stmt = $pdo->prepare("SELECT email, client_type, last_purchase_date, total_spent 
                                FROM clients 
                                WHERE client_name = ? LIMIT 1");
            $stmt->execute([$_GET['name']]);
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