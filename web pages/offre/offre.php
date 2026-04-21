<?php require_once 'stat.php';?>
<?php 
    $pageTitle = "Offer"; 
    include('../squelleteuser/header.php'); 
?>

  <body>
      <link rel="stylesheet" href="offre.css">


    <header class="job-hero">
      <div class="container px-3 px-md-4">

        <h1 class="display-4 display-md-3 fw-bold mb-3 fade-up fade-up-1">
          Find your next big move
        </h1>
        <p class="lead opacity-75 mb-4 mb-md-5 fade-up fade-up-2"
           style="font-family:'Roboto Mono',monospace; font-size:clamp(0.85rem,2vw,1.1rem); max-width:560px">
          Browse through thousands of high-paying jobs from top-rated companies.
        </p>

     
        <div class="row fade-up fade-up-3">
          <div class="col-12 col-lg-8 col-xl-7">
            <div class="search-container d-flex flex-column flex-md-row align-items-center">

              <div class="flex-grow-1 d-flex align-items-center px-3 py-1 w-100">
                <i class="bi bi-search text-muted me-2 flex-shrink-0"></i>
                <input             id="searchInput"
 type="text" class="form-control border-0 shadow-none"
                       placeholder="Job title, keywords…" />
              </div>

              <div class="flex-grow-1 d-flex align-items-center px-3 py-1 border-start w-100">
                <i class="bi bi-geo-alt text-muted me-2 flex-shrink-0"></i>
                <input type="text" class="form-control border-0 shadow-none"
                       placeholder="City or remote" id="locationInput"/>
              </div>

              <button class="btn btn-find flex-shrink-0 fw-bold" id="hhh"
                      style="color:#f6f6f2; background-color:#8d9b6a;
                             min-width:140px; height:45px; font-size:0.85rem;
                             border-radius:50px; white-space:nowrap">
                Find Jobs
              </button>
            </div>
          </div>
        </div>

       
        <div class="tag-pills fade-up fade-up-4" id="hipills">
          <span class="tag-pill">Marketing</span>
          <span class="tag-pill">Data</span>
          <span class="tag-pill">Tech</span>
          <span class="tag-pill">Design</span>
          <span class="tag-pill">Finance</span>
          <span class="tag-pill">HR</span>
        </div>
      </div>
    </header>
    <section class="stats-divider py-5 text-white">
      <div class="container text-center px-3 px-md-4">
        <div class="row g-4">
          <div class="col-12 col-md-4">
            <h2 class="fw-bold" style="color:#8d9b6a"><?php echo $totaloffre; ?></h2>
            <p class="small text-uppercase opacity-50 m-0">Live Job Offers</p>
          </div>
          <div class="col-12 col-md-4 stat-border border-start border-secondary border-opacity-25">
            <h2 class="fw-bold" style="color:#8d9b6a"><?php echo $totalcompany; ?></h2>
            <p class="small text-uppercase opacity-50 m-0">Trusted Partners</p>
          </div>
          <div class="col-12 col-md-4 stat-border border-start border-secondary border-opacity-25">
            <h2 class="fw-bold" style="color:#8d9b6a"><?php echo $totalapplications; ?></h2>
            <p class="small text-uppercase opacity-50 m-0">Monthly Candidates</p>
          </div>
        </div>
      </div>
    </section>

   
    <section class="py-5 my-4 my-md-5">
      <div class="container px-3 px-md-4">

       
        <div class="d-flex flex-column flex-sm-row justify-content-between
                    align-items-start align-items-sm-end gap-3 mb-5">
          <div>
            <p class="section-label mb-1">Featured Opportunities</p>
            <h2 class="fw-bold m-0">Recent job openings</h2>
          </div>
          <a href="../alljob/alljob.php"
             class="btn btn-outline-dark rounded-pill flex-shrink-0">
            View All Jobs <i class="bi bi-arrow-up-right ms-1"></i>
          </a>
        </div>

        <!-- Cards -->
        <div class=" row g-4" id="jobContainer">

       
    

        

          
        

        </div>
      </div>
    </section>

   
    <footer class="footer-forest text-white pt-5 pb-4" id="About">
      <div class="container px-3 px-md-4">
        <div class="row gy-4">

        
          <div class="col-12 col-lg-4 mb-2">
            <div class="d-flex align-items-center gap-2 mb-2">
              <img src="../image/logoentreprisa.png" height="50" width="66" alt="Logo" />
              <span class="fw-bold fs-4 text-uppercase">Entreprisa</span>
            </div>
            <p class="opacity-50 small" style="max-width:280px">
              Connecting ambitious talent with forward-thinking companies.
            </p>
          </div>

          
          <div class="col-6 col-md-4 col-lg-2">
            <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Start your business</h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="#" class="footer-link">
                  <i class="bi bi-cloud-plus me-1"></i>Join as a company
                </a>
              </li>
              <li class="mb-2">
                <a href="#" class="footer-link">
                  <i class="bi bi-bar-chart me-1"></i>Try Entreprisa
                </a>
              </li>
            </ul>
          </div>

          <div class="col-6 col-md-4 col-lg-2">
            <h6 class="text-uppercase small fw-bold mb-3 opacity-50">The Product</h6>
            <ul class="list-unstyled">
              <li class="mb-2"><a href="#" class="footer-link">Contact us</a></li>
              <li class="mb-2"><a href="#" class="footer-link">Questions</a></li>
              <li class="mb-2"><a href="#" class="footer-link">Blog</a></li>
            </ul>
          </div>

          <div class="col-6 col-md-4 col-lg-2">
            <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Legal</h6>
            <ul class="list-unstyled">
              <li class="mb-2"><a href="#" class="footer-link">Terms &amp; Conditions</a></li>
              <li class="mb-2"><a href="#" class="footer-link">Privacy Policy</a></li>
              <li class="mb-2"><a href="#" class="footer-link">Cookie Policy</a></li>
            </ul>
          </div>

        </div>

        <hr class="opacity-25 mt-4" />

        <div class="d-flex flex-column flex-md-row justify-content-between
                    align-items-center gap-2 small opacity-50 pt-2">
          <p class="mb-0">© 2026 Entreprisa Inc. All rights reserved.</p>
          <ul class="list-inline mb-0">
            <li class="list-inline-item me-3">
              <a href="#" class="text-white text-decoration-none">Privacy</a>
            </li>
            <li class="list-inline-item me-3">
              <a href="#" class="text-white text-decoration-none">Terms</a>
            </li>
            <li class="list-inline-item">
              <a href="#" class="text-white text-decoration-none">Cookies</a>
            </li>
          </ul>
        </div>

      </div>
    </footer>
<script src="offre.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>