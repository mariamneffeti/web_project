let allProducts = [];
let visibleCount = 5;
let filteredProducts = [];
document.addEventListener("DOMContentLoaded", () => {
    loadProducts();
    loadCategories();

    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterCategory').addEventListener('change', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);
});

async function loadProducts() {
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">Loading products...</td></tr>`;

    try {
        const response = await fetch("products.php?action=get_products");
        const data = await response.json();

        if (Array.isArray(data)) {
            allProducts = data;
            filteredProducts = [...allProducts];
            visibleCount = 5;

            renderTable();
            updateKPIs(allProducts);
        } else {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Failed to load products</td></tr>`;
        }
    } catch (error) {
        console.error("Fetch error:", error);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Failed to connect to server.</td></tr>`;
    }
}

function renderTable() {
    const tbody = document.getElementById("tableBody");
    const rowCount = document.getElementById("rowCount");
    const toggleBtn = document.getElementById("toggleBtn");

    tbody.innerHTML = "";

    const dataToShow = filteredProducts.slice(0, visibleCount);

    dataToShow.forEach((product, index) => {
        const qty = parseInt(product.stock_quantity) || 0;
        const threshold = parseInt(product.min_threshold) || 20;

        let statusClass = "stock-ok";
        let statusText = "In Stock";

        if (qty <= 0) {
            statusClass = "stock-out";
            statusText = "Out of Stock";
        } else if (qty <= threshold) {
            statusClass = "stock-low";
            statusText = "Low Stock";
        }

        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><span class="fw-bold text-dark">${product.product_name}</span></td>
            <td><code class="small text-muted">${product.sku || 'N/A'}</code></td>
            <td><span class="cat-pill">${product.category}</span></td>
            <td>${parseFloat(product.price).toFixed(2)}DT</td>
            <td>${qty} units</td>
            <td><span class="stock-badge ${statusClass}">${statusText}</span></td>
            <td class="text-end">
                <div class="d-inline-flex gap-2">
                    <button class="btn btn-sm btn-light border" onclick="viewDetails(${product.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editProduct(${product.id})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

    rowCount.textContent = `Showing ${dataToShow.length} of ${filteredProducts.length} products`;

    // Button logic
    if (filteredProducts.length <= 5) {
        toggleBtn.style.display = "none";
    } else {
        toggleBtn.style.display = "inline-block";
        toggleBtn.textContent = visibleCount >= filteredProducts.length ? "Show Less" : "Show More";
    }
}

function toggleRows() {
    if (visibleCount >= filteredProducts.length) {
        visibleCount = 5; // reset
    } else {
        visibleCount += 5;
    }
    renderTable();
}

function updateKPIs(data) {
    const total = data.length;
    const outOfStock = data.filter(p => p.stock_quantity <= 0).length;
    const lowStock = data.filter(p => p.stock_quantity > 0 && p.stock_quantity <= 20).length;
    const inStock = total - outOfStock - lowStock;

    document.getElementById('kpi-total').textContent = total;
    document.getElementById('kpi-instock').textContent = inStock;
    document.getElementById('kpi-low').textContent = lowStock;
    document.getElementById('kpi-out').textContent = outOfStock;
}

function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const cat = document.getElementById('filterCategory').value;
    const status = document.getElementById('filterStatus').value;

    const filtered = allProducts.filter(p => {
        const matchesSearch = p.product_name.toLowerCase().includes(search) || 
                              (p.sku && p.sku.toLowerCase().includes(search));
        const matchesCat = cat === "" || p.category === cat;
        
        let matchesStatus = true;
        const qty = p.stock_quantity;
        if (status === "out") matchesStatus = (qty <= 0);
        if (status === "low") matchesStatus = (qty > 0 && qty <= 20);
        if (status === "ok") matchesStatus = (qty > 20);

        return matchesSearch && matchesCat && matchesStatus;
    });

    filteredProducts = filtered;
    visibleCount = 5; 
    renderTable();
}

async function viewDetails(id) {
    try {
        const res = await fetch(`products.php?action=get_product&id=${id}`);
        const p = await res.json();

        if (p && p.id) {
            let status = "In Stock";
            if (p.stock_quantity <= 0) status = "Out of Stock";
            else if (p.stock_quantity <= (p.min_threshold || 20)) status = "Low Stock";
            document.getElementById('detailBody').innerHTML = `
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">${p.product_name}</h5>
                    <span class="badge bg-secondary">${p.category || 'No category'}</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <small class="text-muted">SKU</small>
                        <div>${p.sku || 'N/A'}</div>
                    </div>

                    <div class="col-6">
                        <small class="text-muted">Price</small>
                        <div>${parseFloat(p.price).toFixed(2)}DT</div>
                    </div>

                    <div class="col-6">
                        <small class="text-muted">Stock Quantity</small>
                        <div>${p.stock_quantity} units</div>
                    </div>

                    <div class="col-6">
                        <small class="text-muted">Min Threshold</small>
                        <div>${p.min_threshold || 20}</div>
                    </div>
                </div>

                <hr>

                <div>
                    <small class="text-muted">Description</small>
                    <p class="mb-0">${p.description || 'No description available.'}</p>
                </div>

                <div class="col-6">
                    <small class="text-muted">Stock Status</small>
                    <div>${status}</div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        }
    } catch (e) { console.error(e); }
}

async function ExportStockToCSV() {
    if (!allProducts || allProducts.length == 0) {
        alert("There are no products to export!");
        return;
    }

    const headers = ["Product Name", "SKU", "Category", "Unit Price", "Stock Quantity", "Status"];

    const rows = allProducts.map(product => {
        const qty = parseInt(product.stock_quantity) || 0;
        let status = "In Stock";
        if (qty <= 0) status = "Out of Stock";
        else if (qty <= 20) status = "Low Stock";

        return [
            `"${product.product_name || ''}"`,
            `"${product.sku || 'N/A'}"`,
            `"${product.category || ''}"`,
            `"${parseFloat(product.price).toFixed(2)}"`,
            `"${qty}"`,
            `"${status}"`
        ];
    });

    const csvContent = [
        headers.join(","),
        ...rows.map(row => row.join(","))
    ].join("\n");

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");

    link.setAttribute("href", url);
    link.setAttribute("download", `inventory_report_${new Date().toISOString().slice(0, 10)}.csv`);
    link.style.visibility = 'hidden';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = "Add Product";
    document.getElementById('productId').value = "";

    document.getElementById('productName').value = "";
    document.getElementById('productSKU').value = "";
    document.getElementById('productCategory').value = "";
    document.getElementById('productPrice').value = "";
    document.getElementById('productStock').value = "";
    document.getElementById('productDesc').value = "";

    new bootstrap.Modal(document.getElementById('productModal')).show();
}

async function editProduct(id) {
    const res = await fetch(`products.php?action=get_product&id=${id}`);
    const p = await res.json();

    if (!p || !p.id) return;

    document.getElementById('modalTitle').textContent = "Edit Product";
    document.getElementById('productId').value = p.id;
    document.getElementById('productThreshold').value = p.min_threshold || 20;
    document.getElementById('productName').value = p.product_name;
    document.getElementById('productSKU').value = p.sku || "";
    document.getElementById('productCategory').value = p.category || "";
    document.getElementById('productPrice').value = p.price;
    document.getElementById('productStock').value = p.stock_quantity;
    document.getElementById('productDesc').value = p.description || "";

    new bootstrap.Modal(document.getElementById('productModal')).show();
}

async function saveProduct() {
    const id = document.getElementById('productId').value;

    const data = {
        id: id,
        product_name: document.getElementById('productName').value,
        sku: document.getElementById('productSKU').value,
        category: document.getElementById('productCategory').value,
        price: document.getElementById('productPrice').value,
        stock_quantity: document.getElementById('productStock').value,
        min_threshold: document.getElementById('productThreshold').value,
        description: document.getElementById('productDesc').value
    };

    const action = id ? "update_product" : "add_product";

    const res = await fetch(`products.php?action=${action}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    });

    const result = await res.json();

    if (result.success) {
        bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();

        await loadProducts();
        await loadCategories();
    } else {
        alert(result.error || "Something went wrong");
    }
}

async function deleteProduct(id) {
    if (!confirm("Are you sure you want to delete this product?")) return;

    const res = await fetch("products.php?action=delete_product", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    const result = await res.json();

    if (result.success) {
        loadProducts();
    } else {
        alert(result.error || "Delete failed");
    }
}
async function loadCategories() {
    try {
        const res = await fetch("products.php?action=get_categories");
        const categories = await res.json();

        const select = document.getElementById("filterCategory");

        // Reset dropdown
        select.innerHTML = `<option value="">All Categories</option>`;

        categories.forEach(cat => {
            const option = document.createElement("option");
            option.value = cat;
            option.textContent = cat;
            select.appendChild(option);
        });

    } catch (e) {
        console.error("Failed to load categories", e);
    }
}