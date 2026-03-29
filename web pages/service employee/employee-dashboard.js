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
const SERVICE_PRICES = {
    'Consultation Fee': 150,
    'Standard Repair': 200,
    'Software Update': 50
};


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
    initServicesTable();

    
    // Set default dates for sales filter
    const today = new Date().toISOString().split('T')[0];
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    document.getElementById('sale-start-date').value = weekAgo;
    document.getElementById('sale-end-date').value = today;
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

// Service Management

function removeServiceRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateOrderSummary();
}
function updateRowPrice(selectElement) {
    const row = selectElement.closest('tr');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    
    row.querySelector('.unit-price').textContent = `$${price}`;
    calculateRowTotal(row.querySelector('.qty-input'));
}
function calculateRowTotal(inputElement) {
    const row = inputElement.closest('tr');
    const qty = parseFloat(inputElement.value) || 0;
    const priceText = row.querySelector('.unit-price').textContent.replace('$', '');
    const price = parseFloat(priceText) || 0;
    
    const total = qty * price;
    row.querySelector('.row-total').textContent = `$${total}`;
    
    calculateOrderSummary();
}
function calculateOrderSummary() {
    let subtotal = 0;

    // Grab every element that has the row-total class
    const rowTotals = document.querySelectorAll('.row-total');
    
    rowTotals.forEach(cell => {
        // Strip out the '$' and commas to get a clean number
        const value = parseFloat(cell.textContent.replace(/[$,]/g, '')) || 0;
        subtotal += value;
    });

    const discount = subtotal * 0.10;
    const total = subtotal - discount;

    // Push the values to the Summary Card IDs we just created
    const subtotalEl = document.getElementById('summary-subtotal');
    const discountEl = document.getElementById('summary-discount');
    const totalEl = document.getElementById('summary-total');

    if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
    if (discountEl) discountEl.textContent = `-${formatCurrency(discount)}`;
    if (totalEl) totalEl.textContent = formatCurrency(total);
}
function initServicesTable() {
    const tbody = document.getElementById('services-tbody');
    if (!tbody) return;


    tbody.addEventListener('change', recalcServices);
    tbody.addEventListener('input', recalcServices);

    
    const addBtn = document.getElementById('add-service-btn');
    if (addBtn) {
        addBtn.onclick = addServiceRow; 
    }


    recalcServices();
}

function recalcServices() {
    const tbody = document.getElementById('services-tbody');
    if (!tbody) return;

    let subtotal = 0;

    tbody.querySelectorAll('tr').forEach(row => {
        const nameEl  = row.querySelector('.service-name');
        const qtyEl   = row.querySelector('.service-qty');
        const unitEl  = row.querySelector('.service-unit-price');
        const totalEl = row.querySelector('.service-line-total');

        if (!nameEl || !qtyEl) return;

        const serviceName = nameEl.value;
        const unitPrice   = SERVICE_PRICES[serviceName] || 0;
        const qty         = Math.max(1, parseInt(qtyEl.value, 10) || 1);
        const lineTotal   = unitPrice * qty;

        if (unitEl)  unitEl.textContent  = formatCurrency(unitPrice);
        if (totalEl) totalEl.textContent = formatCurrency(lineTotal);

        subtotal += lineTotal;
    });

    const discount   = subtotal * 0.10;
    const grandTotal = subtotal - discount;

    // Update Summary Card
    const subtotalEl  = document.getElementById('summary-subtotal');
    const discountEl  = document.getElementById('summary-discount');
    const grandTotalEl = document.getElementById('summary-total');

    if (subtotalEl)   subtotalEl.textContent  = formatCurrency(subtotal);
    if (discountEl)   discountEl.textContent  = `-${formatCurrency(discount)}`;
    if (grandTotalEl) grandTotalEl.textContent = formatCurrency(grandTotal);
}
function addServiceRow() {
    const tbody = document.getElementById('services-tbody');
    if (!tbody) return;

    let options = `<option value="">Select Service</option>`;
    options += Object.keys(SERVICE_PRICES)
        .map(name => `<option value="${name}">${name}</option>`)
        .join('');

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><select class="form-select service-name">${options}</select></td>
        <td><input type="number" class="form-control service-qty" value="1" min="1" style="width:80px"></td>
        <td class="service-unit-price">$0.00</td>
        <td class="fw-bold service-line-total">$0.00</td>
        <td>
            <button class="btn btn-outline-danger btn-sm remove-service-btn">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;

    tr.querySelector('.remove-service-btn').addEventListener('click', () => {
        tr.remove();
        recalcServices();
    });

    tbody.appendChild(tr);
    recalcServices();
}
async function processTransaction() {
    const tbody = document.getElementById('services-tbody');
    const rows = tbody.querySelectorAll('tr');
    const clientName = document.getElementById('client-name').value;
    const clientId = document.getElementById('client-id')?.value || 1;

    if (!clientName || rows.length === 0) {
        showToast('Please select a client and add services', 'warning');
        return;
    }

    // Capture summary values for the receipt BEFORE clearing them
    const summaryData = {
        subtotal: document.getElementById('summary-subtotal').textContent,
        discount: document.getElementById('summary-discount').textContent,
        total: document.getElementById('summary-total').textContent,
        client: clientName,
        items: []
    };

    const transactionData = {
        client_id: clientId,
        sale_date: new Date().toISOString().split('T')[0],
        payment_method: 'Cash',
        payment_status: 'Paid',
        discount: parseFloat(summaryData.discount.replace(/[$-]/g, '')) || 0,
        items: []
    };

    rows.forEach(row => {
        const sName = row.querySelector('.service-name').value;
        const sPrice = row.querySelector('.service-line-total').textContent;
        if(sName) {
            transactionData.items.push({
                product_name: sName,
                quantity: parseInt(row.querySelector('.service-qty').value),
                unit_price: parseFloat(row.querySelector('.service-unit-price').textContent.replace(/[$-]/g, '')),
                discount_percent: 0
            });
            summaryData.items.push({ name: sName, price: sPrice });
        }
    });

    try {
        const response = await fetch('../../api/sales.php?action=create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(transactionData)
        });

        const result = await response.json();

        if (result.success) {
            showToast('Transaction Processed!', 'success');
            
            // --- GENERATE RECEIPT ---
            document.getElementById('receipt-id').textContent = '#' + (result.transaction_id || 'N/A');
            document.getElementById('receipt-date').textContent = new Date().toLocaleDateString();
            document.getElementById('receipt-client-name').textContent = summaryData.client;
            document.getElementById('receipt-subtotal').textContent = summaryData.subtotal;
            document.getElementById('receipt-discount').textContent = summaryData.discount;
            document.getElementById('receipt-total').textContent = summaryData.total;
            
            const itemsBody = document.getElementById('receipt-items');
            itemsBody.innerHTML = summaryData.items.map(item => `
                <tr><td>${item.name}</td><td class="text-end">${item.price}</td></tr>
            `).join('');

            receiptModal.show(); // Show the receipt to the user
            
            // Reset UI
            tbody.innerHTML = ''; 
            document.getElementById('client-name').value = '';
            recalcServices(); 
            loadDashboardStats();
        } else {
            showToast('Error: ' + result.error, 'error');
        }
    } catch (error) {
        showToast('Server error', 'error');
    }
}