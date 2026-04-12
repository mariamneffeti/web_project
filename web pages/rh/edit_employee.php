<?php
require '../../config/database.php';

$pdo = getDB();

if (!isset($_GET['id'])) {
    die("ID missing");
}

$stmt = $pdo->prepare("SELECT * FROM employees WHERE id=?");
$stmt->execute([$_GET['id']]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Employee</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h2>Edit Employee</h2>

<form id="editForm">
  <input type="hidden" name="id" value="<?= $employee['id'] ?>">

  <input type="text" name="name" value="<?= $employee['first_name'] ?>" class="form-control mb-2">
  <input type="email" name="email" value="<?= $employee['email'] ?>" class="form-control mb-2">
  <input type="text" name="department" value="<?= $employee['department'] ?>" class="form-control mb-2">
  <input type="text" name="position" value="<?= $employee['position'] ?>" class="form-control mb-2">

  <button class="btn btn-success">Update</button>
</form>

<a href="rh.php" class="btn btn-secondary mt-3">Back</a>

<script>
document.getElementById('editForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  const res = await fetch('update_employee.php', {
    method: 'POST',
    body: formData
  });

  const data = await res.json();

  if (data.status === 'success') {
    alert("Updated successfully");
    window.location.href = "rh.php";
  }
});
</script>

</body>
</html>