document.querySelectorAll(".view-offer").forEach(btn => {
    btn.addEventListener("click", () => {
        const offer = JSON.parse(btn.dataset.offer);

        document.getElementById("modalTitle").innerText = offer.title;
        document.getElementById("modalDesc").innerText = offer.description;
        document.getElementById("modalMeta").innerHTML = `
            <b>Location:</b> ${offer.location}<br>
            <b>Salary:</b> ${offer.salary_min} - ${offer.salary_max} Dt<br>
            <b>Type:</b> ${offer.type}
        `;

        new bootstrap.Modal(document.getElementById("offerModal")).show();
    });
});
const trigger = document.getElementById("iconTrigger");
const dropdown = document.getElementById("iconDropdown");
const search = document.getElementById("iconSearch");
const input = document.getElementById("iconInput");
const selectedText = document.getElementById("selectedIconText");

trigger.addEventListener("click", () => {
    dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
});

document.querySelectorAll(".icon-item").forEach(icon => {
    icon.addEventListener("click", () => {
        input.value = icon.dataset.id;
        selectedText.innerText = icon.dataset.name;
        dropdown.style.display = "none";
    });
});

search.addEventListener("input", () => {
    let value = search.value.toLowerCase();

    document.querySelectorAll(".icon-item").forEach(icon => {
        icon.style.display = icon.dataset.name.includes(value) ? "block" : "none";
    });
});

document.addEventListener("click", (e) => {
    if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
    }
});
