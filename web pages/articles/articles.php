<?php 
   
    $pageTitle = "Articles"; 
    require_once __DIR__ . '/../../config/session_check.php';
    include('../squelettes entreprise/header.php');
?>

<main class="container page-content">
  <section class="card shadow-sm mb-5">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3 class="mb-4">Add New Article</h3>

      <form class="row g-3" id="articleForm" >
        <div class="col-md-6">
          <label class="form-label">Article Title</label>
          <input type="text" name ="title" class="form-control" placeholder="Enter title">
        </div>

        <div class="col-md-6">
          <label class="form-label">Category / Tag</label>
          <select class="form-select" name="category">
            <option value ="" selected>Choose...</option>
            <option>Technology</option>
            <option>Data & AI</option>
            <option>Cybersecurity</option>
            <option>HR & Careers</option>
            <option>Company News</option>
          </select>
        </div>

        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" placeholder="Article description"></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Author</label>
          <input name="name" type="text" class="form-control" placeholder="Author name">
        </div>

        <div class="col-md-6">
          <label class="form-label">Publish Date</label>
          <input name="date" type="date" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Content Link</label>
          <input name="link" type="url" class="form-control" placeholder="https://example.com/article.pdf">
        </div>

        <div class="col-md-6">
          <label class="form-label">Cover Image</label>
          <input name="image" type="url" class="form-control" placeholder="https://example.com/image.jpg">
        </div>

        <div class="col-12 text-end">
          <button name="submit" type="submit" class="btn btn-primary">Publish Article</button>
        </div>
      </form>
    </div>
  </section>

  <section class="row mb-4">
    <div class="col-md-6 mb-2">
      <input id="searchInput" type="text" class="form-control" placeholder="Search by title or author">
    </div>
        <div class="col-md-2">
      <button class="btn btn-secondary w-100">Search</button>
    </div>
  </section>

  <section class="card shadow-sm">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3 class="mb-4">Article List</h3>

      <div >
        <table >
          <thead >
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Category</th>
              <th>Date</th>
              <th>Content</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>
      <tbody id="articleTableBody">
        <?php
        require '../../config/database.php';

        try {
            $pdo = getDB();
            $query = $pdo->query("SELECT * FROM articles ");

            $articles = $query->fetchAll(PDO::FETCH_ASSOC);

            if (count($articles) > 0) {
                foreach ($articles as $row) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id'] ) . "</td>
                            <td>" . htmlspecialchars($row['title']) . "</td>
                            <td>" . htmlspecialchars($row['author_name']) . "</td>
                            <td>" . htmlspecialchars($row['category']) . "</td>
                            <td>" . htmlspecialchars($row['ar_date']) . "</td>
                            <td>" . htmlspecialchars($row['link']) . "</td>
                            <td>" . htmlspecialchars($row['ar_image']) . "</td>
                            <td>
                              <button class='btn btn-sm btn-outline-primary btn-view' data-id='" . $row['id'] . "'>View</button>
                              <button class='btn btn-sm btn-outline-warning btn-edit' data-id='" . $row['id'] . "'>Edit</button>
                              <button class='btn btn-sm btn-outline-danger btn-delete' data-id='" . $row['id'] . "'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='7' class='text-center'>Aucun article trouvé</td>
                      </tr>";
            }

        } catch (PDOException $e) {
            echo "<tr>
                    <td colspan='7' class='text-danger text-center'>
                        Erreur : " . htmlspecialchars($e->getMessage()) . "
                    </td>
                  </tr>";
        }
        ?>
      </tbody>
        </table>
      </div>
    </div>
  </section>

</main>

<footer class="footer-forest text-white py-5">
        <div class="container">
            <div class="row gy-4 text-center text-md-start">
                <div class="col-lg-4">
                    <div class="footer-brand mb-3">
                        <img src="../image/logoentreprisa.png" height="60" width="80" alt="Logo" />
                        <span class="fw-bold fs-4 text-uppercase">Entreprisa</span>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Division</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">Services & AI</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Communications</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-4">
                    <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">Technical Help</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">IT Support</a></li>
                    </ul>
                </div>
            </div>
            <hr class="opacity-25 mt-5">
            <div class="text-center small opacity-50">
                <p class="mb-0">© 2026 Entreprisa Inc. - Services & AI Division</p>
            </div>
        </div>
    </footer>
<script src="article.js"></script>
</body>
</html>

