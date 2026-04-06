<?php
    require_once __DIR__ . '/../../config/database.php';
    $pdo = Database::getInstance()->getConnection();

    $q = $_GET['q'] ?? '';

    $stmt = $pdo->prepare("
        SELECT id, client_name 
        FROM clients 
        WHERE client_name LIKE ?
        ORDER BY client_name ASC
        LIMIT 10
    ");

    $stmt->execute(["%$q%"]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));