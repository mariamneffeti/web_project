<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entreprisa</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Montserrat:wght@700;800&family=Roboto+Mono:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
 
  <style>
    :root {
      --primary-teal: #388087;
      --dark-forest: #0d1f1b;
      --bg-light: #F6F6F2;
      --accent-blue: #102E4A;
    }
 
    * { box-sizing: border-box; }
 
    body {
      background-color: var(--bg-light);
      font-family: 'Inter', sans-serif;
      color: #111;
    }
 
    /* ── NAVBAR ── */
    .custom-navbar { background: linear-gradient(90deg, #388087, #0d1f1b); }
    .dropdown-toggle::after { display: none !important; }
    .dropdown-menu { border-radius: 8px; padding: 8px 0; background-color: #212529; }
    .dropdown-item { font-weight: 500; transition: background 0.2s; color: white; }
    .dropdown-item:hover { background-color: rgba(255,255,255,0.1); color: white; }
 
    /* ── HERO ── */
    .hero-section {
      width: 100%;
      height: 700px;
      background:
        linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600&q=80')
        no-repeat center / cover;
      position: relative;
    }
 
    .hero-animated-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 700px;
      z-index: 1;
      animation: pulseOverlay 8s ease-in-out infinite;
    }
 
    @keyframes pulseOverlay {
      0%   { background-color: rgba(42,41,41,0.3); }
      50%  { background-color: rgba(0,0,0,0.5); }
      100% { background-color: rgba(42,41,41,0.3); }
    }
 
    .hero-info { padding-top: 15rem; }
 
    .hero-info h1 {
      font-size: 55px;
      color: #F6F6F2;
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      line-height: 1.15;
    }
 
    .hero-info h2 {
      color: #F6F6F2;
      font-family: 'Roboto Mono', monospace;
      font-size: 1.1rem;
      padding-top: 1rem;
      opacity: 0.9;
    }
 
    .btn-hero {
      color: #F6F6F2;
      border: 1px solid #F6F6F2;
      background: transparent;
      transition: background 0.2s, color 0.2s;
    }
    .btn-hero:hover { background: rgba(255,255,255,0.18); color: #fff; }
 
    /* ── SERVICES ── */
    #services { background-color: var(--bg-light); padding: 80px 20px; }
 
    .section-label {
      color: var(--primary-teal);
      font-family: 'Roboto Mono', monospace;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 2px;
    }
 
    .section-title {
      color: var(--accent-blue);
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
    }
 
    .service-card-wrap {
      background: #fff;
      border: 1px solid #ecf1ec;
      border-radius: 12px;
      overflow: hidden;
      height: 100%;
      display: flex;
      flex-direction: column;
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.4s ease, transform 0.4s ease, box-shadow 0.25s ease;
    }
    .service-card-wrap.show { opacity: 1; transform: translateY(0); }
    .service-card-wrap:hover {
      transform: translateY(-6px) !important;
      box-shadow: 0 12px 28px rgba(56,128,135,0.18);
    }
 
    .service-card-img { width: 100%; height: 190px; object-fit: cover; display: block; }
 
    .service-card-body {
      padding: 1.4rem 1.5rem 1.6rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
 
    .card-top-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.75rem;
    }
 
    .service-badge {
      background: #e8f4f5;
      color: var(--primary-teal);
      font-size: 0.72rem;
      font-family: 'Roboto Mono', monospace;
      padding: 3px 10px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
 
    .service-card-icon {
      width: 36px; height: 36px;
      border-radius: 8px;
      background: #e8f4f5;
      color: var(--primary-teal);
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
    }
 
    .service-card-title {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      color: #111;
      margin-bottom: 0.35rem;
    }
 
    .service-card-desc { font-size: 0.85rem; color: #555; margin-bottom: 0.85rem; }
 
    .service-card-list {
      list-style: none;
      padding: 0; margin: 0;
      flex-grow: 1;
    }
    .service-card-list li {
      font-size: 0.82rem;
      color: #444;
      padding: 5px 0;
      border-top: 1px solid #f0f0f0;
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .service-card-list li::before {
      content: '';
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--primary-teal);
      flex-shrink: 0;
    }
 
    .card-footer-row {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      border-top: 1px solid #f0f0f0;
      padding-top: 0.75rem;
      margin-top: 0.85rem;
    }
 
    .card-read-more {
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--primary-teal);
      text-decoration: none;
    }
    .card-read-more:hover { color: var(--accent-blue); }
 
    /* ── CAPABILITIES ── */
    .capabilities-section { background-color: #111e1c; color: white; }
 
    /* ── HIGHLIGHT ── */
    .highlight { animation: highlightAnim 1s ease; }
    @keyframes highlightAnim {
      0%   { background-color: transparent; }
      30%  { background-color: rgba(56,128,135,0.15); }
      100% { background-color: transparent; }
    }
 
    /* ── FOOTER ── */
    .footer-forest {
      background: linear-gradient(180deg, #388087 0%, #0d1f1b 100%);
      border-top: 1px solid rgba(255,255,255,0.1);
      font-family: 'Inter', sans-serif;
    }
    .footer-link {
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }
    .footer-link:hover { color: #fff; padding-left: 5px; }
    .footer-brand { display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem; }
 
    /* ── BACK TO TOP ── */
    #backToTop {
      position: fixed;
      bottom: 24px; right: 24px;
      padding: 10px 16px;
      border: none;
      background: var(--accent-blue);
      color: white;
      border-radius: 8px;
      cursor: pointer;
      display: none;
      font-size: 0.85rem;
      z-index: 999;
      transition: background 0.2s;
    }
    #backToTop:hover { background: #0a1e30; }
  </style>
</head>
<body>
 
<!-- ══ NAVBAR ══ -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark custom-navbar">
  <div class="container-fluid">
    <button class="navbar-toggler me-2" type="button"
      data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <img src="../image/logoentreprisa.png" height="50" width="70" alt="Logo" />
    <a class="navbar-brand fw-bold h1 ms-2" href="#">Entreprisa</a>
 
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-3">
        <li class="nav-item"><a class="nav-link active" href="#" style="font-family:Inter;font-size:1.1rem;">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="aboutBtn" style="font-family:Inter;font-size:1.1rem;">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="servicesBtn" style="font-family:Inter;font-size:1.1rem;">Services</a></li>
      </ul>
    </div>
 
    <div class="navbar-nav ms-auto d-flex flex-row gap-2 align-items-center">
      <a href="../login/login.php" class="nav-link text-white">Login</a>
      <a href="../register/register.php" class="btn btn-sm"
        style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);">
        Register
      </a>
    </div>
  </div>
</nav>
 
<!-- ══ OFFCANVAS SIDEBAR ══ -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar"
  style="background-color:#0d1f1b;color:white;">
  <div class="offcanvas-header border-bottom border-secondary">
    <h5 class="offcanvas-title fw-bold">ENTREPRISA</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="nav flex-column gap-3">
      <li class="nav-item">
        <a class="nav-link text-white d-flex align-items-center" href="#">
          <i class="bi bi-house me-3"></i> Home
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white d-flex align-items-center" href="#" id="aboutBtnCanvas">
          <i class="bi bi-info-circle me-3"></i> About
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white d-flex align-items-center" href="#" id="servicesBtnCanvas">
          <i class="bi bi-grid me-3"></i> Services
        </a>
      </li>
      <li class="nav-item mt-3 border-top border-secondary pt-3">
        <a class="nav-link text-white d-flex align-items-center" href="../login/login.php">
          <i class="bi bi-box-arrow-in-right me-3"></i> Login
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white d-flex align-items-center" href="../register/register.php">
          <i class="bi bi-person-plus me-3"></i> Register
        </a>
      </li>
    </ul>
  </div>
</div>
 
<!-- ══ HERO ══ -->
<section class="hero-section" style="margin-top:56px;">
  <div class="hero-animated-overlay">
    <div class="container">
      <div class="hero-info">
        <h1>Where great<br>partnerships begin</h1>
        <h2>We connect companies, employees &amp; clients<br>in one unified platform.</h2>
        <div class="row mb-3 pt-3">
          <div class="col-auto">
            <a href="../login/login.php" class="btn btn-hero btn-lg px-4">Login</a>
          </div>
          <div class="col-auto">
            <a href="../register/register.php" class="btn btn-hero btn-lg px-4">Register</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
 
<!-- ══ SERVICES ══ -->
<section id="services" class="py-5">
  <div class="container text-center">
    <p class="section-label mb-2">What we offer</p>
    <h2 class="section-title mb-5">Our Services</h2>
 
    <div class="row g-4">
 
      <!-- Card 1 – Company -->
      <div class="col-md-4">
        <div class="service-card-wrap">
          <img src="../image/one.jpg"
               alt="Company Services" class="service-card-img" />
          <div class="service-card-body">
            <div class="card-top-row">
              <span class="service-badge">Enterprise</span>
              <div class="service-card-icon"><i class="bi bi-building"></i></div>
            </div>
            <h5 class="service-card-title">Company Services</h5>
            <p class="service-card-desc">End-to-end tools to run and scale your organisation — from HR to sales pipelines.</p>
            <ul class="service-card-list">
              <li>Clients &amp; Employees Management</li>
              <li>Sales Management</li>
              <li>Recruitment</li>
            </ul>
            <div class="card-footer-row">
              <a href="../login/login.php" class="card-read-more">
                Get started <i class="bi bi-arrow-up-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
 
      <!-- Card 2 – Employee -->
      <div class="col-md-4">
        <div class="service-card-wrap">
          <img src="../image/two.jpg"
               alt="Employee Services" class="service-card-img" />
          <div class="service-card-body">
            <div class="card-top-row">
              <span class="service-badge">Workforce</span>
              <div class="service-card-icon"><i class="bi bi-people"></i></div>
            </div>
            <h5 class="service-card-title">Employee Services</h5>
            <p class="service-card-desc">Everything employees need to manage their work, track clients and close more sales.</p>
            <ul class="service-card-list">
              <li>Clients Management</li>
              <li>Sales Management</li>
              <li>Internal Services</li>
            </ul>
            <div class="card-footer-row">
              <a href="../login/login.php" class="card-read-more">
                Get started <i class="bi bi-arrow-up-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
 
      <!-- Card 3 – Client / Applicant -->
      <div class="col-md-4">
        <div class="service-card-wrap">
          <img src="../image/client.jpg"
               alt="Client Applicant" class="service-card-img" />
          <div class="service-card-body">
            <div class="card-top-row">
              <span class="service-badge">Applicant</span>
              <div class="service-card-icon"><i class="bi bi-person-badge"></i></div>
            </div>
            <h5 class="service-card-title">Client / Applicant</h5>
            <p class="service-card-desc">Discover opportunities, build your CV and connect directly with hiring companies.</p>
            <ul class="service-card-list">
              <li>Articles Feed</li>
              <li>Company Offers</li>
              <li>CV Management</li>
            </ul>
            <div class="card-footer-row">
              <a href="../register/register.php" class="card-read-more">
                Get started <i class="bi bi-arrow-up-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
 
    </div>
  </div>
</section>
 
<!-- ══ CAPABILITIES ══ -->
<section class="capabilities-section py-5">
  <div class="container text-center">
    <div class="mb-5">
      <h6 class="text-uppercase fw-bold small opacity-50">Capabilities</h6>
      <h2 class="display-5 fw-bold mb-3">What we bring to the table</h2>
      <p class="text-secondary mx-auto" style="max-width:600px;">
        Whether you're running a company, managing a team, or searching for your next opportunity —
        we connect you with the tools and people that matter.
      </p>
    </div>
    <div class="row g-4 mt-2">
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-briefcase fs-2 mb-3 d-block" style="color:var(--primary-teal);"></i>
          <h4 class="h5">Seamless Collaboration</h4>
          <p class="text-secondary small">Unified workspace for companies, employees and clients.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-globe fs-2 mb-3 d-block" style="color:var(--primary-teal);"></i>
          <h4 class="h5">Global Reach</h4>
          <p class="text-secondary small">Access markets and talent across every major region.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-gear fs-2 mb-3 d-block" style="color:var(--primary-teal);"></i>
          <h4 class="h5">Expert Support</h4>
          <p class="text-secondary small">Our team stands ready to help you succeed at every step.</p>
        </div>
      </div>
    </div>
    <div class="mt-5 d-flex justify-content-center align-items-center gap-3 flex-wrap">
      
      <a href="../register/register.php" class="text-white text-decoration-none">
        Create account <i class="bi bi-chevron-right"></i>
      </a>
    </div>
  </div>
</section>
 
<!-- ══ FOOTER (same as clienthome) ══ -->
<footer class="footer-forest text-white py-5" id="contact">
  <div class="container" style="margin-top:5rem;">
    <div class="row gy-4">
 
      <!-- Brand -->
      <div class="col-lg-4 col-md-12">
        <div class="footer-brand">
          <img src="../image/logoentreprisa.png" height="60" width="80" alt="Logo" />
          <span class="fw-bold fs-4 text-uppercase">Entreprisa</span>
        </div>
      </div>
 
      <!-- Start your business -->
      <div class="col-6 col-md-3 col-lg-2">
        <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Start<br>your business</h6>
        <ul class="list-unstyled">
          <li class="mb-2">
            <a href="#" class="footer-link">
              <i class="bi bi-cloud-plus me-2"></i>Join us as a company leader
            </a>
          </li>
          <li class="mb-2">
            <a href="#" class="footer-link">
              <i class="bi bi-bar-chart me-2"></i>Try Entreprisa
            </a>
          </li>
        </ul>
      </div>
 
      <!-- The Product -->
      <div class="col-6 col-md-3 col-lg-2">
        <h6 class="text-uppercase small fw-bold mb-3 opacity-50">The Product</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link">Contact us</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Questions</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Blog</a></li>
        </ul>
      </div>
 
      <!-- Legal -->
      <div class="col-6 col-md-3 col-lg-2">
        <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Legal</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link">Terms &amp; Conditions</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Privacy policy</a></li>
        </ul>
      </div>
 
    </div>
 
    <hr class="opacity-25 mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small opacity-50 pt-3">
      <p class="mb-0">© 2026 Entreprisa. All rights reserved.</p>
      <ul class="list-inline mb-0">
        <li class="list-inline-item me-3"><a href="#" class="text-white text-decoration-none">Privacy</a></li>
        <li class="list-inline-item me-3"><a href="#" class="text-white text-decoration-none">Terms</a></li>
        <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Cookies</a></li>
      </ul>
    </div>
  </div>
</footer>
 
<button id="backToTop">⇧ Top</button>
 
<script>
  // About → scroll to footer
  document.getElementById("aboutBtn").addEventListener("click", e => {
    e.preventDefault();
    document.getElementById("contact").scrollIntoView({ behavior: "smooth" });
  });
  document.getElementById("aboutBtnCanvas")?.addEventListener("click", e => {
    e.preventDefault();
    document.getElementById("contact").scrollIntoView({ behavior: "smooth" });
  });
 
  // Services → highlight + animate cards
  function activateServices(e) {
    e.preventDefault();
    const sec = document.getElementById("services");
    sec.scrollIntoView({ behavior: "smooth" });
    sec.classList.remove("highlight");
    void sec.offsetWidth;
    sec.classList.add("highlight");
    document.querySelectorAll(".service-card-wrap").forEach((card, i) => {
      card.classList.remove("show");
      setTimeout(() => card.classList.add("show"), i * 150);
    });
  }
  document.getElementById("servicesBtn").addEventListener("click", activateServices);
  document.getElementById("servicesBtnCanvas")?.addEventListener("click", activateServices);
 
  // Auto-show cards on page load
  window.addEventListener("load", () => {
    document.querySelectorAll(".service-card-wrap").forEach((card, i) => {
      setTimeout(() => card.classList.add("show"), 300 + i * 150);
    });
  });
 
  // Back to top
  const btn = document.getElementById("backToTop");
  window.addEventListener("scroll", () => {
    btn.style.display = window.scrollY > 200 ? "block" : "none";
  });
  btn.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
</script>
</body>
</html>