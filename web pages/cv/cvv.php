
<?php include 'cv.php'; ?>
<?php 
    $pageTitle = "Cv"; 
    include('../squelleteuser/header.php'); 
?>
<?php
require_once __DIR__ . '/../../config/session_check.php';
?>
  <body style="height: 1150px; background-color: #f6f6f2">
    
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

    <?php include('../squelleteuser/footeruser.php'); ?>
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
<?php endif; ?>
  </body>
</html>
