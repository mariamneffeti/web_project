console.log("All jobs script loaded.");

let JOBS = [];
const jobContainer = document.querySelector("#jobContainer");

fetch("/api/get_offers.php")
  .then((res) => res.json())
  .then((data) => {
    JOBS = Array.isArray(data.data) ? data.data : [];

    if (!jobContainer) {
      console.error("Job container not found!");
      return;
    }

    nofiltersdisplay(JOBS);
  })
  .catch((err) => {
    console.error("Fetch failed:", err);
  });

function nofiltersdisplay(tab) {
  const jobContainer = document.querySelector("#jobContainer");
  if (!jobContainer) return;
  jobContainer.innerHTML = ""; // ← clear before adding
  const count = Math.min(tab.length, 3);

  for (let i = 0; i < count; i++) {
    const jobElement = document.createElement("div");
    jobElement.className = "col-lg-4 col-md-6";
    jobElement.innerHTML = `
      <div class="card job-card p-4 w-100">
      
        <div class="d-flex justify-content-between align-items-start mb-4">
          <div class="company-logo">
            <i class="bi ${tab[i].icon} fs-3" style="color:${tab[i].iconColor}"></i>
          </div>
          <span class="badge rounded-pill"
                style="background:#b8cfd2; border:2px solid #388087; color:#388087">
            ${tab[i].type}
            
          </span>
         
        </div>
        <h5 class="fw-bold mb-1">${tab[i].title}</h5>
        <p class="text-muted small">${tab[i].company} · ${tab[i].location}</p>
        <p class="text-secondary small mb-4">${tab[i].desc}</p>
        <div class="d-flex justify-content-between align-items-center mt-auto">
          <span class="fw-bold">${tab[i].salaryMin} – ${tab[i].salaryMax} DT</span>
           <a href="../cv/cvv.php?offre_id=${tab[i].id}" class="btn-apply">
          Apply
        </a>
        </div>
      </div>
    `;
    jobContainer.appendChild(jobElement);
  }
}

function displayJobs() {
  const jobContainer = document.querySelector("#jobContainer");
  if (!jobContainer) return;

  const keywordD = document.getElementById("searchInput").value.toLowerCase();
  const locationN = document
    .getElementById("locationInput")
    .value.toLowerCase();

  let filteredJobs = JOBS.filter((job) => {
    if (
      keywordD &&
      !job.title.toLowerCase().includes(keywordD) &&
      !job.tags.some((tag) => tag.toLowerCase().includes(keywordD)) &&
      !job.company.toLowerCase().includes(keywordD) &&
      !job.type.toLowerCase().includes(keywordD)
    )
      return false;
    if (locationN && !job.location.toLowerCase().includes(locationN))
      return false;
    return true;
  });

  jobContainer.innerHTML = "";
  if (filteredJobs.length === 0) {
    nofiltersdisplay(JOBS);
    return;
  }
  nofiltersdisplay(filteredJobs);
}

const searchButton = document.querySelector("#hhh");
if (!searchButton) {
  console.error("Search button not found!");
} else {
  searchButton.addEventListener("click", displayJobs);
}

const pilldiv = document.querySelector("#hipills");
if (pilldiv) {
  pilldiv.addEventListener("click", (e) => {
    const val = e.target.textContent.trim();
    if (val === "Marketing") pillsdisplay("marketing");
    else if (val === "HR") pillsdisplay("hr");
    else if (val === "Data") pillsdisplay("data");
    else if (val === "Tech") pillsdisplay("tech");
    else if (val === "Design") pillsdisplay("design");
    else if (val === "Finance") pillsdisplay("finance");
    else nofiltersdisplay(JOBS);
  });
}

function pillsdisplay(type) {
  const filtered = JOBS.filter((job) => job.cat === type);
  if (filtered.length === 0) {
    nofiltersdisplay(JOBS);
    return;
  }
  nofiltersdisplay(filtered);
}
