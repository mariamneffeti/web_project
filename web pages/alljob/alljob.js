console.log("All jobs script loaded.");
const JOBS = [
  {
    id: 1,
    title: "Backend Engineer",
    company: "Stripe",
    location: "Remote, Europe",
    type: "Full-time",
    cat: "tech",
    salaryMin: 4000,
    salaryMax: 4500,
    exp: "senior",
    tags: ["Ruby", "Go", "API"],
    icon: "bi-stripe",
    iconColor: "#635bff",
    desc: "Build the financial infrastructure of the internet using Ruby and Go.",
  },
  {
    id: 2,
    title: "UI/UX Designer",
    company: "Flouci",
    location: "Tunis",
    type: "Contract",
    cat: "design",
    salaryMin: 3000,
    salaryMax: 3700,
    exp: "mid",
    tags: ["Figma", "Prototyping", "UX"],
    icon: "bi-piggy-bank-fill",
    iconColor: "rgb(226,62,90)",
    desc: "Design beautiful experiences for millions of Flouci users worldwide.",
  },
  {
    id: 3,
    title: "AI Research Lead",
    company: "NVIDIA",
    location: "Germany",
    type: "Urgent",
    cat: "data",
    salaryMin: 2000,
    salaryMax: 3500,
    exp: "lead",
    tags: ["PyTorch", "CUDA", "LLMs"],
    icon: "bi-nvidia",
    iconColor: "#76b900",
    desc: "Push the boundaries of deep learning and GPU accelerated computing.",
  },
  {
    id: 4,
    title: "Frontend Developer",
    company: "Vermeg",
    location: "Tunis",
    type: "Full-time",
    cat: "tech",
    salaryMin: 2500,
    salaryMax: 3200,
    exp: "mid",
    tags: ["React", "TypeScript", "CSS"],
    icon: "bi-code-slash",
    iconColor: "#388087",
    desc: "Build modern web interfaces for fintech clients across Europe.",
  },
  {
    id: 5,
    title: "Data Analyst",
    company: "Telnet",
    location: "Sfax",
    type: "Full-time",
    cat: "data",
    salaryMin: 1800,
    salaryMax: 2400,
    exp: "junior",
    tags: ["SQL", "Python", "Power BI"],
    icon: "bi-bar-chart-fill",
    iconColor: "#8d9b6a",
    desc: "Transform raw data into actionable insights for telecom operations.",
  },
  {
    id: 6,
    title: "Product Manager",
    company: "InstaDeep",
    location: "Remote",
    type: "Full-time",
    cat: "tech",
    salaryMin: 3500,
    salaryMax: 4800,
    exp: "senior",
    tags: ["Agile", "Roadmap", "Scrum"],
    icon: "bi-kanban",
    iconColor: "#0f172a",
    desc: "Own the product roadmap and drive AI product strategy at InstaDeep.",
  },
  {
    id: 7,
    title: "Social Media Manager",
    company: "Ooredoo TN",
    location: "Tunis",
    type: "Contract",
    cat: "marketing",
    salaryMin: 1500,
    salaryMax: 2200,
    exp: "junior",
    tags: ["Meta", "Content", "Analytics"],
    icon: "bi-megaphone",
    iconColor: "#e23e5a",
    desc: "Grow Ooredoo Tunisia's social presence across multiple platforms.",
  },
  {
    id: 8,
    title: "DevOps Engineer",
    company: "Sofrecom",
    location: "Tunis",
    type: "Full-time",
    cat: "tech",
    salaryMin: 3000,
    salaryMax: 4000,
    exp: "mid",
    tags: ["Docker", "K8s", "CI/CD"],
    icon: "bi-gear-wide-connected",
    iconColor: "#388087",
    desc: "Automate and optimize deployment pipelines for client infrastructure.",
  },
  {
    id: 9,
    title: "Graphic Designer",
    company: "Publicis",
    location: "Tunis",
    type: "Part-time",
    cat: "design",
    salaryMin: 1200,
    salaryMax: 1800,
    exp: "junior",
    tags: ["Illustrator", "Photoshop"],
    icon: "bi-brush",
    iconColor: "#a855f7",
    desc: "Create compelling visual assets for national advertising campaigns.",
  },
  {
    id: 10,
    title: "Financial Analyst",
    company: "Attijari Bank",
    location: "Tunis",
    type: "Full-time",
    cat: "finance",
    salaryMin: 2200,
    salaryMax: 3000,
    exp: "mid",
    tags: ["Excel", "Bloomberg", "Risk"],
    icon: "bi-bank",
    iconColor: "#0f172a",
    desc: "Analyze financial data and provide investment recommendations.",
  },
  {
    id: 11,
    title: "HR Business Partner",
    company: "Poulina",
    location: "Tunis",
    type: "Full-time",
    cat: "hr",
    salaryMin: 2000,
    salaryMax: 2800,
    exp: "mid",
    tags: ["Recruitment", "HRIS", "L&D"],
    icon: "bi-people",
    iconColor: "#8d9b6a",
    desc: "Partner with business leaders to drive talent and culture strategy.",
  },
  {
    id: 12,
    title: "Mobile Developer (iOS)",
    company: "Satoripop",
    location: "Tunis",
    type: "Full-time",
    cat: "tech",
    salaryMin: 2800,
    salaryMax: 3600,
    exp: "mid",
    tags: ["Swift", "SwiftUI", "Xcode"],
    icon: "bi-phone",
    iconColor: "#555",
    desc: "Build sleek iOS applications for startups and enterprise clients.",
  },
  {
    id: 13,
    title: "Content Strategist",
    company: "Wevioo",
    location: "Remote",
    type: "Contract",
    cat: "marketing",
    salaryMin: 1400,
    salaryMax: 2000,
    exp: "junior",
    tags: ["SEO", "Copywriting", "CMS"],
    icon: "bi-pencil-square",
    iconColor: "#388087",
    desc: "Craft content strategies that drive organic growth and engagement.",
  },
  {
    id: 14,
    title: "Cloud Architect",
    company: "Microsoft",
    location: "Remote",
    type: "Full-time",
    cat: "tech",
    salaryMin: 5000,
    salaryMax: 6000,
    exp: "lead",
    tags: ["Azure", "Terraform", "SAP"],
    icon: "bi-cloud",
    iconColor: "#0078d4",
    desc: "Design and implement enterprise-scale cloud infrastructure on Azure.",
  },
  {
    id: 15,
    title: "Audit Intern",
    company: "PwC Tunisia",
    location: "Tunis",
    type: "Internship",
    cat: "finance",
    salaryMin: 600,
    salaryMax: 900,
    exp: "junior",
    tags: ["Excel", "IFRS", "Audit"],
    icon: "bi-clipboard-check",
    iconColor: "#de7200",
    desc: "Support audit teams on financial statement reviews for major clients.",
  },
  {
    id: 16,
    title: "Machine Learning Engineer",
    company: "InstaDeep",
    location: "Tunis",
    type: "Full-time",
    cat: "data",
    salaryMin: 4000,
    salaryMax: 5500,
    exp: "senior",
    tags: ["TensorFlow", "MLOps", "Python"],
    icon: "bi-cpu",
    iconColor: "#10b981",
    desc: "Develop and deploy ML models powering real-world AI products.",
  },
  {
    id: 17,
    title: "Marketing Analyst",
    company: "Jumia TN",
    location: "Tunis",
    type: "Full-time",
    cat: "marketing",
    salaryMin: 1800,
    salaryMax: 2500,
    exp: "junior",
    tags: ["Google Ads", "GA4", "Excel"],
    icon: "bi-graph-up",
    iconColor: "#f97316",
    desc: "Analyze campaign performance and optimize paid marketing spend.",
  },
  {
    id: 18,
    title: "Full-Stack Developer",
    company: "Amen Bank",
    location: "Tunis",
    type: "Full-time",
    cat: "tech",
    salaryMin: 2600,
    salaryMax: 3400,
    exp: "mid",
    tags: ["Spring", "Angular", "SQL"],
    icon: "bi-stack",
    iconColor: "#0f172a",
    desc: "Develop and maintain core banking digital platforms.",
  },
  {
    id: 19,
    title: "Talent Acquisition Specialist",
    company: "Orange TN",
    location: "Tunis",
    type: "Contract",
    cat: "hr",
    salaryMin: 1700,
    salaryMax: 2300,
    exp: "junior",
    tags: ["LinkedIn", "ATS", "Sourcing"],
    icon: "bi-person-badge",
    iconColor: "#f97316",
    desc: "Lead end-to-end recruitment for technical and commercial roles.",
  },
  {
    id: 20,
    title: "Risk Manager",
    company: "UIB",
    location: "Tunis",
    type: "Full-time",
    cat: "finance",
    salaryMin: 2800,
    salaryMax: 3800,
    exp: "senior",
    tags: ["Basel III", "Risk", "RAROC"],
    icon: "bi-shield-check",
    iconColor: "#388087",
    desc: "Oversee credit and operational risk frameworks across the bank.",
  },
  {
    id: 21,
    title: "Motion Designer",
    company: "Cogite",
    location: "Tunis",
    type: "Part-time",
    cat: "design",
    salaryMin: 1400,
    salaryMax: 2100,
    exp: "mid",
    tags: ["After Effects", "Lottie", "AE"],
    icon: "bi-play-circle",
    iconColor: "#ec4899",
    desc: "Produce motion graphics and animations for digital products.",
  },
  {
    id: 22,
    title: "Cybersecurity Analyst",
    company: "Hexabyte",
    location: "Tunis",
    type: "Urgent",
    cat: "tech",
    salaryMin: 2500,
    salaryMax: 3500,
    exp: "mid",
    tags: ["SOC", "Pentest", "SIEM"],
    icon: "bi-shield-lock",
    iconColor: "#ef4444",
    desc: "Monitor and defend critical infrastructure from cyber threats.",
  },
  {
    id: 23,
    title: "Business Developer",
    company: "Tunisie Telecom",
    location: "Tunis",
    type: "Full-time",
    cat: "marketing",
    salaryMin: 2000,
    salaryMax: 3000,
    exp: "mid",
    tags: ["B2B", "CRM", "Negotiation"],
    icon: "bi-briefcase",
    iconColor: "#8d9b6a",
    desc: "Identify and convert new business opportunities for enterprise clients.",
  },
  {
    id: 24,
    title: "Embedded Systems Engineer",
    company: "Continental",
    location: "Sousse",
    type: "Full-time",
    cat: "tech",
    salaryMin: 3200,
    salaryMax: 4200,
    exp: "senior",
    tags: ["C", "RTOS", "CAN Bus"],
    icon: "bi-cpu-fill",
    iconColor: "#6366f1",
    desc: "Develop embedded software for automotive systems and ECUs.",
  },
];
document.getElementById("jobCountall").textContent = JOBS.length;
document.getElementById("jobCounttech").textContent = JOBS.filter(
  (j) => j.cat === "tech",
).length;
document.getElementById("jobCountdesign").textContent = JOBS.filter(
  (j) => j.cat === "design",
).length;
document.getElementById("jobCountfinance").textContent = JOBS.filter(
  (j) => j.cat === "finance",
).length;
document.getElementById("jobCountmarketing").textContent = JOBS.filter(
  (j) => j.cat === "marketing",
).length;
document.getElementById("jobCounthr").textContent = JOBS.filter(
  (j) => j.cat === "hr",
).length;
document.getElementById("jobCountdata").textContent = JOBS.filter(
  (j) => j.cat === "data",
).length;
let activeCat = "all";
let maxSalary = 6000;
let currentPage = 1;
const PAGE_SIZE = 9;

function renderJobs() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const location = document.getElementById("locationInput").value.toLowerCase();
  const sort = document.getElementById("sortSelect").value;
  const checkedTypes = [
    ...document.querySelectorAll("#typeList input:checked"),
  ].map((i) => i.value);
  const checkedExps = [
    ...document.querySelectorAll("#expList input:checked"),
  ].map((i) => i.value);

  let filtered = JOBS.filter((j) => {
    if (activeCat !== "all" && j.cat !== activeCat) return false;
    if (!checkedTypes.includes(j.type)) return false;
    if (!checkedExps.includes(j.exp)) return false;
    if (j.salaryMin > maxSalary) return false;
    if (
      keyword &&
      !j.title.toLowerCase().includes(keyword) &&
      !j.company.toLowerCase().includes(keyword) &&
      !j.tags.join(" ").toLowerCase().includes(keyword)
    )
      return false;
    if (location && !j.location.toLowerCase().includes(location)) return false;
    return true;
  });

  if (sort === "salary-high")
    filtered.sort((a, b) => b.salaryMax - a.salaryMax);
  else if (sort === "salary-low")
    filtered.sort((a, b) => a.salaryMin - b.salaryMin);

  document.getElementById("jobCount").textContent = filtered.length;

  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  if (currentPage > totalPages) currentPage = 1;
  const paged = filtered.slice(
    (currentPage - 1) * PAGE_SIZE,
    currentPage * PAGE_SIZE,
  );

  const grid = document.getElementById("jobsGrid");
  grid.innerHTML = "";

  if (paged.length === 0) {
    grid.innerHTML = `<div class="no-results"><i class="bi bi-search"></i><p>No jobs match your filters.<br><small>Try adjusting your criteria.</small></p></div>`;
  } else {
    paged.forEach((job, idx) => {
      const badgeClass =
        {
          "Full-time": "badge-fulltime",
          Contract: "badge-contract",
          Urgent: "badge-urgent",
          "Part-time": "badge-parttime",
          Internship: "badge-internship",
        }[job.type] || "badge-fulltime";
      const card = document.createElement("div");
      card.className = "job-card";
      card.style.animationDelay = `${idx * 50}ms`;
      card.innerHTML = `
              <button class="bookmark-btn" onclick="toggleSave(this,event)" title="Save job"><i class="bi bi-bookmark"></i></button>
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="company-logo"><i class="${job.icon} fs-4" style="color:${job.iconColor}"></i></div>
                <span class="badge-type ${badgeClass}">${job.type}</span>
              </div>
              <h5>${job.title}</h5>
              <p class="company-loc"><i class="bi bi-building me-1"></i>${job.company} &nbsp;·&nbsp; <i class="bi bi-geo-alt me-1"></i>${job.location}</p>
              <p class="desc">${job.desc}</p>
               <div class="skills-row">${job.tags.map((t) => `<span class="skill-tag">${t}</span>`).join("")}</div>
               <div class="job-footer">
                <span class="salary">${job.salaryMin.toLocaleString()}–${job.salaryMax.toLocaleString()} DT</span>
                <button class="btn-apply">Apply <i class="bi bi-arrow-up-right ms-1"></i></button>
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
  prev.onclick = () => {
    if (currentPage > 1) {
      currentPage--;
      renderJobs();
    }
  };
  wrap.appendChild(prev);
  for (let p = 1; p <= total; p++) {
    const btn = document.createElement("button");
    btn.className = "pg-btn" + (p === currentPage ? " active" : "");
    btn.textContent = p;
    btn.onclick = (() => {
      const pg = p;
      return () => {
        currentPage = pg;
        renderJobs();
      };
    })();
    wrap.appendChild(btn);
  }
  const next = document.createElement("button");
  next.className = "pg-btn";
  next.innerHTML = '<i class="bi bi-chevron-right"></i>';
  next.onclick = () => {
    if (currentPage < total) {
      currentPage++;
      renderJobs();
    }
  };
  wrap.appendChild(next);
}

document.getElementById("catList").addEventListener("click", (e) => {
  const pill = e.target.closest(".cat-pill");
  if (!pill) return;
  document
    .querySelectorAll(".cat-pill")
    .forEach((p) => p.classList.remove("active"));
  pill.classList.add("active");
  activeCat = pill.dataset.cat;
  currentPage = 1;
  renderJobs();
});

function updateSalary(val) {
  maxSalary = parseInt(val);
  document.getElementById("salaryLabel").textContent =
    `Up to ${parseInt(val).toLocaleString()} DT`;
  currentPage = 1;
  renderJobs();
} 

function applyFilters() {
  currentPage = 1;
  renderJobs();
}
document.getElementById("searchInput").addEventListener("input", () => {
  currentPage = 1;
  renderJobs();
});
document.getElementById("locationInput").addEventListener("input", () => {
  currentPage = 1;
  renderJobs();
}); 

function resetFilters() {
  activeCat = "all";
  maxSalary = 6000;
  document
    .querySelectorAll(".cat-pill")
    .forEach((p) => p.classList.remove("active"));
  document.querySelector('.cat-pill[data-cat="all"]').classList.add("active");
  document.getElementById("salaryRange").value = 6000;
  document.getElementById("salaryLabel").textContent = "Up to 6,000 DT";
  document
    .querySelectorAll("#typeList input, #expList input")
    .forEach((i) => (i.checked = true));
  document.getElementById("searchInput").value = "";
  document.getElementById("locationInput").value = "";
  document.getElementById("sortSelect").value = "recent";
  currentPage = 1;
  renderJobs();
} 

function toggleSave(btn, e) {
  e.stopPropagation();
  btn.classList.toggle("saved");
  const icon = btn.querySelector("i");
  icon.className = btn.classList.contains("saved")
    ? "bi bi-bookmark-fill"
    : "bi bi-bookmark";
} 

function toggleMobileFilter() {
  const s = document.getElementById("sidebar");
  s.style.display = s.style.display === "block" ? "none" : "block";
}
