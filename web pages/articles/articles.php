<?php 
    $pageTitle = "Articles"; 
    include('../squelettes entreprise/header.php');
?>
<main class="container page-content">
  <section class="card shadow-sm mb-5">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3 class="mb-4">Add New Article</h3>

      <form class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Article Title</label>
          <input type="text" class="form-control" placeholder="Enter title">
        </div>

        <div class="col-md-6">
          <label class="form-label">Category / Tag</label>
          <select class="form-select">
            <option selected>Choose...</option>
            <option>Technology</option>
            <option>Data & AI</option>
            <option>Cybersecurity</option>
            <option>HR & Careers</option>
            <option>Company News</option>
          </select>
        </div>

        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea class="form-control" rows="4" placeholder="Article description"></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Author</label>
          <input type="text" class="form-control" placeholder="Author name">
        </div>

        <div class="col-md-6">
          <label class="form-label">Publish Date</label>
          <input type="date" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Content Link</label>
          <input type="url" class="form-control" placeholder="https://example.com/article.pdf">
        </div>

        <div class="col-md-6">
          <label class="form-label">Cover Image</label>
          <input type="url" class="form-control" placeholder="https://example.com/image.jpg">
        </div>

        <div class="col-12 text-end">
          <button class="btn btn-primary">Publish Article</button>
        </div>
      </form>
    </div>
  </section>

  <section class="row mb-4">
    <div class="col-md-6 mb-2">
      <input type="text" class="form-control" placeholder="Search by title or author">
    </div>
        <div class="col-md-2">
      <button class="btn btn-secondary w-100">Search</button>
    </div>
  </section>
  <section class="row mb-4">
    <div class="col-md-4 mb-2">
      <input type="text" class="form-control" placeholder="Filter by category">
    </div>
    <div class="col-md-2">
      <button class="btn btn-secondary w-100">Filter</button>
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

          <tbody>
            <tr>
              <td>1A7</td>
              <td>Intro to Web Development</td>
              <td>Peter Kelt</td>
              <td>Technology</td>
              <td>15/10/2025</td>
              <td><a href="#">Click here</a></td>
              <td>
                <img src="https://images.surferseo.art/9602bc4b-cfc4-410e-b291-611d478c9d6a.png" class="img-thumbnail" width="80">
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary">View</button>
                <button class="btn btn-sm btn-outline-warning">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
                <button class="btn btn-sm btn-outline-success">Like</button>
                <button class="btn btn-sm btn-outline-secondary">Comment</button>
              </td>
            </tr>

            <tr>
              <td>1P0</td>
              <td>Cybersecurity Best Practices</td>
              <td>Ali Solt</td>
              <td>Security</td>
              <td>22/08/2025</td>
              <td><a href="#">Click here</a></td>
              <td>
                <img src="https://eu-images.contentstack.com/v3/assets/blt5412ff9af9aef77f/blt8d3c10e7a16c531a/65eecb85af4416040aced770/Service-Cyber-Security.png"
                     class="img-thumbnail" width="80">
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary">View</button>
                <button class="btn btn-sm btn-outline-warning">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
                <button class="btn btn-sm btn-outline-success">Like</button>
                <button class="btn btn-sm btn-outline-secondary">Comment</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

</main>
<?php include('../squelettes entreprise/footer.php'); ?>
