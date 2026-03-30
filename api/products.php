<?php
require_once '../config/database.php';
require_once '../config/session.php';

header('Content-Type: application/json');

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
            $stmt = $pdo->prepare("SELECT id, product_name, sku, price, stock_quantity, category, description 
                                FROM products 
                                WHERE company_id = ? 
                                ORDER BY product_name ASC");
            $stmt->execute([$company_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $products]);
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
    case 'update':
        $id = $_GET['id'] ?? 0;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$id || empty($data['product_name'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            break;
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET product_name = :name, 
                    sku = :sku, 
                    category = :cat, 
                    price = :price, 
                    stock_quantity = :qty, 
                    min_threshold = :threshold, 
                    description = :desc
                WHERE id = :id AND company_id = :company_id
            ");

            $success = $stmt->execute([
                'name'       => $data['product_name'],
                'sku'        => $data['sku'],
                'cat'        => $data['category'],
                'price'      => $data['price'],
                'qty'        => $data['stock_quantity'],
                'threshold'  => $data['min_threshold'] ?? 20,
                'desc'       => $data['description'],
                'id'         => $id,
                'company_id' => $company_id
            ]);

            echo json_encode(['success' => $success, 'message' => $success ? 'Updated' : 'No changes made']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}