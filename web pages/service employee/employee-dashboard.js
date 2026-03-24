/**
 * Employee Dashboard JavaScript
 * 
 * Handles all client-side functionality for the employee dashboard
 */

// Global Variables

let currentPage = 1;
let salesChart = null;
let clientModal, saleModal;

// Initialize on Page Load

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const clientModalEl = document.getElementById('clientModal');
    if (clientModalEl) {
        clientModal = new bootstrap.Modal(clientModalEl);
    }
    
    // Load initial data
    loadDashboardStats();
    
    // Set default dates for sales filter
    const today = new Date().toISOString().split('T')[0];
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    document.getElementById('sale-start-date').value = weekAgo;
    document.getElementById('sale-end-date').value = today;
});

// Navigation

function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Remove active class from all nav links
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected section
    const section = document.getElementById(sectionName + '-section');
    if (section) {
        section.style.display = 'block';
    }
    
    // Add active class to clicked nav link
    event.target.closest('.nav-link').classList.add('active');
    
    // Load data for the section
    switch(sectionName) {
        case 'dashboard':
            loadDashboardStats();
            break;
        case 'clients':
            loadClients();
            break;
        case 'sales':
            loadSales();
            break;
    }
}

// Dashboard Statistics

async function loadDashboardStats() {
    try {
        const response = await fetch('../api/sales.php?action=stats');
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

// Clients Management

async function loadClients() {
    const search = document.getElementById('client-search').value;
    const status = document.getElementById('client-status-filter').value;
    
    try {
        const params = new URLSearchParams({
            action: 'list',
            search: search,
            status: status
        });
        
        const response = await fetch(`../api/clients.php?${params}`);
        const result = await response.json();
        
        if (result.success) {
            displayClients(result.data);
        }
    } catch (error) {
        console.error('Error loading clients:', error);
        showToast('Error loading clients', 'error');
    }
}

function displayClients(clients) {
    const tbody = document.getElementById('clients-table-body');
    
    if (clients.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    No clients found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = clients.map(client => `
        <tr>
            <td class="fw-semibold">${escapeHtml(client.client_name)}</td>
            <td>${client.email || '<span class="text-muted">-</span>'}</td>
            <td>${client.phone || '<span class="text-muted">-</span>'}</td>
            <td><span class="badge bg-info">${client.client_type}</span></td>
            <td><span class="badge bg-${getStatusColor(client.status)}">${client.status}</span></td>
            <td class="fw-semibold text-success">${formatCurrency(client.total_spent)}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary btn-action" onclick="editClient(${client.id})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteClient(${client.id}, '${escapeHtml(client.client_name)}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function searchClients() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        loadClients();
    }, 300);
}

function openClientModal(clientId = null) {
    document.getElementById('client-form').reset();
    document.getElementById('client-id').value = '';
    document.getElementById('clientModalTitle').textContent = 'Add New Client';
    clientModal.show();
}

async function editClient(clientId) {
    try {
        const response = await fetch(`../api/clients.php?action=get&id=${clientId}`);
        const result = await response.json();
        
        if (result.success) {
            const client = result.data;
            document.getElementById('client-id').value = client.id;
            document.getElementById('client-name').value = client.client_name;
            document.getElementById('client-email').value = client.email || '';
            document.getElementById('client-phone').value = client.phone || '';
            document.getElementById('client-address').value = client.address || '';
            document.getElementById('client-type').value = client.client_type;
            document.getElementById('client-status').value = client.status;
            
            document.getElementById('clientModalTitle').textContent = 'Edit Client';
            clientModal.show();
        }
    } catch (error) {
        console.error('Error loading client:', error);
        showToast('Error loading client details', 'error');
    }
}

async function saveClient() {
    const clientId = document.getElementById('client-id').value;
    const clientData = {
        client_name: document.getElementById('client-name').value,
        email: document.getElementById('client-email').value,
        phone: document.getElementById('client-phone').value,
        address: document.getElementById('client-address').value,
        client_type: document.getElementById('client-type').value,
        status: document.getElementById('client-status').value
    };
    
    if (!clientData.client_name) {
        showToast('Client name is required', 'error');
        return;
    }
    
    try {
        const url = clientId 
            ? `../api/clients.php?action=update&id=${clientId}`
            : '../api/clients.php?action=create';
        
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(clientData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            clientModal.hide();
            loadClients();
        } else {
            showToast(result.error || 'Error saving client', 'error');
        }
    } catch (error) {
        console.error('Error saving client:', error);
        showToast('Error saving client', 'error');
    }
}

async function deleteClient(clientId, clientName) {
    if (!confirm(`Are you sure you want to delete "${clientName}"?`)) {
        return;
    }
    
    try {
        const response = await fetch(`../api/clients.php?action=delete&id=${clientId}`, {
            method: 'POST'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Client deleted successfully', 'success');
            loadClients();
        } else {
            showToast(result.error || 'Error deleting client', 'error');
        }
    } catch (error) {
        console.error('Error deleting client:', error);
        showToast('Error deleting client', 'error');
    }
}

// Sales Management

async function loadSales(page = 1) {
    currentPage = page;
    const search = document.getElementById('sale-search').value;
    const startDate = document.getElementById('sale-start-date').value;
    const endDate = document.getElementById('sale-end-date').value;
    
    try {
        const params = new URLSearchParams({
            action: 'list',
            page: page,
            per_page: 10,
            search: search,
            start_date: startDate,
            end_date: endDate
        });
        
        const response = await fetch(`../api/sales.php?${params}`);
        const result = await response.json();
        
        if (result.success) {
            displaySales(result.data);
            updatePagination(result.pagination);
        }
    } catch (error) {
        console.error('Error loading sales:', error);
        showToast('Error loading sales', 'error');
    }
}

function displaySales(sales) {
    const tbody = document.getElementById('sales-table-body');
    
    if (sales.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    No sales found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = sales.map(sale => `
        <tr>
            <td class="fw-semibold">${sale.transaction_id}</td>
            <td>${formatDate(sale.sale_date)}</td>
            <td>${escapeHtml(sale.client_name)}</td>
            <td class="fw-semibold text-success">${formatCurrency(sale.total_amount)}</td>
            <td><span class="badge bg-secondary">${sale.payment_method}</span></td>
            <td><span class="badge bg-${getPaymentStatusColor(sale.payment_status)}">${sale.payment_status}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary btn-action" onclick="viewSale(${sale.id})">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteSale(${sale.id}, '${sale.transaction_id}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function updatePagination(pagination) {
    const container = document.getElementById('sales-pagination');
    
    const infoText = `Showing ${((pagination.page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.page * pagination.per_page, pagination.total)} of ${pagination.total} sales`;
    
    let paginationHTML = `<small class="text-muted">${infoText}</small>`;
    
    if (pagination.total_pages > 1) {
        paginationHTML += '<nav><ul class="pagination pagination-sm mb-0">';
        
        // Previous button
        paginationHTML += `
            <li class="page-item ${pagination.page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadSales(${pagination.page - 1}); return false;">Previous</a>
            </li>
        `;
        
        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            if (i === 1 || i === pagination.total_pages || (i >= pagination.page - 2 && i <= pagination.page + 2)) {
                paginationHTML += `
                    <li class="page-item ${i === pagination.page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadSales(${i}); return false;">${i}</a>
                    </li>
                `;
            } else if (i === pagination.page - 3 || i === pagination.page + 3) {
                paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Next button
        paginationHTML += `
            <li class="page-item ${pagination.page === pagination.total_pages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadSales(${pagination.page + 1}); return false;">Next</a>
            </li>
        `;
        
        paginationHTML += '</ul></nav>';
    }
    
    container.innerHTML = paginationHTML;
}

function searchSales() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        loadSales(1);
    }, 300);
}

async function viewSale(saleId) {
    try {
        const response = await fetch(`../api/sales.php?action=get&id=${saleId}`);
        const result = await response.json();
        
        if (result.success) {
            const sale = result.data;
            alert(`Sale Details:\n\nTransaction: ${sale.transaction_id}\nClient: ${sale.client_name}\nTotal: ${formatCurrency(sale.total_amount)}\nItems: ${sale.items.length}`);
        }
    } catch (error) {
        console.error('Error loading sale:', error);
        showToast('Error loading sale details', 'error');
    }
}

async function deleteSale(saleId, transactionId) {
    if (!confirm(`Are you sure you want to delete sale "${transactionId}"?`)) {
        return;
    }
    
    try {
        const response = await fetch(`../api/sales.php?action=delete&id=${saleId}`, {
            method: 'POST'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Sale deleted successfully', 'success');
            loadSales(currentPage);
            loadDashboardStats();
        } else {
            showToast(result.error || 'Error deleting sale', 'error');
        }
    } catch (error) {
        console.error('Error deleting sale:', error);
        showToast('Error deleting sale', 'error');
    }
}

function openSaleModal() {
    alert('New Sale form will be implemented here');
}

// Utility Functions


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

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getStatusColor(status) {
    const colors = {
        'Active': 'success',
        'Inactive': 'secondary',
        'Prospect': 'warning'
    };
    return colors[status] || 'secondary';
}

function getPaymentStatusColor(status) {
    const colors = {
        'Paid': 'success',
        'Pending': 'warning',
        'Overdue': 'danger'
    };
    return colors[status] || 'secondary';
}

function showToast(message, type = 'info') {
    // Simple toast notification (you can replace with Bootstrap Toast)
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
