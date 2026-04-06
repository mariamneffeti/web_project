<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

$pdo = getDB();
$company_id = 1;

try {
    $stmt = $pdo->prepare("SELECT SUM(total_amount) as revenue FROM sales WHERE company_id = ? AND payment_status = 'Paid' AND YEAR(sale_date) = ?");
    $stmt->execute([$company_id, date("Y")]);
    $revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

    $stmt = $pdo->prepare("SELECT SUM(amount) as expenses FROM expenses WHERE company_id = ? AND YEAR(expense_date) = ?");
    $stmt->execute([$company_id, date("Y")]);
    $expenses = $stmt->fetch(PDO::FETCH_ASSOC)['expenses'] ?? 0;

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM sales WHERE company_id = ? AND YEAR(sale_date) = ?");
    $stmt->execute([$company_id, date("Y")]);
    $salesCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo json_encode([
        'status' => 'success',
        'data' => [
            'revenue' => floatval($revenue),
            'expenses' => floatval($expenses),
            'profit' => floatval($revenue - $expenses),
            'salesCount' => intval($salesCount)
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}