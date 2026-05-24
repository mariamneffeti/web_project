<?php 
    require_once __DIR__ . '/../../config/session_check.php';
    require_once __DIR__ . '/../../config/database.php';
    requireRole('normal');
?>
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
    <style>
        .dropdown-toggle::after { display: none !important; }

        .form-control, .form-select {
            transition: all 0.3s ease;
            border: 1px solid #ced4da;
        }

        .form-control:focus, .form-select:focus {
            transform: translateX(5px);
            border-color: #102E4A;
            box-shadow: 0 4px 8px rgba(16, 46, 74, 0.1);
            outline: none;
        }

        :root {
            --primary-teal: #388087;
            --dark-forest: #0d1f1b;
            --bg-light: #F6F6F2;
            --accent-blue: #102E4A;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
        }
        .custom-navbar {
        background: linear-gradient(90deg, #388087, #0d1f1b);
        }
        .stats-card {
            background: white;
            border: none;
            border-left: 5px solid var(--primary-teal);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .table-custom {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-custom thead {
            background-color: var(--accent-blue);
            color: white;
        }

        .btn-finance {
            background-color: var(--primary-teal);
            color: white;
            border: none;
            transition: opacity 0.3s;
        }

        .btn-finance:hover {
            background-color: var(--dark-forest);
            color: white;
        }

        

        .dropdown-menu {
            border-radius: 8px;
            padding: 8px 0;
        }

        .dropdown-item {
            font-weight: 500;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .dropdown-toggle::after {
            display: none !important;
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
            <a class="navbar-brand h1 fw-bold" href="">Entreprisa</a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav mx-auto gap-3">
                    
                    <a class="nav-link <?php echo ($pageTitle == 'Home') ? 'active' : ''; ?>" href="../clienthome/clienthome.php">Home</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Offer') ? 'active' : ''; ?>" href="../offre/offre.php">Offre</a>
                    <a class="nav-link <?php echo ($pageTitle == 'Cv') ? 'active' : ''; ?>" href="../cv/cvv.php">Cv</a>
                </div>
            </div>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="<?= !empty($currentUser['image']) ? '../../uploads/' . $currentUser['image'] : '../image/profile.png' ?>" alt="Profile" width="32" height="32" class="rounded-circle me-2">
                        <span class="ms-2 d-none d-sm-inline"><?= $currentUser['first_name'] ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="background-color: #212529;">
                        <li><a class="dropdown-item text-white" href="../profil/profil.php"><img src="<?= !empty($currentUser['image']) ? '../../uploads/' . $currentUser['image'] : '../image/profile.png' ?>" width="24" class="me-2"> Profil</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item text-danger" href="../logout/logout.php"><img src="../image/logout.png" width="24" class="me-2"> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" style="background: linear-gradient(90deg, #388087, #0d1f1b); color: white;">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title fw-bold">ENTREPRISA MENU</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column gap-3">
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Home') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../clienthome/clienthome.php"><i class="bi bi-house-heart"></i> Employees</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Offer') ?  'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../offre/offre.php"><i class="bi bi-building-check"></i> Offre</a></li>
                <li class="nav-item"><a class="nav-link text-white d-flex align-items-center <?php echo ($pageTitle == 'Cv') ? 'active "style="background: rgba(56, 128, 135, 0.2); border-radius: 8px;"' : ''; ?>" href="../cv/cvv.php"><i class="bi bi-person-plus me-3"></i> Cv</a></li>
                
            </ul>
        </div>
    </div>