<?php
session_start();
require_once __DIR__ . '/../../config/database_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];
    $role = strtolower(trim($_POST["role"])); 
    if($role=="visitor"){
        $role="normal";
    }
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);

    if ($password !== $password_confirm) {
        die("Passwords do not match");
    }

    try {
        $conn->beginTransaction();


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmtUser = $conn->prepare("INSERT INTO users (email, password, role, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
        $stmtUser->execute([$email, $hashedPassword, $role, $first_name, $last_name]);
        $newUserId = $conn->lastInsertId();

        if ($role === 'company') {
            $company_name = trim($_POST["company_name"]);
            $industry     = trim($_POST["industry"]);
            $address      = trim($_POST["address"]);
            $phone        = trim($_POST["phone"]);


            $stmtCo = $conn->prepare("INSERT INTO companies (user_id, company_name, industry, address, phone) VALUES (?, ?, ?, ?, ?)");
            $stmtCo->execute([$newUserId, $company_name, $industry, $address, $phone]);
        }

        $conn->commit();

        $_SESSION['user_id'] = $newUserId;
        $_SESSION['email']   = $email;
        $_SESSION['role']    = $role;

        header("Location: " . ($role === 'company' ? "../home admin/home.php" : "../clienthome/clienthome.php"));
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
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

    <form action="register.php" method="post">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirm" placeholder="Confirm Password" required>
        
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>

        <input type="text" name="role" id="roleInput" list="roleOptions" placeholder="Select Role (Visitor or Company)" oninput="checkRole()" required autocomplete="off">
        <datalist id="roleOptions">
            <option value="Visitor">
            <option value="Company">
        </datalist>

        <div id="company-fields" style="display:none;">
            <input type="text" name="company_name" id="cname" placeholder="Company Name">
            <input type="text" name="industry" id="ind" placeholder="Industry">
            <input type="text" name="address" id="addr" placeholder="Address">
            <input type="text" name="phone" id="ph" placeholder="Phone Number">
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="../login/login.php">Login</a>
    </div>
</div>

<script>
function checkRole() {
    const roleValue = document.getElementById('roleInput').value.toLowerCase();
    const companyDiv = document.getElementById('company-fields');
    const inputs = companyDiv.getElementsByTagName('input');

    if (roleValue === 'company') {
        companyDiv.style.display = 'block';
        for (let i of inputs) i.required = true;
    } else {
        companyDiv.style.display = 'none';
        for (let i of inputs) i.required = false;
    }
}
</script>

</body>
</html>

