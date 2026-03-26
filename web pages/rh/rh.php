
<!DOCTYPE html>
<html lang="en">
<?php $pageTitle = "Employees"; 
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprisa - <?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Roboto+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark custom-navbar" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <img src="../image/logoentreprisa.png" height="50" width="70" alt="Logo" />
            <a class="navbar-brand h1 fw-bold" href=""><?php echo $pageTitle; ?> Dashboard</a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav mx-auto gap-3">
                    <a class="nav-link <?php echo ($pageTitle == 'Employees') ? 'active' : ''; ?>" href="../rh/rh.php">Employees</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Recruitement') ? 'active' : ''; ?>" href="../recruitement/recruitement.php">Recruitment</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Sales') ? 'active' : ''; ?>" href="../sales company/salesC.php">Sales</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Services') ? 'active' : ''; ?>" href="../service admin/service_admin.php">Services</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Articles') ? 'active' : ''; ?>" href="../articles/articles.php">Articles</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Finance') ? 'active' : ''; ?>" href="../finance/finance.php">Finance</a>
                </div>
            </div>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="../image/profile.png" alt="Profile" width="32" height="32" class="rounded-circle me-2">
                        <span class="ms-2 d-none d-sm-inline">User</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="background-color: #212529;">
                        <li><a class="dropdown-item text-white" href="../profil/profil.php"><img src="../image/profile.png" width="24" class="me-2"> Profil</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item text-danger" href="../login/login.php"><img src="../image/logout.png" width="24" class="me-2"> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" style="background-color: #0d1f1b; color: white;">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title fw-bold">ENTREPRISA MENU</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column gap-3">
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Employees') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../rh/rh.php"><i class="bi bi-people me-3"></i> Employees</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Recruitement') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../recruitement/recruitement.php"><i class="bi bi-person-plus me-3"></i> Recruitment</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Sales') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../sales company/salesC.php"><i class="bi bi-cart me-3"></i> Sales</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Services') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../service admin/service_admin.php"><i class="bi bi-cash-stack me-3"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Articles') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../articles/articles.php"><i class="bi bi-gear me-3"></i> Articles</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Finance') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../finance/finance.php"><i class="bi bi-cash-stack me-3"></i> Finance</a></li>
            </ul>
        </div>
    </div>
<main class="container page-content">

  <section class="card shadow-sm mb-5">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3 class="mb-4">Add New Employee</h3>

      <form id="employeeForm" class="row g-3">

        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input id="name" type="text" class="form-control" placeholder="Employee name">
        </div>

        <div class="col-md-6">
          <label class="form-label">Department</label>
          <select id="department" class="form-select">
            <option selected>Choose...</option>
            <option>IT</option>
            <option>Finance</option>
            <option>Marketing</option>
            <option>Human Resources</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input id="position" type="text" class="form-control" placeholder="Position">
        </div>

        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input id="email" type="email" class="form-control" placeholder="email@company.com">
        </div>

        <div class="col-md-12">
          <label class="form-label">CV Link</label>
          <input type="url" class="form-control" placeholder="https://example.com/cv.pdf">
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-primary">Add Employee</button>
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
          <tr>
            <td>Sara Dh</td>
            <td>05A</td>
            <td>Web Developer</td>
            <td>IT</td>
            <td>sara@company.com</td>
            <td><span class="badge bg-success">Active</span></td>
            <td>
              <button class="btn btn-sm btn-outline-primary">View</button>
              <button class="btn btn-sm btn-outline-warning">Edit</button>
              <button class="btn btn-sm btn-outline-danger btn-delete">Delete</button>
            </td>
          </tr>
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

