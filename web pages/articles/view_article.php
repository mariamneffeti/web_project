<?php
require_once '../../config/database.php';
 
 
if (!isset($_GET['id'])) {
    die("Article ID missing");
}
 
$id = (int)$_GET['id'];

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) { 
        die("Article not found");
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Article</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h2>Article Details</h2>

<ul class="list-group">
  <li class="list-group-item"><strong>Title:</strong> <?= $article['title'] ?></li>
  <li class="list-group-item"><strong>Category:</strong> <?= $article['category'] ?></li>
  <li class="list-group-item"><strong>Description:</strong> <?= $article['ar_description'] ?></li>
  <li class="list-group-item"><strong>Author:</strong> <?= $article['author_name'] ?></li>
  <li class="list-group-item"><strong>Publish Date:</strong> <?= $article['ar_date'] ?></li>
  <li class="list-group-item"><strong>Link:</strong> <?= $article['link'] ?></li>
  <li class="list-group-item"><strong>Image:</strong> <?= $article['ar_image'] ?></li>
  
</ul>

<a href="articles.php" class="btn btn-secondary mt-3">Back</a>

</body>
</html>