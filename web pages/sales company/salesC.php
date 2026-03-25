<?php 
    $pageTitle = "Sales"; 
    include('../squelettes entreprise/header.php'); 
?>
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        <section id="sales-kpis" class="mb-5">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Sales Performance</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card p-4 text-center stats-card">
                        <h6 class="text-secondary text-uppercase small">Monthly Target</h6>
                        <h3 class="fw-bold">50,000 Dt</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 text-center stats-card" style="border-left-color: #102E4A;">
                        <h6 class="text-secondary text-uppercase small">Active Leads</h6>
                        <h3 class="fw-bold">124</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 text-center stats-card" style="border-left-color: #8D9B6A;">
                        <h6 class="text-secondary text-uppercase small">Conversion Rate</h6>
                        <h3 class="fw-bold">12%</h3>
                    </div>
                </div>
            </div>
        </section>

        <section id="add-sale" class="mb-5">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Record New Sale</h2>
                <form id="sales-form" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Client Name</label>
                        <input type="text" class="form-control" placeholder="Enter client name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Product/Service</label>
                        <select class="form-select">
                            <option selected disabled>Choose...</option>
                            <option value="1">Annual Subscription</option>
                            <option value="2">Consulting Package</option>
                            <option value="3">Hardware Bundle</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sale Amount (Dt)</label>
                        <input type="number" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-finance px-5 py-2 rounded-pill mt-3">
                            <i class="bi bi-cart-check me-2"></i>Submit Order
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section id="sales-history" class="mb-5">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Recent Orders</h2>
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Order ID</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 fw-bold text-muted">#ORD-992</td>
                            <td>2026-02-03</td>
                            <td class="fw-bold">Global Corp</td>
                            <td>2,400.00 Dt</td>
                            <td><span class="badge bg-success-subtle text-success px-3">Completed</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 fw-bold text-muted">#ORD-991</td>
                            <td>2026-02-01</td>
                            <td class="fw-bold">Tech Solutions</td>
                            <td>1,150.00 Dt</td>
                            <td><span class="badge bg-warning-subtle text-warning px-3">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
<?php
    $pagePath = "../sales company/salesC.js";
    include('../squelettes entreprise/footer.php'); 
?>