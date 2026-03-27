
document.addEventListener('DOMContentLoaded', () => {
    const employeeForm = document.getElementById('employeeForm');
    const employeeTableBody = document.getElementById('employeeTableBody');
    const searchInput = document.getElementById('searchInput');


    employeeForm.addEventListener('submit', (e) => {

        const formData = new FormData(employeeForm);
        const name = formData.get('name');
        const dept = formData.get('department');
        const pos = formData.get('position');
        const email = formData.get('email');
        const id = Math.floor(Math.random() * 1000) + "B";

        
        if (!name || !email || dept === "Choose...") {
            alert("Please fill in the required fields !");
            return;
        }

        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${name}</td>
            <td>${id}</td>
            <td>${pos}</td>
            <td>${dept}</td>
            <td>${email}</td>
            <td><span class="badge bg-success">Active</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewEmployee('${name}')">View</button>
                <button class="btn btn-sm btn-outline-warning">Edit</button>
                <button class="btn btn-sm btn-outline-danger btn-delete">Delete</button>
            </td>
        `;

    
        employeeTableBody.appendChild(newRow);
        
        employeeForm.reset();
        alert("Employee successfully added !");
    });

    
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


function viewEmployee(name) {
    alert("Employee details :" + name);
}