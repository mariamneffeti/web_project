<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    try {
        $database = Database::getInstance();
        $pdo = $database->getConnection();

        $stmt1 = $pdo->query("
            SELECT SUM(total_amount) as total
            FROM sales
            WHERE MONTH(sale_date) = MONTH(CURDATE())
            AND YEAR(sale_date) = YEAR(CURDATE())
        ");
        $monthlyRevenue = $stmt1->fetch()['total'] ?? 0;

        $stmt2 = $pdo->query("
            SELECT COUNT(DISTINCT client_id) as total
            FROM sales
            WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ");
        $activeClients = $stmt2->fetch()['total'] ?? 0;

        $stmt3 = $pdo->query("
            SELECT SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) as paid,
                COUNT(*) as total
            FROM sales
        ");
        $data = $stmt3->fetch();

        $conversionRate = $data['total'] > 0 
            ? ($data['paid'] / $data['total']) * 100 
            : 0;

        echo json_encode([
            'status' => 'success',
            'data' => [
                'monthlyRevenue' => $monthlyRevenue,
                'activeClients' => $activeClients,
                'conversionRate' => round($conversionRate, 2)
            ]
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }