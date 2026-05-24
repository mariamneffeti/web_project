<?php
session_start();
require_once __DIR__ . '/../../config/database_connection.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error_msg = "All fields are required";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];


            switch($user['role']){
                case 'employee':
                    header("Location: ../sales/sales.html");
                    break;
                case 'company':
                    header("Location: ../home admin/home.php");
                    break;
                default:
                    header("Location: ../clienthome/clienthome.php");
                    break;
            }
            exit();
        } else {
            $error_msg = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <h2>Login</h2>

  <?php if (!empty($error_msg)): ?>
    <div class="error">
      <?= htmlspecialchars($error_msg) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="">

    <input type="email" name="email" placeholder="Email Address" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>
 </form>
    <div class="login-link">
      Don't have an account?
      <a href="../register/register.php">Register</a>
      <br>
      <a href="../home/home.php">Home</a>
    </div>

 
</div>

</body>
</html>