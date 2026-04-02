<?php
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = $_POST['date'] ?? '';
        $type = $_POST['type'] ?? '';
        $amount = $_POST['amount'] ?? 0;
        $entity = $_POST['entity'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Transaction added successfully !',
            'data' => [
                'date' => $date,
                'type' => $type,
                'amount' => $amount,
                'entity' => $entity,
                'notes' => $notes
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    }
