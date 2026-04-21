
<?php 
    $pageTitle = "All Jobs"; 
    include('../squelleteuser/header.php'); 
?>

  <body>
    <main>
         <link rel="stylesheet" href="alljob.css">


    <div class="page-hero">
      <div class="container">
        <p class="lead mb-2">
          ←
          <a
            href="../clienthome/clienthome.php"
            style="color: rgba(255, 255, 255, 0.6); text-decoration: none"
            >Back to Home</a
          >
        </p>
        <h1 class="display-5 fw-bold mb-1">All Job Openings</h1>
        <p class="lead">
          Find the role that fits you — filter by category, type, and salary.
        </p>
        <div class="search-bar mt-4" style="max-width: 660px">
          <i class="bi bi-search text-muted"></i>
          <input
            type="text"
            id="searchInput"
            placeholder="Job title, keyword, company…"
          />
          <div class="divider"></div>
          <i class="bi bi-geo-alt text-muted"></i>
          <input
            type="text"
            id="locationInput"
            placeholder="City or remote"
            style="max-width: 160px"
          />
          <button class="btn-search" onclick="applyFilters()">Search</button>
        </div>
      </div>
    </div>

    <div class="container">
      <button class="mobile-filter-btn" onclick="toggleMobileFilter()">
        <i class="bi bi-sliders"></i> Filters
      </button>

      <div class="main-layout">
        <aside class="sidebar" id="sidebar">
          <div class="filter-card">
            <h6><i class="bi bi-grid-3x3-gap me-2"></i>Category</h6>
            <div id="catList">
              <div class="cat-pill active" data-cat="all">
                All <span class="count" id="jobCountall"></span>
              </div>
              <div class="cat-pill" data-cat="tech">
                Technology <span class="count" id="jobCounttech"></span>
              </div>
              <div class="cat-pill" data-cat="design">
                Design <span class="count" id="jobCountdesign"></span>
              </div>
              <div class="cat-pill" data-cat="finance">
                Finance <span class="count" id="jobCountfinance"></span>
              </div>
              <div class="cat-pill" data-cat="marketing">
                Marketing <span class="count" id="jobCountmarketing"></span>
              </div>
              <div class="cat-pill" data-cat="hr">
                Human Resources <span class="count" id="jobCounthr"></span>
              </div>
              <div class="cat-pill" data-cat="data">
                Data & AI <span class="count" id="jobCountdata"></span>
              </div>
            </div>
          </div>

          <div class="filter-card">
            <h6><i class="bi bi-briefcase me-2"></i>Job Type</h6>
            <div class="type-check" id="typeList">
              <label
                ><input
                  type="checkbox"
                  value="Full-time"
                  checked
                  onchange="applyFilters()"
                />
                Full-time</label
              >
              <label
                ><input
                  type="checkbox"
                  value="Contract"
                  checked
                  onchange="applyFilters()"
                />
                Contract</label
              >
              <label
                ><input
                  type="checkbox"
                  value="Part-time"
                  checked
                  onchange="applyFilters()"
                />
                Part-time</label
              >
              <label
                ><input
                  type="checkbox"
                  value="Internship"
                  checked
                  onchange="applyFilters()"
                />
                Internship</label
              >
              <label
                ><input
                  type="checkbox"
                  value="Urgent"
                  checked
                  onchange="applyFilters()"
                />
                Urgent</label
              >
            </div>
          </div>

          <!-- Salary -->
          <div class="filter-card">
            <h6><i class="bi bi-cash-stack me-2"></i>Monthly Salary (DT)</h6>
            <div class="salary-range">
              <input
                type="range"
                id="salaryRange"
                min="500"
                max="6000"
                value="6000"
                step="100"
                oninput="updateSalary(this.value)"
              />
            </div>
            <div class="salary-labels">
              <span>500 DT</span>
              <span
                id="salaryLabel"
                style="color: var(--teal); font-weight: 700"
                >Up to 6000 DT</span
              >
            </div>
          </div>

          <!-- Experience -->
          <div class="filter-card">
            <h6><i class="bi bi-award me-2"></i>Experience</h6>
            <div class="type-check" id="expList">
              <label
                ><input
                  type="checkbox"
                  value="junior"
                  checked
                  onchange="applyFilters()"
                />
                Entry / Junior</label
              >
              <label
                ><input
                  type="checkbox"
                  value="mid"
                  checked
                  onchange="applyFilters()"
                />
                Mid-level</label
              >
              <label
                ><input
                  type="checkbox"
                  value="senior"
                  checked
                  onchange="applyFilters()"
                />
                Senior</label
              >
              <label
                ><input
                  type="checkbox"
                  value="lead"
                  checked
                  onchange="applyFilters()"
                />
                Lead / Manager</label
              >
            </div>
          </div>

          <button
            class="btn w-100 mt-1"
            style="
              background: var(--teal);
              color: white;
              border-radius: 12px;
              font-weight: 600;
            "
            onclick="resetFilters()"
          >
            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
          </button>
        </aside>

        <div>
          <div class="jobs-header">
            <p class="jobs-count m-0">
              <span id="jobCount"></span> jobs found
            </p>
            <select
              class="sort-select"
              id="sortSelect"
              onchange="applyFilters()"
            >
              <option value="recent">Most Recent</option>
              <option value="salary-high">Salary: High to Low</option>
              <option value="salary-low">Salary: Low to High</option>
            </select>
          </div>

          <div class="jobs-grid" id="jobsGrid"></div>

          <div class="pagination-wrap" id="pagination"></div>
        </div>
      </div>
    </div>
    </main>
    
  <script src="alljob.js"></script>
<?php include('../squelleteuser/footeruser.php'); ?>
</html>
