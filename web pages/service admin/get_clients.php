<?php
    require_once '../../config/database.php';
    require_once '../../config/session_check.php';

    header('Content-Type: application/json');

    $db=getDB();
    $stmt = $db->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    $stmt = $db->prepare("SELECT id,client_name , email FROM clients WHERE company_id = ? ORDER BY client_name ASC");
    $stmt->execute([$company_id]);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clients);