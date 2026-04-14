<?php
require_once __DIR__ . '/../../config/session_check.php';
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo        = getDB();
        $userId     = $_SESSION['user_id'];
        $first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $email      = $_POST['email'];
        $role       = $_POST['role'];

        $password = $_POST['password'];
        $confirm  = $_POST['confirm_password'];

        if (!empty($password)) {
            if ($password !== $confirm) {
                echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
                exit;
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }

        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowed)) {
                echo json_encode(['status' => 'error', 'message' => 'Format non autorisé']);
                exit;
            }
            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                echo json_encode(['status' => 'error', 'message' => 'Image trop lourde (max 2MB)']);
                exit;
            }

            $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('img_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        }

        if ($imageName && !empty($password)) {
            $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=?, password=?, image=? WHERE id=?");
            $stmt->execute([$first_name, $last_name, $email, $role, $hashedPassword, $imageName, $userId]);

        } elseif ($imageName) {
            $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=?, image=? WHERE id=?");
            $stmt->execute([$first_name, $last_name, $email, $role, $imageName, $userId]);

        } elseif (!empty($password)) {
            $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=?, password=? WHERE id=?");
            $stmt->execute([$first_name, $last_name, $email, $role, $hashedPassword, $userId]);

        } else {
            $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=? WHERE id=?");
            $stmt->execute([$first_name, $last_name, $email, $role, $userId]);
        }

        header('Location: profil.php?success=1');
        exit;

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}