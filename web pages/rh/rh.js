
document.addEventListener('DOMContentLoaded', () => {
    const employeeForm = document.getElementById('employeeForm');
    const employeeTableBody = document.getElementById('employeeTableBody');
    const searchInput = document.getElementById('searchInput');

    employeeForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        const name = formData.get('name');
        const dept = formData.get('department');
        const email = formData.get('email');

        if (!name || !dept || !email) {
            alert("Please fill in all required fields!");
            return;
        }

        try {
            const res = await fetch('add_employee.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            alert(data.message);

            if (data.status === 'success') {
                location.reload();
            }

        } catch (error) {
            console.error(error);
            alert("Error while adding employee");
        }
    });
employeeTableBody.addEventListener('click', async (e) => {
    if (e.target.classList.contains('btn-view')) {
        const id = e.target.dataset.id;
        window.location.href = `view_employee.php?id=${id}`;
    }
    if (e.target.classList.contains('btn-edit')) {
        const id = e.target.dataset.id;
        window.location.href = `edit_employee.php?id=${id}`;
    }
    if (e.target.classList.contains('btn-delete')) {
        if (confirm("Are you sure you want to delete this employee ?")) {
        const id = e.target.dataset.id;
        const row = e.target.closest('tr'); 

        try {
            const res = await fetch(`delete_employee.php?id=${id}`, {
                method: 'POST' 
            });

            const data = await res.json();

            if (data.status === 'success') {
                row.remove(); 
            } else {
                alert(data.message);
            }
        } catch (error) {
            alert("Erreur lors de la suppression");
        }
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

