<?php
session_start();
include('../../config/database.php');

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}
$password = null;
$pdo = getDB();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$role       = trim($_POST['role'] ?? '');


    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            die("Passwords do not match ❌");
        }
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }


    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);
    }


    $sql = "UPDATE users SET first_name=?, last_name=?, email=?, role=?";
    $params = [$first_name, $last_name, $email, $role];

    if (!empty($password)) {
        $sql .= ", password=?";
        $params[] = $password;
    }

    if ($imageName) {
        $sql .= ", image=?";
        $params[] = $imageName;
    }

    $sql .= " WHERE id=?";
    $params[] = $user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header("Location: profil.php");
}