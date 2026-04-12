<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    header('Content-Type: application/json');

    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
