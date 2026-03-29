<?php
require_once '../config/database.php';
require_once '../config/session.php';

header('Content-Type: application/json');

// Check if user is authenticated
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$pdo = getDB();
$user = getCurrentUser();
$action = $_GET['action'] ?? '';
$company_id = $user['company_id'] ?? null;
if (!$company_id) {
    echo json_encode(['success' => false, 'message' => 'No company associated with this user']);
    exit();
}


switch ($action) {
    case 'list':
        try {
            // Fetch products specifically for this employee's company
            $stmt = $pdo->prepare("SELECT id, product_name, price, stock_quantity, category 
                                   FROM products 
                                   WHERE company_id = ? 
                                   ORDER BY product_name ASC");
            $stmt->execute([$company_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $products
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'get':
        $id = $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND company_id = ?");
            $stmt->execute([$id, $company_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                echo json_encode(['success' => true, 'data' => $product]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}