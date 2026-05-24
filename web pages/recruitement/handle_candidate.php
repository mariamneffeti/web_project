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
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM cv_applications WHERE id = ?");
        $stmt->execute([$cv_id]);
        $cv = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmtoffre = $pdo->prepare("SELECT * FROM job_offers WHERE id = ?");
        $stmtoffre->execute([$cv['offre_id']]);
        $jobOffer = $stmtoffre->fetch(PDO::FETCH_ASSOC);

        if (!$cv) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Candidate not found']);
            exit;
        }

        if ($action === "delete") {
            $stmt = $pdo->prepare("DELETE FROM cv_applications WHERE id = ?");
            $stmt->execute([$cv_id]);

            $pdo->commit();
            echo json_encode(['status' => 'success']);
            exit;
        }

        $currentStatus = $cv['status'];
        if (in_array($currentStatus, ['Accepted', 'Rejected'])) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'This action cannot be changed.'
            ]);
            exit;
        }

        if ($currentStatus === 'Reviewed' && $action === 'contact') {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Candidate already contacted.'
            ]);
            exit;
        }

        $status = null;

        if ($action === "accept"){
            $status = "Accepted";
            
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$cv['first_name'], $cv['last_name'], $cv['email'], password_hash('password123', PASSWORD_DEFAULT), 'employee']);
            
            $stmt = $pdo->prepare("INSERT INTO employees (user_id, company_id, first_name, last_name, hire_date, salary, email, cv_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$pdo->lastInsertId(), $company_id, $cv['first_name'], $cv['last_name'], date('Y-m-d'), $jobOffer['salary_min'], $cv['email'], $cv['cv_path']]);
        } 
        if ($action === "reject") $status = "Rejected";
        if ($action === "contact") $status = "Reviewed";

        if ($status) {
            $stmt = $pdo->prepare("UPDATE cv_applications SET status = ? WHERE id = ?");
            $stmt->execute([$status, $cv_id]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
    }
    exit;