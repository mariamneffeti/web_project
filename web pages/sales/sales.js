let allTransactions = [];  
let revenueChart    = null;
let filterType      = 'all';
let searchQuery     = '';

//  init
document.addEventListener('DOMContentLoaded', () => {
  loadStats();
  loadTransactions();
  initRevenueChart();
  bindFilters();
  updateChurnKPI();
});

//  HELPERS 
const api = (file, params = {}) => {
  const url = new URL(`../../api/${file}`, window.location.href);
  Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, v));
  return fetch(url).then(r => r.json());
};

const fmt = n =>
  '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

const fmtDate = d =>
  d ? new Date(d).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '—';
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
async function updateChurnKPI() {
    try {
        const response = await fetch('https://churnprediction-production-bae9.up.railway.app/bulk_predict');
        const data = await response.json();

        const churnValue = document.getElementById('stat-churn-risk');
        const churnSubtext = document.querySelector('#stat-churn-risk + .text-danger');

        if (data.success) {
            churnValue.textContent = data.at_risk_count;
            
            if (data.at_risk_count > 0) {
                churnSubtext.textContent = "High risk detected";
                churnSubtext.style.color = "#dc3545"; // Red
            } else {
                churnSubtext.textContent = "Clients are healthy";
                churnSubtext.style.color = "#198754"; // Green
            }
        }
    } catch (error) {
        console.error("AI is offline");
        document.getElementById('stat-churn-risk').textContent = "??"; 
        document.querySelector('.text-danger').textContent = "AI Server Offline";
    }
}
//  KPI CARDS 
async function loadStats() {
  try {
    const res = await api('sales.php', { action: 'stats' });
    if (!res.success) return;

    const { this_month } = res.data;

    // Monthly Revenue KPI (index 0)
    const el = document.getElementById('stat-monthly-revenue');
    if (el) el.textContent = fmt(this_month.total ?? 0);
    // Closed / pending are computed client-side after loadTransactions
  } catch (e) {
    console.error('Stats error:', e);
  }
}

function updateStatusKPIs() {
  const total   = allTransactions.length;
  const closed  = allTransactions.filter(t => t._status === 'completed').length;
  const pending = allTransactions.filter(t => t._status === 'pending').length;

  // KPI 2 — Closed Deals  "closed / total"
  const closedVal = document.getElementById('stat-closed-deals');
  const closedTarget = document.getElementById('stat-closed-target');
  if (closedVal) closedVal.textContent = `${closed} / ${total}`;
  if (closedTarget) closedTarget.textContent = `Target: ${total}`;

  // KPI 3 — Pending Quotes
  const pendingVal = document.getElementById('stat-pending-quotes');
  if (pendingVal) pendingVal.textContent = pending;
  // KPI 4 - churn
  if (typeof updateChurnKPI === "function") {
      updateChurnKPI();
  }
}

//  LOAD TRANSACTIONS 
async function loadTransactions() {
  const tbody = document.querySelector('tbody');
  tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Loading…</td></tr>`;

  try {
    const salesRes = await api('sales.php', { action: 'list', per_page: 200 });
    allTransactions = [];

    if (!salesRes.success) throw new Error(salesRes.error || 'Failed to load sales');

    for (const sale of salesRes.data) {
      const detail = await api('sales.php', { action: 'get', id: sale.id });
      if (!detail.success) continue;
      const d = detail.data;

      // Determine initial status: 'Paid' → completed, anything else → pending
      const status = sale.payment_status === 'Paid' ? 'completed' : 'pending';

      //  Product-sale items 
      if (d.product_items && d.product_items.length > 0) {
        d.product_items.forEach(item => {
          allTransactions.push({
            _id:      `sale-${sale.id}-${item.id}`,
            _saleId:  sale.id,
            _type:    'sale',
            _status:  status,
            _amount:  item.total_price,
            date:     sale.sale_date,
            client:   sale.client_name,
            name:     item.product_name,
          });
        });
      }

      //  Service items linked to this sale 
      if (d.service_items && d.service_items.length > 0) {
        d.service_items.forEach(item => {
          allTransactions.push({
            _id:      `svc-${sale.id}-${item.id}`,
            _saleId:  sale.id,
            _type:    'service',
            _status:  status,
            _amount:  item.total_price,
            date:     sale.sale_date,
            client:   sale.client_name,
            name:     item.service_name,
          });
        });
      }

      //  Fallback: sale with no items 
      if (
        (!d.product_items || d.product_items.length === 0) &&
        (!d.service_items  || d.service_items.length  === 0)
      ) {
        allTransactions.push({
          _id:     `sale-${sale.id}`,
          _saleId: sale.id,
          _type:   'sale',
          _status: status,
          _amount: sale.total_amount,
          date:    sale.sale_date,
          client:  sale.client_name,
          name:    '—',
        });
      }
    }

    renderTable();
    updateStatusKPIs();
    updateRevenueChart();

  } catch (e) {
    console.error('Transactions error:', e);
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Failed to load transactions.</td></tr>`;
  }
}

//  TABLE RENDER 
function renderTable() {
  const tbody = document.querySelector('tbody');
  tbody.innerHTML = '';

  let rows = allTransactions;

  if (filterType !== 'all') rows = rows.filter(t => t._type === filterType);

  if (searchQuery) {
    const q = searchQuery.toLowerCase();
    rows = rows.filter(t =>
      (t.client || '').toLowerCase().includes(q) ||
      (t.name   || '').toLowerCase().includes(q)
    );
  }

  if (rows.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">No transactions found.</td></tr>`;
    return;
  }

  rows.forEach(t => {
    const isCompleted = t._status === 'completed';

    const statusClass = isCompleted ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning';
    const statusLabel = isCompleted ? 'Completed' : 'Pending';

    const typeClass = t._type === 'sale'
      ? 'bg-primary-subtle text-primary'
      : 'bg-info-subtle text-info';
    const typeLabel = t._type === 'sale' ? 'Product Sale' : 'Service';

    const tr = document.createElement('tr');
    tr.dataset.id = t._id;
    tr.innerHTML = `
      <td>${fmtDate(t.date)}</td>
      <td class="fw-semibold">${t.client ?? '—'}</td>
      <td><span class="badge ${typeClass}">${typeLabel}</span></td>
      <td>${t.name ?? '—'}</td>
      <td class="fw-semibold">${fmt(t._amount)}</td>
      <td>
        <span class="badge ${statusClass} status-badge"
              style="cursor:pointer;user-select:none"
              data-id="${t._id}"
              title="Click to toggle status">
          ${statusLabel}
        </span>
      </td>
      <td class="text-end">
        <div class="d-flex justify-content-end gap-2">
          <button class="btn btn-sm btn-outline-secondary" onclick="viewInvoice(${t._saleId})">Invoice</button>
          <button class="btn btn-sm btn-outline-danger"   onclick="deleteSale(${t._saleId})">🗑️</button>
        </div>
      </td>`;
    tbody.appendChild(tr);
  });

  // Bind status toggles
  document.querySelectorAll('.status-badge').forEach(badge =>
    badge.addEventListener('click', () => toggleStatus(badge.dataset.id))
  );
}

//  STATUS TOGGLE 
async function toggleStatus(id) {
  const tx = allTransactions.find(t => t._id === id);
  if (!tx) return;
  if (tx._status === 'completed') {
        showToast('This sale is already finalized.', 'info');
        return;
    }
    if (!confirm("Are you sure you want to complete this sale? This action cannot be undone.")) {
        return;
    }
try {
        const response = await fetch(`../../api/sales.php?action=update_status&id=${tx._saleId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ payment_status: 'Paid' }), 
        });

        const result = await response.json();
        if (result.success) {
            allTransactions
                .filter(t => t._saleId === tx._saleId)
                .forEach(t => (t._status = 'completed'));
            
            renderTable();
            updateStatusKPIs();
            updateRevenueChart();

            await loadStats();
            await updateChurnKPI();

            showToast("Sale completed", "success");
        }
    } catch (e) {
        showToast("Failed to update status", "error");
    }
}

//  DELETE 
async function deleteSale(saleId) {
  if (!confirm('Delete this sale and all its items? This cannot be undone.')) return;

  try {
    const res = await fetch(`../../api/sales.php?action=delete&id=${saleId}`, {
      method: 'POST',
    }).then(r => r.json());

    if (res.success) {
      allTransactions = allTransactions.filter(t => t._saleId !== saleId);
      renderTable();
      updateStatusKPIs();
      updateRevenueChart();
      loadStats(); // refresh monthly revenue KPI
    } else {
      alert('Could not delete: ' + (res.error || 'Unknown error'));
    }
  } catch (e) {
    alert('Network error while deleting.');
  }
}

//  INVOICE 
function viewInvoice(saleId) {
  window.open(`../invoices/invoice.php?sale_id=${saleId}`, '_blank');
}

//  FILTERS 
function bindFilters() {
  const typeSelect  = document.querySelector('.card-header select');
  const searchInput = document.querySelector('.card-header input');

  typeSelect?.addEventListener('change', e => {
    const v = e.target.value;
    if      (v === 'Service') filterType = 'service';
    else if (v === 'sale')    filterType = 'sale';
    else                      filterType = 'all';
    renderTable();
  });

  searchInput?.addEventListener('input', e => {
    searchQuery = e.target.value.trim();
    renderTable();
  });
}

//  REVENUE CHART 
function initRevenueChart() {
  if (typeof Chart === 'undefined') {
    console.warn('Chart.js not loaded — add it to your HTML.');
    return;
  }

  const container = document.getElementById('chart-container');
  if (!container) return;

  const canvas = document.createElement('canvas');
  canvas.id = 'revenueChart';
  
  container.innerHTML = '';
  container.classList.remove('bg-light', 'p-5');
  container.appendChild(canvas);

  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

  revenueChart = new Chart(canvas.getContext('2d'), {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Completed',
          data: new Array(12).fill(0),
          backgroundColor: '#388087cc',
          borderRadius: 6,
        },
        {
          label: 'Pending',
          data: new Array(12).fill(0),
          backgroundColor: '#102E4A55',
          borderRadius: 6,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        tooltip: {
          callbacks: { label: ctx => ` ${fmt(ctx.raw)}` },
        },
      },
      scales: {
        x: { grid: { display: false } },
        y: {
          beginAtZero: true,
          ticks: {
            callback: v => '$' + (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v),
          },
        },
      },
    },
  });
}

function updateRevenueChart() {
  if (!revenueChart) return;

  const completed = new Array(12).fill(0);
  const pending   = new Array(12).fill(0);
  const year      = new Date().getFullYear();

  allTransactions.forEach(t => {
    if (!t.date) return;
    const d = new Date(t.date);
    if (d.getFullYear() !== year) return;
    const m = d.getMonth();
    if (t._status === 'completed') completed[m] += parseFloat(t._amount || 0);
    else                           pending[m]   += parseFloat(t._amount || 0);
  });

  revenueChart.data.datasets[0].data = completed;
  revenueChart.data.datasets[1].data = pending;
  revenueChart.update();
}