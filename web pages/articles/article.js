document.addEventListener('DOMContentLoaded', () => {
    const articleForm = document.querySelector('form');
    const articleTableBody = document.querySelector('tbody');
    const searchInput = document.querySelector('input[placeholder="Search by title or author"]');
    const filterInput = document.querySelector('input[placeholder="Filter by category"]');
    const searchBtn = document.querySelector('section.row .btn-secondary'); 

    
    articleForm.addEventListener('submit', (e) => {
        e.preventDefault();

      
        const title = articleForm.querySelector('input[placeholder="Enter title"]').value;
        const category = articleForm.querySelector('select').value;
        const author = articleForm.querySelector('input[placeholder="Author name"]').value;
        const date = articleForm.querySelector('input[type="date"]').value;
        const contentLink = articleForm.querySelector('input[placeholder="https://example.com/article.pdf"]').value;
        const imageUrl = articleForm.querySelector('input[placeholder="https://example.com/image.jpg"]').value;

        if (!title || category === "Choose..." || !author) {
            alert("Please fill in the main fields (Title, Category, Author)");
            return;
        }

        const id = Math.random().toString(36).substr(2, 3).toUpperCase();

        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${id}</td>
            <td>${title}</td>
            <td>${author}</td>
            <td>${category}</td>
            <td>${date || 'N/A'}</td>
            <td><a href="${contentLink}" target="_blank">Click here</a></td>
            <td><img src="${imageUrl || 'https://via.placeholder.com/80'}" class="img-thumbnail" width="80"></td>
            <td>
                <button class="btn btn-sm btn-outline-primary">View</button>
                <button class="btn btn-sm btn-outline-warning">Edit</button>
                <button class="btn btn-sm btn-outline-danger btn-delete">Delete</button>
                <button class="btn btn-sm btn-outline-success btn-like">Like <span>0</span></button>
                <button class="btn btn-sm btn-outline-secondary">Comment</button>
            </td>
        `;

        articleTableBody.appendChild(newRow);
        articleForm.reset(); 
    });


    const filterArticles = () => {
        const searchText = searchInput.value.toLowerCase();
        const filterText = filterInput.value.toLowerCase();
        const rows = articleTableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const author = row.cells[2].textContent.toLowerCase();
            const category = row.cells[3].textContent.toLowerCase();

            const matchesSearch = title.includes(searchText) || author.includes(searchText);
            const matchesFilter = category.includes(filterText);

            if (matchesSearch && matchesFilter) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    };

   
    searchInput.addEventListener('input', filterArticles);
    filterInput.addEventListener('input', filterArticles);
    searchBtn.addEventListener('click', filterArticles);


    articleTableBody.addEventListener('click', (e) => {
        // Suppression
        if (e.target.classList.contains('btn-delete')) {
            if (confirm("Are you sure you want to delete this article?")) {
                e.target.closest('tr').remove();
            }
        }


        if (e.target.classList.contains('btn-like')) {
            const span = e.target.querySelector('span');
            let count = parseInt(span.innerText);
            span.innerText = count + 1;
            e.target.classList.replace('btn-outline-success', 'btn-success');
        }
    });
});