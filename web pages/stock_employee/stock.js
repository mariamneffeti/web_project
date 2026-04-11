let allProducts = [];

document.addEventListener("DOMContentLoaded", () => {
    loadProducts();
    
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterCategory').addEventListener('change', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);
});

async function loadProducts() {
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">Loading products...</td></tr>`;

    try {
        const response = await fetch("../../api/products.php?action=list");
        const result = await response.json();

        if (result.success) {
            allProducts = result.data;
            renderTable(allProducts);
            updateKPIs(allProducts);
        } else {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error("Fetch error:", error);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Failed to connect to server.</td></tr>`;
    }
}

function renderTable(data) {
    const tbody = document.getElementById("tableBody");
    const rowCount = document.getElementById("rowCount");
    tbody.innerHTML = "";

    data.forEach((product, index) => {
        const qty = parseInt(product.stock_quantity) || 0;
        const threshold = 20; 

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
            <td>$${parseFloat(product.price).toFixed(2)}</td>
            <td>${qty} units</td>
            <td><span class="stock-badge ${statusClass}">${statusText}</span></td>
            <td class="text-end">
                <div class="d-inline-flex gap-2">
                    <button class="btn btn-sm btn-light border" onclick="viewDetails(${product.id})"><i class="bi bi-eye"></i></button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

    rowCount.textContent = `Showing ${data.length} products`;
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

    renderTable(filtered);
}

async function viewDetails(id) {
    try {
        const res = await fetch(`../../api/products.php?action=get&id=${id}`);
        const result = await res.json();
        
        if (result.success) {
            const p = result.data;
            document.getElementById('detailBody').innerHTML = `
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">Description</label>
                    <p class="mb-0">${p.description || 'No description available.'}</p>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-muted small text-uppercase fw-bold">SKU</label>
                        <p>${p.sku || 'N/A'}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small text-uppercase fw-bold">Last Updated</label>
                        <p>${p.updated_at || 'N/A'}</p>
                    </div>
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
