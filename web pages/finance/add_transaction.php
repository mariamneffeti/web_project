<?php
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $company_id = 1; 
        $employee_id = 1;

        $date = $_POST['date'] ?? '';
        $category = $_POST['type'] ?? '';
        $amount = floatval($_POST['amount'] ?? 0);
        $description = $_POST['description'] ?? '';

        if (!$date || !$category || !$amount) {
            echo json_encode(['status' => 'error', 'message' => 'Missing data']);
            exit;
        }
        if ($amount <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Amount must be positive']);
            exit;
        }
        
        try {
            $pdo->beginTransaction();
            $query = "INSERT INTO expenses (company_id, expense_date, category, amount, description) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$company_id, $date, $category, $amount, $description]);
            $pdo->commit();

            echo json_encode([
            'status' => 'success',
            'message' => 'Transaction added successfully !',
        ]);
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error adding transaction']);
            exit;
        }
    }
