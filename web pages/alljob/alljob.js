console.log("All jobs script loaded.");

let JOBS = [];
let activeCat = "all";
let maxSalary = 6000;
let currentPage = 1;
const PAGE_SIZE = 9;
const jobContainer = document.getElementById("jobsGrid");
const scriptTag = document.querySelector('script[src*="offre"]');
const scriptPath = scriptTag ? scriptTag.src : window.location.href;
const baseURL = scriptPath.substring(0, scriptPath.indexOf('/web_project/') + '/web_project/'.length);

fetch(`${baseURL}api/get_offers.php`)
  .then((res) => res.json())
  .then((data) => {console.log("Jobs loaded:", data)
    console.log('Debug info:', data.debug); // ← shows counts
    JOBS = Array.isArray(data.data)
     ? data.data : [];
    console.log("4. jobContainer:", jobContainer);
    document.getElementById("jobCountall").textContent = JOBS.length;
    document.getElementById("jobCounttech").textContent = JOBS.filter(j => j.cat === "tech").length;
    document.getElementById("jobCountdesign").textContent = JOBS.filter(j => j.cat === "design").length;
    document.getElementById("jobCountfinance").textContent = JOBS.filter(j => j.cat === "finance").length;
    document.getElementById("jobCountmarketing").textContent = JOBS.filter(j => j.cat === "marketing").length;
    document.getElementById("jobCounthr").textContent = JOBS.filter(j => j.cat === "hr").length;
    document.getElementById("jobCountdata").textContent = JOBS.filter(j => j.cat === "data").length;

    renderJobs();
  })
  .catch((err) => {
    console.error("Fetch failed:", err);
    document.getElementById("jobsGrid").innerHTML =
      '<p class="text-center text-danger">Failed to load jobs.</p>';
  });

function renderJobs() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const location = document.getElementById("locationInput").value.toLowerCase();
  const sort = document.getElementById("sortSelect").value;
  const checkedTypes = [...document.querySelectorAll("#typeList input:checked")].map(i => i.value);
  const checkedExps  = [...document.querySelectorAll("#expList input:checked")].map(i => i.value);

  let filtered = JOBS.filter((j) => {
    if (activeCat !== "all" && j.cat !== activeCat) return false;
    if (!checkedTypes.includes(j.type)) return false;
    if (!checkedExps.includes(j.exp)) return false;
    if (j.salaryMin > maxSalary) return false;
    if (keyword &&
      !j.title.toLowerCase().includes(keyword) &&
      !j.company.toLowerCase().includes(keyword) &&
      !j.tags.join(" ").toLowerCase().includes(keyword)
    ) return false;
    if (location && !j.location.toLowerCase().includes(location)) return false;
    return true;
  });

  if (sort === "salary-high") filtered.sort((a, b) => b.salaryMax - a.salaryMax);
  else if (sort === "salary-low") filtered.sort((a, b) => a.salaryMin - b.salaryMin);

  document.getElementById("jobCount").textContent = filtered.length;

  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  if (currentPage > totalPages) currentPage = 1;
  const paged = filtered.slice((currentPage - 1) * PAGE_SIZE, currentPage * PAGE_SIZE);

  const grid = document.getElementById("jobsGrid");
  grid.innerHTML = "";

  if (paged.length === 0) {
    grid.innerHTML = `<div class="no-results"><i class="bi bi-search"></i><p>No jobs match your filters.<br><small>Try adjusting your criteria.</small></p></div>`;
  } else {
    paged.forEach((job, idx) => {
      const badgeClass = {
        "Full-time": "badge-fulltime",
        "Contract":  "badge-contract",
        "Urgent":    "badge-urgent",
        "Part-time": "badge-parttime",
        "Internship":"badge-internship",
      }[job.type] || "badge-fulltime";

      const card = document.createElement("div");
      card.className = "job-card";
      card.style.animationDelay = `${idx * 50}ms`;
card.innerHTML = `
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div class="company-logo"><i class="${job.icon} fs-4" style="color:${job.iconColor}"></i></div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge-type ${badgeClass}">${job.type}</span>
      <button class="bookmark-btn" onclick="toggleSave(this,event)" title="Save job">
        <i class="bi bi-bookmark"></i>
      </button>
    </div>
  </div>
  <h5>${job.title}</h5>
  <p class="company-loc"><i class="bi bi-building me-1"></i>${job.company} &nbsp;·&nbsp; <i class="bi bi-geo-alt me-1"></i>${job.location}</p>
  <p class="desc">${job.desc}</p>
  <div class="skills-row">${job.tags.map(t => `<span class="skill-tag">${t}</span>`).join("")}</div>
  <div class="job-footer">
    <span class="salary">${job.salaryMin.toLocaleString()}–${job.salaryMax.toLocaleString()} DT</span>
    <a href="../cv/cvv.php?offre_id=${job.id}" class="btn-apply text-decoration-none text-white">
      Apply Now <i class="bi bi-arrow-up-right ms-1"></i>
    </a>
  </div>
`;
      grid.appendChild(card);
    });
  }

  renderPagination(totalPages);
}

function renderPagination(total) {
  const wrap = document.getElementById("pagination");
  wrap.innerHTML = "";
  if (total <= 1) return;

  const prev = document.createElement("button");
  prev.className = "pg-btn";
  prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
  prev.onclick = () => { if (currentPage > 1) { currentPage--; renderJobs(); } };
  wrap.appendChild(prev);

  for (let p = 1; p <= total; p++) {
    const btn = document.createElement("button");
    btn.className = "pg-btn" + (p === currentPage ? " active" : "");
    btn.textContent = p;
    btn.onclick = (() => { const pg = p; return () => { currentPage = pg; renderJobs(); }; })();
    wrap.appendChild(btn);
  }

  const next = document.createElement("button");
  next.className = "pg-btn";
  next.innerHTML = '<i class="bi bi-chevron-right"></i>';
  next.onclick = () => { if (currentPage < total) { currentPage++; renderJobs(); } };
  wrap.appendChild(next);
}

document.getElementById("catList").addEventListener("click", (e) => {
  const pill = e.target.closest(".cat-pill");
  if (!pill) return;
  document.querySelectorAll(".cat-pill").forEach(p => p.classList.remove("active"));
  pill.classList.add("active");
  activeCat = pill.dataset.cat;
  currentPage = 1;
  renderJobs();
});

function updateSalary(val) {
  maxSalary = parseInt(val);
  document.getElementById("salaryLabel").textContent = `Up to ${parseInt(val).toLocaleString()} DT`;
  currentPage = 1;
  renderJobs();
}

function applyFilters() { currentPage = 1; renderJobs(); }

document.getElementById("searchInput").addEventListener("input", () => { currentPage = 1; renderJobs(); });
document.getElementById("locationInput").addEventListener("input", () => { currentPage = 1; renderJobs(); });

function resetFilters() {
  activeCat = "all";
  maxSalary = 6000;
  document.querySelectorAll(".cat-pill").forEach(p => p.classList.remove("active"));
  document.querySelector('.cat-pill[data-cat="all"]').classList.add("active");
  document.getElementById("salaryRange").value = 6000;
  document.getElementById("salaryLabel").textContent = "Up to 6,000 DT";
  document.querySelectorAll("#typeList input, #expList input").forEach(i => i.checked = true);
  document.getElementById("searchInput").value = "";
  document.getElementById("locationInput").value = "";
  document.getElementById("sortSelect").value = "recent";
  currentPage = 1;
  renderJobs();
}

function toggleSave(btn, e) {
  e.stopPropagation();
  btn.classList.toggle("saved");
  btn.querySelector("i").className = btn.classList.contains("saved") ? "bi bi-bookmark-fill" : "bi bi-bookmark";
}

function toggleMobileFilter() {
  const s = document.getElementById("sidebar");
  s.style.display = s.style.display === "block" ? "none" : "block";
}