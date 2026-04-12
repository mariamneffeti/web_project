<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    try {
        $year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

        $stmt = $pdo->prepare("
            SELECT MONTH(sale_date) AS month, SUM(total_amount) AS total_sales
            FROM sales
            WHERE YEAR(sale_date) = :year AND payment_status = 'Paid' AND company_id = :company_id  
            GROUP BY MONTH(sale_date)
            ORDER BY MONTH(sale_date)
        ");

        $stmt->execute(['year' => $year, 'company_id' => $company_id]);
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $sales
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }