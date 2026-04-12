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

        $stmt = $pdo->prepare("UPDATE employees 
            SET first_name = ?, email = ?, department = ?, position = ?
            WHERE id = ?");

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