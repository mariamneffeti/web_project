<?php
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';

    header('Content-Type: application/json');

    $pdo = getDB();

    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$currentUser['id']]);
    $company_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'] ?? null;

    if (!$company_id) {
        echo json_encode(['status' => 'error', 'message' => 'Company not found']);
        exit;
    }

    $action = $_POST['action'] ?? '';
    $cv_id = (int)($_POST['cv_id'] ?? 0);

    if (!$cv_id || !$action) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }
    try{
        $stmt = $pdo->prepare("SELECT * FROM cv_applications WHERE id = ?");
        $stmt->execute([$cv_id]);
        $cv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cv) {
            echo json_encode(['status' => 'error', 'message' => 'Candidate not found']);
            exit;
        }

        if ($action === "delete") {
            $stmt = $pdo->prepare("DELETE FROM cv_applications WHERE id = ?");
            $stmt->execute([$cv_id]);

            echo json_encode(['status' => 'success']);
            exit;
        }

        $currentStatus = $cv['status'];
        if (in_array($currentStatus, ['Accepted', 'Rejected'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'This action cannot be changed.'
            ]);
            exit;
        }

        if ($currentStatus === 'Reviewed' && $action === 'contact') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Candidate already contacted.'
            ]);
            exit;
        }

        $status = null;

        if ($action === "accept") $status = "Accepted";
        if ($action === "reject") $status = "Rejected";
        if ($action === "contact") $status = "Reviewed";

        if ($status) {
            $stmt = $pdo->prepare("UPDATE cv_applications SET status = ? WHERE id = ?");
            $stmt->execute([$status, $cv_id]);
        }

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
    }
    exit;
    