<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    try {
        $pdo = Database::getInstance()->getConnection();

        $q = $_GET['q'] ?? '';

        if ($q === '') {
            $stmt = $pdo->query("
                SELECT id, product_name, price 
                FROM products 
                ORDER BY product_name ASC 
                LIMIT 10
            ");
        } else {
            $stmt = $pdo->prepare("
                SELECT id, product_name, price 
                FROM products 
                WHERE product_name LIKE ? 
                ORDER BY product_name ASC 
                LIMIT 10
            ");
            $stmt->execute(["%$q%"]);
        }

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($products);

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }