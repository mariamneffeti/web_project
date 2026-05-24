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

    //add offer
    if ($action === 'add') {

        $title = trim($_POST['title']);
        $location = trim($_POST['location']);
        $category = $_POST['category'];
        $type = $_POST['type'];
        $icon_id = $_POST['icon_id'] ?? null;
        $description = trim($_POST['description']);
        $salary_min = $_POST['salary_min'] !== '' ? (int)$_POST['salary_min'] : null;
        $salary_max = $_POST['salary_max'] !== '' ? (int)$_POST['salary_max'] : null;
        $experience_level = $_POST['experience_level'];
        $tags = $_POST['tags'] ?? null;

        if (!$title || !$location || !$description) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            exit;
        }

        if ($salary_min !== null && $salary_min < 500) {
            echo json_encode(['status' => 'error', 'message' => 'Min salary must be ≥ 500']);
            exit;
        }

        if ($salary_max !== null && $salary_min !== null && $salary_max <= $salary_min) {
            echo json_encode(['status' => 'error', 'message' => 'Max salary must be greater than min']);
            exit;
        }

        try {
            $query = "INSERT INTO job_offers 
            (company_id, icon_id, title, location, type, category, salary_min, salary_max, experience_level, description, tags, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $company_id,
                $icon_id,
                htmlspecialchars($title),
                htmlspecialchars($location),
                $type,
                $category,
                $salary_min,
                $salary_max,
                $experience_level,
                htmlspecialchars($description),
                $tags
            ]);

            $stmtIcon = $pdo->prepare("SELECT bootstrap_class, default_color FROM job_icons WHERE id = ?");
            $stmtIcon->execute([$icon_id]);
            $icon = $stmtIcon->fetch();

            echo json_encode([
                'status' => 'success',
                'offer' => [
                    'id' => $pdo->lastInsertId(),   
                    'icon_id' => $icon_id,      
                    'title' => $title,
                    'location' => $location,
                    'category' => $category,
                    'type' => $type,
                    'experience_level' => $experience_level,
                    'salary_min' => $salary_min,
                    'salary_max' => $salary_max,
                    'tags' => $tags,
                    'icon_class' => $icon['bootstrap_class'] ?? 'bi-briefcase',
                    'color' => $icon['default_color'] ?? '#388087'
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        exit;
    }

    //delete offer
    if ($action === 'delete') {

        $id = (int)$_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM job_offers WHERE id = ? AND company_id = ?");
        $stmt->execute([$id, $company_id]);

        echo json_encode(['status' => 'success']);
        exit;
    }

    //edit offer
    if ($action === 'edit') {

        $id = (int)$_POST['id'];

        $title = trim($_POST['title']);
        $location = trim($_POST['location']);
        $category = $_POST['category'];
        $type = $_POST['type'];
        $experience_level = $_POST['experience_level'];
        $description = trim($_POST['description']);
        $tags = $_POST['tags'] ?? null;
        $status = $_POST['status'] ?? 'active';

        $salary_min = $_POST['salary_min'] !== '' ? (int)$_POST['salary_min'] : null;
        $salary_max = $_POST['salary_max'] !== '' ? (int)$_POST['salary_max'] : null;

        // validation 
        if ($salary_min !== null && $salary_min < 500) {
            echo json_encode(['status' => 'error', 'message' => 'Min salary must be ≥ 500']);
            exit;
        }

        if ($salary_max !== null && $salary_min !== null && $salary_max <= $salary_min) {
            echo json_encode(['status' => 'error', 'message' => 'Max salary must be greater than min']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE job_offers 
            SET 
                title = ?,
                location = ?,
                category = ?,
                type = ?,
                experience_level = ?,
                salary_min = ?,
                salary_max = ?,
                description = ?,
                tags = ?,
                status = ?
            WHERE id = ? AND company_id = ?
        ");

        $stmt->execute([
            htmlspecialchars($title),
            htmlspecialchars($location),
            $category,
            $type,
            $experience_level,
            $salary_min,
            $salary_max,
            htmlspecialchars($description),
            $tags,
            $status,
            $id,
            $company_id
        ]);

        echo json_encode([
            'status' => 'success',
            'offer' => [
                'id' => $id,
                'title' => $title,
                'location' => $location,
                'category' => $category,
                'type' => $type,
                'experience_level' => $experience_level,
                'salary_min' => $salary_min,
                'salary_max' => $salary_max,
                'tags' => $tags,
                'status' => $status
            ]
        ]);
        exit;
    }