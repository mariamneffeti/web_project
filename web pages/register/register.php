<?php
require_once __DIR__ . '/../../config/database_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];
    $role = $_POST["role"];

    if (empty($email) || empty($password) || empty($password_confirm) || empty($role)) {
        die("All fields are required");
    }

    if ($password !== $password_confirm) {
        die("Passwords do not match");
    }

    if (!in_array($role, ['company', 'employee'])) {
        die("Invalid role selected");
    }

    try {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$email]);

        if ($checkStmt->rowCount() > 0) {
            die("Email already registered!");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $hashedPassword, $role]);

        echo "User registered successfully as " . htmlspecialchars($role) . "!";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Create Account</h2>

    <form action="#" method="post">
        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="password_confirm" placeholder="Confirm Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="company">Company</option>
            <option value="employee">Employee</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        Already have an account?
        <a href="../login/login.php">Login</a>
    </div>
</div>

</body>
</html>