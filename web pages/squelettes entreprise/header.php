<!DOCTYPE html>
<html lang="en">
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