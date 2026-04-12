<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    $q = $_GET['q'] ?? '';

    $stmt = $pdo->prepare("
        SELECT id, client_name 
        FROM clients 
        WHERE client_name LIKE ? AND company_id = ?
        ORDER BY client_name ASC
        LIMIT 10
    ");

    $stmt->execute(["%$q%", $company_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));