fetch('/projectweb/web_project/api/get_articles.php')
  .then(res => res.json())
  .then(data => {
    const grid = document.getElementById('articlesGrid');
    const articles = data.data || [];

    if (articles.length === 0) {
      grid.innerHTML = '<p class="text-center text-muted">No articles yet.</p>';
      return;
    }

    grid.innerHTML = articles.map(a => {
      const date = a.date ? new Date(a.date).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }) : '';
      const img  = a.image
        ? `<img src="${a.image}" alt="${a.title}" style="width:100%;height:180px;object-fit:cover;">`
        : `<div style="width:100%;height:180px;background:linear-gradient(135deg,#388087,#1B4965);display:flex;align-items:center;justify-content:center;">
             <i class="bi bi-newspaper text-white" style="font-size:2.5rem;opacity:0.5;"></i>
           </div>`;

      return `
       <div class="col-md-6 col-lg-4">
  <div class="h-100 rounded-3 overflow-hidden shadow-sm d-flex flex-column"
       style="background:#fff; border:1px solid #ecf1ec; transition:transform .2s, box-shadow .2s;"
       onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(56,128,135,.15)'"
       onmouseleave="this.style.transform='';this.style.boxShadow=''">
    ${img}
    <div class="p-4 d-flex flex-column flex-grow-1">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="badge rounded-pill px-3" style="background:#e8f4f5;color:#388087;font-size:0.75rem;">${a.category || 'General'}</span>
        <span style="font-size:0.75rem;color:#444;">${date}</span>
      </div>
      <h6 class="fw-bold mb-2" style="color:#111;font-family:Montserrat;line-height:1.4;">${a.title || 'Untitled'}</h6>
      <p style="color:#333;font-size:0.875rem;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;" class="flex-grow-1 mb-3">
        ${a.description || ''}
      </p>
      <div class="d-flex justify-content-between align-items-center mt-auto pt-2" style="border-top:1px solid #f0f0f0;">
        <span style="font-size:0.875rem;color:#555;"><i class="bi bi-building me-1"></i>${a.company_name}</span>
        ${a.link ? `<a href="${a.link}" target="_blank" class="text-decoration-none fw-bold small" style="color:#388087;">Read more <i class="bi bi-arrow-up-right"></i></a>` : ''}
      </div>
    </div>
  </div>
</div>`;
    }).join('');
  })
  .catch(() => {
    document.getElementById('articlesGrid').innerHTML =
      '<p class="text-center text-danger">Failed to load articles.</p>';
  });