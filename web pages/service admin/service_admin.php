<?php
    $pageTitle = "Management";
    include('../squelettes entreprise/header.php');
?>
<style>
  #clientSelect { color: #000; background-color: #fff; }
  #clientSelect option { color: #000; }

  .badge-scheduled { background: #e0f0ff; color: #1565c0; }
  .badge-done      { background: #e6f9ed; color: #1b5e20; }
  .badge-cancelled { background: #fdecea; color: #b71c1c; }

  .filter-bar .form-select,
  .filter-bar .form-control { font-size: 0.85rem; }

  .row-hidden { display: none; }

  #empSearchInput { margin-bottom: 8px; }
  #employeeCheckboxList label { cursor: pointer; }
  #employeeCheckboxList label:hover { background: #f0f4f8; border-radius: 6px; }
</style>

<div class="container py-4" style="margin-top: 100px; margin-bottom: 100px;">

  <section id="calendar" class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="h4 fw-bold mb-0" style="color:#102E4A;">Scheduled Meetings</h2>
      <button class="btn btn-finance rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addMeetingModal">
        <i class="bi bi-plus-lg me-2"></i>Add New Meeting
      </button>
    </div>

    <div class="card p-3 mb-3 shadow-sm rounded-3 filter-bar">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label fw-semibold small mb-1">Search</label>
          <input type="text" id="meetSearch" class="form-control form-control-sm" placeholder="Title, notes...">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small mb-1">Status</label>
          <select id="meetStatusFilter" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            <option value="scheduled">Scheduled</option>
            <option value="done">Done</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small mb-1">Date From</label>
          <input type="date" id="meetDateFrom" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small mb-1">Date To</label>
          <input type="date" id="meetDateTo" class="form-control form-control-sm">
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-finance btn-sm w-100" onclick="applyMeetFilters()"><i class="bi bi-funnel me-1"></i>Filter</button>
          <button class="btn btn-outline-secondary btn-sm w-100" onclick="clearMeetFilters()">Clear</button>
        </div>
      </div>
    </div>

    <div class="table-responsive shadow-sm rounded-3">
      <table class="table table-hover table-custom align-middle mb-0" id="meetingsTable">
        <thead>
          <tr>
            <th class="py-3 px-4">Date</th>
            <th>Time</th>
            <th>Title</th>
            <th>Status</th>
            <th>Meet Link / Code</th>
            <th>Notes</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="meetingsBody">
          <tr><td colspan="7" class="text-center text-muted py-4">Loading meetings...</td></tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center gap-3 mt-3">
      <button id="showMoreBtn"  class="btn btn-outline-secondary btn-sm d-none" onclick="showMoreRows()"><i class="bi bi-chevron-down me-1"></i>Show More</button>
      <button id="showLessBtn"  class="btn btn-outline-secondary btn-sm d-none" onclick="showLessRows()"><i class="bi bi-chevron-up me-1"></i>Show Less</button>
    </div>
  </section>

  <div class="row g-4">

    <div class="col-md-6">
      <section id="ai-assistant" class="h-100">
        <h2 class="h4 mb-4 fw-bold" style="color:#102E4A;">AI Assistant</h2>
        <div class="card p-4 stats-card h-100" style="border-left-color:#388087;">
          <p class="small text-secondary mb-4">Ask questions regarding company operations, clients, or sales.</p>
          <div class="input-group mb-3">
            <input type="text" id="aiInput" class="form-control" placeholder="Ask the AI...">
            <button class="btn btn-finance px-4" onclick="sendAiMessage()">Send</button>
          </div>
          <div class="mt-auto p-3 rounded-3 bg-light border border-secondary border-opacity-10 shadow-inner" style="min-height:80px;" id="aiResponse">
            <em class="text-muted small">AI Response will appear here...</em>
          </div>
        </div>
      </section>
    </div>

    <div class="col-md-6">
      <section id="notifications" class="h-100">
        <h2 class="h4 mb-4 fw-bold" style="color:#102E4A;">Mailing &amp; Alerts</h2>
        <div class="card p-4 stats-card h-100" style="border-left-color:#102E4A;">
          <p class="small text-secondary mb-4">Send automated notifications or emails to clients.</p>

          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <input type="text" id="clientFilterInput" class="form-control form-control-sm" placeholder="Filter clients by name..." oninput="filterClientSelect()">
            </div>
            <div class="col-md-6">
              <select id="clientStatusFilter" class="form-select form-select-sm" onchange="filterClientSelect()">
                <option value="">All Statuses</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Prospect">Prospect</option>
              </select>
            </div>
          </div>

          <div class="d-grid gap-3 mt-auto">
            <div class="mb-3">
              <label class="form-label fw-semibold">Select Client(s)</label>
              <select class="form-select" id="clientSelect" multiple style="height:130px;" onchange="fillClientInfo()">
                <option value="">Loading clients...</option>
              </select>
              <small class="text-muted">Hold <kbd>Ctrl</kbd> to select multiple clients</small>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Client(s) Selected</label>
                <input type="text" class="form-control bg-light" id="clientNameDisplay" placeholder="Auto-filled..." readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Client Email(s)</label>
                <input type="text" class="form-control bg-light" id="clientEmailDisplay" placeholder="Auto-filled..." readonly>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Subject</label>
              <input type="text" class="form-control" id="mailSubject" placeholder="e.g. Invoice Reminder...">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">What should the email say?</label>
              <textarea class="form-control" id="mailPrompt" rows="3" placeholder="e.g. Remind the client their invoice is due next week..."></textarea>
            </div>
            <button class="btn btn-finance py-2 w-100 rounded-3" onclick="sendClientEmail()">
              <i class="bi bi-envelope me-2"></i>Send Email
            </button>
            <div id="mailStatus" class="mt-3"></div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<div class="modal fade" id="addMeetingModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" style="color:#102E4A;"><i class="bi bi-calendar-plus me-2"></i>Add New Meeting</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label fw-semibold">Meeting Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="newTitle" placeholder="e.g. Weekly Team Sync">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select" id="newStatus">
              <option value="scheduled">Scheduled</option>
              <option value="done">Done</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="newDate">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="newTime">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Meet Link / Code</label>
            <input type="text" class="form-control" id="newLink" placeholder="https://meet.google.com/...">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Notes</label>
            <textarea class="form-control" id="newNotes" rows="2" placeholder="Additional notes..."></textarea>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Concerned Employees</label>
            <input type="text" id="empSearchInput" class="form-control form-control-sm mb-2" placeholder="Search employees..." oninput="filterEmployeeList()">
            <div id="employeeCheckboxList" class="border rounded p-2" style="max-height:180px;overflow-y:auto;">
              <em class="text-muted small">Loading employees...</em>
            </div>
          </div>
        </div>
        <div id="addMeetStatus" class="mt-3"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-finance px-4" onclick="saveMeeting()"><i class="bi bi-check-lg me-1"></i>Save Meeting</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="detailMeetModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#102E4A;">
        <h5 class="modal-title text-white fw-bold"><i class="bi bi-info-circle me-2"></i>Meeting Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3 mb-4">
          <div class="col-md-8">
            <p class="text-muted small mb-1 fw-semibold text-uppercase">Title</p>
            <p class="fs-5 fw-bold mb-0" id="detailTitle">—</p>
          </div>
          <div class="col-md-4 text-md-end">
            <p class="text-muted small mb-1 fw-semibold text-uppercase">Status</p>
            <div id="detailStatus"></div>
          </div>
          <div class="col-md-3">
            <p class="text-muted small mb-1 fw-semibold text-uppercase"><i class="bi bi-calendar3 me-1"></i>Date</p>
            <p class="mb-0 fw-semibold" id="detailDate">—</p>
          </div>
          <div class="col-md-3">
            <p class="text-muted small mb-1 fw-semibold text-uppercase"><i class="bi bi-clock me-1"></i>Time</p>
            <p class="mb-0 fw-semibold" id="detailTime">—</p>
          </div>
          <div class="col-md-6">
            <p class="text-muted small mb-1 fw-semibold text-uppercase"><i class="bi bi-link-45deg me-1"></i>Meet Link / Code</p>
            <p class="mb-0" id="detailLink">—</p>
          </div>
          <div class="col-12">
            <p class="text-muted small mb-1 fw-semibold text-uppercase"><i class="bi bi-journal-text me-1"></i>Notes</p>
            <p class="mb-0 text-muted" id="detailNotes">—</p>
          </div>
        </div>

        <hr>
        <p class="fw-semibold mb-2" style="color:#102E4A;"><i class="bi bi-people me-2"></i>Concerned Employees</p>
        <ul class="list-group list-group-flush" id="detailEmployees">
          <li class="list-group-item text-muted small">Loading...</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rescheduleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" style="color:#102E4A;"><i class="bi bi-calendar-check me-2"></i>Reschedule Meeting</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editMeetId">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">New Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="editDate">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">New Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="editTime">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select" id="editStatus">
              <option value="scheduled">Scheduled</option>
              <option value="done">Done</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div id="editMeetStatus" class="mt-3"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-finance px-4" onclick="updateMeeting()"><i class="bi bi-save me-1"></i>Update</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteMeetModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Delete Meeting</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deleteMeetId">
        <p class="mb-0">Are you sure you want to delete this meeting? If it hasn't happened yet, all attendees will be notified by email.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" onclick="confirmDeleteMeeting()">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php 
    $pagePath = '"../service admin/service_admin.js"'; 
    include('../squelettes entreprise/footer.php'); 
?>