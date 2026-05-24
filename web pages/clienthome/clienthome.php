


  
  <body  style="height: 1500px; background-color: #F6F6F2 ;">
    
    <?php 
        $pageTitle = "Home"; 
        include('../squelleteuser/header.php');
      ?>
      <link rel="stylesheet" href="clienthome.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <section class="hero-fade" Style="  width: 100% ; height :700px ; background:linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../image/homeclient.png' ) no-repeat center / cover;">
        <div class="hero-animated-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 700px; z-index: 1;">C:\Users\infosud\Downloads\js\web_project\web pages\image\homeclient.png
        <div class="container ">
            <div class="info" Style=" padding-top: 15rem;">
                <h1 style=" font-size: 55px;  color:#F6F6F2;">Where great<br> partnerships begin</h1>
                <h2 style=" color:#F6F6F2; font-family: 'Roboto Mono'; font-size: 20px; padding-top: 1rem;"> We connect the world's best companies. <br>
                    Build something remarkable with brands you can trust.</h2>
            <div class="row mb-3 pt-3">
                
                    <div class="col-auto ">
                         <a href="#explorer" class="btn btn-outline" Style="color:#F6F6F2; border: 1px solid #F6F6F2;">Explore</a>
                    </div>
                    <div class="col-auto">
                       <a href="#About" class="btn btn-outline" Style="color:#F6F6F2; border: 1px solid #F6F6F2; " >About us</a>
                    </div>
                
            </div>

        </div>
        </div>
        </div>
    </section>
   <section class="py-5" id="explorer" style="background-color: #F6F6F2;">
  <div class="container">
    <p class="text-center mb-2" style="color:#388087; font-family:'Roboto Mono'; font-size:0.85rem; text-transform:uppercase; letter-spacing:2px;">From our partners</p>
    <h2 class="text-center fw-bold mb-5" style="color:#1B4965; font-family:Montserrat;">Latest Articles</h2>

    <div class="row g-4" id="articlesGrid">
      <!-- cards injected by JS -->
      <div class="col-12 text-center text-muted py-5">
        <div class="spinner-border" style="color:#388087" role="status"></div>
      </div>
    </div>
  </div>
</section>
<section class="py-5 bg-dark text-white" >
  <div class="container text-center">
    <div class="mb-5">
      <h6 class="text-uppercase fw-bold small">Capabilities</h6>
      <h2 class="display-5 fw-bold mb-3">What we bring to the table</h2>
      <p class="text-secondary mx-auto" style="max-width: 600px;">
        Whether you’re searching for your next job or researching companies, we connect you with the insights, opportunities, and people that matter.
      </p>
    </div>

    <div class="row g-4 mt-4">
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-briefcase fs-2 mb-3"></i>
          <h4 class="h5">Seamless career discovery</h4>
          <p class="text-secondary small">Connect with job opportunities and company knowledge in a smooth, unified experience.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-globe fs-2 mb-3"></i>
          <h4 class="h5">Global reach</h4>
          <p class="text-secondary small">Access markets and opportunities across every major region.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-gear fs-2 mb-3"></i>
          <h4 class="h5">Expert support</h4>
          <p class="text-secondary small">Our team stands ready to help you succeed at every step.</p>
        </div>
      </div>
    </div>

    <div class="mt-5 d-flex justify-content-center align-items-center gap-3">
      <a href="../offre/offre.php" class="btn btn-outline" Style="color:#F6F6F2; border: 1px solid #F6F6F2;">Discover offers</a>
    </div>
  </div>
</section>
<footer  class="footer-forest text-white py-5" id="About" >
  <div  class="container " Style ="margin-top: 5rem ;">
    <div class="row gy-4">
      
      <div class="col-lg-4 col-md-12">
        <div class="footer-brand mb-3">
          <img src="../image/logoentreprisa.png" height="60" width="80" alt="Logo" />
          <span class="fw-bold fs-4 text-uppercase">Entreprisa</span>
        </div>
      </div>

      <div class="col-6 col-md-3 col-lg-2">
        <h6 class="text-uppercase small fw-bold mb-3 opacity-50">    Start <br>   your busniss</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link"><i class="bi bi-cloud-plus me-2"></i>Join us as a company leader </a></li>
          <li class="mb-2"><a href="#" class="footer-link"><i class="bi bi-bar-chart me-2"></i>Try Entreprisa</a></li>
        </ul>
      </div>

    
      <div class="col-6 col-md-3 col-lg-2">
        <h6 class="text-uppercase small fw-bold mb-3 opacity-50">The Product</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link">Contacts us</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Questions</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Blog</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3 col-lg-2">
        <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Legal</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link">Terms & Conditions</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Privacy policy</a></li>
        </ul>
      </div>
  <hr class="opacity-25">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small opacity-50 pt-3">
                <p>© 2026 CareerFlow Inc. All rights reserved.</p>
                <ul class="list-inline">
                    <li class="list-inline-item me-3"><a href="#" class="text-white text-decoration-none">Privacy</a></li>
                    <li class="list-inline-item me-3"><a href="#" class="text-white text-decoration-none">Terms</a></li>
                    <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Cookies</a></li>
                </ul>
            </div>
    </div>
  </div>
</footer>
<script src="clienthome.js"></script>
</body>
</html>