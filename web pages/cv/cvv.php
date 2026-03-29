
<?php include 'cv.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="cv.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Montserrat:wght@700&display=swap"
      rel="stylesheet"
    />
    <title>Document</title>
  </head>
  
  </style>
  <body style="height: 1150px; background-color: #f6f6f2">
    <nav
      class="navbar navbar-expand-sm fixed-top"
      style="background-color: #388087"
      data-bs-theme="dark"
    >
      <div class="container-fluid">
        <img
          src="../image/logoentreprisa.png"
          height="60"
          width="80"
          alt="Logo"
        />
        <a class="navbar-brand h1" href="../home/home.html">Entreprisa</a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNavAltMarkup"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav mx-auto gap-1 gap-md-3 gap-lg-5">
            <a
              class="nav-link p-2"
              href="../clienthome/clienthome.html"
              style="font-family: Inter; font-size: 1.2rem"
              >Home</a
            >
            <a
              class="nav-link p-2"
              href="../offre/offre.php"
              style="font-family: Inter; font-size: 1.2rem"
              >Offre</a
            >
            <a
              class="nav-link p-2 active"
              href="../cv/cv.php"
              style="font-family: Inter; font-size: 1.2rem"
              >CV</a
            >
          </div>
          <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle d-flex align-items-center"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
              >
                <img
                  src="../image\profile.png"
                  alt="Profile"
                  width="32"
                  height="32"
                  class="rounded-circle me-2"
                />
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="#">
                    <img
                      src="../image/profile.png"
                      alt="Profile"
                      width="32"
                      height="32"
                      class="rounded-circle me-2"
                    /><span>Profil </span></a
                  >
                </li>
                <li><hr class="dropdown-devider" /></li>
                <li>
                  <a class="dropdown-item" href="#"
                    ><img
                      src="../image/logout.png"
                      alt="Profile"
                      width="32"
                      height="32"
                      class="me-2"
                    /><span>Logout </span></a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="container" style="margin-top: 150px">
  <h1 style="font-family: Montserrat; font-size: 50px">Upload your CV</h1>

  <?php if ($offre): ?>
    <h2 style="color: #1b4965; font-family: Inter">
      Applying for <strong><?= htmlspecialchars($offre['title']) ?></strong>
      at <strong><?= htmlspecialchars($offre['company_name']) ?></strong>
    </h2>
  <?php else: ?>
    <h2 style="color: red;"><?= $error ?></h2>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert alert-success mt-3"><?= $success ?></div>
  <?php endif; ?>
</div>

    <div class="container mt-5">
<form action="cvv.php?offre_id=<?= $offre_id ?>" method="post" enctype="multipart/form-data">
      <div class="row gx-5">
        <div class="col-md-4 pe-4">
          <div class="mb-3">
            <label
              for="firstName"
              class="form-label"
              style="font-family: Inter; font-size: 17px"
              >Your Name</label
            >
            <input
              type="text"
              class="form-control"
              name="firstName"
              placeholder="Username"
            />
          </div>

          <div class="mb-3">
            <label
              for="lastName"
              class="form-label"
              style="font-family: Inter; font-size: 17px"
              >Your Family Name</label
            >
            <input
              type="text"
              class="form-control"
              name="lastName"
              placeholder="Family name"
            />
          </div>

          <div class="mb-3">
            <label
              for="email"
              class="form-label"
              style="font-family: Inter; font-size: 17px"
              >Your Email</label
            >
            <input
              type="email"
              class="form-control"
              name="email"
              placeholder="Email"
            />
          </div>

          <div class="row mb-3">
            <div class="col-6">
              <label
                for="nationality"
                class="form-label"
                style="font-family: Inter; font-size: 17px"
                >Nationality</label
              >
              <input
                class="form-control"
                list="nationalityOptions"
                name="nationality"
                placeholder="Nationality"
              />
              <datalist id="nationalityOptions">
                <option value="Tunisian"></option>
                <option value="French"></option>
                <option value="American"></option>
                <option value="British"></option>
                <option value="German"></option>
                <option value="Italian"></option>
                <option value="Spanish"></option>
                <option value="Canadian"></option>
                <option value="Chinese"></option>
                <option value="Japanese"></option>
              </datalist>
            </div>
            <div class="col-6">
              <label
                for="phone"
                class="form-label"
                style="font-family: Inter; font-size: 17px"
                >Phone</label
              >
              <input
                type="tel"
                class="form-control"
                name="phone"
                placeholder="Phone number"
              />
            </div>
          </div>

          <div class="mb-3">
            <label
              for="address"
              class="form-label"
              style="font-family: Inter; font-size: 17px"
              >Adresse</label
            >
            <input
              type="text"
              class="form-control"
              placeholder="your current position"
              name="address"
            />
            <div class="form-text">Adresse doit être précise</div>
          </div>

          <div class="mb-3">
            <label
              for="linkedin"
              class="form-label"
              style="font-family: Inter; font-size: 17px"
              >Profil professionnel</label
            >
            <input
              type="text"
              class="form-control"
              name="linkedin"
              placeholder="LinkedIn"
            />
          </div>

          <div class="form-check mb-3">
            <input
              class="form-check-input"
              type="checkbox"
              name="terms"
              required
            />
            <label class="form-check-label" for="terms">
              I agree to Terms & Conditions
            </label>
          </div>
        </div>

        <div class="col-md-8 ps-5 d-flex flex-column align-items-center">
          <div class="w-100" style="max-width: 500px">
            <label
              class="form-label text-start"
              style="font-family: Montserrat; color: #1b4965"
              >Upload Your CV</label
            >

            <div
              class="border border-4 rounded p-5 text-center mb-3"
              style="
                background-color: #f0f4f8;
                cursor: pointer;
                border-style: dashed !important;
                border-color: #1b4965 !important;
                min-height: 300px;
                display: flex;
                align-items: center;
                justify-content: center;
              "
              onclick="document.getElementById('fileInput').click()"
            >
              <p
                class="mb-0"
                style="color: #1b4965; font-family: Inter; font-size: 1.1rem"
              >
                <strong>Drag & Drop your CV here or<br />Browse Files</strong>
              </p>

              <input
                type="file"
                name="fichier"
                class="d-none"
                id="fileInput"
                accept=".pdf,.docx"
              />
            </div>
          </div>

          <p class="text-muted small mb-3">
            Accepted: <br />
            PDF, DOCX
          </p>

          <button
            type="button"
            class="btn mb-3 px-5"
            style="background-color: #8d9b6a; color: #f6f6f2"
            onclick="document.getElementById('fileInput').click()"
          >
            Browse Files
          </button>

          <button
            type="submit"
            class="btn px-5"
            style="
              background-color: #8d9b6a;
              color: #f6f6f2;
              font-family: Inter;
            "
          >
            Submit Application
          </button>
        </div>
        
      </div>
      </form>
    </div>

    <footer
      class="footer-forest text-white py-5"
      id="About"
      style="margin-top: 5rem"
    >
      <div class="container" style="margin-top: 5rem">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-12">
            <div class="footer-brand mb-3">
              <img
                src="../image/logoentreprisa.png"
                height="60"
                width="80"
                alt="Logo"
              />
              <span class="fw-bold fs-4 text-uppercase">Entreprisa</span>
            </div>
          </div>

          <div class="col-6 col-md-3 col-lg-2">
            <h6 class="text-uppercase small fw-bold mb-3 opacity-50">
              Start <br />
              your busniss
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="#" class="footer-link"
                  ><i class="bi bi-cloud-plus me-2"></i>Join us as a company
                  leader
                </a>
              </li>
              <li class="mb-2">
                <a href="#" class="footer-link"
                  ><i class="bi bi-bar-chart me-2"></i>Try Entreprisa</a
                >
              </li>
            </ul>
          </div>

          <div class="col-6 col-md-3 col-lg-2">
            <h6 class="text-uppercase small fw-bold mb-3 opacity-50">
              The Product
            </h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="#" class="footer-link">Contacts us</a>
              </li>
              <li class="mb-2">
                <a href="#" class="footer-link">Questions</a>
              </li>
              <li class="mb-2"><a href="#" class="footer-link">Blog</a></li>
            </ul>
          </div>

          <div class="col-6 col-md-3 col-lg-2">
            <h6 class="text-uppercase small fw-bold mb-3 opacity-50">Legal</h6>
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="#" class="footer-link">Terms & Conditions</a>
              </li>
              <li class="mb-2">
                <a href="#" class="footer-link">Privacy policy</a>
              </li>
            </ul>
          </div>
          <hr class="opacity-25" />
          <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-center small opacity-50 pt-3"
          >
            <p>© 2026 CareerFlow Inc. All rights reserved.</p>
            <ul class="list-inline">
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
      </div>
    </footer>
    <?php if ($success): ?>
<div id="successOverlay" style="
  position: fixed; inset: 0; background: rgba(0,0,0,0.5);
  display: flex; align-items: center; justify-content: center;
  z-index: 9999;">
  <div style="
    background: white; border-radius: 16px; padding: 40px 50px;
    text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    max-width: 420px; width: 90%;">
    <div style="font-size: 60px;">✅</div>
    <h2 style="font-family: Montserrat; color: #1b4965; margin: 15px 0 10px;">Application Received!</h2>
    <p style="font-family: Inter; color: #555; font-size: 1rem;">
      Your CV has been submitted successfully.<br>We'll be in touch soon!
    </p>
    <div style="
      margin-top: 20px; height: 6px; border-radius: 99px;
      background: #e0e0e0; overflow: hidden;">
      <div id="progressBar" style="
        height: 100%; width: 0%; border-radius: 99px;
        background: #8d9b6a; transition: width 3s linear;">
      </div>
    </div>
    <p style="font-family: Inter; color: #aaa; font-size: 0.85rem; margin-top: 10px;">
      Redirecting in 3 seconds...
    </p>
  </div>
</div>

<script>
  // Start progress bar
  setTimeout(() => {
    document.getElementById('progressBar').style.width = '100%';
  }, 50);

  // Redirect after 3 seconds
  setTimeout(() => {
    window.location.href = '../clienthome/clienthome.html';
  }, 3000);
</script>
<?php endif; ?>
  </body>
</html>
