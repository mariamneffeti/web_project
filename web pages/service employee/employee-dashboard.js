let receiptModal;
let salesChart = null;
let PRODUCT_DATA = [];
let SERVICE_DATA = [];
let currentMode = 'sales';

document.addEventListener('DOMContentLoaded', function() {
    const receiptModalEl = document.getElementById('receiptModal');
    if (receiptModalEl) receiptModal = new bootstrap.Modal(receiptModalEl);

    const searchBtn = document.getElementById('btn-search-client');
    const nameInput = document.getElementById('client-name');
    
    if (searchBtn && nameInput) {
        searchBtn.addEventListener('click', () => performClientSearch(nameInput.value.trim()));
        nameInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); searchBtn.click(); }
        });
    }

    loadDashboardData();
    updateSalesChart();

    const processBtn = document.getElementById('btn-process-transaction');
    if (processBtn) processBtn.addEventListener('click', processTransaction);

    const addBtn = document.getElementById('btn-add-item'); 
    if (addBtn) addBtn.addEventListener('click', addRow);
    
    ['btn-add-sale', 'btn-add-service'].forEach(id => {
        const btn = document.getElementById(id);
        if (btn) btn.addEventListener('click', addRow);
    });

    const today = new Date().toISOString().split('T')[0];
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    if (document.getElementById('sale-start-date')) document.getElementById('sale-start-date').value = weekAgo;
    if (document.getElementById('sale-end-date')) document.getElementById('sale-end-date').value = today;
});
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('client-search-input');
    const resultsContainer = document.getElementById('search-results');
    const clientIdHidden = document.getElementById('client-id');
    
    let allClients = [];

    fetch('../../api/clients.php?action=list')
        .then(res => res.json())
        .then(result => {
            if (result.success) allClients = result.data;
        });

    searchInput.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        resultsContainer.innerHTML = '';
        
        if (val.length < 1) {
            resultsContainer.classList.add('d-none');
            return;
        }

        const filtered = allClients.filter(c => 
            c.client_name.toLowerCase().includes(val)
        );

        if (filtered.length > 0) {
            filtered.forEach(client => {
                const item = document.createElement('div');
                item.className = 'list-group-item list-group-item-action';
                item.innerHTML = `<i class="bi bi-person me-2"></i>${client.client_name}`;
                
                item.onclick = () => {
                    searchInput.value = client.client_name;
                    clientIdHidden.value = client.id;
                    resultsContainer.classList.add('d-none');
                    if(window.updateClientStats) updateClientStats(client.id);
                };
                resultsContainer.appendChild(item);
            });
            resultsContainer.classList.remove('d-none');
        } else {
            resultsContainer.classList.add('d-none');
        }
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target)) resultsContainer.classList.add('d-none');
    });
});
async function updateClientStats(clientId) {
    try {
        const response = await fetch(`../../api/clients.php?action=get&id=${clientId}`);
        const result = await response.json();
        
        if (result.success) {
            const client = result.data;
            const lastPurchaseEl = document.getElementById('last-purchase-date');
            if (lastPurchaseEl) {
                lastPurchaseEl.textContent = client.last_purchase_date || 'No purchases';
            }
            const totalSpentEl = document.getElementById('client-total-spent');
            if (totalSpentEl) {
                totalSpentEl.textContent = formatCurrency(client.total_spent);
            }
        }
    } catch (error) {
        console.error('Error loading client stats:', error);
    }
}
async function performClientSearch(name) {
    try {
        const response = await fetch(`../../api/clients.php?action=list&search=${encodeURIComponent(name)}`);
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            const foundClient = result.data[0]; 
            
            document.getElementById('client-name').value = foundClient.client_name;
            document.getElementById('client-id').value = foundClient.id; 
            
            document.getElementById('client-email').value = foundClient.email || '';
            await updateClientStats(foundClient.id);
            
            showToast(`Found: ${foundClient.client_name}`, 'success');
        } else {
            showToast('No client found', 'error');
            document.getElementById('client-id').value = '';
        }
    } catch (error) {
        showToast('Error searching for client', 'error');
    }
}
function updateSalesChart(salesData) {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    
    const labels = [];
    const data = [];
    
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        
        const sale = salesData.find(s => s.date === dateStr);
        data.push(sale ? parseFloat(sale.total) : 0);
    }
    
    if (salesChart) {
        salesChart.destroy();
    }
    
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Sales',
                data: data,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}
function showToast(message, type = 'info') {

    const colors = {
        'success': '#198754',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#0dcaf0'
    };
    
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
async function loadDashboardData() {
    try {
        const [statsRes, prodRes, servRes] = await Promise.all([
            fetch('../../api/sales.php?action=stats'),
            fetch('../../api/products.php?action=list'),
            fetch('../../api/services.php?action=list')
        ]);

        const statsResult = await statsRes.json();
        const prodResult = await prodRes.json();
        const servResult = await servRes.json();

        PRODUCT_DATA = prodResult.success ? prodResult.data : [];
        SERVICE_DATA = servResult.success ? servResult.data : [];

        if (statsResult.success) {
            updateStatsUI(statsResult.data);
            if (typeof updateSalesChart === 'function') updateSalesChart(statsResult.data.recent_sales);
        }

        const tbody = document.getElementById('services-tbody');
        if (tbody) {
            tbody.innerHTML = ''; 
            addRow(); 
        }
    } catch (error) {
        console.error('Initial load failed:', error);
    }
}


function setMode(mode) {
    const tbody = document.getElementById('services-tbody');
    if (tbody.children.length > 0) {
        if (!confirm("Switching modes will clear current selections. Continue?")) return;
    }

    currentMode = mode;
    tbody.innerHTML = ''; 
    
    document.getElementById('mode-sales').classList.toggle('active', mode === 'sales');
    document.getElementById('mode-services').classList.toggle('active', mode === 'services');
    
    const header = document.getElementById('type-header');
    if(header) header.innerText = (mode === 'sales') ? "Product Name" : "Service Name";

    addRow();
    updateTotals();
}

function addRow() {
    const tbody = document.getElementById('services-tbody');
    const row = document.createElement('tr');
    row.setAttribute('data-type', currentMode === 'sales' ? 'product' : 'service');
    
    const items = (currentMode === 'sales') ? PRODUCT_DATA : SERVICE_DATA;
    
    let optionsHtml = `<option value="" data-price="0">Select Item...</option>`;
    
    items.forEach(item => {
        const name = (currentMode === 'sales') ? item.product_name : item.service_name;
        const price = (currentMode === 'sales') ? item.price : item.base_price;
        const id = item.id;

        optionsHtml += `<option value="${id}" data-name="${name}" data-price="${price}">${name}</option>`;
    });

    row.innerHTML = `
        <td class="ps-4">
            <select class="form-select form-select-sm border-0 bg-light item-select" onchange="handleItemSelect(this)">
                ${optionsHtml}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm text-center item-qty" value="1" min="1" onchange="updateTotals()">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-transparent border-0 text-muted small">$</span>
                <input type="number" class="form-control form-control-sm border-0 bg-light price-input" value="0" step="0.01" onchange="updateTotals()">
            </div>
        </td>
        <td class="fw-bold text-dark row-total">$0.00</td>
        <td class="text-end pe-4">
            <button class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove(); updateTotals();">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}
function handleItemSelect(select) {
    const option = select.options[select.selectedIndex];
    const price = option.getAttribute('data-price') || 0;
    const row = select.closest('tr');
    row.querySelector('.price-input').value = price;
    updateTotals();
}

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('#services-tbody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        row.querySelector('.row-total').innerText = formatCurrency(total);
        subtotal += total;
    });

    const discount = subtotal * 0.10; 
    const total = subtotal - discount;

    document.getElementById('summary-subtotal').innerText = formatCurrency(subtotal);
    document.getElementById('summary-discount').innerText = `-${formatCurrency(discount)}`;
    document.getElementById('summary-total').innerText = formatCurrency(total);
}


async function processTransaction() {
    const clientId = document.getElementById('client-id').value;
    const saleDate = new Date().toISOString().split('T')[0];

    if (!clientId) {
        showToast('Please select a client first', 'warning');
        return;
    }

    const payload = {
        client_id: clientId,
        sale_date: saleDate,
        payment_method: 'Cash', 
        payment_status: 'Pending',
        discount: parseFloat(document.getElementById('summary-discount').textContent.replace(/[$-]/g, '')) || 0,
        tax: 0, 
        notes: "Transaction from Dashboard",
        product_items: [],
        service_items: []
    };

    document.querySelectorAll('#services-tbody tr').forEach(row => {
        const select = row.querySelector('.item-select');
        const id = select.value;
        if (!id) return;

        const qty = parseInt(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const name = select.options[select.selectedIndex].getAttribute('data-name');
        const type = row.getAttribute('data-type');

        if (qty > 0) {
            if (type === 'product') {
                payload.product_items.push({
                    product_id: id,
                    product_name: name,
                    quantity: qty,
                    unit_price: price
                });
            } else {
                payload.service_items.push({
                    service_name: name,
                    quantity_hours: qty,
                    unit_price: price
                });
            }
        }
    });

    if (payload.product_items.length === 0 && payload.service_items.length === 0) {
        showToast('Please add at least one item', 'warning');
        return;
    }

    try {
        const response = await fetch('../../api/sales.php?action=create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showToast('Transaction successful!', 'success');
            if (receiptModal) receiptModal.show();
            loadDashboardData(); // Refresh stats
        } else {
            showToast('Error: ' + (result.error || result.message), 'error');
            console.error("Server 400 Detail:", result);
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        showToast('Server connection failed', 'error');
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount || 0);
}

function updateStatsUI(data) {
    document.getElementById('stat-today-count').textContent = data.today.count;
    document.getElementById('stat-today-amount').textContent = formatCurrency(data.today.total);
    document.getElementById('stat-month-count').textContent = data.this_month.count;
    document.getElementById('stat-month-amount').textContent = formatCurrency(data.this_month.total);
    document.getElementById('stat-clients').textContent = data.total_clients;
}