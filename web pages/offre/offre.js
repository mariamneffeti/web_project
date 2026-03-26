console.log("All jobs script loaded.");
const jobs = [
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
const jobContainer = document.querySelector("#jobContainer");
if (!jobContainer) {
  console.error("Job container not found!");
}
let jobElement;
nofiltersdisplay(jobs);
function nofiltersdisplay(tab) {
  const count = Math.min(tab.length, 3);
  for (let i = 0; i < count; i++) { // ✅ FIX 1: use count not hardcoded 3
    jobElement = document.createElement("div");
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
          <a href="#" class="btn btn-dark btn-sm rounded-pill px-3">Apply</a>
        </div>
      </div>
    `;
    jobContainer.appendChild(jobElement);
  }
}
function displayJobs() {
  const keywordD = document.getElementById("searchInput").value.toLowerCase();
  const locationN = document
    .getElementById("locationInput")
    .value.toLowerCase();
  let filteredJobs = jobs.filter((job) => {
    if (
      keywordD &&
      !job.title.toLowerCase().includes(keywordD) &&
      !job.tags.some((tag) => tag.toLowerCase().includes(keywordD)) &&
      !job.company.toLowerCase().includes(keywordD) &&
      !job.type.toLowerCase().includes(keywordD)
    ) {
      return false;
    }
    if (locationN && !job.location.toLowerCase().includes(locationN)) {
      return false;
    }
    return true;
  });

  jobContainer.innerHTML = "";

  if (filteredJobs.length === 0) {
    nofiltersdisplay(jobs);
    return;
  }

  nofiltersdisplay(filteredJobs);
}
const searchButton = document.querySelector("#hhh");
if (!searchButton) {
  console.error("Search button not found!");
}
searchButton.addEventListener("click", displayJobs);

const pilldiv = document.querySelector("#hipills");
pilldiv.addEventListener("click", (e) => {
  const val = e.target.textContent.trim(); // ✅ FIX 2: spans have no .value, use textContent
  jobContainer.innerHTML = "";             // clear before rendering
  if (val === "Marketing")      pillsdisplay("marketing");
  else if (val === "HR")        pillsdisplay("hr");
  else if (val === "Data")      pillsdisplay("data");
  else if (val === "Tech")      pillsdisplay("tech");
  else if (val === "Design")    pillsdisplay("design");
  else if (val === "Finance")   pillsdisplay("finance");
  else                          nofiltersdisplay(jobs);
});

function pillsdisplay(type) {
  const filtered = jobs.filter(job => job.cat === type); // ✅ FIX 3: job.cat not job.type
  jobContainer.innerHTML = "";
  if (filtered.length === 0) {
    nofiltersdisplay(jobs);
    return;
  }
  nofiltersdisplay(filtered);
}