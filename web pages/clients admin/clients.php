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
      <h4 class="fw-bold mb-0" id="total-clients-count"></h4>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-4 kpi">
      <small class="text-muted fw-semibold">Churn Alerts</small>
      <h4 class="fw-bold mb-0 text-danger" id="stat-churn-risk"></h4>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-4 kpi">
      <small class="text-muted fw-semibold">Avg. Revenue</small>
      <h4 class="fw-bold mb-0" id="stat-month-amount"></h4>
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
          <th>Numero telephone</th>
          <th></th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody id="client-table-body">
        <tr>
          <td class="fw-semibold"></td>
          <td></td>
          <td>
            <div class="progress mb-1" style="height:8px">
              <div class="progress-bar" ></div>
            </div>
            <small></small>
          </td>
          <td>
            <span class="badge bg-danger-subtle text-danger"></span>
          </td>
          <td></td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary">Details</button>
            <button class="btn btn-sm btn-outline-danger">🗑️</button>
            <button type="button" class="btn btn-sm btn-outline-warning">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

</main>
<?php 
    $pagePath = '"clients.js"';
    include("../squelettes entreprise/footer.php");
?>