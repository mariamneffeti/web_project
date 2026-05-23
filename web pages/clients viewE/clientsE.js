let allclients=[];
let searchQuery     = '';
document.addEventListener("DOMContentLoaded",() =>{
    loadclients();
    updateChurnKPI();
    Monthly_load();
});
async function sessionFetch(url, options = {}) {
    const response = await fetch(url, options);
    if (response.status === 401) {
        alert("Your session has expired. Please log in again.");
        window.location.href = "../../web pages/login/login.php";
        throw new Error("Unauthenticated");
    }
    return response;
}
async function loadclients(){
    
    const tbody = document.querySelector("#client-table-body")
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Loading…</td></tr>`;
    try{
        const response = await sessionFetch("../../api/clients.php?action=list");
        const result = await response.json();
        if (!response.ok) throw new Error("Network response was not ok");
        if (result.success){
            allclients = result.data;
            renderClientRows(allclients);
        tbody.innerHTML = "";
        const totalBadge = document.querySelector("#total-clients-count");
        if (totalBadge) {
            totalBadge.textContent = allclients.length;
        }
        if (allclients.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center">No clients found.</td></tr>`;
            return;
        }
        allclients.forEach(client => {
            const score = client.engagement_score || 0;
            let barColor = "bg-success";
            if (score < 30) barColor = "bg-danger";
            else if (score < 70) barColor = "bg-warning";
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${client.client_name}</td>
                <td>${client.email}</td>
                <td id="churn-container-${client.id}">
                    <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
                </td>
                <td>${client.phone || 'N/A'}</td>
                <td></td>
                <td class="text-end">
                <div class="d-inline-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="detailClient(${client.id})">Details</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteClient(${client.id})">🗑️</button>
                    <button class="btn btn-sm btn-outline-warning" onclick="editClient(${client.id})">Edit</button>
                </div>
                </td>
            `;
            tbody.appendChild(row);
            fetchChurnScore(client.id);})
            
        };
        } catch (error) {
        console.error("Fetch error:", error);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Failed to load clients.</td></tr>`;
    }

    }
async function fetchChurnScore(id) {
    try {
        const res = await sessionFetch(`../../api/clients.php?action=churn&id=${id}`);
        const result = await res.json();
        
        const client = allclients.find(c => c.id == id);
        if (client) {
            client.isAtRisk = (result.churn === true || result.risk_score > 0.7);
            client.riskPercent = (result.risk_score * 100).toFixed(0) + '%';
        }

        const globalAtRiskCount = allclients.filter(c => c.isAtRisk).length;
        
        const churnBadge = document.querySelector("#stat-churn-risk");
        if (churnBadge) {
            churnBadge.textContent = globalAtRiskCount;
        }

        const container = document.querySelector(`#churn-container-${id}`);
        if (!container) return; // Exit if row was filtered out during search

        const probability = (result.risk_score * 100).toFixed(0); 
        let color = "bg-success";
        if (probability > 70) color = "bg-danger";
        else if (probability > 30) color = "bg-warning";

        container.innerHTML = `
            <div class="progress" style="height: 10px;">
                <div class="progress-bar ${color}" style="width: ${probability}%"></div>
            </div>
            <small>${probability}% Risk</small>
        `;
    } catch (e) {
        const container = document.querySelector(`#churn-container-${id}`);
        if (container) container.innerHTML = "N/A";
    }
}
async function updateChurnKPI(){
    const churnBadge = document.querySelector("#stat-churn-risk");
    try{
        const res = await sessionFetch(`../../api/clients.php?action=bulk_churn`);
        if (!res.ok) {
            const errorText = await res.text();
            console.error("Server Error Output:", errorText);
            throw new Error("Network response was not ok");
        }
        const result = await res.json();
        if (result.success){
            if (churnBadge){
                churnBadge.textContent = result.at_risk_count;
            }
        }
    }
    catch(error){
        console.error('Error loading client stats:', error);
    }
}
async function Monthly_load() {
    const monthly = document.querySelector("#stat-month-amount");
    try{
        res = await sessionFetch(`../../api/sales.php?action=stats`);
        response = await res.json();
        if (!res.ok) {
                console.error("Server error status:", res.status);
                return;
        }
        if(response.success){
            if (monthly){
                monthly.textContent = response.data.this_month.total;
            }
        }
    }
    catch (error) {
        console.error("Network or Parsing error:", error);
        if (monthly) monthly.textContent = "$0";
    }
}

function handleSearch() {
    const searchInput = document.querySelector('input[placeholder*="Search"]');
    if (!searchInput) return;
    
    const query = searchInput.value.toLowerCase().trim();

    const filteredResults = allclients.filter(client => {
        const name = (client.client_name || "").toLowerCase();
        const email = (client.email || "").toLowerCase();
        const phone = (client.phone || "").toLowerCase();
        
        return name.includes(query) || 
               email.includes(query) || 
               phone.includes(query);
    });

    renderClientRows(filteredResults);
}

function renderClientRows(data) {
    const tbody = document.querySelector("#client-table-body");
    const churnBadge = document.querySelector("#stat-churn-risk");
    if (!tbody) return;
    let currentBatchRiskCount = 0;

    tbody.innerHTML = "";

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">No clients found matching that search.</td></tr>`;
        return;
    }

    data.forEach(client => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${client.client_name}</td>
            <td>${client.email}</td>
            <td id="churn-container-${client.id}">
                <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
            </td>
            <td>${client.phone || 'N/A'}</td>
            <td></td>
            <td class="text-end">
            <div class="d-inline-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="detailClient(${client.id})">Details</button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteClient(${client.id})">🗑️</button>
                <button class="btn btn-sm btn-outline-warning" onclick="editClient(${client.id})">Edit</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
        
        fetchChurnScore(client.id);
    });
}
async function ExportToCSV(){
    if (!allclients || allclients.length==0){
        alert("there are no clients to export!");
        return;
    }
    const headers = ["Name","Email","Churn Risk Percentage","Numero telephone"];
    const rows = allclients.map(client =>[
        `"${client.client_name || ''}"`, 
        `"${client.email || ''}"`,
        `"${client.riskPercent}"`,
        `"${client.phone || 'N/A'}"`
    ])
    const csvContent = [
        headers.join(","), 
        ...rows.map(row => row.join(","))
    ].join("\n");
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    
    link.setAttribute("href", url);
    link.setAttribute("download", `client_portfolio_${new Date().toISOString().slice(0,10)}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
function ExportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(18);
    doc.text("Client Portfolio Report", 14, 22);
    doc.setFontSize(11);
    doc.setTextColor(100);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 30);

    const tableColumn = ["Name", "Email", "Churn Risk", "Phone"];
    const tableRows = allclients.map(client => [
        client.client_name || '',
        client.email || '',
        client.riskPercent || 'N/A',
        client.phone || 'N/A'
    ]);
    doc.autoTable({
        head: [tableColumn],
        body: tableRows,
        startY: 35,
        theme: 'striped',
        headStyles: { fillColor: [44, 62, 80] }, 
        styles: { fontSize: 9 },
        columnStyles: {
            2: { fontStyle: 'bold' } 
        }
    });
    doc.save(`Client_Report_${new Date().toISOString().slice(0,10)}.pdf`);
}
async function Copy() {
    if (!allclients || allclients.length === 0) {
        alert("No data available to copy.");
        return;
    }

    const headers = ["Name", "Email", "Churn Risk", "Phone"];
    
    const rows = allclients.map(client => [
        client.client_name || '',
        client.email || '',
        client.riskPercent || 'Pending',
        client.phone || 'N/A'
    ].join("\t"));

    const content = [headers.join("\t"), ...rows].join("\n");

    try {
        await navigator.clipboard.writeText(content);
        
        alert("Client data copied to clipboard! You can now paste it into Excel.");
    } catch (err) {
        console.error("Failed to copy: ", err);
        alert("Failed to copy data. Please try again.");
    }
}
function ExportToExcel() {
    if (!allclients || allclients.length === 0) {
        alert("No data available to export.");
        return;
    }

    const excelData = allclients.map(client => ({
        "Client Name": client.client_name || '',
        "Email Address": client.email || '',
        "Churn Risk (%)": client.riskPercent || 'Pending',
        "Phone Number": client.phone || 'N/A'
    }));

    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(workbook, worksheet, "Clients");

    const wscols = [
        { wch: 30 }, 
        { wch: 30 }, 
        { wch: 15 }, 
        { wch: 20 }
    ];
    worksheet['!cols'] = wscols;

    XLSX.writeFile(workbook, `Client_Portfolio_${new Date().toISOString().slice(0,10)}.xlsx`);
}
async function deleteClient(id) {
    if (!confirm("Are you sure you want to delete this client?")) return;
    
    const clientToDelete = allclients.find(c => c.id == id);
    const clientName = clientToDelete ? clientToDelete.client_name : "Client";

    try {
        const response = await sessionFetch(`../../api/clients.php?action=delete&id=${id}`, {
            method: 'POST' 
        });
        
        const result = await response.json();

        if (response.ok && result.success) {
            allclients = allclients.filter(c => c.id != id);
            renderClientRows(allclients);
            
            const toastElement = document.getElementById('deleteToast');
            const toastBody = toastElement.querySelector('.toast-body');
            toastBody.innerHTML = ` <strong>${clientName}</strong> deleted successfully!`;
            
            const toast = new bootstrap.Toast(toastElement);
            toast.show(); 
            
            const totalBadge = document.querySelector("#total-clients-count");
            if (totalBadge) totalBadge.textContent = allclients.length;
        } else {
            alert("Error: " + (result.error || "Check sales history"));
        }
    } catch (error) {
        console.error("Delete failed:", error);
    }
}
function editClient(id) {
    const client = allclients.find(c => c.id == id);
    if (!client) return;

    document.querySelector("#edit-id-input").value = client.id;
    document.querySelector("#edit-name-input").value = client.client_name || '';
    document.querySelector("#edit-email-input").value = client.email || '';
    document.querySelector("#edit-phone-input").value = client.phone || '';

    const editModal = new bootstrap.Modal(document.getElementById('editClientModal'));
    editModal.show();
}
async function saveClientEdit() {
    const id = document.querySelector("#edit-id-input").value;
    
    const updateData = {
        client_name: document.querySelector("#edit-name-input").value,
        email: document.querySelector("#edit-email-input").value,
        phone: document.querySelector("#edit-phone-input").value,
        address: "", 
        client_type: "B2C",
        status: "Active"
    };

    try {
        const response = await sessionFetch(`../../api/clients.php?action=update&id=${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify(updateData) 
        });

        const result = await response.json();

        if (result.success) {
            const idx = allclients.findIndex(c => c.id == id);
            if (idx !== -1) {
                allclients[idx] = { ...allclients[idx], ...updateData };
            }

            renderClientRows(allclients);
            
            const modalEl = document.getElementById('editClientModal');
            bootstrap.Modal.getInstance(modalEl).hide();

            const toastEl = document.getElementById('deleteToast');
            toastEl.querySelector('.toast-body').innerHTML = `✅ <strong>${updateData.client_name}</strong> updated!`;
            new bootstrap.Toast(toastEl).show();
            
        } else {
            alert("Error: " + (result.error || "Update failed"));
        }
    } catch (error) {
        console.error("Save Error:", error);
        alert("Failed to reach server.");
    }
}
function detailClient(id) {
    const client = allclients.find(c => c.id == id);
    if (!client) {
        console.error("Client not found for ID:", id);
        return;
    }

    document.getElementById('det-name').textContent = client.client_name;
    document.getElementById('det-email').textContent = client.email || 'N/A';
    document.getElementById('det-phone').textContent = client.phone || 'N/A';
    document.getElementById('det-type').textContent = client.client_type || 'B2C';
    document.getElementById('det-address').textContent = client.address || 'No address on file.';
    
    const riskText = client.riskPercent ? client.riskPercent : "Pending Calculation...";
    document.getElementById('det-risk').textContent = riskText;

    const initials = client.client_name.split(' ').map(n => n[0]).join('').toUpperCase();
    document.getElementById('det-initials').textContent = initials;

    const detailModal = new bootstrap.Modal(document.getElementById('detailClientModal'));
    detailModal.show();
}

function openAddModal() {
    document.getElementById("addClientForm").reset();
    const addModal = new bootstrap.Modal(document.getElementById('addClientModal'));
    addModal.show();
}

async function saveNewClient() {
    const name = document.querySelector("#add-name-input").value;
    const email = document.querySelector("#add-email-input").value;
    const phone = document.querySelector("#add-phone-input").value;

    if (!name || !email) {
        alert("Name and Email are required!");
        return;
    }

    const newClientData = {
        client_name: name,
        email: email,
        phone: phone,
        address: "", 
        client_type: "B2C", 
        status: "Active"
    };

    try {
        const response = await sessionFetch(`../../api/clients.php?action=create`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newClientData)
        });

        const result = await response.json();

        if (result.success) {
            loadclients(); 
            
            const modalEl = document.getElementById('addClientModal');
            bootstrap.Modal.getInstance(modalEl).hide();

            const toastEl = document.getElementById('deleteToast');
            toastEl.querySelector('.toast-body').innerHTML = `✅ <strong>${name}</strong> added successfully!`;
            new bootstrap.Toast(toastEl).show();
        } else {
            alert("Error: " + (result.error || "Failed to add client"));
        }
    } catch (error) {
        console.error("Save Error:", error);
        alert("Failed to reach server.");
    }
}