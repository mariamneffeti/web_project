let allClients = [];

document.addEventListener("DOMContentLoaded", () => {
    loadClients();
});

async function loadClients() {
    const tbody = document.querySelector("#client-table-body");
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Loading…</td></tr>`;

    try {
        const [clientsRes, statsRes] = await Promise.all([
            fetch("handle_clients.php?action=list"),
            fetch("handle_clients.php?action=stats")
        ]);

        const clientsResult = await clientsRes.json();
        const statsResult   = await statsRes.json();

        if (!clientsResult.success) throw new Error("Failed to load clients");

        allClients = clientsResult.data;

        renderClientRows(allClients);

        const totalBadge = document.querySelector("#total-clients-count");
        if (totalBadge) totalBadge.textContent = allClients.length;

        const monthlyBadge = document.querySelector("#stat-month-amount");
        if (monthlyBadge && statsResult.success) {
            monthlyBadge.textContent = parseFloat(statsResult.data.avg_revenue || 0).toFixed(2) + ' Dt';
        }

        allClients.forEach(client => fetchChurnScore(client.id));

    } catch (err) {
        console.error("Load error:", err);
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Failed to load clients.</td></tr>`;
    }
}

function renderClientRows(data) {
    const tbody = document.querySelector("#client-table-body");
    if (!tbody) return;

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">No clients found.</td></tr>`;
        return;
    }

    tbody.innerHTML = data.map(client => `
        <tr>
            <td class="fw-semibold">${client.client_name}</td>
            <td>${client.email || 'N/A'}</td>
            <td id="churn-container-${client.id}">
                <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
            </td>
            <td>${client.phone || 'N/A'}</td>
            <td></td>
            <td class="text-end">
                <div class="d-inline-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="detailClient(${client.id})">Details</button>
                    <button class="btn btn-sm btn-outline-danger"    onclick="deleteClient(${client.id})">🗑️</button>
                    <button class="btn btn-sm btn-outline-warning"   onclick="editClient(${client.id})">Edit</button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function fetchChurnScore(id) {
    try {
        const res    = await fetch(`handle_clients.php?action=churn&id=${id}`);
        const result = await res.json();

        const client = allClients.find(c => c.id == id);
        if (client) {
            client.riskScore   = result.risk_score ?? 0;
            client.riskPercent = Math.round((result.risk_score ?? 0) * 100) + '%';
            client.isAtRisk    = result.churn === true || result.risk_score > 0.7;
        }

        const churnBadge = document.querySelector("#stat-churn-risk");
        if (churnBadge) {
            churnBadge.textContent = allClients.filter(c => c.isAtRisk).length;
        }

        const container = document.querySelector(`#churn-container-${id}`);
        if (!container) return;

        if (result.error) {
            container.innerHTML = `<small class="text-muted">N/A</small>`;
            return;
        }

        const pct   = Math.round((result.risk_score ?? 0) * 100);
        const color = pct > 70 ? 'bg-danger' : pct > 30 ? 'bg-warning' : 'bg-success';
        container.innerHTML = `
            <div class="progress" style="height:10px;">
                <div class="progress-bar ${color}" style="width:${pct}%"></div>
            </div>
            <small>${pct}% Risk</small>
        `;
    } catch (e) {
        const container = document.querySelector(`#churn-container-${id}`);
        if (container) container.innerHTML = `<small class="text-muted">N/A</small>`;
    }
}

async function updateChurnBadge() {
    const churnBadge = document.querySelector("#stat-churn-risk");
    try {
        const res    = await fetch("handle_clients.php?action=bulk_churn");
        const result = await res.json();
        console.log("BULK CHURN:", result);
        if (churnBadge) {
            churnBadge.textContent = result.at_risk_count ?? '—';
        }
    } catch (e) {
        if (churnBadge) churnBadge.textContent = '—';
    }
}

function handleSearch() {
    const input = document.querySelector('input[placeholder*="Search"]');
    if (!input) return;
    const q = input.value.toLowerCase().trim();
    const filtered = allClients.filter(c =>
        (c.client_name || '').toLowerCase().includes(q) ||
        (c.email || '').toLowerCase().includes(q) ||
        (c.phone || '').toLowerCase().includes(q)
    );
    renderClientRows(filtered);
}

// ── CRUD ────────────────────────────────────────────────────────────────────

function openAddModal() {
    document.getElementById("addClientForm").reset();
    new bootstrap.Modal(document.getElementById('addClientModal')).show();
}

async function saveNewClient() {
    const name    = document.querySelector("#add-name-input").value.trim();
    const email   = document.querySelector("#add-email-input").value.trim();
    const phone   = document.querySelector("#add-phone-input").value.trim();
    const address = document.querySelector("#add-address-input").value.trim();
    const type    = document.querySelector("#add-type-input").value;
    const status  = document.querySelector("#add-status-input").value;

    if (!name || !email) { alert("Name and Email are required!"); return; }

    try {
        const res    = await fetch("handle_clients.php?action=create", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                client_name: name, email, phone, 
                address, client_type: type, status 
            })
        });
        const result = await res.json();
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('addClientModal')).hide();
            showToast(`<strong>${name}</strong> added successfully!`);
            loadClients();
        } else {
            alert("Error: " + (result.error || "Failed to add client"));
        }
    } catch (e) { console.error(e); alert("Failed to reach server."); }
}

function editClient(id) {
    const client = allClients.find(c => c.id == id);
    if (!client) return;
    document.querySelector("#edit-id-input").value      = client.id;
    document.querySelector("#edit-name-input").value    = client.client_name || '';
    document.querySelector("#edit-email-input").value   = client.email || '';
    document.querySelector("#edit-phone-input").value   = client.phone || '';
    document.querySelector("#edit-address-input").value = client.address || '';
    document.querySelector("#edit-type-input").value    = client.client_type || 'B2C';
    document.querySelector("#edit-status-input").value  = client.status || 'Active';
    new bootstrap.Modal(document.getElementById('editClientModal')).show();
}

async function saveClientEdit() {
    const id   = document.querySelector("#edit-id-input").value;
    const data = {
        client_name: document.querySelector("#edit-name-input").value,
        email:       document.querySelector("#edit-email-input").value,
        phone:       document.querySelector("#edit-phone-input").value,
        address:     document.querySelector("#edit-address-input").value,
        client_type: document.querySelector("#edit-type-input").value,
        status:      document.querySelector("#edit-status-input").value,
    };
    try {
        const res    = await fetch(`handle_clients.php?action=update&id=${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        if (result.success) {
            const idx = allClients.findIndex(c => c.id == id);
            if (idx !== -1) allClients[idx] = { ...allClients[idx], ...data };
            renderClientRows(allClients);
            bootstrap.Modal.getInstance(document.getElementById('editClientModal')).hide();
            showToast(`<strong>${data.client_name}</strong> updated!`);
        } else {
            alert("Error: " + (result.error || "Update failed"));
        }
    } catch (e) { console.error(e); alert("Failed to reach server."); }
}

function detailClient(id) {
    const client = allClients.find(c => c.id == id);
    if (!client) return;
    document.getElementById('det-name').textContent         = client.client_name;
    document.getElementById('det-email').textContent        = client.email || 'N/A';
    document.getElementById('det-phone').textContent        = client.phone || 'N/A';
    document.getElementById('det-type').textContent         = client.client_type || 'N/A';
    document.getElementById('det-status').textContent       = client.status || 'N/A';
    document.getElementById('det-spent').textContent        = parseFloat(client.total_spent || 0).toFixed(2) + ' Dt';
    document.getElementById('det-last-purchase').textContent = client.last_purchase_date || 'N/A';
    document.getElementById('det-address').textContent      = client.address || 'No address on file.';
    document.getElementById('det-risk').textContent         = client.riskPercent ?? 'Pending';
    document.getElementById('det-initials').textContent     =
        client.client_name.split(' ').map(n => n[0]).join('').toUpperCase();
    new bootstrap.Modal(document.getElementById('detailClientModal')).show();
}

async function deleteClient(id) {
    if (!confirm("Are you sure you want to delete this client?")) return;
    const client = allClients.find(c => c.id == id);
    try {
        const res    = await fetch(`handle_clients.php?action=delete&id=${id}`, { method: 'POST' });
        const result = await res.json();
        if (result.success) {
            allClients = allClients.filter(c => c.id != id);
            renderClientRows(allClients);
            document.querySelector("#total-clients-count").textContent = allClients.length;
            showToast(`<strong>${client?.client_name}</strong> deleted!`);
        } else {
            alert("Error: " + (result.error || "Delete failed"));
        }
    } catch (e) { console.error(e); }
}



function ExportToCSV() {
    if (!allClients.length) { alert("No clients to export!"); return; }
    const rows = [
        ["Name","Email","Churn Risk","Phone"],
        ...allClients.map(c => [
            `"${c.client_name||''}"`, `"${c.email||''}"`,
            `"${c.riskPercent||'Pending'}"`, `"${c.phone||'N/A'}"`
        ])
    ];
    const blob = new Blob([rows.map(r => r.join(",")).join("\n")], { type: 'text/csv;charset=utf-8;' });
    const a = Object.assign(document.createElement("a"), {
        href: URL.createObjectURL(blob),
        download: `clients_${new Date().toISOString().slice(0,10)}.csv`
    });
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
}

function ExportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(18); doc.text("Client Portfolio Report", 14, 22);
    doc.setFontSize(11); doc.setTextColor(100);
    doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 30);
    doc.autoTable({
        head: [["Name","Email","Churn Risk","Phone"]],
        body: allClients.map(c => [c.client_name||'', c.email||'', c.riskPercent||'Pending', c.phone||'N/A']),
        startY: 35, theme: 'striped',
        headStyles: { fillColor: [16, 46, 74] },
        styles: { fontSize: 9 }
    });
    doc.save(`Client_Report_${new Date().toISOString().slice(0,10)}.pdf`);
}

async function Copy() {
    if (!allClients.length) { alert("No data to copy."); return; }
    const rows = [
        ["Name","Email","Churn Risk","Phone"].join("\t"),
        ...allClients.map(c => [c.client_name||'', c.email||'', c.riskPercent||'Pending', c.phone||'N/A'].join("\t"))
    ];
    await navigator.clipboard.writeText(rows.join("\n"));
    alert("Copied to clipboard!");
}

function ExportToExcel() {
    if (!allClients.length) { alert("No data to export."); return; }
    const ws = XLSX.utils.json_to_sheet(allClients.map(c => ({
        "Client Name": c.client_name||'', "Email": c.email||'',
        "Churn Risk": c.riskPercent||'Pending', "Phone": c.phone||'N/A'
    })));
    ws['!cols'] = [{wch:30},{wch:30},{wch:15},{wch:20}];
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Clients");
    XLSX.writeFile(wb, `Clients_${new Date().toISOString().slice(0,10)}.xlsx`);
}


function showToast(html) {
    const el = document.getElementById('deleteToast');
    if (!el) return;
    el.querySelector('.toast-body').innerHTML = html;
    new bootstrap.Toast(el).show();
}