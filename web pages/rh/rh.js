
    document.addEventListener('DOMContentLoaded', () => {
    const employeeForm = document.getElementById('employeeForm');
    const employeeTableBody = document.getElementById('employeeTableBody');
    const searchInput = document.getElementById('searchInput');

    employeeForm.onsubmit = async function(e) {
        e.preventDefault();
        const formData = new FormData(employeeForm);
        const name = formData.get('name');
        const dept = formData.get('department');
        const email = formData.get('email');
        if (!name || !email || dept === "Choose...") {
        alert("Please fill in the required fields !");
        return;
        }
        const res = await fetch('add_employee.php', {
            method: 'POST',
            body: new FormData(this)
        });

        const data = await res.json();

        alert(data.message);

        if (data.status === 'success') {
            location.reload();
            employeeForm.reset();
            alert("Employee successfully added !");
        }
};

    employeeTableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-delete')) {
        if (confirm("Are you sure you want to delete this employee?")) {
        e.target.closest('tr').remove();
        }
        }
    });

     searchInput.addEventListener('keyup', () => {
        const filter = searchInput.value.toLowerCase();
        const rows = employeeTableBody.getElementsByTagName('tr');

        for (let row of rows) {
           const text = row.textContent.toLowerCase();
           row.style.display = text.includes(filter) ? "" : "none";
        }
    });
});

