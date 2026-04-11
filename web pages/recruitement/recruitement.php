<?php 
    $pageTitle = "Recruitement"; 
    require_once __DIR__ . '/../../config/session_check.php';
    include('../squelettes entreprise/header.php'); 
    
?>
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        
        <section id="candidates" class="mb-5">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Recent Candidates</h2>
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Name</th>
                            <th>Email</th>
                            <th>CV</th>
                            <th>Applied Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 fw-bold">Ilef B.</td>
                            <td>ilef@example.com</td>
                            <td><a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-pdf me-1"></i>View CV</a></td>
                            <td><span class="badge bg-info-subtle text-dark">Assistant Marketing</span></td>
                            <td>
                                <button class="btn btn-sm btn-finance rounded-pill px-3">Accept</button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Reject</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="post-offer" class="mb-5">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Post New Job Opening</h2>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Job Title</label>
                        <input type="text" class="form-control" placeholder="e.g. Web Developer">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Location</label>
                        <input type="text" class="form-control" placeholder="e.g. Tunis, La Marsa">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Job Description</label>
                        <textarea class="form-control" rows="4" placeholder="Describe the responsibilities and requirements..."></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-finance px-5 py-2 rounded-pill mt-2">
                            <i class="bi bi-megaphone me-2"></i>Publish Offer
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section id="existing-offers" class="mb-5">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Active Job Postings</h2>
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Title</th>
                            <th>Location</th>
                            <th>Description Snippet</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 fw-bold">Commercial</td>
                            <td>La Marsa</td>
                            <td class="text-muted small">Vente de produits et relation clients...</td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash me-1"></i>Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

<?php
    $pagePath = "../recruitement/recruitement.js";
    include('../squelettes entreprise/footer.php'); 
?>