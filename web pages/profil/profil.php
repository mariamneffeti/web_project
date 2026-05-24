<?php
require_once __DIR__ . '/../../config/session_check.php';
?>
   <?php 
    
    if($currentUser['role'] === 'employee') {
        $pageTitle = "Employee Profile";?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $pageTitle; ?></title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        <script src="clientsE.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <style>
        body{
        background:#F6F6F2;
        font-family: Inter, system-ui, sans-serif;
        color:#1f2933;
        }

      
        .navbar{
        background:#388087;
        }

  
        .card{
        border:none;
        border-radius:16px;
        box-shadow:0 10px 28px rgba(0,0,0,.06);
        transition:.3s ease;
        }
        .card:hover{
        transform:translateY(-4px);
        }

       
        .kpi{
        border-left:6px solid #102E4A;
        }

      
        .btn-primary-custom{
        background:#388087;
        border:none;
        color:white;
        }
        .btn-primary-custom:hover{
        background:#2f6f75;
        }

   
        .table thead{
        background:#102E4A;
        color:white;
        }

        footer{
        background:linear-gradient(180deg,#388087,#0d1f1b);
        }
        a.footer-link,
        a.footer-link:link,
        a.footer-link:visited {
        color: rgba(255,255,255,0.8) !important;
        text-decoration: none;
        }
        a.footer-link:hover {
        color: white !important;
        padding-left: 5px;
        }
        </style>
        </head>

        <body>

        <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Employee Dashboard</a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="nav">
            <ul class="navbar-nav gap-4 fw-semibold">
                <li class="nav-item"><a class="nav-link" href="../clients%20viewE/clientsE.html">Clients</a></li>
                <li class="nav-item"><a class="nav-link" href="../sales/sales.html">Sales</a></li>
                <li class="nav-item"><a class="nav-link" href="../service%20employee/service_employee.html">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="../stock_employee/stock.html">Stock</a></li>
            </ul>
            </div>

            <div class="d-flex align-items-center ms-auto">
            <span class="text-white me-3"><?= $currentUser['first_name'] ?></span>
            <button class="btn btn-sm btn-outline-light">Logout</button>
            </div>
        </div>
        </nav> 
    <?php 
    } elseif ($currentUser['role'] === 'normal') {
        $pageTitle = "User Profile";
        include('../squelleteuser/header.php'); 
    }
    else {
        $pageTitle = "Company Profile";
        include('../squelettes entreprise/header.php'); 
    }
    
?>
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


    <main class="container page-content mt-5 pt-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="profile-header"></div>
                    <div class="card-body text-center">
                        <div class="profile-avatar-container shadow-sm">
                        <img src="<?= !empty($currentUser['image']) ? '../../uploads/' . $currentUser['image'] : '../image/profile.png' ?>"  class="rounded-circle mx-auto" width="150" height="150">
                        </div>
                        <h4 class="fw-bold mt-3"><?= $currentUser['first_name'] . " _ " . $currentUser['last_name'] ?></h4>
                        <div class="badge bg-success mb-3"><?= $currentUser['role'] ?></div>
                        <hr class="opacity-25">
                        <div class="text-start px-3">
                            <p class="small mb-1 text-uppercase fw-bold opacity-50">Email</p>
                            <p class="mb-3"><?= $currentUser['email'] ?></p>
                            <p class="small mb-1 text-uppercase fw-bold opacity-50">Department</p>
                            <p class="mb-0"><?= $currentUser['role'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm p-4 h-100" style="border-left: 4px solid #388087;">
                    <h3 class="mb-4">Account Settings</h3>
                    
                    <form id="profileForm" class="row g-3" method="POST" action="edit_profil.php" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input name="first_name" type="text" class="form-control" value="<?= $currentUser['first_name'] ?>" placeholder="first_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input name="last_name" type="text" class="form-control" value="<?= $currentUser['last_name'] ?>" placeholder="last_name">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Email</label>
                            <input name="email" type="email" class="form-control" value="<?= $currentUser['email'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">New Password</label>
                            <input name="password" type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input name="confirm_password" type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Image</label>
                            <input type="file" name="image" class="form-control mb-3">
                        </div>
                        
                        <div class="col-12 mt-4"></div>
                        <div class="col-12 mt-4"></div>
                        <div class="col-12 mt-4"></div>

                        <div class="col-12 text-end mt-4">
                            <button name="cancel" type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary w-100" style="background-color: #388087; border: none;">Save Changes</button>
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