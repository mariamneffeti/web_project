<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    try {
        $q = $_GET['q'] ?? '';

        if ($q === '') {
            $stmt = $pdo->query("
                SELECT id, product_name, price 
                FROM products 
                WHERE company_id = $company_id
                ORDER BY product_name ASC 
                LIMIT 10
            ");
        } else {
            $stmt = $pdo->prepare("
                SELECT id, product_name, price 
                FROM products 
                WHERE product_name LIKE ?  AND company_id = ?
                ORDER BY product_name ASC 
                LIMIT 10
            ");
            $stmt->execute(["%$q%", $company_id]);
        }

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($products);

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }