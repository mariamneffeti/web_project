let JOBS = [];
let activeCat = "all";
let maxSalary = 6000;
let currentPage = 1;
const PAGE_SIZE = 9;

fetch("/api/get_offers.php")
  .then(res => res.json())
  .then(data => {
    JOBS = Array.isArray(data.data) ? data.data : [];

    // Update category counts
    const count = (cat) => JOBS.filter(j => j.cat === cat).length;

    document.getElementById("jobCountall").textContent = JOBS.length;
    document.getElementById("jobCounttech").textContent = count("tech");
    document.getElementById("jobCountdesign").textContent = count("design");
    document.getElementById("jobCountfinance").textContent = count("finance");
    document.getElementById("jobCountmarketing").textContent = count("marketing");
    document.getElementById("jobCounthr").textContent = count("hr");
    document.getElementById("jobCountdata").textContent = count("data");

    renderJobs();
  })
  .catch(() => {
    document.getElementById("jobsGrid").innerHTML =
      '<p class="text-center text-danger">Failed to load jobs.</p>';
  });


function renderJobs() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const location = document.getElementById("locationInput").value.toLowerCase();
  const sort = document.getElementById("sortSelect").value;

  const checkedTypes = [...document.querySelectorAll("#typeList input:checked")].map(i => i.value);
  const checkedExps  = [...document.querySelectorAll("#expList input:checked")].map(i => i.value);

  let filtered = JOBS.filter(j =>
    (activeCat === "all" || j.cat === activeCat) &&
    checkedTypes.includes(j.type) &&
    checkedExps.includes(j.exp) &&
    j.salaryMin <= maxSalary &&
    (!keyword ||
      j.title.toLowerCase().includes(keyword) ||
      j.company.toLowerCase().includes(keyword) ||
      j.tags.join(" ").toLowerCase().includes(keyword)
    ) &&
    (!location || j.location.toLowerCase().includes(location))
  );

  if (sort === "salary-high") filtered.sort((a, b) => b.salaryMax - a.salaryMax);
  if (sort === "salary-low") filtered.sort((a, b) => a.salaryMin - b.salaryMin);

  document.getElementById("jobCount").textContent = filtered.length;

  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  if (currentPage > totalPages) currentPage = 1;

  const paged = filtered.slice(
    (currentPage - 1) * PAGE_SIZE,
    currentPage * PAGE_SIZE
  );

  const grid = document.getElementById("jobsGrid");
  grid.innerHTML = "";

  if (!paged.length) {
    grid.innerHTML = `
      <div class="no-results">
        <i class="bi bi-search"></i>
        <p>No jobs match your filters.<br><small>Try adjusting your criteria.</small></p>
      </div>`;
    return;
  }

  paged.forEach((job, idx) => {
    const badgeClass = {
      "Full-time": "badge-fulltime",
      "Contract": "badge-contract",
      "Urgent": "badge-urgent",
      "Part-time": "badge-parttime",
      "Internship": "badge-internship",
    }[job.type] || "badge-fulltime";

    const card = document.createElement("div");
    card.className = "job-card";
    card.style.animationDelay = `${idx * 50}ms`;

    card.innerHTML = `
      <div class="d-flex justify-content-between mb-3">
        <div class="company-logo">
          <i class="${job.icon}" style="color:${job.iconColor}"></i>
        </div>
        <div class="d-flex gap-2">
          <span class="badge-type ${badgeClass}">${job.type}</span>
          <button class="bookmark-btn" onclick="toggleSave(this,event)">
            <i class="bi bi-bookmark"></i>
          </button>
        </div>
      </div>

      <h5>${job.title}</h5>
      <p class="company-loc">
        ${job.company} · ${job.location}
      </p>

      <p class="desc">${job.desc}</p>

      <div class="skills-row">
        ${job.tags.map(t => `<span class="skill-tag">${t}</span>`).join("")}
      </div>

      <div class="job-footer">
        <span class="salary">
          ${job.salaryMin.toLocaleString()}–${job.salaryMax.toLocaleString()} DT
        </span>
        <a href="../cv/cvv.php?offre_id=${job.id}" class="btn-apply">
          Apply
        </a>
      </div>
    `;

    grid.appendChild(card);
  });

  renderPagination(totalPages);
}


function renderPagination(total) {
  const wrap = document.getElementById("pagination");
  wrap.innerHTML = "";

  if (total <= 1) return;

  const createBtn = (text, onClick, active = false) => {
    const btn = document.createElement("button");
    btn.className = "pg-btn" + (active ? " active" : "");
    btn.innerHTML = text;
    btn.onclick = onClick;
    return btn;
  };

  wrap.appendChild(createBtn("«", () => {
    if (currentPage > 1) {
      currentPage--;
      renderJobs();
    }
  }));

  for (let p = 1; p <= total; p++) {
    wrap.appendChild(createBtn(p, () => {
      currentPage = p;
      renderJobs();
    }, p === currentPage));
  }

  wrap.appendChild(createBtn("»", () => {
    if (currentPage < total) {
      currentPage++;
      renderJobs();
    }
  }));
}


document.getElementById("catList").addEventListener("click", e => {
  const pill = e.target.closest(".cat-pill");
  if (!pill) return;

  document.querySelectorAll(".cat-pill").forEach(p => p.classList.remove("active"));
  pill.classList.add("active");

  activeCat = pill.dataset.cat;
  currentPage = 1;
  renderJobs();
});

document.getElementById("searchInput").addEventListener("input", () => {
  currentPage = 1;
  renderJobs();
});

document.getElementById("locationInput").addEventListener("input", () => {
  currentPage = 1;
  renderJobs();
});


function updateSalary(val) {
  maxSalary = parseInt(val);
  document.getElementById("salaryLabel").textContent =
    `Up to ${maxSalary.toLocaleString()} DT`;
  currentPage = 1;
  renderJobs();
}

function applyFilters() {
  currentPage = 1;
  renderJobs();
}

function resetFilters() {
  activeCat = "all";
  maxSalary = 6000;

  document.querySelectorAll(".cat-pill").forEach(p => p.classList.remove("active"));
  document.querySelector('[data-cat="all"]').classList.add("active");

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
  btn.querySelector("i").className =
    btn.classList.contains("saved")
      ? "bi bi-bookmark-fill"
      : "bi bi-bookmark";
}

function toggleMobileFilter() {
  const s = document.getElementById("sidebar");
  s.style.display = s.style.display === "block" ? "none" : "block";
}