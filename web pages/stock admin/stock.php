<?php
    $pageTitle = "Stock";
    include('../squelettes entreprise/header.php');
?>
<main class="container py-4" style="margin-top: 100px; margin-bottom: 100px;">

  <header class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
      <h1 class="h3 fw-bold">Stock Management</h1>
      <p class="text-muted mb-0">Monitor inventory levels, restocking alerts, and product catalogue.</p>
    </div>
    <button class="btn btn-finance rounded-pill px-4 shadow-sm" onclick="openAddModal()">
        <i class="bi bi-plus-lg me-2"></i> Add Product
    </button>
  </header>

  <div class="row mb-4 g-3">
    <div class="col-md-3 animate-in">
      <div class="card p-4 kpi kpi-success">
        <small class="text-muted fw-semibold"><i class="bi bi-box-seam me-1"></i>Total Products</small>
        <h4 class="fw-bold mb-0" id="kpi-total">5</h4>
      </div>
    </div>
    <div class="col-md-3 animate-in">
      <div class="card p-4 kpi kpi-success">
        <small class="text-muted fw-semibold"><i class="bi bi-check-circle me-1"></i>In Stock</small>
        <h4 class="fw-bold mb-0 text-success" id="kpi-instock">—</h4>
      </div>
    </div>
    <div class="col-md-3 animate-in">
      <div class="card p-4 kpi kpi-warning">
        <small class="text-muted fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>Low Stock</small>
        <h4 class="fw-bold mb-0 text-warning" id="kpi-low">—</h4>
      </div>
    </div>
    <div class="col-md-3 animate-in">
      <div class="card p-4 kpi kpi-danger">
        <small class="text-muted fw-semibold"><i class="bi bi-x-circle me-1"></i>Out of Stock</small>
        <h4 class="fw-bold mb-0 text-danger" id="kpi-out">—</h4>
      </div>
    </div>
  </div>

  <section class="filter-bar d-flex gap-3 mb-4 flex-wrap animate-in">
    <div class="input-group" style="max-width:340px;">
      <input class="form-control" id="searchInput" placeholder="Search by name, SKU, or category…">
      <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
    </div>

    <select class="form-select" style="max-width:180px;" id="filterCategory">
      <option value="">All Categories</option>
    </select>

    <select class="form-select" style="max-width:180px;" id="filterStatus">
      <option value="">All Stock Levels</option>
      <option value="ok">In Stock</option>
      <option value="low">Low Stock</option>
      <option value="out">Out of Stock</option>
    </select>

    <button class="btn btn-outline-secondary ms-auto" id="exportBtn" onclick="ExportStockToCSV()">
      <i class="bi bi-download me-1"></i>Export CSV
    </button>
  </section>

  <section class="card animate-in">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="stockTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Unit Price</th>
            <th>Stock Qty</th>
            <th>Stock Level</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody">
        </tbody>
      </table>
    </div>
    <div class="p-3 border-top d-flex justify-content-between align-items-center text-muted small">
      <span id="rowCount">Showing 0 products</span>
    </div>
  </section>
  <div class="text-center py-3">
    <button class="btn btn-outline-primary" id="toggleBtn" onclick="toggleRows()">
        Show More
    </button>
  </div>

</main>




<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 overflow-hidden">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="bi bi-info-circle me-2"></i>Product Details</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="detailBody">
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">

      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalTitle">Add Product</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="productId">

        <div class="row g-3">
          
          <div class="col-12">
            <label class="form-label fw-semibold">Product Name</label>
            <input type="text" id="productName" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">SKU</label>
            <input type="text" id="productSKU" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Category</label>
            <input type="text" id="productCategory" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Price</label>
            <input type="number" step="0.01" id="productPrice" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Stock Quantity</label>
            <input type="number" id="productStock" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Min Threshold</label>
            <input type="number" id="productThreshold" class="form-control" value="20">
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea id="productDesc" class="form-control" rows="3"></textarea>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="saveProduct()">Save</button>
      </div>

    </div>
  </div>
</div>
<?php 
    $pagePath = '"stock.js"'; 
    include('../squelettes entreprise/footer.php');
?>