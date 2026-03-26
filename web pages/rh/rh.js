document.addEventListener('DOMContentLoaded', () => {
    const employeeForm = document.querySelector('form');
    const employeeTableBody = document.querySelector('tbody');
    const searchInput = document.querySelector('input[placeholder="Search by name or ID"]');
    const filterSelect = document.querySelectorAll('.form-select')[1]; // Le deuxième select (filtre)
    const applyBtn = document.querySelector('.btn-secondary');

    
    employeeForm.addEventListener('submit', (e) => {
        e.preventDefault();

       
        const name = employeeForm.querySelector('input[placeholder="Employee name"]').value;
        const dept = employeeForm.querySelector('select').value;
        const pos = employeeForm.querySelector('input[placeholder="Position"]').value;
        const email = employeeForm.querySelector('input[placeholder="email@company.com"]').value;
        const id = Math.floor(Math.random() * 100) + 'C';

        if (name && dept !== "Choose..." && pos && email) {
            const newRow = `
                <tr>
                    <td>${name}</td>
                    <td>${id}</td>
                    <td>${pos}</td>
                    <td>${dept}</td>
                    <td>${email}</td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">View</button>
                        <button class="btn btn-sm btn-outline-warning">Edit</button>
                        <button class="btn btn-sm btn-outline-danger btn-delete">Delete</button>
                    </td>
                </tr>
            `;
            employeeTableBody.insertAdjacentHTML('beforeend', newRow);
            employeeForm.reset();
            alert("Employee successfully added !");
        } else {
            alert("Please fill in all the blancs.");
        }
    });

    
    employeeTableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-delete')) {
            if (confirm("Are you sure you want to remove this employee? ?")) {
                e.target.closest('tr').remove();
            }
        }
    });


    const filterEmployees = () => {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = employeeTableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            
            if (matchesSearch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    };

    applyBtn.addEventListener('click', filterEmployees);
    searchInput.addEventListener('keyup', filterEmployees);
});