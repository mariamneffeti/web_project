<?php
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
        case 'list':
            $stmt = $db->prepare("
                SELECT i.*, s.total_amount, s.transaction_id, c.client_name 
                FROM invoices i
                JOIN sales s ON i.sale_id = s.id
                JOIN clients c ON s.client_id = c.id
                WHERE s.company_id = :company_id
                ORDER BY i.issue_date DESC
            ");
            $stmt->execute(['company_id' => $user['company_id']]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
        case 'get_details':
            $invoiceId = $_GET['id'] ?? 0;
            $stmt = $db->prepare("
                SELECT i.*, s.*, c.client_name, c.address, c.email as client_email
                FROM invoices i
                JOIN sales s ON i.sale_id = s.id
                JOIN clients c ON s.client_id = c.id
                WHERE i.id = :id AND s.company_id = :company_id
            ");
            $stmt->execute(['id' => $invoiceId, 'company_id' => $user['company_id']]);
            $invoice = $stmt->fetch();

            if (!$invoice) {
                http_response_code(404);
                echo json_encode(['error' => 'Invoice not found']);
                exit();
            }
            $stmtItems = $db->prepare("SELECT * FROM sale_items WHERE sale_id = ?");
            $stmtItems->execute([$invoice['sale_id']]);
            $invoice['items'] = $stmtItems->fetchAll();
            
            echo json_encode(['success' => true, 'data' => $invoice]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}