<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $database = Database::getInstance();
    $db = $database->getConnection();

    $stmt = $db->query("
        SELECT 
            jo.id,
            jo.title,
            jo.location,
            jo.type,
            jo.category AS cat,
            jo.salary_min AS salaryMin,
            jo.salary_max AS salaryMax,
            jo.experience_level AS exp,
            jo.tags,
            jo.description AS `desc`,
            c.company_name AS company,
            ji.bootstrap_class AS icon,
            ji.default_color AS iconColor
        FROM job_offers jo
        LEFT JOIN companies c  ON jo.company_id = c.id
        LEFT JOIN job_icons ji ON jo.icon_id = ji.id
        WHERE jo.status = 'active'
        ORDER BY jo.created_at DESC
    ");

    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($offers as &$offer) {
        $offer['tags'] = array_map('trim', explode(',', $offer['tags'] ?? ''));
    }

    echo json_encode([
        'status' => 'success',
        'data' => $offers
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}