<!DOCTYPE html>
<html lang="en">
<?php $pageTitle = "Profil"; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprisa - <?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Roboto+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #0d1f1b 0%, #388087 100%);
            height: 150px;
            border-radius: 15px 15px 0 0;
        }
        .profile-avatar-container {
            margin-top: -75px;
            padding: 5px;
            background: white;
            border-radius: 50%;
            display: inline-block;
        }
        .profile-avatar {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark custom-navbar" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <img src="../image/logoentreprisa.png" height="50" width="70" alt="Logo" />
            <a class="navbar-brand h1 fw-bold" href=""><?php echo $pageTitle; ?> Settings</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav mx-auto gap-3">
                    <a class="nav-link" href="../rh/rh.php">Employees</a>
                    <a class="nav-link" href="../recruitement/recruitement.php">Recruitment</a>
                    <a class="nav-link" href="../sales company/salesC.php">Sales</a>
                    <a class="nav-link" href="../service admin/service_admin.php">Services</a>
                    <a class="nav-link" href="../articles/articles.php">Articles</a>
                    <a class="nav-link" href="../finance/finance.php">Finance</a>
                </div>
            </div>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center active" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="../image/profile.png" alt="Profile" width="32" height="32" class="rounded-circle me-2">
                        <span class="ms-2 d-none d-sm-inline">Sara Dh</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="background-color: #212529;">
                        <li><a class="dropdown-item text-white" href="#"><img src="../image/profile.png" width="24" class="me-2"> Profil</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item text-danger" href="#"><img src="../image/logout.png" width="24" class="me-2"> Logout</a></li>
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
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center" href="../rh/rh.php"><i class="bi bi-people me-3"></i> Employees</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center" href="../recruitement/recruitement.php"><i class="bi bi-person-plus me-3"></i> Recruitment</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center" href="../sales company/salesC.php"><i class="bi bi-cart me-3"></i> Sales</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center" href="../service admin/service_admin.php"><i class="bi bi-cash-stack me-3"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center" href="../articles/articles.php"><i class="bi bi-gear me-3"></i> Articles</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center" href="../finance/finance.php"><i class="bi bi-cash-stack me-3"></i> Finance</a></li>
            </ul>
        </div>
    </div>

    <main class="container page-content mt-5 pt-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="profile-header"></div>
                    <div class="card-body text-center">
                        <div class="profile-avatar-container shadow-sm">
                            <img src="../image/profile.png" alt="User Avatar" class="profile-avatar">
                        </div>
                        <h4 class="fw-bold mt-3">Sara Dh</h4>
                        <p class="text-muted">Senior Web Developer</p>
                        <div class="badge bg-success mb-3">Administrator</div>
                        <hr class="opacity-25">
                        <div class="text-start px-3">
                            <p class="small mb-1 text-uppercase fw-bold opacity-50">Email</p>
                            <p class="mb-3">sara@company.com</p>
                            <p class="small mb-1 text-uppercase fw-bold opacity-50">Department</p>
                            <p class="mb-0">IT & Digital Strategy</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm p-4 h-100" style="border-left: 4px solid #388087;">
                    <h3 class="mb-4">Account Settings</h3>
                    
                    <form id="profileForm" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" value="Sara">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" value="Dh">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control" value="sara@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">New Password</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        
                        <div class="col-12 mt-4">
                            <h5 class="border-bottom pb-2">Preferences</h5>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notifyEmail" checked>
                                <label class="form-check-label" for="notifyEmail">Email notifications for new recruitment</label>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="background-color: #388087; border: none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-forest text-white py-5 mt-5">
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
                        <li class="mb-2"><a href="#" class="footer-link text-decoration-none text-white-50">Services & AI</a></li>
                        <li class="mb-2"><a href="#" class="footer-link text-decoration-none text-white-50">Communications</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-4">
                    <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Support</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link text-decoration-none text-white-50">Technical Help</a></li>
                        <li class="mb-2"><a href="#" class="footer-link text-decoration-none text-white-50">IT Support</a></li>
                    </ul>
                </div>
            </div>
            <hr class="opacity-25 mt-5">
            <div class="text-center small opacity-50">
                <p class="mb-0">© 2026 Entreprisa Inc. - Services & AI Division</p>
            </div>
        </div>
    </footer>
</body>
</html>