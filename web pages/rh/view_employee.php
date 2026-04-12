<?php
require '../../config/database.php';

if (!isset($_GET['id'])) {
    die("Employee ID missing");
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$_GET['id']]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Employee not found");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Employee</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h2>Employee Details</h2>

<ul class="list-group">
  <li class="list-group-item"><strong>Name:</strong> <?= $employee['first_name'] ?></li>
  <li class="list-group-item"><strong>Email:</strong> <?= $employee['email'] ?></li>
  <li class="list-group-item"><strong>Department:</strong> <?= $employee['department'] ?></li>
  <li class="list-group-item"><strong>Position:</strong> <?= $employee['position'] ?></li>
</ul>

<a href="rh.php" class="btn btn-secondary mt-3">Back</a>

</body>
</html>