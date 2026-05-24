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

        $new_user_id = $pdo->lastInsertId();
        $sql = "INSERT INTO employees 
                (user_id, company_id, first_name, last_name, position, department, hire_date, email)
                VALUES 
                (:user_id, :company_id, :first_name, :last_name, :position, :department, :hire_date, :email)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':user_id'    => $user_id,
            ':company_id' => $company_id,
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':position'   => $position,
            ':department' => $department,
            ':hire_date'  => $hire_date,
            ':email'      => $email
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