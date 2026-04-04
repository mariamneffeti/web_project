<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    try {
        $pdo = getDB();

        $year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

        $stmt = $pdo->prepare("
            SELECT MONTH(sale_date) AS month, SUM(total_amount) AS total_sales
            FROM sales
            WHERE YEAR(sale_date) = :year AND payment_status = 'Paid'
            GROUP BY MONTH(sale_date)
            ORDER BY MONTH(sale_date)
        ");

        $stmt->execute(['year' => $year]);
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