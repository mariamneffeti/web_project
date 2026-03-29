document.addEventListener('DOMContentLoaded', () => {
    const articleForm = document.getElementById('articleForm');
    const articleTableBody = document.getElementById('articleTableBody');
    const searchInput = document.getElementById('searchInput');

    articleForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        const title = formData.get('title');
        const name = formData.get('name');
        const link = formData.get('link');
        const category = formData.get('category');
        const date = formData.get('date');

        if (!name || !title || !link || !category || !date) {
            alert("Please fill in all required fields!");
            return;
        }

        try {
            const res = await fetch('add_articles.php', {
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
            alert("Error while adding article");
        }
    });
articleTableBody.addEventListener('click', async (e) => {
    if (e.target.classList.contains('btn-delete')) {

        if (confirm("Are you sure you want to delete this article?")) {

                    e.target.closest('tr').remove();
            
        }
    }
});
    searchInput.addEventListener('keyup', () => {
        const filter = searchInput.value.toLowerCase();
        const rows = articleTableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        }
    });

});
