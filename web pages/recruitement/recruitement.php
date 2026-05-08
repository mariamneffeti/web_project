<?php 
    $pageTitle = "Recruitement"; 
    include('../squelettes entreprise/header.php'); 
?>

<div class="container py-4" style="margin-top: 100px; margin-bottom: 100px;">
    
    <section id="candidates" class="mb-5">
        <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Recent Candidates</h2>
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" id="filterCandidatePosition" class="form-control form-control-sm rounded-pill" placeholder="Filter by position...">
            </div>
            <div class="col-md-3">
                <select id="filterCandidateStatus" class="form-select form-select-sm rounded-pill">
                    <option value="">All Statuses</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-4">Name</th>
                        <th>Applied Position</th>
                        <th>Status</th>
                        <th>CV</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cvQuery = "SELECT cv.*, jo.title as job_title 
                                FROM cv_applications cv
                                LEFT JOIN job_offers jo ON cv.offre_id = jo.id
                                WHERE cv.company_id = ?
                                ORDER BY cv.submitted_at DESC";
                    $cvStmt = $pdo->prepare($cvQuery);
                    $cvStmt->execute([$company_id]);

                    while ($cv = $cvStmt->fetch(PDO::FETCH_ASSOC)):
                        $statusClass = match($cv['status']) {
                            'Accepted' => 'bg-success-subtle text-success border-success-subtle',
                            'Rejected' => 'bg-danger-subtle text-danger border-danger-subtle',
                            'Reviewed' => 'bg-primary-subtle text-primary border-primary-subtle',
                            default    => 'bg-warning-subtle text-warning border-warning-subtle',
                        };
                    ?>
                    <tr class="table-row d-none">
                        <td class="px-4">
                            <div class="fw-bold text-dark"><?= htmlspecialchars($cv['first_name'] . ' ' . $cv['last_name']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($cv['email']) ?></div>
                        </td>
                        <td><span class="text-secondary small fw-bold"><?= htmlspecialchars($cv['job_title'] ?? 'General Application') ?></span></td>
                        <td><span class="badge <?= $statusClass ?> border px-3 rounded-pill"><?= $cv['status'] ?></span></td>
                        <td>
                            <a href="<?= '../cv/' . htmlspecialchars($cv['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                                <i class="bi bi-file-earmark-pdf me-1"></i>CV
                            </a>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php 
                                    $isFinal = in_array($cv['status'], ['Accepted', 'Rejected']);
                                    $isReviewed = $cv['status'] === 'Reviewed';
                                ?>
                                <button class="btn btn-sm btn-finance rounded-pill px-3 me-2 action-btn"
                                    data-id="<?= $cv['id'] ?>" data-action="accept"
                                    <?= $isFinal ? 'disabled' : '' ?>>
                                    Accept
                                </button>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-2 action-btn"
                                    data-id="<?= $cv['id'] ?>" data-action="contact"
                                    <?= ($isFinal || $isReviewed) ? 'disabled' : '' ?>>
                                    Contact
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3 action-btn"
                                    data-id="<?= $cv['id'] ?>" data-action="reject"
                                    <?= $isFinal ? 'disabled' : '' ?>>
                                    Reject
                                </button>
                                <button class="btn btn-sm btn-outline-dark rounded-pill px-3 action-btn-delete"
                                    data-id="<?= $cv['id'] ?>">
                                    Delete
                                </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary btn-sm btn-show-more rounded-pill px-4">Show More (+5)</button>
            <button class="btn btn-outline-secondary btn-sm btn-show-less rounded-pill px-4 d-none">Show Less</button>
        </div>
    </section>

    <section id="post-offer" class="mb-5">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Post New Job Opening</h2>
            <form id="jobOfferForm" class="row g-3">
                <input type="hidden" name="id" id="editId">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Job Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Backend Engineer" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="e.g. Remote / Tunis" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Icon</label>
                    <div class="position-relative">
                        <div id="iconTrigger" class="form-control d-flex align-items-center justify-content-between" style="cursor:pointer;">
                            <span id="selectedIconText">Choose an icon</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div id="iconDropdown" class="shadow rounded-4 p-3 bg-white" style="position:absolute; width:100%; top:110%; z-index:1000; display:none;">
                            <input type="text" id="iconSearch" class="form-control mb-3" placeholder="Search icon...">
                            <div id="iconContainer" style="max-height:200px; overflow-y:auto;" class="d-flex flex-wrap gap-2">
                                <?php
                                $icons = $pdo->query("SELECT * FROM job_icons");
                                while ($icon = $icons->fetch()):
                                ?>
                                    <div class="icon-item p-2 rounded text-center" data-id="<?= $icon['id'] ?>" data-name="<?= strtolower($icon['icon_name']) ?>" style="width:60px; cursor:pointer;">
                                        <i class="bi <?= $icon['bootstrap_class'] ?> fs-5" style="color: <?= $icon['default_color'] ?>"></i>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <input type="hidden" name="icon_id" id="iconInput">
                    </div>                   
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="tech">Technology</option>
                        <option value="design">Design</option>
                        <option value="data">Data Science</option>
                        <option value="marketing">Marketing</option>
                        <option value="finance">Finance</option>
                        <option value="hr">Human Resources</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="Full-time">Full-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Experience Level</label>
                    <select name="experience_level" class="form-select" required>
                        <option value="junior">Junior</option>
                        <option value="mid">Mid-Level</option>
                        <option value="senior">Senior</option>
                        <option value="lead">Lead / Manager</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Min Salary (Dt)</label>
                    <input type="number" name="salary_min" class="form-control" min="500" step="50"required placeholder="500">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Max Salary (Dt)</label>
                    <input type="number" name="salary_max" class="form-control" min="500" step="50" required placeholder="1000">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Tags (Comma separated)</label>
                    <input type="text" name="tags" class="form-control" placeholder="React, SQL, PHP">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Job Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Detailed job requirements..." required></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-finance px-5 py-2 rounded-pill mt-2">
                        <i class="bi bi-plus-circle me-2"></i>Post Job Offer
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section id="existing-offers">
        <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Active Job Postings</h2>
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="filterOffersGlobal" class="form-control form-control-sm rounded-pill" placeholder="Search postings (title, location, tags, status)...">
            </div>
        </div>
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-4">Title</th>
                        <th>Location</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Tags</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="offers-list">
                    <?php
                    $offerQuery = "SELECT jo.*, ji.bootstrap_class, ji.default_color 
                                FROM job_offers jo 
                                LEFT JOIN job_icons ji ON jo.icon_id = ji.id
                                WHERE jo.company_id = ?
                                ORDER BY jo.created_at DESC";
                    $offerStmt = $pdo->prepare($offerQuery);
                    $offerStmt->execute([$company_id]);

                    while ($offer = $offerStmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr class="table-row d-none">
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 d-flex align-items-center justify-content-center rounded" 
                                    style="width: 40px; height: 40px; background-color: <?= $offer['default_color'] ?>20; color: <?= $offer['default_color'] ?>;">
                                    <i class="bi <?= $offer['bootstrap_class'] ?? 'bi-briefcase' ?> fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($offer['title']) ?></div>
                                    <div class="small text-muted"><?= ucfirst($offer['category']) ?> • <?= $offer['experience_level'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td><i class="bi bi-geo-alt me-1 text-muted"></i><?= htmlspecialchars($offer['location']) ?></td>
                        <td>
                            <div class="small fw-bold text-dark"><?= $offer['salary_min'] ?> - <?= $offer['salary_max'] ?> Dt</div>
                            <div class="text-muted" style="font-size: 0.7rem;"><?= $offer['type'] ?></div>
                        </td>
                        <td>
                            <span class="badge <?= $offer['status']=='active'?'bg-success':'bg-secondary' ?>">
                                <?= $offer['status'] ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                $tags = explode(',', $offer['tags']);
                                foreach(array_slice($tags, 0, 2) as $tag): 
                                    if(empty(trim($tag))) continue;
                            ?>
                                <span class="badge bg-light text-secondary border-0 p-1 px-2" style="font-size: 0.65rem;">#<?= trim($tag) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button class="btn btn-light btn-sm rounded-circle view-offer" data-offer='<?= json_encode($offer) ?>'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-light btn-sm rounded-circle edit-offer" data-offer='<?= json_encode($offer) ?>'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-light btn-sm rounded-circle text-danger delete-offer" data-id="<?= $offer['id'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary btn-sm btn-show-more rounded-pill px-4">Show More (+5)</button>
            <button class="btn btn-outline-secondary btn-sm btn-show-less rounded-pill px-4 d-none">Show Less</button>
        </div>
    </section>
</div>

<div class="modal fade" id="offerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

      <div class="p-4 d-flex align-items-center gap-3" id="modalHeader">
        <div id="modalIcon"
             class="d-flex align-items-center justify-content-center rounded"
             style="width:55px;height:55px;">
          <i class="bi fs-3"></i>
        </div>

        <div>
          <h5 class="mb-0 fw-bold" id="modalTitle"></h5>
          <div class="text-muted small" id="modalMeta"></div>
        </div>
      </div>

      <div class="p-4 pt-0">

        <div class="mb-3">
          <span class="badge bg-light text-dark border me-1" id="modalCategory"></span>
          <span class="badge bg-light text-dark border" id="modalType"></span>
        </div>

        <p class="text-muted mb-3" id="modalDescription"></p>

        <div class="d-flex justify-content-between text-center bg-light rounded-3 p-3">
          <div>
            <div class="small text-muted">Location</div>
            <div class="fw-bold" id="modalLocation"></div>
          </div>

          <div>
            <div class="small text-muted">Salary</div>
            <div class="fw-bold" id="modalSalary"></div>
          </div>

          <div>
            <div class="small text-muted">Level</div>
            <div class="fw-bold" id="modalLevel"></div>
          </div>
        </div>

        <div class="mt-3" id="modalTags"></div>

      </div>
    </div>
  </div>
</div>
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-body text-center p-4">
        <h5 id="confirmText">Are you sure?</h5>
        <div class="mt-4 d-flex justify-content-center gap-3">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-danger" id="confirmYes">Yes</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
    $pagePath = "../recruitement/recruitement.js";
    include('../squelettes entreprise/footer.php'); 
?>