<?php
    require_once '../../config/database.php';
    require_once '../../config/session_check.php';

    header('Content-Type: application/json');

    $db     = getDB();
    $action = $_GET['action'] ?? '';

    $stmt = $db->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $row  = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { echo json_encode(['error' => 'Company not found']); exit; }
    $company_id = $row['id'];

    if (!$row) {
        echo json_encode(['error' => 'Company not found']);
        exit;
    }

    $company_id = $row['id'];

    switch ($action) {
        case 'get_products':
            $stmt = $db->prepare("
                SELECT id, product_name, sku, price, stock_quantity, category, description
                FROM products
                WHERE company_id = ?
                ORDER BY product_name ASC
            ");
            $stmt->execute([$company_id]);

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;
        case 'get_product':
            $id = intval($_GET['id'] ?? 0);

            if (!$id) {
                echo json_encode([]);
                break;
            }

            $stmt = $db->prepare("
                SELECT *
                FROM products
                WHERE id = ? AND company_id = ?
            ");
            $stmt->execute([$id, $company_id]);

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode($product ?: []);
            break;
        case 'add_product':
            $body = json_decode(file_get_contents('php://input'), true);

            $name = trim($body['product_name'] ?? '');
            $sku  = trim($body['sku'] ?? '');
            $cat  = trim($body['category'] ?? '');
            $price = floatval($body['price'] ?? 0);
            $qty   = intval($body['stock_quantity'] ?? 0);
            $desc  = trim($body['description'] ?? '');
            $threshold = intval($body['min_threshold'] ?? 20);

            if (!$name) {
                echo json_encode(['success' => false, 'error' => 'Product name is required']);
                break;
            }

            try {
                $stmt = $db->prepare("
                    INSERT INTO products 
                    (company_id, product_name, sku, category, price, stock_quantity, min_threshold, description)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([$company_id, $name, $sku, $cat, $price, $qty, $threshold, $desc]);

                echo json_encode([
                    'success' => true,
                    'id' => $db->lastInsertId()
                ]);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
        case 'update_product':
            $body = json_decode(file_get_contents('php://input'), true);

            $id   = intval($body['id'] ?? 0);
            $name = trim($body['product_name'] ?? '');

            if (!$id || !$name) {
                echo json_encode(['success' => false, 'error' => 'Invalid data']);
                break;
            }

            $check = $db->prepare("SELECT id FROM products WHERE id = ? AND company_id = ?");
            $check->execute([$id, $company_id]);

            if (!$check->fetch()) {
                echo json_encode(['success' => false, 'error' => 'Product not found']);
                break;
            }

            try {
                $stmt = $db->prepare("
                    UPDATE products SET
                        product_name = ?,
                        sku = ?,
                        category = ?,
                        price = ?,
                        stock_quantity = ?,
                        min_threshold = ?,
                        description = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $name,
                    $body['sku'] ?? '',
                    $body['category'] ?? '',
                    $body['price'] ?? 0,
                    $body['stock_quantity'] ?? 0,
                    $body['min_threshold'] ?? 20,
                    $body['description'] ?? '',
                    $id
                ]);

                echo json_encode(['success' => true]);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
        case 'delete_product':
            $body = json_decode(file_get_contents('php://input'), true);
            $id   = intval($body['id'] ?? 0);

            if (!$id) {
                echo json_encode(['success' => false, 'error' => 'Invalid id']);
                break;
            }

            $check = $db->prepare("SELECT id FROM products WHERE id = ? AND company_id = ?");
            $check->execute([$id, $company_id]);

            if (!$check->fetch()) {
                echo json_encode(['success' => false, 'error' => 'Not found']);
                break;
            }

            $db->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);

            echo json_encode(['success' => true]);
            break;
        case 'get_categories':
            $stmt = $db->prepare("
                SELECT DISTINCT category 
                FROM products 
                WHERE company_id = ? AND category IS NOT NULL AND category != ''
                ORDER BY category ASC
            ");
            $stmt->execute([$company_id]);

            echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
            break;
        default:
            echo json_encode(['error' => 'Unknown action: ' . htmlspecialchars($action)]);
    }