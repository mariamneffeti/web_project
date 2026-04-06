document.addEventListener("DOMContentLoaded", () => {
    // KPI handling
    async function loadFinanceKPIs() {
        try {
          const res = await fetch("get_finance_kpis.php");
          const result = await res.json();

          if (result.status !== "success") return;

          const data = result.data;

          document.querySelector("#revenue-kpi").innerText =
            data.revenue.toFixed(2) + " Dt";

          document.querySelector("#expenses-kpi").innerText =
            data.expenses.toFixed(2) + " Dt";

          document.querySelector("#net-profit-kpi").innerText =
            data.profit.toFixed(2) + " Dt";

          document.querySelector("#salecnt-kpi").innerText =
            data.salesCount;

        } catch (err) {
          console.error("KPI error:", err);
        }
    }

    // Chart handling
    let financeChart;

    async function loadFinanceChart(year) {
      const ctx = document.getElementById("financeChart");
      if (!ctx) return;

      try {
        const res = await fetch(`get_finance_chart.php?year=${year}`);
        const result = await res.json();

        if (result.status !== "success") return;

        const labels = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const revenueData = new Array(12).fill(0);
        const expenseData = new Array(12).fill(0);

        result.data.revenues.forEach(r => {
          revenueData[r.month - 1] = parseFloat(r.total);
        });

        result.data.expenses.forEach(e => {
          expenseData[e.month - 1] = parseFloat(e.total);
        });

        if (financeChart) financeChart.destroy();

        financeChart = new Chart(ctx, {
          type: "line",
          data: {
            labels,
            datasets: [
              {
                label: "Revenue (Dt)",
                data: revenueData,
                borderColor: "#388087",
                backgroundColor: "rgba(56, 128, 135, 0.1)",
                fill: true,
                tension: 0.4,
              },
              {
                label: "Expenses (Dt)",
                data: expenseData,
                borderColor: "#102E4A",
                backgroundColor: "rgba(16, 46, 74, 0.1)",
                fill: true,
                tension: 0.4,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: "bottom" },
            },
          },
        });

      } catch (err) {
        console.error("Chart error:", err);
      }
    }
  const yearFilter = document.getElementById('yearFilter');
  loadFinanceKPIs();
  loadFinanceChart(yearFilter.value);
  yearFilter.addEventListener('change', () => {
      loadFinanceChart(yearFilter.value);
  });

  // Transaction form handling
  const transactionForm = document.getElementById("transaction-form");

  if (transactionForm) {
    transactionForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      if (!formData.get("type") || !formData.get("amount") || !formData.get("date")) {
        alert("Please fill in all required fields.");
        return;
      }

      if (isNaN(formData.get("amount")) || parseFloat(formData.get("amount")) <= 0) {
        alert("Please enter a valid positive amount.");
        return;
      }

      if (!confirm("Are you sure you want to add this transaction?")) {
        return;
      }
      
      fetch('add_transaction.php', {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.status === "success") {
            location.reload();
          } else {
            alert("Error : " + result.message);
          }
        })
        .catch((error) => {
          console.error("Error during submission :", error);
        });
    });

    // Filter and pagination handling
    const filterType = document.getElementById("filter-type");
    const filterDateStart = document.getElementById("filter-date-start");
    const filterDateEnd = document.getElementById("filter-date-end");
    const btnReset = document.getElementById("reset-filters");

    const showMoreBtn = document.getElementById("show-more-expenses");
    const tableBody = document.getElementById("transaction-list");

    const allRows = Array.from(tableBody.querySelectorAll("tr"));

    const increment = 5;
    let itemsToShow = increment;
    let filteredRows = [];

    function updateDisplay() {
      const valType = filterType.value;
      const valStart = filterDateStart.value ? new Date(filterDateStart.value) : null;
      const valEnd = filterDateEnd.value ? new Date(filterDateEnd.value) : null;

      filteredRows = allRows.filter((row) => {
        const textDate = row.cells[0].innerText;
        const rowDate = new Date(textDate);
        const textType = row.cells[1].innerText.trim();

        const matchType = valType === "all" || textType === valType;
        const matchDate =
          (!valStart || rowDate >= valStart) &&
          (!valEnd || rowDate <= valEnd);

        return matchType && matchDate;
      });

      allRows.forEach((row) => (row.style.display = "none"));

      const visibleRows = filteredRows.slice(0, itemsToShow);
      visibleRows.forEach((row) => (row.style.display = ""));

      if (showMoreBtn) {
        if (filteredRows.length <= increment) {
          showMoreBtn.style.display = "none";
        } else if (itemsToShow >= filteredRows.length) {
          showMoreBtn.style.display = "inline-block";
          showMoreBtn.innerHTML =
            '<i class="bi bi-dash-circle me-2"></i>Show Less';
          showMoreBtn.classList.replace(
            "btn-outline-primary",
            "btn-outline-secondary"
          );
        } else {
          showMoreBtn.style.display = "inline-block";
          showMoreBtn.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Show More (${filteredRows.length - itemsToShow})`;
          showMoreBtn.classList.replace(
            "btn-outline-secondary",
            "btn-outline-primary"
          );
        }
      }
    }

    [filterType, filterDateStart, filterDateEnd].forEach((el) => {
      el.addEventListener("input", () => {
        itemsToShow = increment; 
        updateDisplay();
      });
    });

    btnReset.addEventListener("click", () => {
      filterType.value = "all";
      filterDateStart.value = "";
      filterDateEnd.value = "";

      itemsToShow = increment;
      updateDisplay();
    });

    if (showMoreBtn) {
      showMoreBtn.addEventListener("click", () => {
        if (itemsToShow >= filteredRows.length) {
          itemsToShow = increment;
        } else {
          itemsToShow += increment;
        }
        updateDisplay();
      });
    }

    updateDisplay();
  }
});
