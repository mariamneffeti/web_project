<?php 
    $pageTitle = "Recruitment"; 
    include('../squelettes entreprise/header.php'); 
    require_once __DIR__ . '/../../config/database.php';
    $pdo = getDB();
    $company_id = 1; 
?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    
    <section id="candidates" class="mb-5">
        <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Recent Candidates</h2>
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
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold text-dark"><?= htmlspecialchars($cv['first_name'] . ' ' . $cv['last_name']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($cv['email']) ?></div>
                        </td>
                        <td><span class="text-secondary small fw-bold"><?= htmlspecialchars($cv['job_title'] ?? 'General Application') ?></span></td>
                        <td><span class="badge <?= $statusClass ?> border px-3 rounded-pill"><?= $cv['status'] ?></span></td>
                        <td>
                            <a href="<?= htmlspecialchars($cv['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                                <i class="bi bi-file-earmark-pdf me-1"></i>CV
                            </a>
                        </td>
                        <td>
                            <div class="btn-group">
                                <form method="POST" action="handle_candidate.php" class="d-inline">
                                    <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button class="btn btn-sm btn-finance rounded-pill px-3 me-2">Accept</button>
                                </form>

                                <form method="POST" action="handle_candidate.php" class="d-inline">
                                    <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="post-offer" class="mb-5">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Post New Job Opening</h2>
            
            <form action="process_offer.php" method="POST" class="row g-3">
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

                        <div id="iconDropdown" class="shadow rounded-4 p-3 bg-white"
                            style="position:absolute; width:100%; top:110%; z-index:1000; display:none;">

                            <input type="text" id="iconSearch" class="form-control mb-3"
                                placeholder="Search icon...">

                            <div id="iconContainer"
                                style="max-height:200px; overflow-y:auto;"
                                class="d-flex flex-wrap gap-2">

                                <?php
                                $icons = $pdo->query("SELECT * FROM job_icons");
                                while ($icon = $icons->fetch()):
                                ?>
                                    <div class="icon-item p-2 rounded text-center"
                                        data-id="<?= $icon['id'] ?>"
                                        data-name="<?= strtolower($icon['icon_name']) ?>"
                                        style="width:60px; cursor:pointer;">

                                        <i class="bi <?= $icon['bootstrap_class'] ?> fs-5"
                                        style="color: <?= $icon['default_color'] ?>"></i>
                                    </div>
                                <?php endwhile; ?>

                            </div>
                        </div>

                        <input type="hidden" name="icon_id" id="iconInput">

                    </div>                   
                </div>

                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="Full-time">Full-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Experience Level</label>
                    <select name="experience_level" class="form-select" required>
                        <option value="junior">Junior</option>
                        <option value="mid">Mid-Level</option>
                        <option value="senior">Senior</option>
                        <option value="lead">Lead / Manager</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Min Salary (Dt)</label>
                    <input type="number" name="salary_min" class="form-control" placeholder="2000">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Max Salary (Dt)</label>
                    <input type="number" name="salary_max" class="form-control" placeholder="3500">
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
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-4">Title</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Applicants</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                    <tr>
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
                            <div class="small fw-bold text-dark">
                                <?= $offer['salary_min'] ?> - <?= $offer['salary_max'] ?> Dt
                            </div>
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
                            ?>
                                <span class="badge bg-light text-secondary border-0 p-1 px-2" style="font-size: 0.65rem;">#<?= trim($tag) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-2">

                                <button class="btn btn-light btn-sm rounded-circle view-offer"
                                        data-offer='<?= json_encode($offer) ?>'>
                                    <i class="bi bi-eye"></i>
                                </button>

                                <a href="edit_offer.php?id=<?= $offer['id'] ?>"
                                class="btn btn-light btn-sm rounded-circle">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST" action="delete_offer.php" class="m-0">
                                    <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                                    <button class="btn btn-light btn-sm rounded-circle text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php
    $pagePath = "../recruitement/recruitement.js";
    include('../squelettes entreprise/footer.php'); 
?>
