<?php
include ('../../config/database.php');
var_dump($_POST);
exit;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = $_POST['name']; 
        $last_name  = '';   
        $user_id     = 1;
        $company_id  = 1;              
        $position    = $_POST['position'];
        $department  = $_POST['department'];
        $hire_date   = date('Y-m-d');
        $email  = $_POST['email'];
        $cv   = $_POST['cv'];
    
    $pdo = getDB();
    $sql = "INSERT INTO employees (user_id, company_id, first_name, last_name, position, department, hire_date)
            VALUES (:user_id, :company_id, :first_name, :last_name, :position, :department, :hire_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
?>