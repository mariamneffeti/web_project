document.addEventListener('DOMContentLoaded', () => {
    async function loadKPIs() {
        try {
            const res = await fetch('get_kpis.php');
            const result = await res.json();
            if (result.status !== 'success') {
                console.error(result.message);
                return;
            }
            const data = result.data;
            document.getElementById('kpi-revenue').innerText =
                parseFloat(data.monthlyRevenue || 0).toFixed(2) + " Dt";
            document.getElementById('kpi-clients').innerText =
                data.activeClients;
            document.getElementById('kpi-conversion').innerText =
                data.conversionRate + "%";
        } catch (err) {
            console.error("KPI error:", err);
        }
    }
    loadKPIs();



    let salesChart;
    async function loadChart(year) {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        try {
            const res = await fetch(`get_sales_data.php?year=${year}`);
            const result = await res.json();

            if (result.status !== 'success') {
                console.error(result.message);
                return;
            }

            const rawData = result.data;

            const labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const monthlyTotals = new Array(12).fill(0);

            rawData.forEach(row => {
                const monthIndex = row.month - 1;
                monthlyTotals[monthIndex] = parseFloat(row.total_sales); 
            });

            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Revenue (${year})`,
                        data: monthlyTotals,
                        backgroundColor: 'rgba(16, 46, 74, 0.8)',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false } 
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        } catch (err) {
            console.error("Chart error:", err);
        }
    }

    const yearFilter = document.getElementById('yearFilter');
    loadChart(yearFilter.value);
    yearFilter.addEventListener('change', () => {
        loadChart(yearFilter.value);
    });

    function refreshDashboard() {
        loadKPIs();
        loadChart(document.getElementById('yearFilter').value);
    }

    const productLines = document.getElementById('product-lines');

    if (productLines) {
        productLines.addEventListener('click', (e) => {
            const addBtn = e.target.closest('.add-line');

            if (addBtn) {
                const firstRow = document.querySelector('.product-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelector('.product-search').value = "";
                newRow.querySelector('.product-id').value = "";
                newRow.querySelector('.quantity-input').value = "";
                newRow.querySelector('.price-input').value = "";
                newRow.querySelector('.product-results').innerHTML = "";
                newRow.dataset.price = 0;

                const btn = newRow.querySelector('.add-line');
                btn.classList.replace('btn-outline-primary', 'btn-outline-danger');
                btn.classList.replace('add-line', 'remove-line');
                btn.innerHTML = '<i class="bi bi-trash"></i>';

                productLines.appendChild(newRow);
            }

            const removeBtn = e.target.closest('.remove-line');
            if (removeBtn) {
                removeBtn.closest('.product-row').remove();
                calculateTotal();
            }
        });

        productLines.addEventListener('input', (e) => {
            const row = e.target.closest('.product-row');
            if (!row) return;

            if (e.target.classList.contains('quantity-input')) {
                updateRowTotal(row);
            }
        });
    }

    function updateRowTotal(row) {
        const quantityInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.price-input');

        const basePrice = parseFloat(row.dataset.price) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;

        const total = basePrice * quantity;

        priceInput.value = total.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;

        document.querySelectorAll('.price-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const discountRate = parseFloat(document.getElementById('discountRange').value) || 0;
        total = total * (1 - discountRate / 100);

        document.getElementById('form-total').innerText = total.toFixed(2);
    }

    const discountRange = document.getElementById('discountRange');
    const discountValue = document.getElementById('discountValue');

    discountRange.addEventListener('input', () => {
        discountValue.innerText = discountRange.value + "%";
        calculateTotal();
    });

    document.addEventListener('input', async (e) => {
        if (!e.target.classList.contains('product-search')) return;

        const query = e.target.value.trim();
        const row = e.target.closest('.product-row');
        const resultsBox = row.querySelector('.product-results');

        try {
            const res = await fetch(`search_products.php?q=${encodeURIComponent(query)}`);
            const products = await res.json();

            resultsBox.innerHTML = "";

            products.forEach(p => {
                const item = createProductItem(p, row, resultsBox);
                resultsBox.appendChild(item);
            });

        } catch (err) {
            console.error("Autocomplete error:", err);
        }
    });

    document.addEventListener('focusin', async (e) => {
        if (!e.target.classList.contains('product-search')) return;

        const row = e.target.closest('.product-row');
        const resultsBox = row.querySelector('.product-results');

        try {
            const res = await fetch(`search_products.php?q=`);
            const products = await res.json();

            resultsBox.innerHTML = "";

            products.forEach(p => {
                const item = createProductItem(p, row, resultsBox);
                resultsBox.appendChild(item);
            });

        } catch (err) {
            console.error(err);
        }
    });

    document.addEventListener('input', async (e) => {
        if (!e.target.classList.contains('client-search')) return;

        const query = e.target.value.trim();
        const container = e.target.closest('.position-relative');
        const resultsBox = container.querySelector('.client-results');

        try {
            const res = await fetch(`search_clients.php?q=${encodeURIComponent(query)}`);
            const clients = await res.json();

            resultsBox.innerHTML = "";

            clients.forEach(c => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = c.client_name;

                item.addEventListener('click', () => {
                    container.querySelector('.client-search').value = c.client_name;
                    container.querySelector('.client-id').value = c.id;

                    resultsBox.innerHTML = "";
                });

                resultsBox.appendChild(item);
            });

        } catch (err) {
            console.error("Client search error:", err);
        }
    });

    document.addEventListener('focusin', async (e) => {
        if (!e.target.classList.contains('client-search')) return;

        const container = e.target.closest('.position-relative');
        const resultsBox = container.querySelector('.client-results');

        try {
            const res = await fetch(`search_clients.php?q=`);
            const clients = await res.json();

            resultsBox.innerHTML = "";

            clients.forEach(c => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = c.client_name;

                item.addEventListener('click', () => {
                    container.querySelector('.client-search').value = c.client_name;
                    container.querySelector('.client-id').value = c.id;

                    resultsBox.innerHTML = "";
                });

                resultsBox.appendChild(item);
            });

        } catch (err) {
            console.error(err);
        }
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll('.client-results').forEach(box => {
            if (!box.contains(e.target) && !e.target.classList.contains('client-search')) {
                box.innerHTML = "";
            }
        });
    });

    function createProductItem(p, row, resultsBox) {
        const item = document.createElement('button');
        item.type = "button";
        item.className = "list-group-item list-group-item-action";
        item.textContent = `${p.product_name}`;

        item.addEventListener('click', () => {
            row.querySelector('.product-search').value = p.product_name;
            row.querySelector('.product-id').value = p.id;

            row.dataset.price = p.price;

            const qtyInput = row.querySelector('.quantity-input');
            qtyInput.value = 1;

            resultsBox.innerHTML = "";
            updateRowTotal(row);
        });

        return item;
    }

    document.addEventListener('click', (e) => {
        document.querySelectorAll('.product-results').forEach(box => {
            if (!box.contains(e.target) && !e.target.classList.contains('product-search')) {
                box.innerHTML = "";
            }
        });
    });

    const salesForm = document.getElementById('sales-form');

    if (salesForm) {
        salesForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const rows = document.querySelectorAll('.product-row');

            let valid = false;

            rows.forEach(row => {
                const product = row.querySelector('.product-id').value;
                const qty = row.querySelector('.quantity-input').value;

                if (product && qty > 0) {
                    valid = true;
                }
            });

            if (!valid) {
                alert("Select at least one product with quantity.");
                return;
            }

            if (!confirm("Confirm adding this sale?")) {
                return;
            }
            const formData = new FormData(this);

            fetch('add_sale.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
           .then(result => {
                if (result.status === 'success') {
                    location.reload();
                    refreshDashboard();
                } else {
                    alert(result.message);
                }
            })
            .catch(err => console.error(err));
        });
    }

    document.addEventListener('click', (e) => { 
        if (e.target.classList.contains('status-badge') && e.target.dataset.status === 'Pending') { 
            const saleId = e.target.dataset.id; 
            const badge = e.target; 

            if (confirm("Mark this sale as paid?")) { 
                fetch('update_status.php', { 
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, 
                    body: `id=${encodeURIComponent(saleId)}&status=Paid` 
                }) 
                .then(res => res.json()) 
                .then(result => { 
                    if (result.status === 'success') { 
                        badge.innerText = 'Paid'; 
                        badge.classList.replace('bg-warning-subtle', 'bg-success-subtle'); 
                        badge.classList.replace('text-warning', 'text-success'); 
                        badge.dataset.status = 'Paid'; 
                        badge.style.cursor = 'default'; 
                        refreshDashboard();
                    } else { 
                        alert("Error updating status."); 
                    } 
                }) .catch(err => console.error("Status error:", err)); 
            } 
        } 
    });

    document.addEventListener('focusin', async (e) => {
        if (!e.target.classList.contains('explorer-search')) return;

        const container = e.target.closest('.position-relative');
        const resultsBox = container.querySelector('.explorer-results');

        const res = await fetch(`search_clients.php?q=`);
        const clients = await res.json();

        resultsBox.innerHTML = "";

        clients.forEach(c => {
            const item = createExplorerItem(c, e.target, resultsBox);
            resultsBox.appendChild(item);
        });
    });

    document.addEventListener('input', async (e) => {
        if (!e.target.classList.contains('explorer-search')) return;

        const query = e.target.value.trim();
        const container = e.target.closest('.position-relative');
        const resultsBox = container.querySelector('.explorer-results');

        const res = await fetch(`search_clients.php?q=${encodeURIComponent(query)}`);
        const clients = await res.json();

        resultsBox.innerHTML = "";

        clients.forEach(c => {
            const item = createExplorerItem(c, e.target, resultsBox);
            resultsBox.appendChild(item);
        });
    });

    function createExplorerItem(c, input, resultsBox) {
        const item = document.createElement('button');
        item.type = "button";
        item.className = "list-group-item list-group-item-action";
        item.textContent = c.client_name;

        item.addEventListener('click', () => {
            input.value = c.client_name;
            document.getElementById('explorer-client-id').value = c.id;

            resultsBox.innerHTML = "";
            loadClientDetails(c.client_name);
        });

        return item;
    }

    function loadClientDetails(clientName) {
        fetch(`get_client.php?name=${encodeURIComponent(clientName)}`)
            .then(res => res.json())
            .then(result => {
                if (result.status === 'success') {
                    const client = result.data;

                    document.getElementById('explorer-email').value = client.email || 'No email';
                    document.getElementById('explorer-type').value = client.client_type;
                    document.getElementById('explorer-last-date').innerText =
                        client.last_purchase_date || '--/--/--';

                    document.getElementById('explorer-total-spent').innerText =
                        parseFloat(client.total_spent || 0).toFixed(2);
                } else {
                    alert("Client not found.");
                }
            })
            .catch(err => console.error("Explorer error:", err));
    }

    document.addEventListener('click', (e) => {
        document.querySelectorAll('.explorer-results').forEach(box => {
            if (!box.contains(e.target) && !e.target.classList.contains('explorer-search')) {
                box.innerHTML = "";
            }
        });
    });

});