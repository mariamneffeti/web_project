<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $db = getDB();
    $stmt = $db->query("
        SELECT a.id, a.title, a.category, a.date, a.description, 
               a.link, a.image, a.author_name, c.company_name
        FROM articles a
        JOIN companies c ON a.company_id = c.id
        ORDER BY a.created_at DESC
        LIMIT 6
    ");
    echo json_encode(['data' => $stmt->fetchAll()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
