<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    $year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

    try {
        $stmt = $pdo->prepare("
            SELECT MONTH(sale_date) as month, SUM(total_amount) as total
            FROM sales
            WHERE company_id = ? AND YEAR(sale_date) = ? AND payment_status = 'Paid'
            GROUP BY MONTH(sale_date)
        ");
        $stmt->execute([$company_id, $year]);
        $revenues = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("
            SELECT MONTH(expense_date) as month, SUM(amount) as total
            FROM expenses
            WHERE company_id = ? AND YEAR(expense_date) = ?
            GROUP BY MONTH(expense_date)
        ");
        $stmt->execute([$company_id, $year]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'revenues' => $revenues,
                'expenses' => $expenses
            ]
        ]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }