<?php 
    $pageTitle = "Home";
    include '../squelettes entreprise/header.php';
?>

<section class="hero text-center py-5">
  <div class="container py-4" style="margin-top: 100px; margin-bottom: 100px;">
    <h1 class="mb-3">Welcome to Our Website</h1>
    <p class="lead mb-4">
      Our website aims to create a structured platform that connects companies,
      employees, and users in one system.
    </p>
    <div class="d-flex justify-content-center gap-3">
      <a href="../logout/logout.php" class="btn btn-outline-danger btn-lg">Logout</a>
      <a href="../profil/profil.php" class="btn btn-outline-primary btn-lg">View Profile</a>
    </div>
  </div>
</section>


<section class="services py-5" id="services">
  <div class="container text-center" class="service-card">
    <h2 class="mb-4">Services</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="service-card" >
            <h5 class="card-title">Company Services</h5>
            <p class="card-text">Tools and services for companies.</p>
            <ul>
                    <li>Clients & Employees Management</li>
                    <li>Sales Management</li>
                    <li>Recruitment</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4" class="service-card">
        <div class="card h-100">
          <div class="service-card" >
            <h5 class="card-title">Employee Services</h5>
            <p class="card-text">Employee management and resources.</p>
            <ul>
                    <li>Clients Management</li>
                    <li>Sales Management</li>
                    <li>Services</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4" >
        <div class="card h-100">
          <div class="service-card" >
            <h5 class="card-title">Client / Applicant</h5>
            <p class="card-text">Applications and user services.</p>
            <ul>
                    <li>Articles Feed</li>
                    <li>Company Offers</li>
                    <li>CV Management</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php 
    $pagePath = "";
    include '../squelettes entreprise/footer.php'; ?>