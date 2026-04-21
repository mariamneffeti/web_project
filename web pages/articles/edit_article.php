<?php
require_once '../../config/database.php';
 
 
if (!isset($_GET['id'])) {
    die("Article ID missing");
}


    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    
if (!$article) {
    die("Article introuvable.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Article</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h2>Edit Article</h2>

<form id="editForm">
    <input type="hidden" name="id" value="<?= $article['id'] ?>" class="form-control mb-2">
  <input type="text" name="title" value="<?= $article['title'] ?>" class="form-control mb-2">
  <input type="text" name="category" value="<?= $article['category'] ?>" class="form-control mb-2">
  <input type="text" name="description" value="<?= $article['ar_description'] ?>" class="form-control mb-2">
  <input type="text" name="author" value="<?= $article['author_name'] ?>" class="form-control mb-2">
  <input type="date" name="date" value="<?= $article['ar_date'] ?>" class="form-control mb-2">
  <input type="url" name="link" value="<?= $article['link'] ?>" class="form-control mb-2">
  <input type="url" name="image" value="<?= $article['ar_image'] ?>" class="form-control mb-2">

  <button type="submit"  class="btn btn-success">Update</button>
</form>

<a href="articles.php" class="btn btn-secondary mt-3">Back</a>

<script>
document.getElementById('editForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  const res = await fetch('update_article.php', {
    method: 'POST',
    body: formData
  });

  const data = await res.json();

  if (data.status === 'success') {
    alert("Updated successfully");
    window.location.href = "articles.php";
  }
});
</script>

</body>
</html>