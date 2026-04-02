/*/
Global variables : recieptModal : it has the client,the discount and the amount and the service
current page : useless for now i don't know if we would need it after linking
saleschart : the chart of 7 days
/*/ 

// TODO : linking between pages
let receiptModal;
let currentPage = 1;
let salesChart = null;
let saleModal;
let PRODUCT_DATA = [];
let SERVICE_DATA = [];

/*/
initializing the modals and the current page
/*/
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const receiptModalEl = document.getElementById('receiptModal');
    if (receiptModalEl) receiptModal = new bootstrap.Modal(receiptModalEl);
    const searchBtn = document.getElementById('btn-search-client');
    if (searchBtn) {
        searchBtn.addEventListener('click', async function() {
            const searchTerm = nameInput.value.trim();
            
            if (!searchTerm) {
                showToast('Please enter a name to search', 'warning');
                return;
            }
            await performClientSearch(searchTerm);});}
    const nameInput = document.getElementById('client-name');
    if (nameInput) {
        nameInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                document.getElementById('btn-search-client').click();
            }});}
    
    // Load initial data
    loadDashboardStats();
    loadInventory();

    
    // Set default dates for sales filter
    const today = new Date().toISOString().split('T')[0];
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    document.getElementById('sale-start-date').value = weekAgo;
    document.getElementById('sale-end-date').value = today;
    const processBtn = document.getElementById('btn-process-transaction');
    if (processBtn) processBtn.addEventListener('click', processTransaction);
    const addSaleBtn = document.getElementById('btn-add-sale');
    if (addSaleBtn) addSaleBtn.addEventListener('click', addSaleRow);

    const addServiceBtn = document.getElementById('btn-add-service');
    if (addServiceBtn) addServiceBtn.addEventListener('click', addServiceRow);
});

// Dashboard Statistics


// Function to update the cards for a SPECIFIC client
async function updateClientStats(clientId) {
    try {
        // Fetch data for just ONE client
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
async function loadDashboardStats() {
    try {
        const response = await fetch('../../api/sales.php?action=stats');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update stat cards
            document.getElementById('stat-today-count').textContent = data.today.count;
            document.getElementById('stat-today-amount').textContent = formatCurrency(data.today.total);
            
            document.getElementById('stat-month-count').textContent = data.this_month.count;
            document.getElementById('stat-month-amount').textContent = formatCurrency(data.this_month.total);
            
            document.getElementById('stat-clients').textContent = data.total_clients;
            
            // Update sales chart
            updateSalesChart(data.recent_sales);
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
        showToast('Error loading dashboard statistics', 'error');
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
            document.getElementById('client-id').value = ''; // Clear ID if not found
        }
    } catch (error) {
        showToast('Error searching for client', 'error');
    }
}

function updateSalesChart(salesData) {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    
    // Prepare data for last 7 days
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

// helper functions


function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount || 0);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
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

// TODO
/*/
add a button afterwards for login and profile
/*/
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = '../auth/logout.php';
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);
// loading service and sale tables
async function loadInventory() {
    try {
        const [prodRes, servRes] = await Promise.all([
            fetch('../../api/products.php?action=list'),
            fetch('../../api/services.php?action=list')
        ]);
        
        const products = await prodRes.json();
        const services = await servRes.json();

        if (products.success) PRODUCT_DATA = products.data;
        if (services.success) SERVICE_DATA = services.data;
        
        console.log("Inventory Loaded:", { products: PRODUCT_DATA.length, services: SERVICE_DATA.length });
    } catch (e) {
        showToast("Failed to sync inventory", "error");
    }
}

// Sale Management
function addSaleRow() {
    const tbody = document.getElementById('sales-tbody');
    let options = PRODUCT_DATA.map(p => 
        `<option value="${p.id}" data-price="${p.price}">${p.product_name}</option>`
    ).join('');
    
    createRow(tbody, options, 'product');
}
// Service Management
function addServiceRow() {
    const tbody = document.getElementById('services-tbody');
    let options = SERVICE_DATA.map(s => 
        `<option value="${s.id}" data-price="${s.base_price}">${s.service_name}</option>`
    ).join('');
    
    createRow(tbody, options, 'service');
}
// Sales and Services
function createRow(tbody, options, type) {
    const tr = document.createElement('tr');
    tr.setAttribute('data-type', type);
    tr.innerHTML = `
        <td>
            <select class="form-select item-select">
                <option value="">Select ${type === 'product' ? 'Product' : 'Service'}...</option>
                ${options}
            </select>
        </td>
        <td><input type="number" class="form-control item-qty" value="1" min="1" style="width:80px"></td>
        <td class="unit-price">$0.00</td>
        <td class="fw-bold row-total">$0.00</td>
        <td>
            <button class="btn btn-outline-danger btn-sm" onclick="removeRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tr.querySelector('.item-select').addEventListener('change', (e) => updateRowPrice(e.target));
    tr.querySelector('.item-qty').addEventListener('input', (e) => calculateRowTotal(e.target));
    
    tbody.appendChild(tr);
}

function updateRowPrice(selectElement) {
    const row = selectElement.closest('tr');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    
    row.querySelector('.unit-price').textContent = formatCurrency(price);
    
    const qtyInput = row.querySelector('.item-qty');
    calculateRowTotal(qtyInput);
}

function calculateRowTotal(inputElement) {
    const row = inputElement.closest('tr');
    const qty = parseFloat(inputElement.value) || 0;
    
    const priceText = row.querySelector('.unit-price').textContent.replace(/[$,]/g, '');
    const price = parseFloat(priceText) || 0;
    
    const total = qty * price;
    row.querySelector('.row-total').textContent = formatCurrency(total);
    
    calculateOrderSummary();
}
function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateOrderSummary(); 
}
function calculateOrderSummary() {
    let subtotal = 0;

    document.querySelectorAll('.row-total').forEach(cell => {
        const value = parseFloat(cell.textContent.replace(/[$,]/g, '')) || 0;
        subtotal += value;
    });

    const discount = subtotal * 0.10; 
    const total = subtotal - discount;

    const subtotalEl = document.getElementById('summary-subtotal');
    const discountEl = document.getElementById('summary-discount');
    const totalEl = document.getElementById('summary-total');

    if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
    if (discountEl) discountEl.textContent = `-${formatCurrency(discount)}`;
    if (totalEl) totalEl.textContent = formatCurrency(total);
}

// Transaction

async function processTransaction() {
    const clientId = document.getElementById('client-id').value;
    const clientName = document.getElementById('client-name').value;

    if (!clientId || !clientName) {
        showToast('Please search for and select a client first', 'warning');
        return;
    }

    const payload = {
        client_id: clientId,
        sale_date: new Date().toISOString().split('T')[0],
        payment_method: 'Cash',
        payment_status: 'Pending',
        discount: parseFloat(document.getElementById('summary-discount').textContent.replace(/[$-]/g, '')) || 0,
        tax: 0, 
        notes: "Transaction from Employee Dashboard",
        product_items: [],
        service_items: []
    };

    document.querySelectorAll('.item-select').forEach(select => {
        const row = select.closest('tr');
        const itemId = select.value;
        if (!itemId) return;

        const selectedOption = select.options[select.selectedIndex];
        const itemName = selectedOption.text;
        const qty = parseInt(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const type = row.getAttribute('data-type');

        if (qty > 0) {
            if (type === 'product') {
                payload.product_items.push({
                    product_id: itemId,
                    product_name: itemName,
                    quantity: qty,
                    unit_price: price
                });
            } else {
                payload.service_items.push({
                    service_name: itemName,
                    quantity_hours: qty, 
                    unit_price: price
                });
            }
        }
    });

    if (payload.product_items.length === 0 && payload.service_items.length === 0) {
        showToast('Please add at least one product or service', 'warning');
        return;
    }

    try {
        const response = await fetch('../../api/sales.php?action=create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.success) {
            showToast('Transaction successful!', 'success');
            
            document.getElementById('receipt-id').textContent = '#' + (result.transaction_id || result.sale_id);
            document.getElementById('receipt-client-name').textContent = clientName;
            document.getElementById('receipt-total').textContent = document.getElementById('summary-total').textContent;
            
            const modalEl = document.getElementById('receiptModal');
            modalEl.removeAttribute('aria-hidden');
            receiptModal.show();

            document.getElementById('client-name').value = '';
            document.getElementById('client-id').value = '';
            
            const emailField = document.getElementById('client-email');
            if (emailField) emailField.value = '';
            
            const lastPurchase = document.getElementById('last-purchase-date');
            if (lastPurchase) lastPurchase.textContent = 'No purchases';

            const totalSpent = document.getElementById('client-total-spent');
            if (totalSpent) totalSpent.textContent = '$0.00';

            document.getElementById('sales-tbody').innerHTML = '';
            document.getElementById('services-tbody').innerHTML = '';
            
            calculateOrderSummary();
            
            loadDashboardStats();

        } else {
            showToast('Error: ' + (result.error || result.message), 'error');
            console.error("Server Error Detail:", result);
        }
    } catch (error) {
        console.error('Transaction failed:', error);
        showToast('Server error processing sale. Check console.', 'error');
    }
}