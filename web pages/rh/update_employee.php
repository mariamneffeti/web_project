<?php
require '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $pdo = getDB();

        $id         = $_POST['id'];
        $name       = $_POST['name'];
        $email      = $_POST['email'];
        $department = $_POST['department'];
        $position   = $_POST['position'];
        $cv_path    = null;

        $current = $pdo->prepare("SELECT cv_path, email FROM employees WHERE id = ?");
        $current->execute([$id]);
        $existing = $current->fetch(PDO::FETCH_ASSOC);
        $cv_path  = $existing['cv_path']; // garder l'ancien par défaut
        
                if (!empty($_FILES['cv_file']['name'])) {
            $allowed_mimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            $file_mime = $_FILES['cv_file']['type'];
            $file_size = $_FILES['cv_file']['size'];

            if (!in_array($file_mime, $allowed_mimes)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format.']);
                exit;
            }
            if ($file_size > 5 * 1024 * 1024) {
                echo json_encode(['status' => 'error', 'message' => 'File too large (max 5MB).']);
                exit;
            }

            $upload_dir = __DIR__ . '/../../uploads/cv/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $ext          = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
            $new_filename = 'cv_' . uniqid() . '.' . $ext;

            if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $upload_dir . $new_filename)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save the file.']);
                exit;
            }

            $cv_path = 'uploads/cv/' . $new_filename;
        }

        $stmt = $pdo->prepare("
            UPDATE employees 
            SET first_name = ?, email = ?, department = ?, position = ?, cv_path = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $department, $position, $cv_path, $id]);

        
        $userStmt = $pdo->prepare("
            UPDATE users 
            SET first_name = ?, email = ?
            WHERE email = ?
        ");
        $userStmt->execute([$name, $email, $existing['email']]);

        $stmt->execute([$name, $email, $department, $position, $id]);

        echo json_encode([
            "status" => "success",
            "message" => "Employee updated successfully"
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}