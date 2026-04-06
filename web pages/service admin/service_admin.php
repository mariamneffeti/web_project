<?php 
    $pageTitle = "Management"; 
    include('../squelettes entreprise/header.php'); 
?>
<style>
    #clientSelect {
    color: #000;
    background-color: #fff;
}

#clientSelect option {
    color: #000;
}
</style>
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        
        <section id="calendar" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0" style="color: #102E4A;">Scheduled Meetings</h2>
                <button class="btn btn-finance rounded-pill px-4 shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Add New Meeting
                </button>
            </div>
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Date</th>
                            <th>Time</th>
                            <th>Topic</th>
                            <th>Attendees</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4">2026-02-05</td>
                            <td class="fw-bold">10:00</td>
                            <td>Strategy Meeting</td>
                            <td>John, Maria</td>
                            <td class="text-muted small">Prepare report</td>
                        </tr>
                        <tr>
                            <td class="px-4">2026-02-06</td>
                            <td class="fw-bold">14:00</td>
                            <td>Client Follow-up</td>
                            <td>Sarah, Tom</td>
                            <td class="text-muted small">Send proposal</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-md-6">
                <section id="ai-assistant" class="h-100">
                    <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">AI Assistant</h2>
                    <div class="card p-4 stats-card h-100" style="border-left-color: #388087;">
                        <p class="small text-secondary mb-4">Ask questions regarding company operations, clients, or sales.</p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Ask the AI...">
                            <button class="btn btn-finance px-4">Send</button>
                        </div>
                        <div class="mt-auto p-3 rounded-3 bg-light border border-secondary border-opacity-10 shadow-inner" style="min-height: 80px;">
                            <em class="text-muted small">AI Response will appear here...</em>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-6">
                <section id="notifications" class="h-100">
                    <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Mailing & Alerts</h2>
                    <div class="card p-4 stats-card h-100" style="border-left-color: #102E4A;">
                        <p class="small text-secondary mb-4">Send automated notifications or emails to clients and employees.</p>
                       <div class="d-grid gap-3 mt-auto">
<div class="mb-3">
    <label class="form-label fw-semibold">Select Client(s)</label>
    <select class="form-select" id="clientSelect" multiple 
        style="height: 130px;" onchange="fillClientInfo()">
        <option value="">Loading clients...</option>
    </select>
    <small class="text-muted">Hold <kbd>Ctrl</kbd> to select multiple clients</small>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Client(s) Selected</label>
        <input type="text" class="form-control bg-light" id="clientNameDisplay" 
            placeholder="Auto-filled..." readonly>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Client Email(s)</label>
        <input type="text" class="form-control bg-light" id="clientEmailDisplay" 
            placeholder="Auto-filled..." readonly>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Subject</label>
    <input type="text" class="form-control" id="mailSubject" 
        placeholder="e.g. Invoice Reminder...">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">What should the email say?</label>
    <textarea class="form-control" id="mailPrompt" rows="3" 
        placeholder="e.g. Remind the client their invoice is due next week...">
    </textarea>
</div>

<button class="btn btn-finance py-2 w-100 rounded-3" onclick="sendClientEmail()">
    <i class="bi bi-envelope me-2"></i>Send Email
</button>

<div id="mailStatus" class="mt-3"></div>

    <button class="btn btn-outline-dark border-2 fw-bold py-2" style="border-radius: 8px;">
        <i class="bi bi-envelope me-2"></i>Notify Clients
    </button>

</div>
                    </div>
                </section>
            </div>
        </div>
    </div>
<?php
    $pagePath = "../service admin/service_admin.js";
    include('../squelettes entreprise/footer.php'); 
?>
