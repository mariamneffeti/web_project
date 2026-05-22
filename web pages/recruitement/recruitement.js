document.addEventListener("DOMContentLoaded", function() {
    const ROWS_PER_STEP = 5;
    let currentEditingRow = null;
    // filtrage et pagination
    function setupTableLogic(sectionId, filterElements) {
        const section = document.getElementById(sectionId);
        if (!section) return;

        const getRows = () => Array.from(section.querySelectorAll('.table-row'));
        const btnMore = section.querySelector('.btn-show-more');
        const btnLess = section.querySelector('.btn-show-less');
        let visibleCount = ROWS_PER_STEP;

        function updateDisplay() {
            const rows = getRows();
            
            let filteredRows = rows.filter(row => {
                let isMatch = true;
                filterElements.forEach(filter => {
                    const val = filter.element.value.toLowerCase().trim();
                    if (val === "") return;

                    const target = filter.selector === 'self' ? row : row.querySelector(filter.selector);
                    const content = target ? target.innerText.toLowerCase() : "";
                    
                    if (!content.includes(val)) isMatch = false;
                });
                return isMatch;
            });

            rows.forEach(row => row.classList.add('d-none'));

            filteredRows.forEach((row, index) => {
                if (index < visibleCount) {
                    row.classList.remove('d-none');
                }
            });

            if (btnMore) {
                (visibleCount >= filteredRows.length) ? btnMore.classList.add('d-none') : btnMore.classList.remove('d-none');
            }
            if (btnLess) {
                (visibleCount > ROWS_PER_STEP) ? btnLess.classList.remove('d-none') : btnLess.classList.add('d-none');
            }
        }

        filterElements.forEach(f => {
            if (f.element) {
                f.element.addEventListener('input', () => {
                    visibleCount = ROWS_PER_STEP; 
                    updateDisplay();
                });
            }
        });

        if (btnMore) {
            btnMore.onclick = () => { 
                visibleCount += ROWS_PER_STEP;
                updateDisplay();
            };
        }
        if (btnLess) {
            btnLess.onclick = () => {
                visibleCount = ROWS_PER_STEP;
                updateDisplay();
                section.scrollIntoView({ behavior: 'smooth' });
            };
        }

        updateDisplay();
        return () => {
            visibleCount = ROWS_PER_STEP;
            updateDisplay();
        };
    }

    // initialisation des tableaux
    
    // Pour les candidats
    setupTableLogic('candidates', [
        { element: document.getElementById('filterCandidatePosition'), selector: 'td:nth-child(2)' }, 
        { element: document.getElementById('filterCandidateStatus'), selector: 'td:nth-child(3)' }
    ]);

    // Pour les postes
    const refreshOffersTable = setupTableLogic('existing-offers', [
        { element: document.getElementById('filterOffersGlobal'), selector: 'self' } 
    ]);

    //selection d une icone
    const iconTrigger = document.getElementById('iconTrigger');
    const iconDropdown = document.getElementById('iconDropdown');
    const iconInput = document.getElementById('iconInput');
    const selectedIconText = document.getElementById('selectedIconText');
    const iconSearch = document.getElementById('iconSearch');
    const iconItems = document.querySelectorAll('.icon-item');

    if (iconTrigger) {
        iconTrigger.addEventListener('click', () => {
            iconDropdown.style.display = iconDropdown.style.display === 'none' ? 'block' : 'none';
        });

        iconItems.forEach(item => {
            item.addEventListener('click', () => {
                const iconId = item.getAttribute('data-id');
                const iconHtml = item.innerHTML; 
                iconInput.value = iconId;
                selectedIconText.innerHTML = iconHtml + " Icon selected";
                iconDropdown.style.display = 'none';
                iconItems.forEach(i => i.classList.remove('bg-light', 'border'));
                item.classList.add('bg-light', 'border');
            });
        });

        if (iconSearch) {
            iconSearch.addEventListener('input', (e) => {
                const filter = e.target.value.toLowerCase();
                iconItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    item.style.display = name.includes(filter) ? 'block' : 'none';
                });
            });
        }

        document.addEventListener('click', (e) => {
            if (iconDropdown && !iconTrigger.contains(e.target) && !iconDropdown.contains(e.target)) {
                iconDropdown.style.display = 'none';
            }
        });
    }

    // ajout ou modification d'un poste
    const jobForm = document.getElementById("jobOfferForm");
    jobForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const isEdit = formData.get("id") !== "";
        formData.append("action", isEdit ? "edit" : "add");

        if (!formData.get("title") || !formData.get("location")) {
            alert("Please fill in all required fields.");
            return;
        }

        const minInput = this.querySelector('[name="salary_min"]');
        const maxInput = this.querySelector('[name="salary_max"]');

        const min = parseInt(minInput.value);
        const max = parseInt(maxInput.value);

        minInput.setCustomValidity("");
        maxInput.setCustomValidity("");

        if (min < 500) {
            minInput.setCustomValidity("Minimum salary must be at least 500 Dt");
        }

        if (max <= min) {
            maxInput.setCustomValidity("Max salary must be strictly greater than min salary");
        }

        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        fetch('posts_actions.php', {
            method: "POST",
            body: formData,
        })
        .then(res => res.json())
        .then(result => {
            if (result.status === "success") {

                if (isEdit) {
                    updateOfferRow(currentEditingRow, result.offer);
                    showToast("Post updated ✅");
                } else {
                    addNewOfferRow(result.offer);
                    showToast("Post added ✅");
                }

                resetFormUI();
                currentEditingRow = null;

                if (refreshOffersTable) refreshOffersTable();
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(err => console.error("Fetch error:", err));
    });

    function resetFormUI() {
        jobForm.reset();
        document.getElementById("editId").value = "";

        iconInput.value = "";
        selectedIconText.innerHTML = "Choose an icon";

        document.querySelectorAll(".icon-item").forEach(i => {
            i.classList.remove("bg-light", "border");
        });

        const btnSubmit = document.querySelector("#jobOfferForm button[type=submit]");
        btnSubmit.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Post Job Offer`;
    }

    function updateOfferRow(row, offer) {
        row.querySelector(".fw-bold.text-dark").innerText = offer.title;
        row.querySelector(".small.text-muted").innerText =
            `${offer.category} • ${offer.experience_level}`;
        row.children[1].innerHTML =
            `<i class="bi bi-geo-alt me-1 text-muted"></i>${offer.location}`;
        row.children[2].innerHTML = `
            <div class="small fw-bold text-dark">
                ${offer.salary_min ?? ''} - ${offer.salary_max ?? ''} Dt
            </div>
            <div class="text-muted" style="font-size:0.7rem;">
                ${offer.type}
            </div>
        `;

        const statusCell = row.children[3];

        let badgeClass = "bg-success";
        if (offer.status === "closed") badgeClass = "bg-secondary";

        statusCell.innerHTML = `
            <span class="badge ${badgeClass}">
                ${offer.status}
            </span>
        `;

        const tagsCell = row.children[4];
        tagsCell.innerHTML = "";

        if (offer.tags) {
            offer.tags.split(",").slice(0,2).forEach(tag => {
                tag = tag.trim();
                if (!tag) return;

                tagsCell.innerHTML += `
                    <span class="badge bg-light text-secondary border-0 p-1 px-2"
                        style="font-size:0.65rem;">
                        #${tag}
                    </span>
                `;
            });
        }
    }

    function addNewOfferRow(offer) {
        const tableBody = document.querySelector('#existing-offers tbody');

        const newRow = document.createElement('tr');
        newRow.className = 'table-row d-none'; 

        let tagsHTML = '';
        if (offer.tags) {
            offer.tags.split(',').slice(0, 2).forEach(tag => {
                if (tag.trim() !== '') {
                    tagsHTML += `<span class="badge bg-light text-secondary border-0 p-1 px-2" style="font-size:0.65rem;">#${tag.trim()}</span> `;
                }
            });
        }

        newRow.innerHTML = `
            <td class="px-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3 d-flex align-items-center justify-content-center rounded" 
                        style="width:40px;height:40px;background-color:${offer.color}20;color:${offer.color};">
                        <i class="bi ${offer.icon_class} fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">${offer.title}</div>
                        <div class="small text-muted">${offer.category} • ${offer.experience_level}</div>
                    </div>
                </div>
            </td>
            <td><i class="bi bi-geo-alt me-1 text-muted"></i>${offer.location}</td>
            <td>
                <div class="small fw-bold text-dark">${offer.salary_min ?? ''} - ${offer.salary_max ?? ''} Dt</div>
                <div class="text-muted" style="font-size:0.7rem;">${offer.type}</div>
            </td>
            <td>
                <span class="badge bg-success">active</span>
            </td>
            <td>${tagsHTML}</td>
            <td class="text-end px-4">
                <div class="d-flex justify-content-end align-items-center gap-2">
                    <button class="btn btn-light btn-sm rounded-circle view-offer" data-offer='${JSON.stringify(offer)}'>
                        <i class="bi bi-eye"></i>
                    </button>

                    <button class="btn btn-light btn-sm rounded-circle edit-offer" data-offer='${JSON.stringify(offer)}'>
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-light btn-sm rounded-circle text-danger delete-offer" data-id="${offer.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;

        tableBody.prepend(newRow);
    }

    //suppression d un poste
    document.addEventListener("click", function(e) {
        const btn = e.target.closest(".delete-offer");
        if (!btn) return;

        const id = btn.dataset.id;

        showConfirm("Delete this job offer?", () => {

            fetch("posts_actions.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=delete&id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    btn.closest("tr").remove();
                    showToast("Post deleted 🗑️", "danger");
                }
                if (refreshOffersTable) refreshOffersTable();
            });
        });
    });

    //voir details poste
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".view-offer");
        if (!btn) return;

        const offer = JSON.parse(btn.dataset.offer);

        document.getElementById("modalTitle").innerText = offer.title;
        document.getElementById("modalMeta").innerText =
            `${offer.category} • ${offer.experience_level}`;

        const iconBox = document.getElementById("modalIcon");
        iconBox.style.backgroundColor = offer.default_color + "20";
        iconBox.style.color = offer.default_color;

        iconBox.innerHTML = `<i class="bi ${offer.bootstrap_class} fs-3"></i>`;

        document.getElementById("modalLocation").innerText = offer.location;
        document.getElementById("modalSalary").innerText =
            `${offer.salary_min ?? "-"} - ${offer.salary_max ?? "-"} Dt`;
        document.getElementById("modalLevel").innerText = offer.experience_level;
        document.getElementById("modalDescription").innerText = offer.description;

        document.getElementById("modalCategory").innerText = offer.category;
        document.getElementById("modalType").innerText = offer.type;

        const tagsContainer = document.getElementById("modalTags");
        tagsContainer.innerHTML = "";

        if (offer.tags) {
            offer.tags.split(",").forEach(tag => {
                tag = tag.trim();
                if (!tag) return;

                tagsContainer.innerHTML += `
                    <span class="badge bg-light text-secondary border me-1 mb-1">
                        #${tag}
                    </span>
                `;
            });
        }

        const modal = new bootstrap.Modal(document.getElementById("offerModal"));
        modal.show();
    });

    //modifier poste
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".edit-offer");
        if (!btn) return;

        const offer = JSON.parse(btn.dataset.offer);

        currentEditingRow = btn.closest("tr");

        document.getElementById("editId").value = offer.id;

        document.querySelector('[name="title"]').value = offer.title;
        document.querySelector('[name="location"]').value = offer.location;
        document.querySelector('[name="salary_min"]').value = offer.salary_min;
        document.querySelector('[name="salary_max"]').value = offer.salary_max;
        document.querySelector('[name="description"]').value = offer.description;
        document.querySelector('[name="tags"]').value = offer.tags;
        document.querySelector('[name="status"]').value = offer.status;
        document.querySelector('[name="category"]').value = offer.category;
        document.querySelector('[name="type"]').value = offer.type;
        document.querySelector('[name="experience_level"]').value = offer.experience_level;

        selectIcon(offer);

        document.querySelector("#jobOfferForm button[type=submit]").innerHTML =
            `<i class="bi bi-check-circle me-2"></i>Update Job Offer`;

        document.getElementById("post-offer")
            .scrollIntoView({ behavior: "smooth" });
    });
    function selectIcon(offer) {
        const iconInput = document.getElementById("iconInput");
        const selectedIconText = document.getElementById("selectedIconText");
        const iconItems = document.querySelectorAll(".icon-item");

        iconItems.forEach(item => {
            const id = item.getAttribute("data-id");

            item.classList.remove("bg-light", "border");

            if (String(id) === String(offer.id)) {
                item.classList.add("bg-light", "border");

                iconInput.value = offer.id;

                selectedIconText.innerHTML = item.innerHTML + " Icon selected";
            }
        });
    }

    //handling candidates
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".action-btn");
        if (!btn) return;

        const cv_id = btn.dataset.id;
        const action = btn.dataset.action;

        const row = btn.closest("tr");
        const name = row.querySelector("td .fw-bold")?.innerText || "";
        const email = row.querySelector("td .small")?.innerText || "";

        let subject = "";
        let body = "";
        let newStatus = "";

        if (action === "accept") {
            newStatus = "Accepted";
            subject = "Application Accepted 🎉";
            body = `Hello ${name},\n\nWe are happy to inform you that your application has been accepted.\nYou can login in Entreprisa website as an employee using your email and the password: password123 that you have to change!`;

        } else if (action === "reject") {
            newStatus = "Rejected";
            subject = "Application Update";
            body = `Hello ${name},\n\nThank you for your application. Unfortunately, we will not move forward.`;

        } else if (action === "contact") {
            newStatus = "Reviewed";
            subject = "Interview Invitation";
            body = `Hello ${name},\n\nWe would like to invite you for an interview. Please reply to schedule a time.`;
        }

        fetch("handle_candidate.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `cv_id=${cv_id}&action=${action}`
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                const ZAPIER_WEBHOOK = process.env.ZAPIER_URL; 

                let badge = row.querySelector("td:nth-child(3) .badge");

                if (action === "accept") {
                    badge.className = "badge bg-success-subtle text-success border-success-subtle px-3 rounded-pill";
                    badge.innerText = "Accepted";
                    showToast("Candidate accepted ✅");
                }

                if (action === "reject") {
                    badge.className = "badge bg-danger-subtle text-danger border-danger-subtle px-3 rounded-pill";
                    badge.innerText = "Rejected";
                    showToast("Candidate rejected ❌", "danger"); 
                }

                if (action === "contact") {
                    badge.className = "badge bg-primary-subtle text-primary border-primary-subtle px-3 rounded-pill";
                    badge.innerText = "Reviewed";
                    showToast("Interview email sent 📩", "primary");
                }
                const buttons = row.querySelectorAll(".action-btn");
                if (action === "accept" || action === "reject") {
                    buttons.forEach(b => {
                        b.disabled = true;
                        b.classList.add("opacity-50");
                    });
                }
                if (action === "contact") {
                    const contactBtn = row.querySelector('[data-action="contact"]');
                    if (contactBtn) {
                        contactBtn.disabled = true;
                        contactBtn.classList.add("opacity-50");
                    }
                }

                fetch(ZAPIER_WEBHOOK, {
                    method: "POST",
                    body: JSON.stringify({
                        to: email,
                        name: name,
                        subject: subject,
                        body: body,
                        type: action
                    }),
                });
            }
        });
    });

    //toasts
    function showToast(message, type = "success") {
        const container = document.getElementById("toastContainer");

        const toast = document.createElement("div");
        toast.className = `alert alert-${type} shadow-sm`;
        toast.style.minWidth = "250px";
        toast.style.marginBottom = "10px";
        toast.innerText = message;

        container.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    //delete candidate
    document.addEventListener("click", function(e) {
        const btn = e.target.closest(".action-btn-delete");
        if (!btn) return;

        const cv_id = btn.dataset.id;
        const row = btn.closest("tr");

        showConfirm("Delete this candidate?", () => {

            fetch("handle_candidate.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `cv_id=${cv_id}&action=delete`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    row.remove();
                    showToast("Candidate deleted 🗑️", "dark");
                } else {
                    showToast("Error deleting candidate", "danger");
                }
            });
        });
    });

    //confirmation popup
    function showConfirm(message, onConfirm) {
        const modalEl = document.getElementById("confirmModal");
        const modal = new bootstrap.Modal(modalEl);

        document.getElementById("confirmText").innerText = message;

        const yesBtn = document.getElementById("confirmYes");

        const handler = () => {
            onConfirm();
            yesBtn.removeEventListener("click", handler);
            modal.hide();
        };

        yesBtn.addEventListener("click", handler);
        modal.show();
    }
});