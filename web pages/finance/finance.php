<?php 
    $pageTitle = "Finance"; 
    require_once __DIR__ . '/../../config/session_check.php';
    include('../squelettes entreprise/header.php'); 
?>
<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <section id="summary" class="mb-5">
        <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Financial Summary</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card p-4 text-center stats-card">
                    <h6 class="text-secondary text-uppercase small">Total Revenue</h6>
                    <h3 class="fw-bold" id="revenue-kpi">XXXX Dt</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4 text-center stats-card" style="border-left-color: #102E4A;">
                    <h6 class="text-secondary text-uppercase small">Total Expenses</h6>
                    <h3 class="fw-bold" id="expenses-kpi">XXXX Dt</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4 text-center stats-card">
                    <h6 class="text-secondary text-uppercase small">Net Profit</h6>
                    <h3 class="fw-bold" id="net-profit-kpi">XXXX Dt</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4 text-center stats-card" style="border-left-color: #8D9B6A;">
                    <h6 class="text-secondary text-uppercase small">Sales count</h6>
                    <h3 class="fw-bold" id="salecnt-kpi">XX</h3>
                </div>
            </div>
        </div>
    </section>

     <section id="analytics" class="mb-5">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Financial Analytics</h2>
                <select id="yearFilter" class="form-select w-auto">
                    <?php
                    $currentYear = date("Y");
                    for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                        echo "<option value='$y'>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="position: relative; height:300px;">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </section>

    <section id="add-transaction" class="mb-5">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h2 class="h4 mb-4 fw-bold" style="color: #102E4A;">Add Transaction</h2>
            <form id="transaction-form" method="POST" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Type</label>
                    <select name="type" class="form-select" required> 
                        <option value="Salary">Salary</option>
                        <option value="Supply">Supply</option>
                        <option value="Rent">Rent</option>
                        <option value="Tools">Tools</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Amount</label>
                    <input type="number" name="amount"  class="form-control" placeholder="0.00" step="0.01" required> 
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Description"> </div>

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
        <div class="card border-0 shadow-sm p-3 mb-4 bg-light rounded-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">Type</label>
                    <select id="filter-type" class="form-select form-select-sm">
                        <option value="all">All Types</option>
                        <option value="Salary">Salary</option>
                        <option value="Supply">Supply</option>
                        <option value="Rent">Rent</option>
                        <option value="Tools">Tools</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">From</label>
                    <input type="date" id="filter-date-start" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">To</label>
                    <input type="date" id="filter-date-end" class="form-control form-control-sm">
                </div>
                <div class="col-md-5 text-end">
                    <button id="reset-filters" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-4">Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="transaction-list">
                    <?php
                        $query = "SELECT * 
                                FROM expenses
                                ORDER BY id DESC";
                        $stmt = $pdo->query($query); 

                       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            switch ($row['category']) {
                                case 'Rent':      $customClass = 'badge-rent'; break;
                                case 'Salary':    $customClass = 'badge-salary'; break;
                                case 'Tools':     $customClass = 'badge-tools'; break;
                                case 'Marketing': $customClass = 'badge-marketing'; break;
                                case 'Supply':    $customClass = 'badge-supplies'; break;
                                default:          $customClass = 'badge-other';
                            }

                            echo "<tr>
                                    <td>{$row['expense_date']}</td>
                                    <td>
                                        <span class='badge {$customClass} category-badge'>
                                            {$row['category']}
                                        </span>
                                    </td>
                                    <td class='fw-bold'>
                                        {$row['amount']} Dt
                                    </td>
                                    <td>{$row['description']}</td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
            <div class="text-center my-4">
                <button id="show-more-expenses" class="btn btn-outline-primary rounded-pill px-5">
                    Show More
                </button>
            </div>
        </div>
    </section>
</div>

<?php
    $pagePath = "../finance/finance.js";
    include('../squelettes entreprise/footer.php'); 
?>