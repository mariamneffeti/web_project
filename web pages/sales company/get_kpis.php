<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    try {

        $stmt1 = $pdo->query("
            SELECT SUM(total_amount) as total
            FROM sales
            WHERE MONTH(sale_date) = MONTH(CURDATE())
            AND YEAR(sale_date) = YEAR(CURDATE()) AND payment_status = 'Paid' AND company_id = $company_id
        ");
        $monthlyRevenue = $stmt1->fetch()['total'] ?? 0;

        $stmt2 = $pdo->query("
            SELECT COUNT(DISTINCT client_id) as total
            FROM sales
            WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND payment_status = 'Paid' AND company_id = $company_id
        ");
        $activeClients = $stmt2->fetch()['total'] ?? 0;

        $stmt3 = $pdo->query("
            SELECT SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) as paid,
                COUNT(*) as total
            FROM sales 
            WHERE company_id = $company_id
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