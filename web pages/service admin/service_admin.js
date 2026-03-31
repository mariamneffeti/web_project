async function loadClients() {
  const select = document.getElementById("clientSelect");
  try {
    const res = await fetch("get_clients.php");
    const clients = await res.json();

    select.innerHTML = "";
    clients.forEach((client) => {
      const opt = document.createElement("option");
      opt.value = client.email;
      opt.textContent = client.client_name;
      select.appendChild(opt);
    });
  } catch (err) {
    console.error("Could not load clients:", err);
  }
}

function fillClientInfo() {
  const selectEl = document.getElementById("clientSelect");
  const selected = Array.from(selectEl.selectedOptions);

  const names = selected.map((o) => o.text).join(", ");
  const emails = selected.map((o) => o.value).join(", ");

  document.getElementById("clientNameDisplay").value = names || "";
  document.getElementById("clientEmailDisplay").value = emails || "";
}

async function sendClientEmail() {
  const selectEl = document.getElementById("clientSelect");
  const selected = Array.from(selectEl.selectedOptions);

  const subject = document.getElementById("mailSubject").value;
  const body = document.getElementById("mailPrompt").value;
  const status = document.getElementById("mailStatus");

  if (selected.length === 0 || !subject || !body) {
    status.innerHTML =
      '<div class="alert alert-warning">Please fill in all fields and select at least one client.</div>';
    return;
  }

  const ZAPIER_WEBHOOK =
    "https://hook.eu1.make.com/ua29p5rhbs5e1y59nqwaycsfmyhmabhh";

  status.innerHTML = '<div class="alert alert-info">📤 Sending...</div>';

  const sends = selected.map((client) =>
    fetch(ZAPIER_WEBHOOK, {
      method: "POST",
      body: JSON.stringify({
        to: client.value,
        clientName: client.text,
        subject,
        body,
      }),
    }),
  );

  try {
    await Promise.all(sends); // send all at the same time
    status.innerHTML = `<div class="alert alert-success">✅ Email sent to ${selected.length} client(s)!</div>`;
  } catch (err) {
    status.innerHTML =
      '<div class="alert alert-danger">❌ Failed to send some emails.</div>';
  }
}

document.addEventListener("DOMContentLoaded", loadClients);
