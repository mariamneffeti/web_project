<?php
include ('../../config/database.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $first_name = $_POST['name']; 
        $last_name  = 'NN';   
        $user_id     = 1; 
        $company_id  = 1;              
        $position    = $_POST['position'];
        $department  = $_POST['department'];
        $hire_date   = date('Y-m-d');
        $email       = $_POST['email'];
        $cv_path     = null;
        $pdo = getDB();
        $temp_password    = 'Emp@' . rand(1000, 9999); 
        $hashed_password  = password_hash($temp_password, PASSWORD_DEFAULT);

        $userStmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, role, password)
            VALUES (:first_name, :last_name, :email, :role, :password)
        ");
        $userStmt->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':email'      => $email,
            ':role'       => 'employee',
            ':password'   => $hashed_password,
        ]);
        
                if (!empty($_FILES['cv_file']['name'])) {

            $allowed_mimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            $file_mime = $_FILES['cv_file']['type'];
            $file_size = $_FILES['cv_file']['size'];
            $max_size  = 5 * 1024 * 1024; // 5MB

            if (!in_array($file_mime, $allowed_mimes)) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Invalid file format. Only PDF, DOC, DOCX allowed.'
                ]);
                exit;
            }

            if ($file_size > $max_size) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'File too large. Max size is 5MB.'
                ]);
                exit;
            }

            $upload_dir = __DIR__ . '/../../uploads/cv/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $ext         = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
            $new_filename = 'cv_' . uniqid() . '.' . $ext;

            if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $upload_dir . $new_filename)) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Failed to save the file.'
                ]);
                exit;
            }

            $cv_path = 'uploads/cv/' . $new_filename;
        }

        $new_user_id = $pdo->lastInsertId();
        $sql = "INSERT INTO employees 
                (user_id, company_id, first_name, last_name, position, department, hire_date, email, cv_path)
                VALUES 
                (:user_id, :company_id, :first_name, :last_name, :position, :department, :hire_date, :email, :cv_path)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id'    => $user_id,
            ':company_id' => $company_id,
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':position'   => $position,
            ':department' => $department,
            ':hire_date'  => $hire_date,
            ':email'      => $email,
            ':cv_path'    => $cv_path,
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Employee added successfully'
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?>