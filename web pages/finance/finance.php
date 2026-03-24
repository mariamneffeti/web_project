<?php 
    $pageTitle = "Finance"; 
    include('../squelettes entreprise/header.php'); 
?>
<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <section id="summary" class="mb-5">
        <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Financial Summary</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card p-4 text-center stats-card">
                    <h6 class="text-secondary text-uppercase small">Total Revenue</h6>
                    <h3 class="fw-bold">XXXX Dt</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4 text-center stats-card" style="border-left-color: #102E4A;">
                    <h6 class="text-secondary text-uppercase small">Total Expenses</h6>
                    <h3 class="fw-bold">XXXX Dt</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4 text-center stats-card">
                    <h6 class="text-secondary text-uppercase small">Net Profit</h6>
                    <h3 class="fw-bold">XXXX Dt</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4 text-center stats-card" style="border-left-color: #8D9B6A;">
                    <h6 class="text-secondary text-uppercase small">Sales count</h6>
                    <h3 class="fw-bold">XX</h3>
                </div>
            </div>
        </div>
    </section>

    <section id="add-transaction" class="mb-5">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Add Transaction</h2>
            <form id="transaction-form" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Type</label>
                    <select class="form-select">
                        <option>Sale</option>
                        <option>Expense</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Amount</label>
                    <input type="number" class="form-control" placeholder="0.00">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Client / Vendor</label>
                    <input type="text" class="form-control" placeholder="Name">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Notes</label>
                    <input type="text" class="form-control" placeholder="Description">
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-finance px-5 py-2 rounded-pill mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Add Transaction
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section id="transactions" class="mb-5">
        <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Recent Transactions</h2>
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-4">Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Client / Vendor</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4">2026-02-01</td>
                        <td><span class="badge bg-success-subtle text-success px-3">Sale</span></td>
                        <td class="fw-bold">500 Dt</td>
                        <td>Client A</td>
                        <td class="text-muted italic">Invoice #001</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include('../squelettes entreprise/footer.php'); ?>