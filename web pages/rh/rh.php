
<!DOCTYPE html>
<html lang="en">
<?php
 $pageTitle = "Employees"; 
 require_once __DIR__ . '/../../config/session_check.php';
 include('../squelettes entreprise/header.php');
?>
<main class="container page-content">

  <section class="card shadow-sm mb-5">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3 class="mb-4">Add New Employee</h3>

      <form id="employeeForm" class="row g-3">

        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input name="name" type="text" class="form-control" placeholder="Employee name" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Department</label>
          <select name="department" class="form-select" required>
            <option value="" selected>Choose...</option>
            <option>IT</option>
            <option>Finance</option>
            <option>Marketing</option>
            <option>Human Resources</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input name="position" type="text" class="form-control" placeholder="Position">
        </div>

        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" placeholder="email@company.com" required>
        </div>

        <div class="col-md-12">
          <label class="form-label">CV</label>
              <div class="input-group">
                  <input name="cv_path" id="cvPathInput" type="text" class="form-control" placeholder="No CV uploaded yet" readonly>

                            <a href="../cv/cvv.php" onclick="openCvPicker(event)" class="btn text-white" style="background:#388087;">
                                <i class="bi bi-upload me-1"></i> Upload CV
                            </a>
                        </div>
                        <div class="form-text">
                            Click "Upload CV" to go to the CV upload page and attach a file.
                        </div>
                    </div>

        <div class="col-12 text-end">
          <button name="submit" type="submit" class="btn btn-primary">Add Employee</button>
        </div>

      </form>
    </div>
  </section>

  <section class="row mb-4">
    <div class="col-md-6 mb-2">
      <input id="searchInput" type="text" class="form-control" placeholder="Search by name or ID">
    </div>

    <div class="col-md-4 mb-2">
      <select id="filterSelect" class="form-select">
        <option selected>Filter by...</option>
        <option>Department</option>
        <option>Position</option>
        <option>Status</option>
      </select>
    </div>

    <div class="col-md-2">
      <button id="applyBtn" type="button" class="btn btn-secondary w-100">Apply</button>
    </div>
  </section>


  <section class="tab">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3>Employee List</h3>

      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Position</th>
            <th>Department</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>

      <tbody id="employeeTableBody">
        <?php
        require '../../config/database.php';

        try {
            $pdo = getDB();
            $query = $pdo->query("SELECT * FROM employees ");

            $employees = $query->fetchAll(PDO::FETCH_ASSOC);

            if (count($employees) > 0) {
                foreach ($employees as $row) {
                    echo "<tr>
                            <td>" . $row['first_name'] . "</td>
                            <td>" . $row['id'] . "</td>
                            <td>" . $row['position'] . "</td>
                            <td>" . $row['department'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td><span class='badge bg-success'>Active</span></td>
                            <td>
                              <button class='btn btn-sm btn-outline-primary btn-view' data-id='" . $row['id'] . "'>View</button>
                              <button class='btn btn-sm btn-outline-warning btn-edit' data-id='" . $row['id'] . "'>Edit</button>
                              <button class='btn btn-sm btn-outline-danger btn-delete' data-id='" . $row['id'] . "'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='7' class='text-center'>Aucun employé trouvé</td>
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
      <script src="rh.js"></script>
</body>
</html>

