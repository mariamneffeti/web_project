<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    try {
        $pdo = Database::getInstance()->getConnection();

        $q = $_GET['q'] ?? '';

        if ($q === '') {
            $stmt = $pdo->query("
                SELECT id, service_name, base_price 
                FROM services 
                ORDER BY service_name ASC 
                LIMIT 10
            ");
        } else {
            $stmt = $pdo->prepare("
                SELECT id, service_name, base_price 
                FROM services 
                WHERE service_name LIKE ? 
                ORDER BY service_name ASC 
                LIMIT 10
            ");
            $stmt->execute(["%$q%"]);
        }

        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($services);

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }