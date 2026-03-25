document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('financeChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue (Dt)',
                    data: [1200, 1900, 1500, 2500, 2200, 3000],
                    borderColor: '#388087',
                    backgroundColor: 'rgba(56, 128, 135, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Expenses (Dt)',
                    data: [800, 1200, 900, 1500, 1100, 1400],
                    borderColor: '#102E4A',
                    backgroundColor: 'rgba(16, 46, 74, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    const transactionForm = document.getElementById('transaction-form');
    const transactionTableBody = document.getElementById('transaction-list');

    if (transactionForm) {
        transactionForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('add_transaction.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) 
        .then(result => {
            if (result.status === 'success') {
                addEntryToTable(result.data);
                this.reset(); 
            } else {
                alert("Erreur : " + result.message);
            }
        })
        .catch(error => {
            console.error("Erreur lors de l'envoi :", error);
        });
    });

    function addEntryToTable(data) {
        const newRow = document.createElement('tr');
        newRow.style.animation = "fadeIn 0.5s ease-in-out"; 
        
        const isSale = data.type.toLowerCase() === 'sale';
        const badgeClass = isSale ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
        const typeLabel = isSale ? 'Sale' : 'Expense';

        newRow.innerHTML = `
            <td class="px-4">${data.date}</td>
            <td><span class="badge ${badgeClass} px-3">${typeLabel}</span></td>
            <td class="fw-bold">${data.amount} Dt</td>
            <td>${data.entity}</td>
            <td class="text-muted small italic">${data.notes}</td>
        `;

        if (transactionTableBody) {
            transactionTableBody.prepend(newRow);
        }
    }

    const filterEntity = document.getElementById('filter-entity');
    const filterType = document.getElementById('filter-type');
    const filterDateStart = document.getElementById('filter-date-start');
    const filterDateEnd = document.getElementById('filter-date-end');
    const btnReset = document.getElementById('reset-filters');

    function applyFilters() {
        const rows = document.querySelectorAll('#transaction-list tr');
        
        const valEntity = filterEntity.value.toLowerCase();
        const valType = filterType.value;
        const valStart = filterDateStart.value ? new Date(filterDateStart.value) : null;
        const valEnd = filterDateEnd.value ? new Date(filterDateEnd.value) : null;

        rows.forEach(row => {
            const textDate = row.cells[0].innerText;
            const rowDate = new Date(textDate);
            const textType = row.cells[1].innerText.trim();
            const textEntity = row.cells[3].innerText.toLowerCase();

            const matchEntity = textEntity.includes(valEntity);
            const matchType = (valType === 'all' || textType === valType);
            const matchDate = (!valStart || rowDate >= valStart) && (!valEnd || rowDate <= valEnd);

            if (matchEntity && matchType && matchDate) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    [filterEntity, filterType, filterDateStart, filterDateEnd].forEach(el => {
        el.addEventListener('input', applyFilters);
    });

    btnReset.addEventListener('click', () => {
        filterEntity.value = "";
        filterType.value = "all";
        filterDateStart.value = "";
        filterDateEnd.value = "";
        applyFilters();
    });

    }
});