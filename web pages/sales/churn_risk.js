async function loadChurn(clientId) {
    const res = await fetch(`/api/clients.php?action=churn&id=${clientId}`);
    const data = await res.json();

    const risk = Math.round(data.risk_score * 100);

    document.getElementById("stat-churn-risk").innerText = risk + "%";

    if (risk > 70) {
        document.getElementById("stat-churn-risk").style.color = "red";
    } else if (risk > 40) {
        document.getElementById("stat-churn-risk").style.color = "orange";
    } else {
        document.getElementById("stat-churn-risk").style.color = "green";
    }
}