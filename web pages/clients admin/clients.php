<?php
    $pageTitle = "Clients";
    include("../squelettes entreprise/header.php");
?>
<main class="container py-4" style="margin-top: 100px; margin-bottom: 100px;">
<header class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 fw-bold">Client Portfolio Management</h1>
    <p class="text-muted">Manage your relationships and churn predictions.</p>
  </div>
  <button class="btn btn-primary-custom" onclick="openAddModal()">+ Add New Client</button>
</header>

<div class="row mb-4 g-3">
  <div class="col-md-4">
    <div class="card p-4 kpi">
      <small class="text-muted fw-semibold">Total Clients</small>
      <h4 class="fw-bold mb-0" id="total-clients-count">--</h4>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-4 kpi">
      <small class="text-muted fw-semibold">Churn Alerts</small>
      <h4 class="fw-bold mb-0 text-danger" id="stat-churn-risk">--</h4>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-4 kpi">
      <small class="text-muted fw-semibold">Avg. Revenue</small>
      <h4 class="fw-bold mb-0" id="stat-month-amount">--</h4>
    </div>
  </div>
</div>

<section class="d-flex gap-3 mb-4">
  <div class="input-group w-50">
    <input class="form-control" placeholder="Search by name, ID, or company…" oninput="handleSearch()">
    <button class="btn btn-outline-secondary">🔍</button>
  </div>
  <button class="btn btn-outline-dark" onclick="ExportToCSV()">Export CSV</button>
  <button class="btn btn-outline-dark" onclick="ExportToPDF()">Export PDF</button>
  <button class="btn btn-outline-dark" onclick="Copy()">Copy</button>
  <button class="btn btn-outline-dark" onclick="ExportToExcel()">Excel</button>
</section>

<section class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Churn Risk</th>
          <th>Phone</th>
          <th></th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody id="client-table-body">
        <tr><td colspan="6" class="text-center py-4 text-muted">Loading…</td></tr>
      </tbody>
    </table>
  </div>
</section>

<div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="add-name-input" class="form-control" placeholder="Client name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Email <span class="text-danger">*</span></label>
                        <input type="email" id="add-email-input" class="form-control" placeholder="email@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Phone</label>
                        <input type="text" id="add-phone-input" class="form-control" placeholder="+216 XX XXX XXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Address</label>
                        <input type="text" id="add-address-input" class="form-control" placeholder="City, Country">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-bold small">Client Type</label>
                            <select id="add-type-input" class="form-select">
                                <option value="B2C">B2C</option>
                                <option value="B2B">B2B</option>
                                <option value="B2G">B2G</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Status</label>
                            <select id="add-status-input" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Prospect">Prospect</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="saveNewClient()">Add Client</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editClientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-id-input">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Full Name</label>
                    <input type="text" id="edit-name-input" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email</label>
                    <input type="email" id="edit-email-input" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Phone</label>
                    <input type="text" id="edit-phone-input" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Address</label>
                    <input type="text" id="edit-address-input" class="form-control">
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Client Type</label>
                        <select id="edit-type-input" class="form-select">
                            <option value="B2C">B2C</option>
                            <option value="B2B">B2B</option>
                            <option value="B2G">B2G</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Status</label>
                        <select id="edit-status-input" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Prospect">Prospect</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-warning" onclick="saveClientEdit()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailClientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#102E4A;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold"
                         style="width:48px;height:48px;color:#102E4A;font-size:1.2rem" id="det-initials">--</div>
                    <h5 class="modal-title fw-bold text-white mb-0" id="det-name">--</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">EMAIL</label>
                        <p class="fw-semibold" id="det-email">--</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">PHONE</label>
                        <p class="fw-semibold" id="det-phone">--</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">CLIENT TYPE</label>
                        <p class="fw-semibold" id="det-type">--</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">STATUS</label>
                        <p class="fw-semibold" id="det-status">--</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">TOTAL SPENT</label>
                        <p class="fw-semibold" id="det-spent">--</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">LAST PURCHASE</label>
                        <p class="fw-semibold" id="det-last-purchase">--</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">CHURN RISK</label>
                        <p class="fw-semibold" id="det-risk">--</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted">ADDRESS</label>
                        <p class="fw-semibold" id="det-address">--</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="deleteToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

</main>
<?php 
    $pagePath = '"clients.js"';
    include("../squelettes entreprise/footer.php");
?>